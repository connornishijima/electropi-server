<?php
	$helpArray = array(
		"badIP" => "CRITICAL ERROR: The IP address you're attempting control from is not registered. API Key/IP setup is located in the security menu. This may have simply occurred because your device does not have a static IP address, and drifted to a new one.<br>",
		"badKey" => "CRITICAL ERROR: The API key you provided was not valid/registered. API Key/IP setup is located in the security menu.<br>",
	);

	function vecho($string){
		if($_GET["verbose"] == "true"){
			echo $string;
		}
	}

	vecho("ELECTROPI CONTROL API V1.0<br><br>");

	function notify($n){
		if($_GET["notify"] != "false"){
	                file_put_contents("misc/notification.txt",$n);
	                file_put_contents("logs/notifications.log",$n."\n", FILE_APPEND);
		}
        }


	function getSwitchInfo($UIDForInfo){
		$states = file_get_contents("conf/applianceStates.txt");
                $states = explode("\n",$states);
		foreach($states as &$stateLine){
			$stateLine = explode("|",$stateLine);
                        $stateSaved = $stateLine[1];
                        $UID = str_replace("'","",$stateLine[2]);
			if($UID == $UIDForInfo){
				$state = $stateSaved;
			}
		}

		$switches = file_get_contents("conf/appliances.txt");
                $switches = explode("\n",$switches);
                foreach($switches as &$switchLine){
			$switchLine = explode("|",$switchLine);
			$name = $switchLine[0];
			$onCode = $switchLine[2];
			$offCode = $switchLine[3];
			$repeat = $switchLine[7];
			$UID = str_replace("'","",$switchLine[8]);
			$freq = $switchLine[9];

			if($UID == $UIDForInfo){
				$infoArray = array(
					"name" => $name,
					"state" => $state,
					"onCode" => $onCode,
					"offCode" => $offCode,
					"repeat" => $repeat,
					"uid" => $UID,
					"freq" => $freq,
				);
				return $infoArray;
			}
		}
	}

	function getWemoInfo($UIDForInfo){
		$wemoList = file_get_contents("conf/wemo.list");
                $wemoList = explode("\n",$wemoList);
                foreach($wemoList as &$wemo){
			$wemo = explode("|",$wemo);
			$UID = $wemo[0];
			$name = str_replace(" ","_",$wemo[1]);
			$state = $wemo[2];

			if($UID == $UIDForInfo){
				$infoArray = array(
					"name" => $name,
					"state" => $state,
					"uid" => $UID,
				);
				return $infoArray;
			}
		}
	}

	function writeState($i,$s){
                $i = "'".$i."'";
                $stateString = file_get_contents("conf/applianceStates.txt");
                $switches = explode("\n",$stateString);
                foreach ($switches as &$switchS) {
                        if(strlen($switchS) > 5){
                                $switchOrig = $switchS;
                                $pieces = explode("|",$switchS);
                                if($pieces[2] == $i){
                                      $switchS = $pieces[0] . "|" . $s . "|" . $pieces[2] . "\n";
                                }
                                else{
                                        $switchS = $switchOrig . "\n";
                                }
                                $outString = $outString . $switchS;
                        }
                }
                file_put_contents("conf/applianceStates.txt",$outString);
        }

	function writeWemoState($i,$s){
                $i = "'".$i."'";
                $stateString = file_get_contents("conf/wemo.list");
                $wemos = explode("\n",$stateString);
                foreach ($wemos as &$wemo) {
                        if(strlen($wemo) > 5){
                                $wemoOrig = $wemo;
                                $pieces = explode("|",$wemo);
                                if($pieces[0] == $i){
                                      $wemo = $pieces[0] . "|" . $pieces[1] . "|" . $s ."\n";
                                }
                                else{
                                        $wemo = $wemoOrig . "\n";
                                }
                                $outString = $outString . $wemo;
                        }
                }
                file_put_contents("conf/wemo.list",$outString);
        }



	// AUTHENTICATION ---------------------------------------

	function authUser($uIP,$uKey){
		$authIP = false;
		$authKey = false;

		$devices = file_get_contents("conf/device.list");
		$devices = explode("\n",$devices);
		foreach($devices as &$device){
			$device = explode("|",$device);
			$nick = $device[0];
			$mac = $device[1];
			$ip = $device[2];
			if($ip == $uIP){
				$authIP = true;
				$authNick = $nick;
			}
		}

		$keys = file_get_contents("conf/api.keys");
                $keys = explode("\n",$keys);
		foreach($keys as &$key){
                        $key = explode("|",$key);
                        $ip = $key[0];
                        $k = $key[1];
			if($k == $uKey){
				$authKey = true;
			}
                }

		if($authKey == true){
			vecho("CREDENTIALS ACCEPTED (KEY:".$uKey.")<br>");
			if($authIP == true){
				vecho("IP ACCEPTED (IP:".$uIP.")<br>Authenicated ".$authNick."!<br>");
				return "PASS";
			}
			else{
				echo("WRONG IP OR IP HAS CHANGED! Static IP addresses are your friend.<br>");
				return "FAIL-IP";
			}
		}
		else{
			echo "CREDENTIALS REFUSED<br>" ;
			return "FAIL-KEY";
		}
	}
	//--------------------------------------------------------

	$userIP = $_SERVER['REMOTE_ADDR'];
	$userKey = $_GET["key"];

	$authState = authUser($userIP,$userKey);

	if($authState == "FAIL-IP"){
		echo $helpArray["badIP"];
		die("-----------------------------<br>ELECTROPI API CONTROL REFUSED");
	}
	else if($authState == "FAIL-KEY"){
		echo $helpArray["badKey"];
		die("-----------------------------<br>ELECTROPI API CONTROL REFUSED");
	}
	else{
		vecho("API access granted to ".$userIP.".<br>");

		if(isset($_GET["controlType"])){
			$controlType = $_GET["controlType"];
			vecho($controlType." control requested.<br>");

			if($controlType == "RF"){
				$switchUID = $_GET["switchUID"];
				$switchInfo = getSwitchInfo($switchUID);
				vecho("Switch to control is UID: ".$switchInfo['uid'].". (NICK:'".$switchInfo['name']."')<br>");

				if(isset($_GET["setState"])){
					$setState = $_GET["setState"];
					echo "Switching '".$switchInfo['name']."' state to ".$setState."...";
					if($setState == "1" || $setState == "ON"){
						$txCode = $switchInfo['onCode'];
						$humanTerm = "ON";
					}
					else if($setState == "0" || $setState == "OFF"){
						$txCode = $switchInfo['offCode'];
						$humanTerm = "OFF";
					}
					notify("Switching ".$switchInfo['name']." ".$humanTerm."... | API");
					file_put_contents("misc/command.".$switchInfo['freq'].".list","RF:./tx " . $txCode . " " . $switchInfo['repeat'] . "\n", FILE_APPEND);
					writeState($switchInfo['uid'],$setState);
					echo "DONE.<br>";
				}
				if(isset($_GET["getState"])){
					$getState = $_GET["getState"];
					if($getState == "binary"){
						vecho("Getting binary state of '".$switchInfo['name']."'...<br> State is: ");
						echo $switchInfo['state'];
					}
					if($getState == "lingual"){
						vecho("Getting lingual state of '".$switchInfo['name']."'...<br> State is: ");
						if($switchInfo['state'] == "1"){
							echo "ON";
						}
						else if($switchInfo['state'] == "0"){
							echo "OFF";
						}
					}
				}
			}
			if($controlType == "WEMO"){
				$wemoUID = $_GET["wemoUID"];
                                $wemoInfo = getWemoInfo($wemoUID);
				if(isset($_GET["setState"])){
					$setState = $_GET["setState"];
                                        echo "Switching '".$wemoInfo['name']."' state to ".$setState."...";
                                        if($setState == "1" || $setState == "ON"){
                                                $humanTerm = "ON";
                                        }
                                        else if($setState == "0" || $setState == "OFF"){
                                                $humanTerm = "OFF";
                                        }
					notify("Switching ".$wemoInfo['name']." ".$humanTerm."... | API");
                                        file_put_contents("misc/wemo.command.list","WEMO:".$wemoInfo['name'].":".$setState."\n", FILE_APPEND);
                                        writeWemoState($wemoInfo['uid'],$setState);
                                        echo "DONE.<br>";

				}
				if(isset($_GET["getState"])){
					$getState = $_GET["getState"];
					if($getState == "binary"){
						vecho("Getting binary state of '".$wemoInfo['name']."'...<br> State is: ");
						echo $wemoInfo['state'];
					}
					if($getState == "lingual"){
						vecho("Getting lingual state of '".$wemoInfo['name']."'...<br> State is: ");
						if($wemoInfo['state'] == "1"){
							echo "ON";
						}
						else if($wemoInfo['state'] == "0"){
							echo "OFF";
						}
					}
				}
			}
		}

		vecho("<br>(END API OUTPUT)");
	}

?>
