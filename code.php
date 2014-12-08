<?php
	include("settings.php");
	$onColor = readSetting("ONCOLOR");
	$offColor = readSetting("OFFCOLOR");
	$searchUID = "'" . $_GET["uid"] . "'";

	$specialColorEnabled = trim(file_get_contents("misc/special/colorEnabled"));
        if($specialColorEnabled == "ENABLED"){
                $onColor = trim(file_get_contents("misc/special/onColor"));
                $offColor = trim(file_get_contents("misc/special/offColor"));
        }

	$appString = file_get_contents("conf/appliances.txt");
	$lines = explode("\n",$appString);
	foreach($lines as &$line){
		$pieces = explode("|",$line);
		$onCode = $pieces[2];
		$offCode = $pieces[3];
		$long = $pieces[4];
		$short = $pieces[5];
		$space = $pieces[6];
		$uid = $pieces[8];
		if($uid == $searchUID){
			$onCodeDisplay = $onCode;
			$offCodeDisplay = $offCode;
			$longDisplay = $long;
			$shortDisplay = $short;
			$spaceDisplay = $space;
		}
	}

	if($_GET["code"] == "on"){
		$code = $onCodeDisplay;
		$long = $longDisplay;
		$short = $shortDisplay;
		$space = $spaceDisplay;
	}
	else if($_GET["code"] == "off"){
		$code = $offCodeDisplay;
		$long = $longDisplay;
		$short = $shortDisplay;
		$space = $spaceDisplay;
	}

	$length = strlen($code);
	$count = 0;
	$subCount = 0;
	$codeString = "C";

	while($count < $length){
		$bit = $code[$count];
		if($bit == "1"){
			$subCount = 0;
			while($subCount < $long / 8){
				$codeString = $codeString . "H<br>";
				$subCount++;
			}
			$subCount = 0;
			while($subCount < $short / 8){
				$codeString = $codeString . "L<br>";
				$subCount++;
			}
			$count++;
		}
		else if($bit == "0"){
			$subCount = 0;
			while($subCount < $short / 8){
				$codeString = $codeString . "H<br>";
				$subCount++;
			}
			$subCount = 0;
			while($subCount < $long / 8){
				$codeString = $codeString . "L<br>";
				$subCount++;
			}
			$count++;
		}
	}
	$count = 0;
	while($count < intval($space) / 8){
		$codeString = $codeString . "S<br>";
		$count++;
	}

	$codeString = explode("<br>",$codeString);
	$length = count($codeString);
	$count = 0;
	$bitString = "";
	$threshold = 10;
	if($length > 100000){
		$threshold = 2000;
	}
	if($length > 10000){
		$threshold = 200;
	}
	else if($length > 1000){
		$threshold = 20;
	}
	foreach($codeString as &$bit){
		if($count == $threshold){
			if($bit == "H"){
				$bitString = $bitString . "<td style='height:50px;'><div style='background-color:" . $onColor . ";height:50px;'></div></td>";
			}
			if($bit == "L"){
				$bitString = $bitString . "<td style='height:50px;'><div style='background-color:none;height:50px;'></div></td>";
			}
			if($bit == "S"){
				$bitString = $bitString . "<td style='height:50px;'><div style='background-color:" . $offColor . ";height:50px;'></div></td>";
			}
			$count = 0;
		}
		$count++;
	}
?>

<html>
	<head>
	</head>
	<body style="background:#242424;color:#cccccc;margin:0px">
		<table width="100%" cellpadding="0" cellspacing="0" style="background-color:none">
			<tr>
				<?php echo $bitString;?>
			</tr>
		</table>
		<table width="100%" cellpadding="0" cellspacing="0" style="background-color:none">
			<tr>
				<td style="width:100%;"><div style="width:100%;background-color:<?php echo $onColor;?>;height:3px;margin-top: -3px;"></div></td>
			</tr>
		</table>
	</body>
</html>
