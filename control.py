from __future__ import division

import os
import time
import datetime
from time import gmtime, strftime
import sys
import RPi.GPIO as GPIO
import argparse
import urllib2

pid = os.getpid()
with open("/tmp/control.pid","w") as f:
	f.write(str(pid))

with open("/etc/ep.root") as f:
        rootDir = f.read().strip("\n")

with open("/etc/ep-web.root") as f:
        webDir = f.read().strip("\n")

os.chdir(rootDir)
global pidTime
pidTime = 0

# These are to keep the logs at a reasonable size. Under normal use, this is only run once per day at 4AM.
with open(rootDir+"/logs/client_watch.log","w") as f:
	f.write("")
with open(rootDir+"/logs/control_watch.log","w") as f:
	f.write("")
with open(rootDir+"/logs/notifications.log","w") as f:
	f.write("")
with open(rootDir+"/logs/wemo_watch.log","w") as f:
	f.write("")
with open("/var/log/apache2/error.log","w") as f:
	f.write("")
with open("/var/log/apache2/access.log","w") as f:
	f.write("")

class Logger(object):
    def __init__(self):
        self.terminal = sys.stdout
        self.log = open(rootDir+"/logs/control_watch.log", "a")

    def write(self, message):
        self.terminal.write(message)
        self.log.write(message)

sys.stdout = Logger()

def dprint(string):
	print string

print " "
print "///////////////////////////////////////////////////////////////////////"
print " "
print '8888888888 888                   888                     8888888b.  d8b '
print '888        888                   888                     888   Y88b Y8P '
print '888        888                   888                     888    888     '
print '8888888    888  .d88b.   .d8888b 888888 888d888  .d88b.  888   d88P 888 '
print '888        888 d8P  Y8b d88P"    888    888P"   d88""88b 8888888P"  888 '
print '888        888 88888888 888      888    888     888  888 888        888 '
print '888        888 Y8b.     Y88b.    Y88b.  888     Y88..88P 888        888 '
print '8888888888 888  "Y8888   "Y8888P  "Y888 888      "Y88P"  888        888' 
print " "
print "///////////////////////////////////////////////////////////////////////"
print " "

# define output pins...
txPin = 18
rPin = 11
gPin = 15
bPin = 13

# set up GPIO...
GPIO.setwarnings(False)
GPIO.setmode(GPIO.BOARD)

GPIO.setup(txPin,GPIO.OUT)
GPIO.setup(rPin,GPIO.OUT)
GPIO.setup(gPin,GPIO.OUT)
GPIO.setup(bPin,GPIO.OUT)

rPWM = GPIO.PWM(rPin,50)
gPWM = GPIO.PWM(gPin,50)
bPWM = GPIO.PWM(bPin,50)

rPWM.start(0)
gPWM.start(100)
bPWM.start(100)

def timeoutCheck():
        pid = os.getpid()
        print "Checking PID",str(pid),"timeout..."
        with open("conf/pids/pids.list") as f:
                pids = f.read()
        pids = pids.split("\n")
        pidFound = False
        for item in pids:
                if item == str(pid)+"|RF":
                        pidFound = True
        if pidFound == False:
                with open("conf/pids/pids.list","a") as f:
                        f.write(str(pid) + "|RF\n")
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

timeoutCheck()

def powerRecover():
	statesToDo = []
	count = 0
	with open("conf/applianceStates.txt") as f:
		states = f.read()
	states = states.split("\n")
	for item in states:
		if len(item) > 3:
			item = item.split("|")
			state = item[1]
			statesToDo.append(state)
	print statesToDo
	with open("conf/appliances.txt") as f:
		switches = f.read()
	switches = switches.split("\n")
	for item in switches:
		if len(item) > 3:
			item = item.split("|")
			nick = item[0]
			onCode = item[2]
			offCode = item[3]
			repeat = item[7]
			AID = item[8]
			if str(statesToDo[count]) == "1":
				txCode = onCode
			elif str(statesToDo[count]) == "0":
				txCode = offCode
			command = "sudo ./tx "+txCode+" "+repeat
			os.system(command)
			count += 1

timeoutCheck()

#//////////////////////////////////////////////
# FUNCTION TO HANDLE RESTARTS...
def restart_program(type):
	print "RESTART",type
	if type == "NORM":
		type = "NORM"
	elif type == "FAST":
		pass


	os.system("sudo python "+rootDir+"/control_watch.py " + type + "&")
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
# READ SINGLE SETTING FUNCTION...
def readSetting(searchName):
	with open("conf/settings.conf") as f:
		confString = f.readlines()
	try:
		for item in confString:
			if not item[0] == '#':
				item = item.strip("\n")
				item = item.strip("\r")
				item = item.split("=")
				storedName = item[0]
				storedValue = item[1]
				if storedName == searchName:
					print "SETTING READ: " + searchName + " = " + storedValue
					return storedValue
	except:	
		print "SETTING ERROR: NAME" + searchName + " NOT FOUND!"

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
ledBrightness = int(readSetting("BRIGHTNESS"))
rgbLed = readSetting("RGBLED")
freq = readSetting("FREQ_ATTACHED")

#//////////////////////////////////////////////
# READ ALL APPLIANCES FUNCTION
def readAllAppliances():
	with open("conf/appliances.txt") as f:
		aList = f.readlines()
	return aList
#//////////////////////////////////////////////

#//////////////////////////////////////////////
# FUNCTION TO UPDATE APPLIANCE STATES
def writeApplianceState(appNum,appState):
	with open("conf/appliances.txt") as f:
                aList = f.readlines()
	line = aList[int(appNum)]
	backupline = line
	line = line.split("|")
	if appState == "ON":
		appState = "1"
	if appState == "OFF":
		appState = "0";

	newline = line[0] + "|" + appState + "|" + line[2] + "|" + line[3] + "|" + line[4] + "|" + line[5] + "|" + line[6] + "|" + line[7]

	with open('conf/appliances.txt', 'r') as input_file, open('conf/appliances.tmp', 'w') as output_file:
		for line in input_file:
        		if line == backupline:
            			output_file.write(newline)
        		else:
            			output_file.write(line)
	os.system("sudo cp conf/appliances.tmp conf/appliances.txt")
	os.system("sudo rm conf/appliances.tmp")
#//////////////////////////////////////////////

#//////////////////////////////////////////////
# FUNCTION TO WRITE COLOR TO GPIO
def colorWrite(color):
	rgbLed = readSetting("RGBLED")
	if rgbLed == "ENABLED":
		brightness = int(readSetting("BRIGHTNESS"))
		if color == "kill":
			rPWM.ChangeDutyCycle(100)
	                gPWM.ChangeDutyCycle(100)
	                bPWM.ChangeDutyCycle(100)
		# this writes colors to the LED
		elif color == "red":
			rPWM.ChangeDutyCycle(100-brightness)
			gPWM.ChangeDutyCycle(100)
			bPWM.ChangeDutyCycle(100)
		elif color == "green":
	                rPWM.ChangeDutyCycle(100)
	                gPWM.ChangeDutyCycle(100-brightness)
	                bPWM.ChangeDutyCycle(100)
		elif color == "blue":
	                rPWM.ChangeDutyCycle(100)
	                gPWM.ChangeDutyCycle(100)
	                bPWM.ChangeDutyCycle(100-brightness)
#//////////////////////////////////////////////
# FUNCTION TO BREATHE LED
def ledBreathe():
	if rgbLed == "ENABLED":
		global breatheDirection
		global breatheLevel
		multiplier = ledBrightness / 100
	
		if breatheDirection == "UP":
			breatheLevel += 1 * multiplier
			if breatheLevel >= ledBrightness:
				breatheDirection = "DOWN"
		if breatheDirection == "DOWN":
			breatheLevel -= 1 * multiplier
			if breatheLevel <= 0:
				breatheDirection = "UP"
	
		rPWM.ChangeDutyCycle(100)
        	gPWM.ChangeDutyCycle(100)
        	bPWM.ChangeDutyCycle(100-int(breatheLevel))
#//////////////////////////////////////////////

#//////////////////////////////////////////////
# FUNCTION TO HANDLE COMMANDS
def parseCommand(command):
	command = command.strip("\n")
	command = command.split(":")
	type = command[0]
	if type == "RF":
		print "RF"
		com = "sudo nice -n -20 "+command[1]
		os.system(com)
	if type == "RST":
		restart_program("NORM")
	if type == "RST-FAST":
		restart_program("FAST")
	if type == "MANUAL":
		print "MANUAL"

def scheduleCheck():
	global lastString

	t = str(datetime.datetime.now().time())
	print t
	t = t.split(".")
	x = str(t[0])
	x = x.split(":")
	hour = x[0]
	print hour
	min = x[1]
	timeString = hour+":"+min
	print timeString,lastString
	if not timeString == lastString:
		lastString = timeString
		print "TIME CHANGED"
		with open("conf/event.list") as f:
			eventList = f.read()
		eventList = eventList.split("\n")
		for item in eventList:
			if len(item) > 3:
				item = item.split("|")
				nickS = item[0]
				hourS = item[1]
				minS = item[2]
				AID = item[3]
				eventTime = hourS+":"+minS
				if timeString == eventTime:
					print "EVENT",nickS,"HAPPENING!"
					urllib2.urlopen("http://192.168.1.88/"+webDir+"system.php?type=ACTION&AID="+AID)

timeoutCheck()
global breatheDirection
global breatheLevel
breatheDirection = "UP"
breatheLevel = 0

global lastString
lastString = "X"

try:
	argument = sys.argv[1]
except:
	argument = "NONE"

print "Argument:",argument

timeoutCheck()
if argument == "NONE" or argument == "NORM":
	print "STARTING WATCHDOG..."

	print "Checking reboot type..."
	with open("misc/rebootStatus.txt") as f:
		rStatus = f.read().strip("\n")
	if rStatus == "GOOD":
		with open("misc/rebootStatus.txt","w") as f:
			f.write("X")
	elif rStatus == "X":
		print "IMPROPER REBOOT!"
		notify("IMPROPER REBOOT!");
		powerRecover()

elif argument == "FAST":
	print "Watchdog starting in fast mode. Update check skipped."

timeoutCheck()
settings = readAllSettings()
aList = readAllAppliances()
blipCount = 1
bloopCount = 1

if rgbLed == "ENABLED":
	fade = 0
	while fade < 100:
		fade += 1
		rPWM.ChangeDutyCycle(fade)
		time.sleep(0.005)
else:
	colorWrite("kill")

while True:

	time.sleep(0.1)
	colorWrite("kill")
	blipCount += 1
	bloopCount += 1

	if blipCount > 5:
		colorWrite("blue")
		blipCount = 0
		timeoutCheck()
	
#		GPIO.output(txPin,1)
#		time.sleep(0.02)
#		GPIO.output(txPin,0)
#		time.sleep(0.02)

		scheduleCheck()

	if bloopCount >= 60:
		colorWrite("red")
		print "BLOOP"
		bloopCount = 0

	
	f = open("conf/updating.state","r")
	updating = f.read()
	f.close()

	if updating == "TRUE":
		print "------------------------------------"
		print "-------CHECKING FOR UPDATE----------"
		print "------------------------------------\n"
		time.sleep(2)
		os.system("sudo python conf/update.py")
		print "\n------------------------------------"
		print "------UPDATE CHECK FINISHED---------"
		print "------------------------------------\n"

	f = open("misc/time.txt","w")
	f.write(str(time.time()))
	f.close()

	with open("misc/command."+freq+".list","r") as f:
		command = f.read()

	if len(command) > 0:
		f = open("misc/command."+freq+".list","w")
		f.write("")
		f.close()

	command = command.split("\n")
	for item in command:
		if len(item) > 1:
			colorWrite("green")
			print "!------------------------------------------------ RECEIVED COMMAND: " + item
			parseCommand(item)
