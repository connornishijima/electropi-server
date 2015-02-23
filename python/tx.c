#include <wiringPi.h>
#include <stdio.h>
#include <unistd.h>
#include <string>

	int main(int argc, char *argv[])
	{
		wiringPiSetup();
		piHiPri(99);
		pinMode(5,OUTPUT);
		pinMode(6,OUTPUT);
		std::string code = argv[1];

		int times = 0;
		int pin = 0;

		int repeat;
                sscanf (argv[2],"%d",&repeat);
		int frequency;
                sscanf (argv[3],"%d",&frequency);

		int length = code.length();
		int count = 0;

		if(frequency == 315){
			pin = 5;
		}
		else if(frequency == 433){
			pin = 6;
		}

		while(times < repeat){
			while(count < length){
				char bit = code[count];
				if(bit == '1'){
					digitalWrite(pin,HIGH);
				}
				if(bit == '0'){
					digitalWrite(pin,LOW);
				}
				delayMicroseconds(45);
				count++;
			}
			count = 0;
		times++;
		}
   		return 0;
	}
