<?php
$dataFile = "misc/visitors.txt";

$sessionTime = 1; //this is the time in **minutes** to consider someone online before removing them from our file

//Please do not edit bellow this line

error_reporting(E_ERROR | E_PARSE);

if(!file_exists($dataFile)) {
	$fp = fopen($dataFile, "w+");
	fclose($fp);
}

$ip = $_SERVER['REMOTE_ADDR'];

if (isset($argv[1])) {
    	if($argv[1] == "REFRESH"){
		$ip = "192.168.999.999";
	}
    	else{
		$ip = $argv[1];
	}
}

$users = array();
$onusers = array();

//getting
$fp = fopen($dataFile, "r");
flock($fp, LOCK_SH);
while(!feof($fp)) {
	$users[] = rtrim(fgets($fp, 32));
}
flock($fp, LOCK_UN);
fclose($fp);


//cleaning
$x = 0;
$alreadyIn = FALSE;
foreach($users as $key => $data) {
	list( , $lastvisit) = explode("|", $data);
	if(time() - $lastvisit >= $sessionTime * 60) {
		$users[$x] = "";
	} else {
		if(strpos($data, $ip) !== FALSE) {
			$alreadyIn = TRUE;
			$users[$x] = "$ip|" . time(); //updating
		}
	}
	$x++;
}

if($alreadyIn == FALSE) {
	$users[] = "$ip|" . time();
}

//writing
$fp = fopen($dataFile, "w+");
flock($fp, LOCK_EX);
$i = 0;
foreach($users as $single) {
	if($single != "") {
		fwrite($fp, $single . "\r\n");
		$i++;
	}
}
flock($fp, LOCK_UN);
fclose($fp);

if (!isset($argv[1])) {
	echo '<div style="padding:5px; margin:auto; background-color:#fff"><b>' . $i . ' visitors online</b></div>';
}

?>
