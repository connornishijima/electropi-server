#Installing the ElectroPi Server#

Thank you for making ElectroPi your choice for Home Automation! Let's begin.

##WGET the installer##

This is the powerhouse that will be in charge of control codes, appliances, device tracking and more. ElectroPi can be installed with two commands:

```bash
$ sudo wget http://connor-n.com/electropi/ep_install.py
$ sudo python ep_install.py
```

Immediately you will be greeted with an ElectroPi intro screen, and you can begin the installation.

##PERSONALIZATION##

During this installation your Pi will prompt you for a few questions to help tailor your experience. They are as follows:

#####Do you want to wipe the '/var/www' Apache2 directory?#####
If you already have a web server installed, answer "N". Otherwise, the installer will wipe the '/var/www' directory clean.

#####Configure Timezone#####
This will have you select your timezone for control scheduling purposes.

#####Will this Pi be WiFi-based?#####
If so, the installer will ask your for your WiFi credentials so the Pi can join the your wireless network.

#####Enter the IP address to assign to ElectroPi#####
This is the address at which you can access the ElectroPi control screen to switch your appliances. This is a "static" IP address - meaning that once set no other networked device can take it's address. (ex: `192.168.1.88`)

#####Enter your Netmask#####
Check with your network administrator for this value. Most home networks are Class-C netmasks, so `255.255.255.0` will almost always work.

#####Enter your router's IP#####
This is the router's IP address, or "gateway". Depending on the brand of router (or personal settings) this is either `192.168.1.0` or `192.168.1.1`. Test both IP addresses in a browser to see which one is yours. If a user/password appears, that's probably your router.

##ALL SYSTEMS GO!##
Alright, we're done! The Pi will reboot, and you may begin setting up your wireless switches. To access the ElectroPi control screen, enter the "static" IP address your specified earlier into any browser. (ex: `192.168.1.88`)
