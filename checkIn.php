<?php
	// ELECTROPI DEVICE CHECK-IN PORTAL

	function getLines($file){
		$f = fopen($file, 'rb');
		$lines = 0;

		while (!feof($f)) {
			$lines += substr_count(fread($f, 8192), "\n");
		}

		fclose($f);
		return $lines;
	}

	function readcsv($filename, $header=false) {
		$handle = fopen($filename, "r");
		echo '<table>';
		//display header row if true
		if ($header) {
			$csvcontents = fgetcsv($handle);
			echo '<tr>';
			foreach ($csvcontents as $headercolumn) {
				echo "<th style='width:20%;text-align:left'>$headercolumn</th>";
			}
			echo '</tr>';
		}
		// displaying contents
		while ($csvcontents = fgetcsv($handle)) {
			echo '<tr>';
			foreach ($csvcontents as $column) {
				echo "<td style='width:20%;text-align:left'>$column</td>";
			}
			echo '</tr>';
		}
		echo '</table>';
		fclose($handle);
	}

	$sets = parse_ini_file("config/settings.ini",true);
	$logLength = intval($sets['SETTINGS']['deviceLogLength']);

	$info = getdate();
	$date = $info['mday'];
	$month = $info['mon'];
	$year = $info['year'];
	$hour = $info['hours'];
	$min = $info['minutes'];
	$sec = $info['seconds'];
	$timeSum = time() - strtotime("today");

	// These keep the currentDate string the right length for Python parser
	if(strlen($hour)<2){ $hour = "0".$hour; }
	if(strlen($min)<2) { $min = "0".$min; }
	if(strlen($sec)<2){ $sec = "0".$sec; }

	$currentTime = $hour."-".$min."-".$sec;

	if(isset($_GET["type"])){
		$checkInType = $_GET["type"];
		if($checkInType == "android"){
			if(isset($_GET["deviceNickname"])){
				$deviceNickname = $_GET["deviceNickname"];
				$logLine = "ANDROID,".$deviceNickname.",".$currentTime.",".$timeSum."\n";
				$logFile = file_get_contents("requestLogs.csv");

				if(getLines("requestLogs.csv") < $logLength + 1){
					file_put_contents("requestLogs.csv",$logLine,FILE_APPEND);
					die("1");
				}
				else{
					$header = strtok($logFile, "\n")."\n";
					$arr = explode("\n", $logFile);
					array_shift($arr);
					array_shift($arr);
					$logLine = $header.implode("\n", $arr).$logLine;
					file_put_contents("requestLogs.csv",$logLine);
					die("1");
				}
			}
		}
	}

	if(isset($_GET["seeLogs"])){
		readcsv("requestLogs.csv",true);
		die();
	}

	if(isset($_GET["interval"])){
		$sets = parse_ini_file("config/settings.ini",true);
		echo $sets['SETTINGS']['deviceInterval'];
		die();
	}

	die("0\nERROR - NO INFORMATION PROVIDED!");
?>

