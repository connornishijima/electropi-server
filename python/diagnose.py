import time
import os
import commands

print "ELECTROPI DIAGNOSTIC TOOL"

def askBool(question):
	yesResponses = ["y","Y","yes","YETH PLEATH"]
	noResponses = ["n","N","no","NOOOOOOOOOOOOOOOOOOOOOOO!"]
	
	answer = raw_input("\n"+question+"\n")
	if answer in yesResponses:
		answer = True
	elif answer in noResponses:
		answer = False
	else:
		print "Answer",answer,"not valid."
		answer = "NULL"

	return answer

def checkPackages():
	print "Checking linux package installations..."
	try:
		import apt
	except:
		if askBool("python-apt is necessary, but not a standard Python module. Would you like me to install it real quick? (Y/N)") == True:
			os.system("sudo apt-get install python-apt -y")
			import apt
		else:
			print "Cannot check installed Linux packages without python-apt. Skipping."
			return False
	
	cache = apt.Cache()
	linuxPackages = ["apache2","php5"]
	needed = len(linuxPackages)
	have = 0
	missingList = []

	for item in linuxPackages:
		try:
			if cache[item].is_installed:
				print item,"is installed"
				have += 1
			else:
				print item,"is NOT installed! --------------!"
				missingList.append(item)
		except:
			print item,"is NOT installed! --------------!"
			missingList.append(item)			
	
	print str(have)+"/"+str(needed),"packages installed."
	missing = needed - have
	if missing > 0:
		print "!+++++++++++++++++++++++++++++++++++++++!"
		print "ERROR! Missing packages:"
		for item in missingList:
			print item
		print "!+++++++++++++++++++++++++++++++++++++++!"

		if askBool("Whoops! You are missing some ElectroPi dependencies! Want me to fix that? (Y/N)") == True:
			for item in missingList:
				os.system("sudo apt-get install "+item+" -y")

	print "Linux Package Check DONE."
	return True

def checkPIDs():
	scripts = ["control","client","slave","wemo","timeout"]
	liveList = []
	deadList = []

	for item in scripts:
		try:
			with open("/tmp/"+item+".pid") as f:
				pid = f.read()
			try:
				os.kill(pid, 0)
				liveList.append(item)
				print item,"script is running!"
			except OSError:
				deadList.append(item)
				print item,"script is dead! ------------!"
		except:
			deadList.append(item)
			print item,"script is dead! ------------!"

	if len(deadList) > 0:
		print "!+++++++++++++++++++++++++++++++++++++++!"
		print "ERROR! Scripts not running:"
		for item in deadList:
			print item
		print "!+++++++++++++++++++++++++++++++++++++++!"

checkPIDs()
