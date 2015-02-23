import commands
import os

deviceStates = {}
print "START"

def identifyDevice(ip):
	print "Identifying",ip
	netInterface = "wlan0"
	response = commands.getoutput("sudo arp-scan -I " + str(netInterface) + " " + str(ip))
	response = response.split("\n")
	response = response[2].split("\t")

	try:
		mac = response[1]
		print("IP "+ip+" IS MAC ADDRESS: "+mac)

	except:
		identifyDevice(ip)

def checkAllClients():
	with open("client.list") as f:
		ipList = f.read()
	ipList = ipList.split("\n")
	for item in ipList:
		if len(item) > 3:
			IP = item
			response = commands.getoutput("sudo nmap -sP "+IP)
			if "Nmap done: 1 IP address (1" in response:
				try:
					if deviceStates[IP] != "PRESENT" and deviceStates[IP] != "MISSING":
						print IP,"is up."
						deviceStates[IP] = "PRESENT"
				except KeyError:
					deviceStates[IP] = "PRESENT"
			else:
				try:
					if deviceStates[IP] != "ABSENT" and deviceStates[IP] != "MISSING":
						if deviceStates[IP] != "MISSING":
							deviceStates[IP] = "MISSING"
					elif deviceStates[IP] != "ABSENT":
						deviceStates[IP] = "ABSENT"
				except KeyError:
					deviceStates[IP] = "ABSENT"


checkAllClients()

while True:
	checkAllClients()
	print deviceStates
