<?php

	$stateString = file_get_contents("conf/applianceStates.txt");
	$lines = explode("\n",$stateString);

	$states = array();

	foreach($lines as &$line){
		if(strlen($line) > 3){
			$pieces = explode("|",$line);
			$nick = $pieces[0];
			$state = $pieces[1];
			$uid = $pieces[2];
			$uid = str_replace("'","",$uid);

			$states[$uid] = $state;
		}
	}

	$stateJSON = "";
	//THIS PARSES STATES
	foreach($states as $uid => $state){
		$stateJSON .= '{"' . $uid . '":"' . $state . '"},';
	}

	echo '{"states":['
		.json_encode($states).
	']}'."\n";

	$watchdogJSON = array();
	$watchdogJSON["watchdog_status"] = "online";
	echo json_encode($watchdogJSON)."\n";

	$cpuJSON = array();
	$cpuJSON["cpu_usage"] = "96";
	echo json_encode($cpuJSON)."\n";

	$updateJSON = array();
	$updateJSON["updating"] = "false";
	echo json_encode($updateJSON)."\n";

	$briefJSON = array();
	$briefJSON["brief_message"] = "Brief Message Here!";
	echo json_encode($briefJSON)."\n";

	$notificationJSON = array();
	$notificationJSON["notification"] = "Switching DESK LAMP on...";
	echo json_encode($notificationJSON)."\n";

?>
