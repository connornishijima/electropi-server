<?php
	if(isset($_GET["uid"])){
		$uid = "'" . $_GET["uid"] . "'";
		$stateString = file_get_contents("conf/applianceStates.txt");
		$apps = explode("\n",$stateString);
		foreach ($apps as &$app) {
			$pieces = explode("|",$app);
			if($pieces[2] == $uid){
				echo $pieces[1];
			}
		}
	}
	else if(isset($_GET["wemo"])){
		$wemoID = $_GET["wemo"];
		$wemoString = file_get_contents("conf/wemo.list");
                $wemos = explode("\n",$wemoString);
                foreach ($wemos as &$wemo) {
                        $pieces = explode("|",$wemo);
                        if($pieces[0] == $wemoID){
                                echo $pieces[2];
                        }
                }

	}
?>
