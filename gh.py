import os

os.system("sudo git add -A")
print "What has changed in this commit?"
commit = raw_input()
os.system('sudo git commit -m "' + str(commit) + '"')
os.system("sudo git push -u origin master")
print "Done."
