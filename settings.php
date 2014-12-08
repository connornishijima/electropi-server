<?php
	function readSetting($searchName){
		$confString = file_get_contents("conf/settings.conf");
		$lines = explode("\n",$confString);
		foreach ($lines as &$setting) {
			if(substr($setting, 1) != "#"){
				$setting = explode("=",$setting);
				$storedName = $setting[0];
				if($storedName == $searchName){
					$storedValue = $setting[1];
					return trim($storedValue);
				}
			}
		}
		die("SETTING TO READ NOT FOUND = " . $searchName);
	}

	function writeSetting($newName,$newValue){

		$outLines = array();
		$found = 0;

		$confString = file_get_contents("conf/settings.conf");
		$lines = explode("\n",$confString);
                foreach ($lines as &$setting) {
			$setting2 = $setting;
			if(substr($setting, 1) != "#"){
				$setting = explode("=",$setting);
	                        $storedName = $setting[0];
	                        $storedValue = $setting[1];
				if($storedName == $newName){
					$setting2 = $newName . "=" . $newValue;
					$found = 1;
				}
				else{
					//NO CHANGE, WRITE ORIGINAL LINE BACK
				}
				if(strlen($setting2) > 3){
                                        array_push($outLines,$setting2);
                                }
			}
			else{
				if(strlen($setting2) > 3){
                                        array_push($outLines,$setting2);
                                }
			}
		}
		if($found == 0){
			die("SETTING TO WRITE NOT FOUND = " . $newName);
		}
		$confString = "";
		foreach ($outLines as &$item) {
			$confString = $confString . $item . "\n";
                }
		file_put_contents("conf/settings.conf",$confString);
	}
?>
