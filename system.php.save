<?php
	function notify($n){
		file_put_contents("misc/notification.txt",$n);
		file_put_contents("logs/notifications.log",$n."\n", FILE_APPEND);
	}
	function writeState($i,$s){
		$i = "'" . $i . "'";
		$stateString = file_get_contents("conf/applianceStates.txt");
        	$apps = explode("\n",$stateString);
        	foreach ($apps as &$app) {
			if(strlen($app) > 5){
				$appOrig = $app;
	        	        $pieces = explode("|",$app);
	        	        if($pieces[2] == $i){
	        	              $app = $pieces[0] . "|" . $s . "|" . $pieces[2] . "\n";
	        	        }
				else{
					$app = $appOrig . "\n";
				}
				$outString = $outString . $app;
			}
        	}
		echo $outString;
		file_put_contents("conf/applianceStates.txt",$outString);
	}
	function writeWemoState($i,$s){
		$stateString = file_get_contents("conf/wemo.list");
		$wemos = explode("\n",$stateString);
		foreach ($wemos as &$wemo) {
			if(strlen($wemo) > 5){
				$wemoOrig = $wemo;
				$pieces = explode("|",$wemo);
				if($pieces[1] == $i){
					$wemo = $pieces[0] . "|" . $pieces[1] . "|".$s."\n";
				}
				else{
					$wemo = $wemoOrig . "\n";
				}
				$outString = $outString . $wemo;
			}
		}
                file_put_contents("conf/wemo.list",$outString);
	}
	function uidLookup($i){
		$i = "'" . $i . "'";
		$appString = file_get_contents("conf/appliances.txt");
        	$apps = explode("\n",$appString);
        	foreach ($apps as &$app) {
			if(strlen($app) > 5){
	        	        $pieces = explode("|",$app);
				$nick = $pieces[0];
				$si = $pieces[8];
				echo "SI: " . $si . " I: " . $i;
				if($si == $i){
					return $nick;
				}
			}
        	}
		return "NOT FOUND";
	}

	function aidLookup($a){
		$actionString = file_get_contents("conf/actions/actions.list");
        	$actions = explode("\n",$actionString);
        	foreach ($actions as &$action) {
			if(strlen($action) > 5){
	        	        $pieces = explode("|",$action);
				$nick = $pieces[0];
				$si = $pieces[1];
				echo "SI: " . $si . " I: " . $a;
				if($si == $a){
					return $nick;
				}
			}
        	}
		return "NOT FOUND";
	}
	function stateLookup($s){
		$uid = "'".$s."'";
		$subject = file_get_contents("conf/applianceStates.txt");
		$subject = explode("\n",$subject);
		foreach($subject as &$line){
			$pieces = explode("|",$line);
			$nick = $pieces[0];
			$state = $pieces[1];
			$sUID = $pieces[2];
			echo "UID |".$uid."|<br>";
			echo "SUID |".$sUID."|<br>";
			if($uid == $sUID){
				return $state;
			}
		}
		return "NOT FOUND";
	}

	function doAction($a){
		$todo = file_get_contents("conf/actions/$a.txt");
		$list = explode("\n",$todo);
		$repeatAction = 1;
		$n = aidLookup($a);
		echo "<br><br>";
		echo $n;
		echo "<br>";
		notify("Applying action '" . $n . "'...</font> | " . $_SERVER['REMOTE_ADDR']);
		while($repeatAction > 0){
			foreach($list as &$line){
				if(strlen($line) == 7){
					$pieces = explode("-",$line);
					$uid = $pieces[0];
					$state = $pieces[1];
					echo "<br>UID: ".$uid." STATE: ".$state."<br>";

					$i = "'" . $uid . "'";
	                		$appString = file_get_contents("conf/appliances.txt");
	                		$apps = explode("\n",$appString);
					echo "<br>".$repeatAction."<br>";
					$repeatAction = $repeatAction - 1;
	                		foreach ($apps as &$app) {
	                        		if(strlen($app) > 5){
	                                		$pieces = explode("|",$app);
	                                		$si = $pieces[8];
		                                	if($si == $i){
								$nick = $pieces[0];
								$stateS = stateLookup($uid);
								$onCode = $pieces[2];
								$offCode = $pieces[3];
								$repeat = $pieces[7];
								$freq = $pieces[9];

								echo "STATE|$state|STATE-S|$stateS<br>";
								if($state == "1"){
									$f = "misc/command.".$freq.".list";
									file_put_contents($f,"RF:./tx " . $onCode . " " . $repeat . "\n", FILE_APPEND);
									writeState($uid,$state);
									echo $f;
								}
								else if($state == "0"){
									$f = "misc/command.".$freq.".list";
									file_put_contents($f,"RF:./tx " . $offCode . " " . $repeat . "\n", FILE_APPEND);
									writeState($uid,$state);
								}
							}
	                                	}
					}
                		}
				if(strlen($line) == 14){
					$line = explode("-",$line);
					$wemoID = $line[0];
					$wemoState = $line[1];
					$subject = file_get_contents("conf/wemo.list");
					$subject = explode("\n",$subject);
					foreach($subject as &$wemoLine){
						$wemoLine = explode("|",$wemoLine);
						$wemoIDS = $wemoLine[0];
						if($wemoIDS == $wemoID){
							$wemoName = $wemoLine[1];
						}
					}
					echo $wemoName;
					file_put_contents("misc/wemo.command.list","WEMO:".$wemoName.":".$wemoState."\n", FILE_APPEND);
		                        writeWemoState($wemoName,$wemoState);
				}
			}
		}
		notify("Applying action '" . $n . "'...DONE!</font> | " . $_SERVER['REMOTE_ADDR']);
	}

	$uid = $_GET["uid"];

	$onCode = $_GET["on"];
	$offCode = $_GET["off"];

	$repeat = $_GET["repeat"];
	$freq = $_GET["freq"];

	$state = $_GET["state"];
	$type = $_GET["type"];


	if($type == "CONTROL"){
		if($state == "1"){
			$nickname = uidLookup($uid);
			notify("Switching " . $nickname . " on... | " . $_SERVER['REMOTE_ADDR']);
			writeState($uid,$state);
			file_put_contents("misc/command.".$freq.".list","RF:./tx " . $onCode . " " . $repeat . "\n", FILE_APPEND);
		}
		else if($state == "0"){
			$nickname = uidLookup($uid);
			notify("Switching " . $nickname . " off... | " . $_SERVER['REMOTE_ADDR']);
			writeState($uid,$state);
			file_put_contents("misc/command.".$freq.".list","RF:./tx " . $offCode . " " . $repeat . "\n", FILE_APPEND);
		}
	}
	if($type == "TEST"){
		if($state == "1"){
			writeState($uid,$state);
			file_put_contents("misc/command.".$freq.".list","RF:./tx " . $onCode . " " . $repeat . "\n", FILE_APPEND);
		}
		else if($state == "0"){
			writeState($uid,$state);
			file_put_contents("misc/command.".$freq.".list","RF:./tx " . $offCode . " " . $repeat . "\n", FILE_APPEND);
		}
	}
	if($type == "ACTION"){
		$AID = $_GET["AID"];
		doAction($AID);
	}
	if($type == "WEMO"){
		$name = $_GET["name"];
		$nameAlt = str_replace("_"," ",$name);
		$state = $_GET["state"];
		if($state == "1"){
			notify("Switching " . $nameAlt . " on... | " . $_SERVER['REMOTE_ADDR']);
			file_put_contents("misc/wemo.command.list","WEMO:".$name.":1\n", FILE_APPEND);
			writeWemoState($name,$state);
		}
		if($state == "0"){
			notify("Switching " . $nameAlt . " off... | " . $_SERVER['REMOTE_ADDR']);
			file_put_contents("misc/wemo.command.list","WEMO:".$name.":0\n", FILE_APPEND);
			writeWemoState($name,$state);
		}
	}
	if($type == "WEMO-REPOP"){
		notify("Repopulating WeMo list... | " . $_SERVER['REMOTE_ADDR']);
		file_put_contents("misc/wemo.command.list","WEMO-REPOP", FILE_APPEND);
	}
?>
