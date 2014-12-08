#include <wiringPi.h>
#include <stdio.h>
#include <unistd.h>
#include <string>
#include <iostream>
#include <time.h>
#include <cstring>
#include <stdlib.h>

	int main(int argc, char *argv[])
	{
		int arraySize = 64;
		float array[arraySize];
		float sampleSize = 0.00004535147;

		int ctr;
		for( ctr=1; ctr < argc; ctr++ ){
			array[ctr] = atoi(argv[ctr]);
		}
		ctr = 1;

		for( ctr=1; ctr < argc; ctr++ ){
                        printf("%d ",array[ctr]);
                }

		printf("TX BEGIN\n");
		wiringPiSetup();
		piHiPri(99);
		pinMode(5,OUTPUT);

		int count = 1;
		int state = 0;
		while(count < arraySize){
			if(state == 0){
				printf("1");
				state = 1;
				digitalWrite(5,HIGH);
				usleep(array[count] * sampleSize);
			}
			else if(state == 1){
				printf("0");
				state = 0;
				digitalWrite(5,LOW);
				usleep(array[count] * sampleSize);
			}
			count++;
		}

		printf("TX END\n");
   		return 0;
	}
