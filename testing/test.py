import time
import RPi.GPIO as GPIO

GPIO.setwarnings(False)
GPIO.setmode(GPIO.BOARD)
GPIO.setup(18,GPIO.OUT)

times = [8,14,8,14,8,14,15,6,14,7,15,7,15,6,8,14,8,14,8,13,8,14,14,7,200]

sampleSize = 0.000045;
state = 0
count = 0

repeat = 30


while count < repeat:
	for item in times:
		if state == 0:
			state = 1
			GPIO.output(18,1)
		elif state == 1:
			state = 0;
			GPIO.output(18,0)
		sleepTime = ((sampleSize*item))
		time.sleep(sleepTime)
	count += 1
