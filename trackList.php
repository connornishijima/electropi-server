<?php
	include("password_protect.php");
	$title = "TRACK LIST";

	$onColor = readSetting("ONCOLOR");
	$offColor = readSetting("OFFCOLOR");
	$uiScale = readSetting("UI_SCALE");
	$pass = readSetting("PASSMD5");

	if(isset($_GET["trackRemove"])){
		$mac = $_GET["mac"];
		$jAction = $_GET["jAction"];
		$lAction = $_GET["lAction"];
		$mac = str_replace("-",":",$mac);

		$outList = '';
		$trackList = file_get_contents("conf/track.list");
		$trackList = explode("\n",$trackList);
		foreach($trackList as $trackLine){
		        if(strlen($trackLine) > 3){
				$trackLineB = $trackLine;
				$trackLine = explode("|",$trackLine);
				$macS = $trackLine[0];
				$jActionS = $trackLine[1];
				$lActionS = $trackLine[2];
				if($mac == $macS && $jAction == $jActionS && $lAction == $lActionS){
				}
				else{
					$outList = $outList . $trackLineB . "\n";
				}

		        }
		}
		file_put_contents("conf/track.list",$outList);
	}

	$trackList = file_get_contents("conf/track.list");
	$trackList = explode("\n",$trackList);
	foreach($trackList as $track){
		if(strlen($track) > 3){
			$pieces = explode("|",$track);
			$mac = $pieces[0];
			$mac = str_replace(":","-",$mac);
			$jAction = $pieces[1];
			$lAction = $pieces[2];
			$desc = $pieces[3];
			$desc = str_replace("+","<br>",$desc);
			$link = "'trackList.php?trackRemove=true&mac=" . $mac . "&jAction=". $jAction . "&lAction=" . $lAction . "'";
			$trackTable = $trackTable . '<tr id="trackName" style="cursor:pointer;"><td style="padding-left: 20px;font-size:18px;">' . strtoupper($desc) . '</td><td onclick="window.location = '.$link.';" style="display: inline-block;float: right;margin-right: 20px;width: 25px;height: 25px;background-image: url(images/delete.png);background-size: cover;background-repeat: no-repeat;background-position: center center;margin-top: 20px;opacity: 0.2;"></td></tr><tr id="verticalSpace"></tr>';
		}
	}
?>

<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=<?php echo $uiScale; ?>,maximum-scale=<?php echo $uiScale; ?>, user-scalable=no"/>
		<title>ElectroPi Device Tracking</title>

		<?php include("header.php");?>

		<STYLE type="text/css">
                        #linkButton{color:<?php echo $offColor;?>;}
                        a{text-decoration:none;color:<?php echo $onColor;?>;}
                </STYLE>

		<script type="text/javascript" src="js/smoothie.js"></script>
		<script type="text/javascript">
			$(function(){  // $(document).ready shorthand
				$("#notify").hide();
    			});
			function booleanSwitch(name){
				haptic();
				var els=document.getElementsByName(name);
				var div=document.getElementById(name);
				var div2=document.getElementById(name + "Name");
				for (var i=0;i<els.length;i++) {
					if(els[i].value == "ENABLED"){
						div2.innerHTML = "DISABLED";
						els[i].value = "DISABLED";
						div.style.backgroundColor="<?php echo $offColor; ?>";
					}
					else if(els[i].value == "DISABLED"){
						div2.innerHTML = "ENABLED";
						els[i].value = "ENABLED";
						div.style.backgroundColor="<?php echo $onColor; ?>";
					}
				}
			}
		</script>

	</head>

	<body id="body">

		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: 800px;">
			<tr><td><a href="index.php" style="color:<?php echo $offColor; ?>;">ELECTROPI</a> >> <a href="setup.php" style="color:<?php echo $offColor; ?>;">CONFIG</a> >> DEVICE TRACKING ACTIONS</td></tr>
                        <tr id="verticalSpace"></tr>
			<tr id="verticalSpace"></tr>
			<?php echo $trackTable;?>
		</table>
		<br>


		<?php include("footer.php");?>

		</div>
		<div id="dummy" style="display:none"></div>
	</body>
</html>
