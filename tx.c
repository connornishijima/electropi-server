#include <wiringPi.h>
#include <stdio.h>
#include <unistd.h>
#include <string>

	int main(int argc, char *argv[])
	{
		wiringPiSetup();
		piHiPri(99);
		pinMode(5,OUTPUT);
		std::string code = argv[1];

		int times = 0;
		int repeat;
                sscanf (argv[2],"%d",&repeat);

		int length = code.length();
		int count = 0;

		while(times < repeat){
			while(count < length){
				char bit = code[count];
				if(bit == '1'){
					digitalWrite(5,HIGH);
				}
				if(bit == '0'){
					digitalWrite(5,LOW);
				}
				delayMicroseconds(45);
				count++;
			}
			count = 0;
		times++;
		}
   		return 0;
	}
