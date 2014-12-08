import os

with open("/etc/ep.root") as f:
	rootDir = f.read().strip("\n")

while True:
	print "LAUNCHING CONTROL WATCHDOG..."
	os.system("sudo python "+rootDir+"/control.py")
