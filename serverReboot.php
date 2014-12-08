<?php
	include("settings.php");
	$storedPass = readSetting("PASSMD5");
	$sentPass = $_GET["pass"];
	if($storedPass == $sentPass){
		echo "GOOD!";
		file_put_contents("misc/rebootStatus.txt","GOOD");
		file_put_contents("misc/command.315.list","RBT\n",FILE_APPEND);
		file_put_contents("misc/command.433.list","RBT\n",FILE_APPEND);
		system("sudo reboot");
	}
	else{
		echo "BAD!";
	}
?>
