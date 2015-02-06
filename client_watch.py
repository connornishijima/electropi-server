import time
import commands
import urllib2
import os
import psutil
import sys

pid = os.getpid()
with open("/tmp/client.pid","w") as f:
	f.write(str(pid))

with open("/etc/ep.root") as f:
	rootDir = f.read().strip("\n")

os.nice(20)
os.chdir(rootDir)

class Logger(object):
    def __init__(self):
        self.terminal = sys.stdout
        self.log = open(rootDir+"/logs/client_watch.log", "a")

    def write(self, message):
        self.terminal.write(message)
        self.log.write(message)

sys.stdout = Logger()

top = commands.getoutput("sudo top -bn1")
with open("misc/top.txt","w") as f:
	f.write(top)

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
                                        return storedValue.strip("\n")
        except:
                print "SETTING ERROR: NAME" + searchName + " NOT FOUND!"

#//////////////////////////////////////////////

netInterface = readSetting("NET_INTERFACE")

def dprint(string):
	print string
	with open("misc/macDebug.txt","a") as f:
		f.write(str(string) + " <br>\n")

def identifyDevice(ip):
	netInterface = readSetting("NET_INTERFACE")
	with open("misc/macDebug.txt","w") as f:
		f.write("")
	dprint("IDENTIFYING "+ip+"...")
	response = commands.getoutput("sudo arp-scan -I " + str(netInterface) + " " + str(ip))
	print response
	dprint(str(response))
	response = response.split("\n")
	response = response[2].split("\t")

	try:
		mac = response[1]
		dprint("MAC of device is "+mac)
		dprint("IP "+ip+" IS MAC ADDRESS: "+mac)
		with open("conf/device.list") as f:
			deviceInfo = f.read()
		deviceInfo = deviceInfo.split("\n")
		device = False
		for item in deviceInfo:
			print item
			if len(item) > 3:
				item = item.split("|")
				nickS = item[0]
				macS = item[1]
				ipS = item[2].strip("\n")
				if macS.lower() == mac.lower():
					dprint("FOREIGN IP IDENTIFIED AS: "+nickS+" "+macS)
					with open("misc/mac.txt","w+") as f:
						f.write("GOOD|"+ip+"|"+mac)
					devFound(nickS)
					device = True
					with open("conf/device.list") as f:
						deviceInfo = f.read()
						deviceString = ""
					deviceInfo = deviceInfo.split("\n")
					for item in deviceInfo:
						if len(item) > 1:
							item = item.split("|")
							nickX = item[0]
							macX = item[1]
							ipX = item[2]
							if macX == macS:
								ipX = ip
								deviceString = deviceString + nickX + "|" + macX + "|" + ipX + "\n"
								with open("conf/device.list","w") as f:
									f.write(deviceString)
								with open("conf/clients/" + ip + ".txt","w+") as f:
									f.write("1")
									might = ""
				else:
					with open("misc/mac.txt","w+") as f:
                                                f.write("BAD|"+ip+"|"+mac)
		if device == False:
			with open("misc/mac.txt","w+") as f:
	                        f.write("BAD|"+ip+"|"+mac)			

	except:
		identifyDevice(ip)


def commandCheck():
	with open("conf/clients/command.list") as f:
		command = f.read()
	if len(command) > 1:
		with open("conf/clients/command.list","w") as f:
			f.write("")
		command = command.strip("\n")
		command = command.split("|")
		if command[0] == "IDENTIFY":
			ip = command[1]
			identifyDevice(ip)

def checkTrack(mac,state):
	print "TRACK"
	with open("conf/track.list") as f:
		trackList = f.read()
	trackList = trackList.split("\n")
	print trackList
	for item in trackList:
		if len(item) > 3:
			print item
			item = item.split("|")
			macL = item[0]
			jAction = item[1]
			lAction = item[2]
			print mac,macL
			if mac == macL:
				if state == "JOIN":
					print "DO ACTION:",jAction
					response = urllib2.urlopen("http://127.0.0.l/system.php?type=ACTION&AID="+jAction)
				elif state == "LEAVE":
					print "DO ACTION:",lAction
					response = urllib2.urlopen("http://127.0.0.1/system.php?type=ACTION&AID="+lAction)

def getInterfaces():
        listf = ""
        list = commands.getoutput("sudo ifconfig")
        list = list.split("\n")
        for item in list:
                if not item[0:3] == "   " and len(item)>3:
                        item = item.split(" ")
                        if not item[0] == "lo":
                                listf = listf + item[0]+"\n"
        with open("misc/network.ifaces","w+") as f:
                f.write(listf)

while True:
	commandCheck()

	with open("conf/device.list") as f:
		deviceList = f.read()
	deviceList = deviceList.split("\n")
	for item in deviceList:
		if len(item) > 3:
			item = item.split("|")
			nick = item[0]
			mac = item[1]
			ip = item[2]
			print "CHECKING "+nick
			response = commands.getoutput("sudo nmap -sP "+ip)
			print response
			if "Nmap done: 1 IP address (1" in response:
				newStatus = "1"
				with open("conf/clients/"+ip+".txt") as f:
					oldStatus = f.read()
				with open("conf/clients/"+ip+".might","w") as f:
                                	f.write("0")
				if newStatus == "1" and oldStatus == "0":
					print "PLAUSIBLE RETURN OF "+nick+", CHECKING MAC ADDRESS..."
					tryCount = 0
					while tryCount < 3:
						try:
							command = "sudo arp-scan -I " + str(netInterface) + " " + str(ip)
							print command
							response = commands.getoutput(command)
							if response == "ioctl: No such device":
								print "TRIED USING AN INTERFACE THAT DOESN'T EXIST AND FAILED!"
							response = response.split("\n")
							response = response[2].split("\t")
	
							macSeen = response[1]
							tryCount = 99
						except:
							tryCount += 1
					if mac.upper() == macSeen.upper():
						print "EXPECTED: "+mac
						print "SAW:      "+macSeen
						print nick+" IS BACK ONLINE!"
						with open("conf/clients/"+ip+".txt","w") as f:
		                                        f.write("1")
						checkTrack(mac,"JOIN")
					else:
						print "EXPECTED: "+mac
						print "SAW:      "+macSeen
						print ip+" is no longer tied to "+nick+". Removing device from Trusted list for now."
						
			elif "Nmap done: 1 IP address (0" in response:
				print "NOT SEEN"
				with open("conf/clients/"+ip+".txt") as f:
                                        oldStatus = f.read()
				if oldStatus == "1":
					newStatus = "0"
					with open("conf/clients/"+ip+".might") as f:
						mightCount = f.read()
					if int(mightCount) < 4:
						mightCount = int(mightCount) + 1
						with open("conf/clients/"+ip+".might","w") as f:
							f.write(str(mightCount))
					else:
						mightCount = "0"
                                                with open("conf/clients/"+ip+".might","w") as f:
                                                        f.write(mightCount)

						with open("conf/clients/"+ip+".txt") as f:
							oldStatus = f.read()
						if newStatus == "0" and oldStatus == "1":
							print nick+" FELL OFFLINE!"
							with open("conf/clients/"+ip+".txt","w") as f:
		        	                                f.write("0")
							checkTrack(mac,"LEAVE")

	print "Updating network interface list..."
	getInterfaces()
	print "Checking CPU USAGE..."
	cpu = int(psutil.cpu_percent())
        if not cpu == 0:
        	print "CPU:",cpu
        	with open("misc/cpu.txt","w") as f:
        		f.write(str(cpu)+"%")
        if readSetting("CPU_MON") == "ENABLED":
        	top = "CPU USAGE: " + str(cpu) + "%\n\n" + commands.getoutput("ps -eo pcpu,pid,user,args | sort -k 1 -r | head -20")
        	with open("misc/top.txt","w") as f:
        		f.write(top)

	print "sleep"
	time.sleep(3)
