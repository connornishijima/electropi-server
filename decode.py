from __future__ import division
import sys
import wave, struct
import os

rows, columns = os.popen('stty size', 'r').read().split()
try:
	inFile = sys.argv[1]
except:
	print "You must specify an input file as an argument. (e.g. 'sudo python decode.py infile.wav')"
	sys.exit(0)

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

def prettyPrint(s,n):
	import re
	print re.sub("(.{"+str(n)+"})", "\\1\n", s, 0, re.DOTALL)

def median(numbers):
    return (sorted(numbers)[int(round((len(numbers) - 1) / 2.0))] + sorted(numbers)[int(round((len(numbers) - 1) // 2.0))]) / 2.0

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

def printPercent(soFar,total):
	percent = soFar/total
	drawProgressBar(percent,50,soFar,"/",total,"samples")

print "\n--------------------"
print "ElectroPi RF Decoder"
print "by Connor Nishijima"
print "--------------------\n"

print "Reading WAV file..."
waveFile = wave.open(inFile, 'r')
inArray = []

print inFile,"opened."
print "Sample rate:",waveFile.getframerate()
print "Number of channels:",waveFile.getnchannels()
print "Total samples:",waveFile.getnframes()

if waveFile.getframerate() != 22050 or waveFile.getnchannels() != 1 or waveFile.getnframes() > 110250:
	print "You must supply a mono .WAV file with a sample rate of 22050 Hz, no more than 5 seconds in length."
	sys.exit(0)

length = waveFile.getnframes()
print "File is",length,"samples /",(length/22050),"seconds long.\n"

print "Building binary array..."

for i in range(0,length):
        waveData = waveFile.readframes(1)
        data = struct.unpack("<h", waveData)
        if int(data[0]) > 0:
                inArray.append("1")
		if str(i)[-2:] == "00":
			printPercent(i,length)
        else:
                inArray.append("0")
		if str(i)[-2:] == "00":
			printPercent(i,length)
printPercent(length,length)

countArray = []
count = 0
i = 0
bit = "X"
lastBit = "X"

print "\nGetting pulse lengths..."

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

lh = lowHigh(countArray)
med = median(lh)
space = lh[1]

outArray = []

totalSamples = 0
keep = 0
done = 0

i = 0
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

print "\nCODE FOUND: " + ("="*(int(columns)-12))
print ("="*int(columns)) + "\n"
print outString
print "\n"+("="*int(columns))
print ("="*int(columns)) + "\n"

print "\nTX CODE BEGIN..."
os.system("sudo /var/www/tx "+outString+" 30")
print "TX END"
