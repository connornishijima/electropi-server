<?php
	echo "<!-- FUNCTIONS INCLUDED HERE -->";

	function checkIP($ip){
	}

	function setSwitchState($UID,$newState){
		$found = 0;

		if($newState == "1"){
			$oldState = "0";
		}
		else if($newState == "0"){
			$oldState = "1";
		}
		$file = "/var/www/data/switches/".$UID."/info.ini";
		$switchInfo = file_get_contents($file);
		//echo $switchInfo;
		$lines = explode("\n",$switchInfo);
		$searchString = "State=".$oldState;
		$newString = "State=".$newState;
		$outString = "";
		foreach($lines as &$line){
			if($line == $searchString){
				$line = $newString;
				$found = 1;
			}
			else{
				// Do nothing if no match
			}
			$line .= "\n";
			$outString .= $line;
		}
		file_put_contents("/var/www/data/switches/".$UID."/info.ini",$outString);
		return $found;
	}

	function runRFCommand($com){
		$trustedCommand = "X";
		$com = str_replace("%20"," ",$com);
		echo $com."<br>";
		$piecesCom = explode(" ",$com);
		$piecesComCount = count($piecesCom);
		$trusted = file_get_contents("/var/www/config/commands.trusted");
		$trustedLines = explode("\n",$trusted);
		foreach($trustedLines as &$trustedLine){
			$piecesTrusted = explode(" ",$trustedLine);
			$piecesTrustedCount = count($piecesTrusted);
			if($piecesComCount == $piecesTrustedCount){
				$tempCount = 0;
				$trustCount = 0;
				while($tempCount < $piecesComCount){
					if($piecesTrusted[$tempCount] != "*"){
						if($piecesTrusted[$tempCount] == $piecesCom[$tempCount]){
							$trustCount++;
						}
					}
					else{
						$trustCount++;
					}
					$tempCount++;
				}
			}
			if($trustCount == $piecesComCount){
				$trustedCommand = $com;
				echo "TRUSTED<br>";
			}
			else{
//				echo $trustCount." / ".$piecesComCount;
			}
		}
		if($trustedCommand != "X"){
			echo $trustedCommand;
			file_put_contents("python/command.list","COM-RF:".$trustedCommand."\n",FILE_APPEND);
		}
	}

function write_ini_file($assoc_arr, $path, $has_sections=FALSE) { 
    $content = ""; 
    if ($has_sections) { 
        foreach ($assoc_arr as $key=>$elem) { 
            $content .= "[".$key."]\n"; 
            foreach ($elem as $key2=>$elem2) { 
                if(is_array($elem2)) 
                { 
                    for($i=0;$i<count($elem2);$i++) 
                    { 
                        $content .= $key2."[] = \"".$elem2[$i]."\"\n"; 
                    } 
                } 
                else if($elem2=="") $content .= $key2." = \n"; 
                else $content .= $key2." = \"".$elem2."\"\n"; 
            } 
        } 
    } 
    else { 
        foreach ($assoc_arr as $key=>$elem) { 
            if(is_array($elem)) 
            { 
                for($i=0;$i<count($elem);$i++) 
                { 
                    $content .= $key."[] = \"".$elem[$i]."\"\n"; 
                } 
            } 
            else if($elem=="") $content .= $key." = \n"; 
            else $content .= $key." = \"".$elem."\"\n"; 
        } 
    } 

    if (!$handle = fopen($path, 'w')) { 
        return false; 
    }

    $success = fwrite($handle, $content);
    fclose($handle); 

    return $success; 
}

?>
