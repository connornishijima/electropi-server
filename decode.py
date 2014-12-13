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

rows, columns = os.popen('stty size', 'r').read().split() # Get console width for pretty output!
os.system("clear") # Clear the terminal

# Check if an input file was specified, die if it was not.
try:
	inFile = sys.argv[1]
except:
	print "You must specify an input file as an argument. (e.g. 'sudo python decode.py infile.wav')"
	sys.exit(0)

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

start_time = time.time() # GO!

print "\n--------------------"
print "ElectroPi RF Decoder"
print "by Connor Nishijima"
print "--------------------\n"

print "Reading WAV file..."
waveFile = wave.open(inFile, 'r') # Open WAV file
inArray = []

# Print cool stats
print inFile,"opened."
print "Sample rate:",waveFile.getframerate()
print "Number of channels:",waveFile.getnchannels()
print "Total samples:",waveFile.getnframes()

# Check if the WAV has the proper format
if waveFile.getnchannels() != 1 or waveFile.getnframes() > 200000:
	print "XYou must supply a mono .WAV file with a sample rate of 22050 Hz/44100Hz, no more than 5 seconds in length."
	sys.exit(0)

sampleRateOK = False
if waveFile.getframerate() == 22050:
	sampleRateOK = True
if waveFile.getframerate() == 44100:
	sampleRateOK = True

if sampleRateOK == False:
	print "You must supply a mono .WAV file with a sample rate of 22050 Hz/44100Hz, no more than 5 seconds in length."
        sys.exit(0)

length = waveFile.getnframes()
print "File is",length,"samples /",(length/waveFile.getframerate()),"seconds long.\n"

is44KHz = False

if waveFile.getframerate() == 44100:
	is44KHz = True

# Warn stupid people if they can't follow directions
if waveFile.getnframes() > 12000 and is44KHz == False:
	print "\n*****\nWARNING: Supplying a clip longer than 0.5 seconds is usually redundant, and takes much longer to process.\nYour file is",(waveFile.getnframes()/waveFile.getframerate()),"seconds long.\n*****\n"
if waveFile.getnframes() > 23000 and is44KHz == True:
	print "\n*****\nWARNING: Supplying a clip longer than 0.5 seconds is usually redundant, and takes much longer to process.\nYour file is",(waveFile.getnframes()/waveFile.getframerate()),"seconds long.\n*****\n"

print "Building binary array..."

# Read through every sample of the file. If the sample is above zero, that's HIGH. If it's negative, that's LOW.
# These binary values populate in inArray[].

i = 0
while i < length:
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

	if is44KHz == False:
		i += 1
	else:
		i += 2

printPercent(length,length)

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

	if is44KHz == False:
		i += 1
	else:
		i += 2

printPercent(len(inArray),len(inArray))
print "\n\nPulse lengths:"
print countArray

lh = lowHigh(countArray) # Get the lowest and highest value in countArray[]
med = median(lh) # Median between the two
space = lh[1] # Code spacing in samples

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

print "\nDecoder finished in",str(elapsed_time),"seconds! Your code is above. Each bit repesents a state of LOW or HIGH (0 or 1) for a period of ~45uS - or 1 sample at 22050 Hz.\n"
