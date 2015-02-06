from __future__ import division

import os
import time
import sys
from ouimeaux.environment import Environment
import urllib2

pid = os.getpid()
with open("/tmp/wemo.pid","w") as f:
	f.write(str(pid))

with open("/etc/ep.root") as f:
        rootDir = f.read().strip("\n")
os.chdir(rootDir)
os.system("sudo wemo clear")
global pidTime
pidTime = 0

class Logger(object):
    def __init__(self):
        self.terminal = sys.stdout
        self.log = open(rootDir+"/logs/wemo_watch.log", "a")

    def write(self, message):
        self.terminal.write(message)
        self.log.write(message)

sys.stdout = Logger()

def dprint(string):
	print string

def timeoutCheck():
	pid = os.getpid()
	print "Checking PID",str(pid),"timeout..."
	with open("conf/pids/pids.list") as f:
		pids = f.read()
	pids = pids.split("\n")
	pidFound = False
	for item in pids:
		if item == str(pid)+"|WEMO":
			pidFound = True
	if pidFound == False:
		with open("conf/pids/pids.list","a") as f:
			f.write(str(pid) + "|WEMO\n")
	global pidTime
	pidTime += 1
	with open("conf/pids/"+str(pid)+".txt","w+") as f:
		f.write(str(pidTime))

timeoutCheck()

#//////////////////////////////////////////////
# FUNCTION TO NOTIFY WEB UI
def notify(notification):
	currentTime = strftime("%m/%d %H:%M:%S")
	with open("misc/notification.txt","w") as f:
		f.write(notification)
	with open("logs/notifications.log","a") as f:
		f.write(currentTime + " | " + notification  + "\n")

#//////////////////////////////////////////////

#//////////////////////////////////////////////
# FUNCTION TO HANDLE RESTARTS...
def restart_program():
	print "RESTART"
	with open("conf/wemo.state","w") as f:
        	f.write("1")
	sys.exit()
#//////////////////////////////////////////////

#//////////////////////////////////////////////
# READ ALL SETTINGS FUNCTION...
def readAllSettings():
	settings = {}
        with open("conf/settings.conf") as f:
                confString = f.readlines()
                for item in confString:
			try:
	                        if not item[0] == '#':
	                                item = item.strip("\n")
	                                item = item.strip("\r")
	                                item = item.split("=")
	                                storedName = item[0]
	                                storedValue = item[1]
					settings[storedName] = storedValue
			except:
				pass
	return settings

#//////////////////////////////////////////////

#//////////////////////////////////////////////
# WRITE SINGLE SETTING FUNCTION...
def writeSetting(newName,newValue):

	outLines = []
	found = 0

	with open("conf/settings.conf") as f:
                confString = f.readlines()
	for item in confString:
		try:
			item2 = item
			if not item[0] == '#':
				item = item.strip("\n")
	                        item = item.strip("\r")
	                        item = item.split("=")
	                        storedName = item[0]
	                        storedValue = item[1]
	                        if storedName == newName:
					item2 = newName + "=" + newValue
					found = 1
				else:
					pass
					#NO CHANGE, WRITE ORIGINAL LINE BACK
				outLines.append(item2)
			outLines.append(item2)
		except:
			outLines.append(item)			
	if found == 0:
		print "SETTING ERROR: NAME" + newName + " NOT FOUND!"
	outString = ""
        for item in outLines:
                outString = str(outString) + str(item) + "\n";
        with open("conf/settings.conf","w") as f:
		f.write(outString)
	print "SETTING WROTE: " + newName + "=" + newValue
#//////////////////////////////////////////////

timeoutCheck()

#----------------------------------------------------------- READ SETTINGS YOU NEED HERE!
settings = readAllSettings()

def on_switch(switch):
        print "Switch found!", switch.name

try:
	env = Environment(on_switch)
	env.start()
	env.discover(seconds=3)
except Exception,e:
	print str(e)
	print "WeMo environment already set up!"

timeoutCheck()

def wemoSwitchState(switchName,state):
        switch = env.get_switch(switchName)
        print "SWITCH",switchName
        switch.basicevent.SetBinaryState(BinaryState=state)

def wemoGetState(switchName):
        switch = env.get_switch(switchName)
        stateCheck = switch.basicevent.GetBinaryState()
        return int(stateCheck['BinaryState'])

#//////////////////////////////////////////////
# FUNCTION TO HANDLE COMMANDS
def parseCommand(command):
	command = command.strip("\n")
	command = command.split(":")
	type = command[0]
	if type == "WEMO":
		switchN = command[1].replace("_"," ")
		switchS = command[2]
		wemoSwitchState(switchN,switchS)
		
	if type == "RST" or type == "WEMO-REPOP":
		restart_program()
	if type == "MANUAL":
		print "MANUAL"

def populateWeMo():
        print "LOADING..."
	with open("conf/wemo.state","w") as f:
		f.write("1")

	global wemoNameList
	global wemoIDList
        wemoNameList = []
        wemoIDList = []

        def on_switch(switch):
                print "Switch found!", switch.name

        print "DISCOVERING WEMO SWITCHES..."

        print "=================="
        print "DONE! Found these:"
        wemoNameList = env.list_switches()
        print wemoNameList
        print "=================="

        with open("conf/wemo.list","w") as f:
                f.write("")

        print "Gathering info about WeMo switches..."

        for item in wemoNameList:
                switchName = item
                switch = env.get_switch(switchName)
                info = switch.deviceinfo.GetDeviceInformation()
                infoString = info['DeviceInformation'].strip("\n").split("|")
                switchID = infoString[0]
                switchState = infoString[4]
                switchName = infoString[5]
                deviceDuplicate = False
                for item in wemoIDList:
                        if switchID == item:
                                print "Ignoring duplicate device!"
                                deviceDuplicate = True
                if deviceDuplicate == False:
                        wemoIDList.append(switchID)
                        line = switchID+"|"+switchName+"|"+str(switchState)+"\n"
                        print line
                        with open("conf/wemo.list","a") as f:
                                f.write(line)

        print "\nWeMo switches/states populated:"
        with open("conf/wemo.list") as f:
                wemoList = f.read()
	wemoList = wemoList.split("\n")
	wemoNameList = []
	for item in wemoList:
		if len(item) > 3:
			item = item.split("|")
			switchName = item[1]
			wemoNameList.append(switchName)
	with open("conf/wemo.state","w") as f:
		f.write("0")

def checkWemoStates():
	with open("conf/wemo.list") as f:
		wemoLines = f.read()
	wemoString = ""
	wemoLines = wemoLines.split("\n")
	for item in wemoLines:
		if len(item) > 5:
			item = item.split("|")
			wemoID = item[0]
			wemoName = item[1]
			wemoState = item[2]
			switch = env.get_switch(wemoName)
	                info = switch.basicevent.GetBinaryState()
	                wemoState = str(info['BinaryState'])
			line = wemoID+"|"+wemoName+"|"+wemoState+"\n"
			wemoString = wemoString + line
			print line
	with open("conf/wemo.list","w") as f:
		f.write(wemoString)

timeoutCheck()
populateWeMo()
timeoutCheck()

global wemoNameList
global wemoIDList

print wemoNameList

blipCount = 1
bloopCount = 1

while True:

	time.sleep(0.1)

	blipCount += 1
	bloopCount += 1

	if blipCount > 10:
		timeoutCheck()
		blipCount = 0
		print "CHECKING WEMO STATES..."
		checkWemoStates()

	if bloopCount >= 120:
		print "BLOOP"
		bloopCount = 0
#		os.system("sudo wemo clear")
#		populateWeMo()

	with open("misc/wemo.command.list","r") as f:
		command = f.read()

	if len(command) > 0:
		f = open("misc/wemo.command.list","w")
		f.write("")
		f.close()
		print "WEMO COMMAND:",command

	command = command.split("\n")
	for item in command:
		if len(item) > 1:
			print "!------------------------------------------------ RECEIVED COMMAND: " + item
			parseCommand(item)
