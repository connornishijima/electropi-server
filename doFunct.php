<?php
	require_once("includes/functions.php");

	$funct = $_GET['funct'];
	if($funct == "setSwitchState"){
		$UID = $_GET['UID'];
		$newState = $_GET['newState'];
		setSwitchState($UID,$newState);
	}
	if($funct == "runRFCommand"){
		$com = $_GET['com'];
		runRFCommand($com);
	}

?>
