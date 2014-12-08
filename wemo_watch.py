import os
import time

#//////////////////////////////////////////////
# READ SINGLE SETTING FUNCTION...
def readSetting(searchName):
        with open("/var/www/conf/settings.conf") as f:
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

while True:
	time.sleep(1)
	wemoSupport = readSetting("WEMO_SUPPORT")
	if wemoSupport == "ENABLED":
		print "LAUNCHING WEMO WATCHDOG..."
		os.system("sudo python /var/www/wemo.py")
