#------------------------------------------------------------------
#
# ElectroPi EXPLORER
#
# - A way for folks to turn WAV's into control codes.
#
# To use this script, record a ~0.5 second sample of your remote's
# control code at 22050 Hz in your DAW of choice. Then, export the
# recording as a mono, 16-bit WAV file with a 22050 Hz sample rate.
# Then, include the exported file as an argument to this script:
#
# sudo python decode.py recording.wav
#
#------------------------------------------------------------------

# Import modules
from __future__ import division
import sys
import wave, struct
import os
import time
import commands
import string
import random
import re

try:
	rows, columns = os.popen('stty size', 'r').read().split() # Get console width for pretty output!
	os.system("clear") # Clear the terminal
except:
	rows = 100
	columns = 100
	pass

# Check if an input frequency was specified, die if it was not.
try:
	freq = sys.argv[1]
except:
	print "ERROR: You must specify a frequency as an argument. (e.g. 'sudo python decode.py 315 OFF')"
	sys.exit(0)

# Check what switch state we're learning
try:
	global switchType
	switchType = sys.argv[2]
except:
	print "ERROR: You must specify a code type as an argument. (e.g. 'sudo python decode.py 315 OFF')"
	sys.exit(0)

# Check if we should just return a code
try:
	quiet = sys.argv[3]
	if quiet == "quiet":
		verbose = False
except:
	verbose = True

# Function for generating serial string
def id_generator(size=6, chars=string.ascii_uppercase):
	return ''.join(random.choice(chars) for _ in range(size))

# Function for progress bar printing
def drawProgressBar(percent, barLen = 20, num=" ", sep=" ",den=" ",units=" "):
    sys.stdout.write("\r")
    progress = ""
    for i in range(barLen):
        if i < int(barLen * percent):
            progress += "="
        else:
            progress += " "
    sys.stdout.write("[%s] %.2f%% %s%s%s %s" % (progress, percent * 100,num,sep,den,units))
    sys.stdout.flush()

# Function to get the median of a number set
def median(numbers):
    return (sorted(numbers)[int(round((len(numbers) - 1) / 2.0))] + sorted(numbers)[int(round((len(numbers) - 1) // 2.0))]) / 2.0

# Find the lowest and highest number of a set
def lowHigh(numbers):
	lowest = 100000
	highest = 0
	last = 0
	for item in numbers:
		if item > highest:
			highest = item

	for item in numbers:
		if item < lowest:
			lowest = item

	return [lowest,highest]

# Passes data to drawProgressBar()
def printPercent(soFar,total):
	percent = soFar/total
	drawProgressBar(percent,50,soFar,"/",total,"samples")

def output(message):
	global switchType
	with open("/var/www/python/decode."+switchType,"w") as f:
		f.write(message)

def prettyWrap(s):
	return re.sub("(.{64})", "\\1\n", s, 0, re.DOTALL)

start_time = time.time() # GO!

print "\n--------------------"
print "ElectroPi RF Decoder"
print "by Connor Nishijima"
print "--------------------\n"

print "Building binary array..."

inBin = "111111"+commands.getoutput("sudo /var/www/python/rx "+freq)
inArray = list(inBin)

countArray = []
count = 0
i = 0
bit = "X"
lastBit = "X"

print "\nGetting pulse lengths..."

# Read through inArray and get the length in samples of each HIGH and LOW.
# These counts populate in countArray[].
while i < len(inArray):
	bit = inArray[i]
	
	if bit != lastBit:
		lastBit = bit
		countArray.append(count)
		count = 0
	else:
		count += 1
	if str(i)[-2:] == "00":
		printPercent(i,len(inArray))

	i += 1

printPercent(len(inArray),len(inArray))
print "\n\nPulse lengths:"
print countArray

lh = lowHigh(countArray) # Get the lowest and highest value in countArray[]
med = median(lh) # Median between the two
space = lh[1] # Code spacing in samples

print lh
print med
print space

outArray = []

totalSamples = 0
keep = 0
done = 0

i = 0
# Trim the code data to one single code using spaces to help truncate
while i < len(countArray):
	length = countArray[i]
	if length > med:
		if keep == 1 and done == 0:
			keep = 0
			done = 1
		if keep == 0 and done == 0:
			keep = 1
	else:
		if done == 0 and keep == 1:
			outArray.append(length)

	i += 1

outArray.append(space)
print "\nCode pulse lengths:"
print outArray

bit = 0
outString = ""
first = 1

# Convert to final output string of binary
for item in outArray:
	if bit == 0:
		if first == 1:
			first = 0
		else:
			outString = outString + ("0")
		bit = 1
		outString = outString + ("1" * item)
	elif bit == 1:
		if first == 1:
			first = 0
		else:
			outString = outString + ("1")
		bit = 0
		outString = outString + ("0" * item)

print "\n\nCODE FOUND: " + ("="*(int(columns)-12))
print ("="*int(columns)) + "\n"
print outString
print "\n"+("="*int(columns))
print ("="*int(columns)) + "\n"

# Done!
elapsed_time = time.time() - start_time
elapsed_time = ("%.2f" % round(elapsed_time,2))

totalCount = len(outString)
zeroCount = 1
i = 0
while i < totalCount:
	if outString[i] == "0":
		zeroCount+=1
	i+=1

zeroPercent = (zeroCount/totalCount)*100
outString = prettyWrap(outString)

print "\nDecoder finished in",str(elapsed_time),"seconds! Your code is above. Each bit repesents a state of LOW or HIGH (0 or 1) for a period of ~45uS - or 1 sample at 22050 Hz.\n"

if totalCount > 2000 or zeroPercent > 95:
	warning = "WARNING! \nThis code is pretty long,("+str(totalCount)+" chars) and pretty empty. ("+str(int(zeroPercent))+"% zeroes) Try re-running the decoder for best results."
	print warning
	output(warning+" \n\n"+outString)
elif zeroCount == 1:
	warning = "WARNING! \nThis code is pretty strange,(Only "+str(zeroCount-1)+" zeroes) Try re-running the decoder for best results."
	print warning
	output(warning+" \n\n"+outString)	
else:
	fileString = "GOOD!\n\n"+outString
	output(fileString)
