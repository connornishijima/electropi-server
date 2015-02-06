import time
import socket
import os
import commands

pid = os.getpid()
with open("/tmp/slave.pid","w") as f:
	f.write(str(pid))

with open("/etc/ep.root") as f:
        rootDir = f.read().strip("\n")

os.chdir(rootDir)

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

def testForSlave(slaveIP):
	response = commands.getoutput("sudo nmap -sP "+slaveIP)
	print response
	if "Nmap done: 1 IP address (1" in response:
		print "UP!"
	else:
		print "DOWN!"
		with open("conf/slave.list") as f:
			slaveList = f.read()
		slaveList = slaveList.split("\n")
		slaveListOut = ""
		for item in slaveList:
			if len(item) > 3:
				searchLine = slaveIP+"|"+slaveFreq
				if item != searchLine:
					slaveListOut = slaveListOut + item + "\n"
		with open("conf/slave.list","w") as f:
			f.write(slaveListOut)

def testAllSlaves():
	with open("conf/slave.list") as f:
		slaveList = f.read()
	slaveList = slaveList.split("\n")
	for item in slaveList:
		if len(item) > 3:
			item = item.split("|")
			slaveIP = item[0]
			testForSlave(slaveIP)

def removeDupSlaves():
	print "Removing duplicate slave entries..."
	lines_seen = set() # holds lines already seen
	outfile = open("conf/slave.list.temp", "w")
	for line in open("conf/slave.list", "r"):
		if line not in lines_seen: # not a duplicate
			outfile.write(line)
	        	lines_seen.add(line)
	outfile.close()
	os.system("sudo mv conf/slave.list.temp conf/slave.list")
	os.system("sudo chmod 777 conf/slave.list")

def commandSlave(slaveIP,MESSAGE):
	TARGET_IP = slaveIP
	UDP_PORT = 5005
	
	print "UDP target IP:", TARGET_IP
	print "UDP target port:", UDP_PORT
	print "message:", MESSAGE
	
	sock = socket.socket(socket.AF_INET, # Internet
	                     socket.SOCK_DGRAM) # UDP
	
        sock.sendto(MESSAGE, (TARGET_IP, UDP_PORT))

iteration = 0

while True:
	time.sleep(0.1)
	iteration += 1
	print "LOOKING FOR SLAVES..."

	with open("conf/slave.list") as f:
		slaveList = f.read()
	slaveList = slaveList.split("\n")
	for item in slaveList:
		if len(item) > 3:
			if item[0] != "*":
				item = item.split("|")
				slaveIP = item[0]
				slaveFreq = item[1]
				print slaveFreq,"MHz SLAVE:",slaveIP
				with open("misc/command."+slaveFreq+".list") as f:
					commandList = f.read()
				
				if len(commandList) > 3:
					commandSlave(slaveIP,commandList)
	
					with open("misc/command."+slaveFreq+".list","w") as f:
	                                        f.write("")


	if iteration == 50:
		iteration = 0
		removeDupSlaves()
		testAllSlaves()
