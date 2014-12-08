<?php
	function getAppName($searchUIDN){
		$searchUIDN = "'" . $searchUIDN . "'";
		$confStringN = file_get_contents("conf/appliances.txt");
                $linesN = explode("\n",$confStringN);
		foreach($linesN as &$lineN){
			$piecesN = explode("|",$lineN);
			$nicknameN = $piecesN[0];
			$uidN = $piecesN[8];
			if($uidN == $searchUIDN){
				return $nicknameN;
			}
		}
		return "NOT FOUND";
	}

	function readApp($searchNameA){
		$confStringA = file_get_contents("conf/appliances.txt");
		$linesA = explode("\n",$confStringA);
		foreach ($linesA as &$settingA) {
			if(substr($settingA, 1) != "#"){
				$setting2A = $settingA;
				$settingA = explode("|",$settingA);
				$storedNameA = $settingA[0];
				if($storedNameA == $searchNameA){
					return trim($setting2A);
				}
			}
		}
		die("APP TO READ NOT FOUND = " . $searchNameA);
	}

	function writeApp($newNameA,$newValueA){

		$outLinesA = array();
		$foundA = 0;

		$confStringA = file_get_contents("conf/appliances.txt");
		$linesA = explode("\n",$confStringA);
                foreach ($linesA as &$settingA) {
			$setting2A = $settingA;
			if(substr($settingA, 1) != "#"){
				$settingA = explode("|",$settingA);
	                        $storedNameA = $settingA[0];
				if($storedNameA == $newNameA){
					$setting2A = $newValueA;
					$foundA = 1;
				}
				else{
					//NO CHANGE, WRITE ORIGINAL LINE BACK
				}
				$setting2A = trim($setting2A, "\n");
				if(strlen($setting2A) > 3){
					array_push($outLinesA,$setting2A);
				}
			}
			else{
				$setting2A = trim($setting2A, "\n");
				if(strlen($setting2A) > 3){
					array_push($outLinesA,$setting2A);
				}
			}
		}
		if($foundA == 0){
			die("APP TO WRITE NOT FOUND = " . $newNameA);
		}
		else{
			$confStringA = "";
			foreach ($outLinesA as &$itemA) {
				$confStringA = $confStringA . $itemA . "\n";
	                }
			file_put_contents("conf/appliances.txt",$confStringA);
			file_put_contents("command.list","RST");
		}
	}
?>
