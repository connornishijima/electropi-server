import os

#print "Erasing user-specific files..."
os.chdir("/var/www")
#with open("testing/erase.list") as f:
#        eraseList = f.read()
#        eraseList = eraseList.split("\n")
#
#os.system("sudo rm /var/www/conf/actions/*.txt")
#os.system("sudo rm /var/www/conf/clients/*.txt")
#os.system("sudo rm /var/www/conf/pids/*.txt")
#os.system("sudo rm /var/www/conf/clients/*.might")

#for item in eraseList:
#        if len(item) > 3:
#                print "Erasing:",item
#                with open(item,"w") as f:
#                        f.write('')

#print "Restoring default configuration..."
#os.system("sudo mv conf/default.conf conf/settings.conf")

print "Uploading..."
os.system("sudo git add -A")
print "What has changed in this commit?"
commit = raw_input()
print "Which branch is this for?"
branch = raw_input()
os.system('sudo git commit -m "' + str(commit) + '"')
os.system("sudo git push -u origin "+branch)
print "Done."
