from __future__ import division

import os
import time
import datetime
from time import gmtime, strftime
import sys
import RPi.GPIO as GPIO
import argparse
import urllib2
from ConfigParser import ConfigParser
import tornado.ioloop
import tornado.web
import tornado.websocket
import tornado.template
import threading
from threading import Thread
import string
import random
import psutil
import commands

config = ConfigParser()
config.read('/var/www/config/settings.ini')

rootDir = config.get("SETTINGS","rootDir").strip('"')
webDir = config.get("SETTINGS", "webDir").strip('"')
rgbLed = config.get("SETTINGS", "rgbLed").strip('"')
masterFreq = config.get("SETTINGS", "masterFreq").strip('"')
boardType = config.get("SETTINGS", "boardType").strip('"')

os.chdir(rootDir)

pid = os.getpid()
with open("python/pid.temp") as f:
        lastPid = f.read()
os.system("sudo kill -9 "+lastPid)
with open("python/pid.temp","w") as f:
        f.write(str(pid))
print "PID is:",pid,"- killed",lastPid

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

global lastString
lastString = "X"

print "STARTING SERVER..."

class MainHandler(tornado.web.RequestHandler):
	def get(self):
		loader = tornado.template.Loader(".")
		self.write(loader.load("/var/www/header.php").generate())

class WSHandler(tornado.websocket.WebSocketHandler):
	global connections
	connections = set()
	
	def check_origin(self, origin):
		return True
	def open(self):
		connections.add(self)
		print 'connection opened...'
		self.write_message("The server says: 'Hello'. Connection was accepted.")
	
	def on_message(self, message):
		parseCommand(message)
	
	def on_close(self):
		connections.remove(self)
		print 'connection closed...'

application = tornado.web.Application([
	(r'/ws', WSHandler),
	(r'/', MainHandler),
	(r"/(.*)", tornado.web.StaticFileHandler, {"path": "./resources"}),
])

def runServer():
	application.listen(9393)
	print "SERVER STARTED."
	tornado.ioloop.IOLoop.instance().start()

def getSwitchName(UID):
	foundName = 0
	while foundName == 0:
		try:
			UIDconfig = ConfigParser()
			UIDfile = '/var/www/data/switches/'+UID+'/info.ini'
			print UIDfile
			UIDconfig.read(UIDfile)
			nick = UIDconfig.get("ID", "Nickname")
			nick = nick.replace("_"," ")
			foundName = 1
		except:
			print "INI READ ERROR!"
	return nick

def sendAll(msg):
	print "SENT TO CLIENTS:",str(msg)
	colorWrite("blue")
	try:
		for con in connections:
			con.write_message(str(msg))
	except:
		pass

def notify(msg,type):
	if type == "power":
		toSend = "NOTIFY|<img src='images/lightning-icon.png' style='width:40px;height:64px;margin-right:-24px;'/>|<font style='margin-left:-14px;'>"+str(msg)+"</font>"
		sendAll(toSend)
		print toSend
	if type == "general":
		toSend = "NOTIFY||<font style='margin-left:-64px;'>"+str(msg)+"</font>"
		sendAll(toSend)
		print toSend

def setConfigLine(file,section,key,val):
	config = ConfigParser()
	config.read(file)
	config.set(section,key,val)
	with open(file, 'wb') as configfile:
		config.write(configfile)

def sendAliveSignal(size=6, chars=string.ascii_uppercase + string.digits):
	s = ''.join(random.choice(chars) for _ in range(size))
	with open("/var/www/watchdog.rng","w") as f:
		f.write(s)

def getCPUusage():
	cpu = int(psutil.cpu_percent())
        if not cpu == 0:
        	print "CPU:",cpu
		sendAll("CPU|"+str(cpu))

def fixSwitchPositions():
	inconsistency = 0

	switches = commands.getoutput("sudo ls data/switches").replace("\n"," ").split(" ")
	if str(switches) == "['']":
		return
	currentMessID = []
	currentMessPos = []
	currentMessPosOld = []
	index = 0
	
	for item in switches:
		UID = item
		with open("data/switches/"+UID+"/info.ini") as f:
			switchInfo = f.read()
		switchInfo = switchInfo.split("\n")
		for item in switchInfo:
			line = item
			try:
				line = line.split(" = ")
				key = line[0]
				val = line[1]
				if key == "position":
					switchPos = int(val)
					index += 1
			except:
				pass
	
		currentMessID.append(UID)
		currentMessPos.append(switchPos)
		currentMessPosOld.append(switchPos)
	
	print currentMessID
	print currentMessPos
	
	correctNeeded = len(currentMessPos)
	correctSwitches = 0
	
	while correctSwitches != correctNeeded:
		correctSwitches = 0
		gapStart = 0
		gapStarted = False
		shift = 0
		count = 1
		countTo = 0
		for item in currentMessPos:
			if int(item) > countTo:
				countTo = int(item)
		
		while count <= countTo:
			numFound = 0
			for item in currentMessPos:
				if int(item) == count:
					numFound = 1
			if numFound == 1:
				if gapStarted == True:
					shift = gapStart - count
					count = 999
				correctSwitches += 1
			else:
				if gapStarted == False:
					inconsistency = 1
					gapStarted = True
					gapStart = count
			count += 1
		
		index = 0
		while index < len(currentMessPos):
			if currentMessPos[index] > gapStart:
				currentMessPos[index] = currentMessPos[index]+shift
			index += 1
	
	index = 0
	switchCount = len(currentMessID)
	
	while index < switchCount:
		print "--------------------------------------------"
		print "UID: "+str(currentMessID[index])+" NEW:"+str(currentMessPos[index])+" OLD:"+str(currentMessPosOld[index])
		print "--------------------------------------------"
		UID = str(currentMessID[index])
		outString = ""
	        with open("data/switches/"+UID+"/info.ini") as f:
	                switchInfo = f.read()	
		switchInfo = switchInfo.split("\n")
		for item in switchInfo:
			line = item
			outLine = line
			try:
				line = line.split(" = ")
				key = line[0]
				if key == "position":
					outLine = "position = "+str(currentMessPos[index])
			except:
				pass
	
			outString += outLine+"\n"
	
		with open("data/switches/"+UID+"/info.ini","w") as f:
			f.write(outString)
	
		index += 1
	
	print "DONE SORTING SWITCHES"
	if inconsistency == 1:
		print "An inconsistency in your switch positions was automatically corrected."

#//////////////////////////////////////////////
# FUNCTION TO HANDLE COMMANDS
def parseCommand(commandIN):
	print "RECEIVED: "+commandIN
	colorWrite("green")
	command = commandIN.strip("\n")
	command = command.split(":")
	type = command[0]

	if type == "COM-RF":
		print "COM-RF"
		input = command[1]
		inputFreq = input[:3]
		print "FREQUENCY IS",inputFreq

		if boardType == "STANDARD":
			if inputFreq == masterFreq:
				com = "sudo nice -n -20 "+input[3:]+" "+inputFreq
				os.system(com)
			else:
				print "Sending command to slave instead!"
		elif boardType == "PRO":
			com = "sudo nice -n -20 "+input[3:]+" "+inputFreq
			os.system(com)			

	elif type == "AJAX-UPDATE":
		UID = command[1]
		newState = command[2]
		if newState == "1":
			notify("SWITCHED "+getSwitchName(UID)+" ON","power")
		if newState == "0":
			notify("SWITCHED "+getSwitchName(UID)+" OFF","power")
		print "AJAX updating",UID+"'s state to",newState
		sendAll("AJAX:"+UID+":"+newState)
		setConfigLine("/var/www/data/switches/"+UID+"/info.ini","HTML","State",str(newState))

	elif type == "LEARN":
		freq = command[1]
		state = command[2]
		os.system("sudo python /var/www/python/decode.py "+freq+" "+state)
		with open("/var/www/python/decode."+state) as f:
			message = f.read()
		sendAll("LEARNED|"+message)

	elif type == "ACTION":
		AID = command[1]
		doAction("data/actions/"+AID+".action")

	elif type == "FIX-SWITCHES":
		fixSwitchPositions()

	elif type == "RST":
		restart_program("NORM")

	elif type == "RST-FAST":
		restart_program("FAST")

	elif type == "MANUAL":
		print "MANUAL"
	else:
		print "Client says: "+commandIN

def doAction(file):
	with open(file) as f:
		actions = f.read()
	actions = actions.split("\n")
	actionNick = actions[0]
	for item in actions:
		if len(item) > 1:
			if item[0] != "*" and item[0] != "$" and item[0] != "/":
				item = item.split(" | ")
				UID = item[0]
				newState = item[1]
				try:
					with open("data/switches/"+UID+"/info.ini") as f:
						switchInfo = f.read()
					switchInfo = switchInfo.split("\n")
					for itemS in switchInfo:
						try:
							itemS = itemS.split(" = ")
							key = itemS[0]
							val = itemS[1]
							if key == "state":
								oldState = val;
						except:
							pass
					if str(oldState) != str(newState):
						com = item[2]
						parseCommand("AJAX-UPDATE:"+UID+":"+newState)
						parseCommand(com)
					else:
						print "Skipping",UID,", state is already",newState+"."

					UIDPresent.append(UID)
				except:
					print "ACTION CALLS FOR MISSING SWITCH!"
					fixActions()
					pass

def fixActions():
	print "Checking actions for inconsistencies..."
	actions = commands.getoutput("sudo ls data/actions").replace("\n"," ").split(" ")
	if str(actions) == "['']":
		return

	for file in actions:
		file = "data/actions/"+file
		UIDNeeded = []
		UIDPresent = []
		inconsistency = 0
	
		with open(file) as f:
			actions = f.read()
		actions = actions.split("\n")
		actionNick = actions[0]
		for item in actions:
			if len(item) > 1:
				if item[0] != "*" and item[0] != "$" and item[0] != "/":
					item = item.split(" | ")
					UID = item[0]
					UIDNeeded.append(UID)
					try:
						with open("data/switches/"+UID+"/info.ini") as f:
							switchInfo = f.read()
	
						UIDPresent.append(UID)
					except:
						inconsistency = 1
						print "ACTION "+actionNick+" CALLS FOR MISSING SWITCH!"
						pass
	
		if inconsistency == 1:
			print "Something wrong with action "+actionNick+", investigating..."
			missingSwitches = []
			outString = ""
			outLines = []
			for item in UIDNeeded:
				if item in UIDPresent:
					pass
				else:
					print "SWITCH "+item+" IS NO LONGER PRESENT!"
					missingSwitches.append(item)
			for item in missingSwitches:
				with open(file) as f:
			                actions = f.read()
			        actions = actions.split("\n")
			        for itemA in actions:
			                if len(itemA) > 1:
						UID = itemA[:5]
						if UID in missingSwitches:
							warning = "// (SWITCH with UID "+UID+" was deleted, so this command was removed automatically.)"
							if not warning in outLines:
								outLines.append(warning)
						else:
							if not itemA in outLines:
								outLines.append(itemA)
			for item in outLines:
				outString += item+"\n"
	
			with open(file,"w") as f:
				f.write(outString)
	
			print "A deleted switch was called in this action, the action was altered to no longer call for these switches:"
			print missingSwitches
	
			with open(file) as f:
	                        actions = f.read()
	                actions = actions.split("\n")
	
			switches = commands.getoutput("sudo ls data/switches").replace("\n"," ").split(" ")
	
			anyLeft = 0
	                for item in actions:
	                        if len(item) > 1:
					UID = item[:5]
					if UID in switches:
						anyLeft = 1
			if anyLeft == 0:
				print "Action "+actionNick+" now contains no current switches, deleting empty action."
				os.system("sudo rm "+file)
			else:
				print "After changes, action "+actionNick+" still contains at least one current switch."


def scheduleCheck():
	sendAll("WATCHDOG")

	global lastString

	t = str(datetime.datetime.now().time())
	t = t.split(".")
	x = str(t[0])
	x = x.split(":")
	hour = x[0]
	min = x[1]
	sec = x[2]
	timeString = hour+":"+min
	timeStringWeb = timeString+":"+sec
	sendAll("TIME|"+timeStringWeb)
	print "TIME = ",hour,min,sec
#	print timeString,lastString
	if not timeString == lastString:
		eventFound = False
		lastString = timeString
		print "TIME CHANGED, CHECKING SCHEDULED EVENTS..."
		notify("TIME IS NOW: "+timeString,"general")
		with open("python/event.list") as f:
			eventList = f.read()
		eventList = eventList.split("\n")
		for item in eventList:
			if len(item) > 3:
				item = item.split("|")
				nickS = item[0]
				typeS = item[1]
				hourS = item[2]
				minS = item[3]
				AIDS = item[4]
				eventTime = hourS+":"+minS
				if timeString == eventTime:
					eventFound = True
					print "EVENT",nickS,"HAPPENING!"
					notify("EVENT "+nickS+" HAPPENING!","general")
					doAction("data/actions/"+AIDS+".action");
					if typeS == "TEMP":
						print "Deleting temporary event '"+nickS+"'..."
						outString = ""
						with open("python/event.list") as f:
							eventList = f.read()
                				eventList = eventList.split("\n")
                				for itemLine in eventList:
                        				if len(itemLine) > 3:
                                				item = itemLine.split("|")
                                				AIDD = item[4]
								if AIDD != AIDS:
									outString = outString + itemLine + "\n"
						with open("python/event.list","w") as f:
							f.write(outString)
		if eventFound == False:
			print "Nothing scheduled."

#//////////////////////////////////////////////
# FUNCTION TO WRITE COLOR TO GPIO
def colorWrite(color):
	if rgbLed == "ENABLED":
		if color == "kill":
			GPIO.output(rPin,1)
			GPIO.output(gPin,1)
			GPIO.output(bPin,1)
		# this writes colors to the LED
		elif color == "red":
			GPIO.output(rPin,0)
			GPIO.output(gPin,1)
			GPIO.output(bPin,1)
		elif color == "green":
			GPIO.output(rPin,1)
			GPIO.output(gPin,0)
			GPIO.output(bPin,1)
		elif color == "blue":
			GPIO.output(rPin,1)
			GPIO.output(gPin,1)
			GPIO.output(bPin,0)

#/////////////////////////////////////////////

if rgbLed == "ENABLED":
	fade = 0
	while fade < 100:
		fade += 1
		rPWM.ChangeDutyCycle(fade)
		time.sleep(0.005)
else:
	colorWrite("kill")

def doLoop():
	blipCount = 1
	bloopCount = 1

	while True:
		time.sleep(0.1)
		colorWrite("kill")
		blipCount += 1
		bloopCount += 1
	
		if blipCount > 3:
			blipCount = 0
		
	#		GPIO.output(txPin,1)
	#		time.sleep(0.02)
	#		GPIO.output(txPin,0)
	#		time.sleep(0.02)
	
			getCPUusage()
			scheduleCheck()
	
		if bloopCount >= 60:
			colorWrite("red")
			print "BLOOP"
			bloopCount = 0
			fixSwitchPositions()
			fixActions()
		
		f = open("python/time.txt","w")
		f.write(str(time.time()))
		f.close()
	
		with open("python/command.list","r") as f:
			command = f.read()
	
		if len(command) > 0:
			f = open("python/command.list","w")
			f.write("")
			f.close()
	
		command = command.split("\n")
		for item in command:
			if len(item) > 1:
				colorWrite("green")
				print "!------------------------------------------------ RECEIVED COMMAND: " + item
				parseCommand(item)

def doAlive():
	while True:
		sendAliveSignal()
		time.sleep(0.5)

Thread(target = runServer).start()
Thread(target = doLoop).start()
Thread(target = doAlive).start()
