<?php
	echo "ELECTROPI CONTROL API V1.0<br><br>";

	$helpArray = array(
		"badIP" => "CRITICAL ERROR: The IP address you're attempting control from is not registered. API Key/IP setup is located in the security menu. This may have simply occurred because your device does not have a static IP address, and drifted to a new one.<br>",
		"badKey" => "CRITICAL ERROR: The API key you provided was not valid/registered. API Key/IP setup is located in the security menu.<br>",
	);

	function vecho($string){
		if($_GET["verbose"] == "true"){
			echo $string;
		}
	}

	function notify($n){
                file_put_contents("misc/notification.txt",$n);
                file_put_contents("logs/notifications.log",$n."\n", FILE_APPEND);
        }


	function getSwitchInfo($UIDForInfo){
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
		echo $outString;
                file_put_contents("conf/applianceStates.txt",$outString);
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
					echo "Switching '".$switchInfo['name']."' state to ".$setState.".<br>";
					if($setState == "1"){
						$txCode = $switchInfo['onCode'];
						$humanTerm = "on";
					}
					else if($setState == "0"){
						$txCode = $switchInfo['offCode'];
						$humanTerm = "off";
					}
					notify("Switching ".$switchInfo['name']." ".$humanTerm."... | API");
					file_put_contents("misc/command.".$switchInfo['freq'].".list","RF:./tx " . $txCode . " " . $switchInfo['repeat'] . "\n", FILE_APPEND);
					writeState($switchInfo['uid'],$setState);
				}
			}
		}

		echo "DONE.";
	}

?>
