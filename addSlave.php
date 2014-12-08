<?php
	$slaveIP = $_GET["slaveIP"];
	$slaveFreq = $_GET["slaveFreq"];
	file_put_contents("/var/www/conf/slave.list",$slaveIP."|".$slaveFreq."\n",FILE_APPEND);
	echo $slaveIP." ADDED!";
?>
