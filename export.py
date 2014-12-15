print "ELECTROPI EPC EXPORTER\n"

from time import gmtime, strftime
import os

epcString = ""

print "BACKING UP CONFIGURATION..."
with open("conf/settings.conf") as f:
	config = f.read()
config = config.split("\n")
for item in config:
	if len(item) > 3:
		if item[0] != "#":
			epcString = epcString + "CONF $ " + item + "\n"

print "BACKING UP APPPLIANCES..."
with open("conf/appliances.txt") as f:
        appliances = f.read()
appliances = appliances.split("\n")
for item in appliances:
	if len(item) > 3:
		if item[0] != "#":
			epcString = epcString + "APPLIANCE $ " + item + "\n"

print "BACKING UP APPLIANCE STATES..."
with open("conf/applianceStates.txt") as f:
        applianceStates = f.read()
applianceStates = applianceStates.split("\n")
for item in applianceStates:
	if len(item) > 3:
		if item[0] != "#":
			epcString = epcString + "STATE $ " + item + "\n"

print "BACKING UP DEVICES..."
with open("conf/device.list") as f:
        devices = f.read()
devices = devices.split("\n")
for item in devices:
	if len(item) > 3:
		if item[0] != "#":
			epcString = epcString + "DEVICE $ " + item + "\n"

print "BACKING UP TRACKS..."
with open("conf/track.list") as f:
        tracks = f.read()
tracks = tracks.split("\n")
for item in tracks:
	if len(item) > 3:
		if item[0] != "#":
			epcString = epcString + "TRACK $ " + item + "\n"

print "BACKING UP EVENTS..."
with open("conf/event.list") as f:
        events = f.read()
events = events.split("\n")
for item in events:
	if len(item) > 3:
		if item[0] != "#":
			epcString = epcString + "EVENT $ " + item + "\n"

print "BACKING UP APP ORDER..."
with open("conf/app.order") as f:
        order = f.read()
order = order.split("\n")
for item in order:
	if len(item) > 3:
		if item[0] != "#":
			epcString = epcString + "ORDER $ " + item + "\n"

print "BACKING UP ROOT DIRECTORY POINTER..."
with open("conf/root.directory") as f:
        root = f.read()
	epcString = epcString + "ROOT $ " + root + "\n"

print "DELETING TEMP FOLDER..."
os.system("sudo mv conf/temp/uploads conf")
os.system("sudo rm -R conf/temp/*")
os.system("sudo mv conf/uploads conf/temp")

print "BACKING UP ACTIONS..."
os.system("sudo cp -R conf/actions conf/temp")
print "BACKING UP CLIENTS..."
os.system("sudo cp -R conf/clients conf/temp")

t = strftime("%Y-%m-%d-%H-%M-%S_", gmtime())

with open("conf/temp/restore.list","w+") as f:
	f.write(epcString)
os.chdir("conf/temp")
print "ZIPPING BACKUP..."
os.system("sudo zip -r "+t+"restore.epc .")
print "DONE!"

os.chdir("../../misc")

with open("export.state","w") as f:
	f.write("0")
