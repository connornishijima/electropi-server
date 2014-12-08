<?php
	$command = $_GET["command"] . "\n";
	$slaveFreq = $_GET["slaveFreq"];
	file_put_contents("misc/command.".$slaveFreq.".list",$command,FILE_APPEND);
?>
