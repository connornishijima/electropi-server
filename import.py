import sys
import os

def insertConf(newLine):
	newLineS = newLine.split("=")
	setName = newLineS[0]
	setValue = newLineS[1]
	
	outString = ""

	with open("../settings.conf") as f:
		conf = f.read()
	conf = conf.split("\n")
	for item in conf:
		if len(item) > 3 and item[0] != "#":
			item = item.split("=")
			storedName = item[0]
			storedValue = item[1]
			if storedName == setName:
				storedValue = setValue
			outString = outString + storedName+"="+storedValue+"\n"

	with open("../settings.conf","w") as f:
		f.write(outString)

def insertAppliance(newLine):
	applianceExists = False
	with open("../appliances.txt") as f:
		appliances = f.read()
	appliancesList = appliances.split("\n")
	for item in appliancesList:
		if item == newLine:
			applianceExists = True
			print "APP MATCH!",newLine.strip("\n")

	if applianceExists == False:
		appliances = appliances + newLine+"\n"
		with open("../appliances.txt","w") as f:
			f.write(appliances)

def insertApplianceS(newLine):
	applianceSExists = False
	with open("../applianceStates.txt") as f:
		appliancesS = f.read()
	appliancesSList = appliancesS.split("\n")
	for item in appliancesSList:
		if item == newLine:
			applianceSExists = True
			print "STATE MATCH!",newLine.strip("\n")

	if applianceSExists == False:
		appliancesS = appliancesS + newLine+"\n"
		with open("../applianceStates.txt","w") as f:
			f.write(appliancesS)

def insertApplianceO(newLine):
	applianceOExists = False
	with open("../app.order") as f:
		appliancesO = f.read()
	appliancesOList = appliancesO.split("\n")
	for item in appliancesOList:
		if item == newLine:
			applianceOExists = True
			print "ORDER MATCH!",newLine.strip("\n")

	if applianceOExists == False:
		appliancesO = appliancesO + newLine+"\n"
		with open("../app.order","w") as f:
			f.write(appliancesO)

def insertDevice(newLine):
	deviceExists = False
	with open("../device.list") as f:
		devices = f.read()
	devicesList = devices.split("\n")
	for item in devicesList:
		if item == newLine:
			deviceExists = True
			print "DEVICE MATCH!",newLine.strip("\n")

	if deviceExists == False:
		devices = devices + newLine+"\n"
		with open("../device.list","w") as f:
			f.write(devices)

def insertEvent(newLine):
	eventExists = False
	with open("../event.list") as f:
		events = f.read()
	eventsList = events.split("\n")
	for item in eventsList:
		if item == newLine:
			eventExists = True
			print "EVENT MATCH!",newLine.strip("\n")

	if eventExists == False:
		events = events + newLine+"\n"
		with open("../event.list","w") as f:
			f.write(events)

def insertTrack(newLine):
	trackExists = False
	with open("../track.list") as f:
		tracks = f.read()
	tracksList = tracks.split("\n")
	for item in tracksList:
		if item == newLine:
			trackExists = True
			print "TRACK MATCH!",newLine.strip("\n")

	if trackExists == False:
		tracks = tracks + newLine+"\n"
		with open("../track.list","w") as f:
			f.write(tracks)

def insertRoot(newLine):
	with open("../root.directory","w") as f:
		f.write(newLine)

epcFile = sys.argv[1]
print epcFile

os.chdir("conf/temp")
os.system('sudo find . ! -name "*.epc" -exec rm -rf {} \;')

os.system("sudo unzip restore.epc")

os.system("sudo cp -R actions ..")
os.system("sudo cp -R clients ..")

with open("restore.list") as f:
	restoreList = f.read()

restoreList = restoreList.split("\n")

for item in restoreList:
	if len(item) > 3:
		item = item.split(" $ ")
		type = item[0]
		value = item[1]
		if type == "CONF":
			insertConf(value)
		if type == "APPLIANCE":
			insertAppliance(value)
		if type == "STATE":
			insertApplianceS(value)
		if type == "ORDER":
			insertApplianceO(value)
		if type == "DEVICE":
			insertDevice(value)
		if type == "EVENT":
			insertEvent(value)
		if type == "TRACK":
			insertTrack(value)
		if type == "ROOT":
			insertRoot(value)
