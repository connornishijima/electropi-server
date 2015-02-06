import os
import time
import signal

pid = os.getpid()
with open("/tmp/timeout.pid","w") as f:
	f.write(str(pid))

pids = {}
pidStuck = {}
timeout = 10

with open("/etc/ep.root") as f:
        rootDir = f.read().strip("\n")
os.chdir(rootDir)
os.system("sudo rm conf/pids/*.txt")
with open("conf/pids/pids.list","w") as f:
	f.write("")

with open("conf/pids/pids.list") as f:
        pidList = f.read()
pidList = pidList.split("\n")
for item in pidList:
        if len(item) > 3:
		item = item.split("|")
                pidS = item[0]
                sourceS = item[1]
		pids[pidS] = "X"
		pidStuck[pidS] = 0

print pids
print pidStuck

def check_pid(pid, source):        
	""" Check For the existence of a unix pid. """
	try:
		os.kill(pid, 0)
		print str(pid),"("+source+") is alive."
	except OSError:
		print str(pid),"("+source+") is dead."
		del pids[str(pid)]
		with open("conf/pids/pids.list") as f:
			pidList = f.read()
			pidList = pidList.split("\n")
			pidString = ""
			for item in pidList:
				if len(item) > 3:
					item = item.split("|")
					pidS = item[0]
					sourceS = item[1]
					search1 = str(pid)+"|"+sourceS
					search2 = pidS+"|"+sourceS
					print search1+"|"+search2
					if search1 == search2:
						pass
					else:
						pidString = pidString + pidS + "|" + sourceS + "\n"
		print pidString
		with open("conf/pids/pids.list","w") as f:
			f.write(pidString)		

def check_pid_time(pid,source):
	with open("conf/pids/"+pid+".txt") as f:
		ptime = f.read()
	try:
		if pids[pid] == ptime:
			print "STUCK!"
			stuckCount = pidStuck[pid]
			stuckCount = stuckCount + 1
			if stuckCount >= timeout:
				print str(pid),"("+source+") timed out! Killing process..."
				os.kill(int(pid), signal.SIGQUIT)
			pidStuck[pid] = stuckCount
			print "STUCK LIST:"
			print pidStuck
		else:
			print source,"RUNNING!"
			pids[pid] = ptime
			print "PID LIST:"
			print pids
			stuckCount = 0
                        pidStuck[pid] = stuckCount
	except:
		print "PID NOT FOUND, RETRY!"
		pids[pid] = "X"
		pass

while True:
	print "-----------------------------------------\nSLEEP\n-----------------------------------------\n"
	time.sleep(1)
	with open("conf/pids/pids.list") as f:
		pidList = f.read()
	pidList = pidList.split("\n")
	for item in pidList:
		if len(item) > 3:
			item = item.split("|")
			pidS = item[0]
                        sourceS = item[1]
			print "CHECKING STATUS OF",sourceS
			check_pid(int(pidS),sourceS)
			check_pid_time(pidS, sourceS)
			print "-----------------------------------------\n"
