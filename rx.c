#include <wiringPi.h>
#include <stdio.h>
#include <unistd.h>
#include <string>
#include <iostream>
#include <time.h>

	int main(int argc, char *argv[])
	{
		printf("RX BEGIN\n");
		wiringPiSetup();
		piHiPri(99);
		pinMode(4,INPUT);

		int count = 0;
		int length = 10000;
		while(count < length){
			int state = digitalRead(4);
			if(state == 1){
				printf("1");
			}
			else if(state == 0){
				printf("0");
			}
			usleep(50);
			count++;
		}
		printf("RX END\n");
	}
