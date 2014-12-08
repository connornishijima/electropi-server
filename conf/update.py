import os
import sys
import time
import urllib2

def internet_on():
	try:
		response=urllib2.urlopen('http://74.125.228.100',timeout=1)
		print "INTERNET CONNECTION: TRUE"
		return True
	except urllib2.URLError as err: pass
	print "INTERNET CONNECTION: FALSE"
	return False

os.chdir("/var/www/conf")

with open("updating.state","w") as f:
	f.write("TRUE")

def dprint(string):
	print string
	with open("/var/www/conf/update.log","a") as f:
		f.write(string + "\n")

dprint("<br><font style='font-family:monospace;'>Checking for new version...")
os.system("sudo wget http://connor-n.com/electropi/remote.version -O remote.version")

with open("local.version") as f:
	localV = f.read()
with open("remote.version") as f:
	remoteV = f.read()

localV = localV.strip("\n")
remoteV = remoteV.strip("\n")

dprint("Local version is:<br>|" + localV + "|")
dprint("Remote version is:<br>|" + remoteV + "|")

if not localV == remoteV:
	if internet_on() == True:
		dprint("<pre style='color:rgb(251, 219, 0)'>OOH! NEW UPDATE AVAILABLE!</pre>")
		
		os.chdir("/var/www")
	
		dprint("Removing old server software...")
		os.system("sudo chmod u+x /var/www/remove.sh")
		os.system("sudo /var/www/remove.sh")
		dprint("Downloading new server version...")
		os.system("sudo wget http://www.connor-n.com/electropi/" + remoteV + ".zip")
		os.system("sudo wget http://www.connor-n.com/electropi/change.log")
		dprint("Extracting...")
		os.system("sudo unzip -n " + remoteV + ".zip")
		dprint("Setting permissions...")
		os.system("sudo chmod -R 777 *")
		dprint("Cleaning up...")
		os.system("sudo rm *.zip")
	
		os.chdir("/var/www/conf")
	
		os.system("sudo cp remote.version local.version")
		with open("local.version") as f:
		        localV = f.read()
		dprint("Local version is now updated to: " + localV)

		with open("../misc/command.list", "w") as f:
			f.write("RST")

else:
	dprint("<pre style='color:Aquamarine;'>VERSION IS UP TO DATE!</pre>")

with open("updating.state","w") as f:
	f.write("FALSE")

dprint("DONE!</font>")
dprint(" ")
os.chdir("/var/www")
