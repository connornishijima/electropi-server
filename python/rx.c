#include <wiringPi.h>
#include <stdio.h>
#include <unistd.h>
#include <string>

	int main(int argc, char *argv[])
	{
		int freq;
		sscanf (argv[1],"%d",&freq);
		int pin;

		if(freq == 315){
			pin = 1;
		}
		else if(freq == 433){
			pin = 4;
		}

		wiringPiSetup();
		piHiPri(99);
		pinMode(pin,INPUT);

		int length = 22050;
		int count = 0;
		char out[length];
		char bit;

		while(count < length){
			int state = digitalRead(pin);
			if(state == HIGH){
				bit = '1';
			}
			if(state == LOW){
				bit = '0';
			}
			out[count] = bit;
			delayMicroseconds(45);
			count++;
		}
		count = 0;
		int j;

		for ( j = 0; j < length; j++ ){
			printf( "%c", out[j] );
		}
   		return 0;
	}
