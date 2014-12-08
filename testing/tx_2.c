#include <wiringPi.h>
#include <stdio.h>
#include <unistd.h>
#include <string>
#include <iostream>
#include <time.h>
#include <cstring>

	int main(int argc, char *argv[])
	{
		printf("TX BEGIN\n");
		wiringPiSetup();
		piHiPri(99);
		pinMode(5,OUTPUT);

		std::string code = argv[1];
		int repeat;
        	sscanf (argv[2],"%d",&repeat);
		int speed;
        	sscanf (argv[3],"%d",&repeat);

		int length = code.length();
		int times = 0;

		while(times < repeat){
			int count = 0;
			while(count < length){
				char bit = code[count];
				if(bit == '1'){
					//printf("1");
					digitalWrite(5,HIGH);
				}
				if(bit == '0'){
					//printf("0");
					digitalWrite(5,LOW);
				}
				usleep(speed);
				count++;
			}
			times++;
		}
		printf(" TX END\n");
   		return 0;
	}
