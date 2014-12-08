#include <wiringPi.h>
#include <stdio.h>
#include <unistd.h>
#include <string>
#include <iostream>

	int main(int argc, char *argv[])
	{
		printf("TX BEGIN\n");
		wiringPiSetup();
		pinMode(5,OUTPUT);
		std::string code = argv[1];
		int longPulseInt;
		sscanf (argv[2],"%d",&longPulseInt);
		int shortPulseInt;
		sscanf (argv[3],"%d",&shortPulseInt);
		int spaceInt;
		sscanf (argv[4],"%d",&spaceInt);
		int repeat;
        	sscanf (argv[5],"%d",&repeat);
		int times = 0;
		while(times < repeat){
			int count = 0;
			int length = code.length();
			while(count < length){
				char bit = code[count];
				if(bit == '1'){
					digitalWrite(5,HIGH);
					usleep(longPulseInt);
					digitalWrite(5,LOW);
					usleep(shortPulseInt);
				}
				if(bit == '0'){
					digitalWrite(5,HIGH);
					usleep(shortPulseInt);
					digitalWrite(5,LOW);
					usleep(longPulseInt);
				}
				count++;
			}
			usleep(spaceInt);
			times++;
		}
		printf("TX END\n");
   		return 0;
	}
