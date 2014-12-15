<?php
include("password_protect.php");
$title = "DEVICES";

$onColor = readSetting("ONCOLOR");
$offColor = readSetting("OFFCOLOR");
$uiScale = readSetting("UI_SCALE");
$pass = readSetting("PASSMD5");

if(isset($_GET["macRemove"])){
	$mac = $_GET["macRemove"];
	$mac = str_replace("-",":",$mac);
	$outList = '';
	$deviceList = file_get_contents("conf/device.list");
	$deviceList = explode("\n",$deviceList);
	foreach($deviceList as $device){
	        if(strlen($device) > 3){
	                $pieces = explode("|",$device);
	                $nickS = $pieces[0];
	                $macS = $pieces[1];
			if($mac != $macS){
				$outList = $outList . $device . "\n";
			}
	        }
	}
	file_put_contents("conf/device.list",$outList);
}

$deviceList = file_get_contents("conf/device.list");
$deviceList = explode("\n",$deviceList);
foreach($deviceList as $device){
	if(strlen($device) > 3){
		$pieces = explode("|",$device);
		$nick = $pieces[0];
		$mac = $pieces[1];
		$mac2 = $pieces[1];
		if($mac == "XX:XX:XX:XX:XX:XX"){
			$mac = "GUEST (NO VERIFIED MAC)";
		}
		$ip = $pieces[2];
		$mac = str_replace(":","-",$mac);
		$present = file_get_contents("conf/clients/".$ip.".txt");
		if(trim($present) == "1"){
			$present = '<font style="color:'.$onColor.';">PRESENT</font>';
		}
		else{
			$present = '<font style="color:'.$offColor.';">ABSENT</font>';
		}
		$link = "'deviceList.php?macRemove=" . $mac2 . "'";
		$deviceTable = $deviceTable . '<tr id="deviceName" style="cursor:pointer;"><td style="padding-left: 20px;">' . strtoupper($nick) . ' | <font style="color:' . $offColor . ';">' . strtoupper($mac) . '</font> | '.$present.'</td><td onclick="window.location = '.$link.';" style="display: inline-block;float: right;margin-right: 20px;width: 25px;height: 25px;background-image: url(images/delete.png);background-size: cover;background-repeat: no-repeat;background-position: center center;margin-top: 20px;opacity: 0.2;"></td></tr><tr id="verticalSpace"></tr>';
	}
}

?>

<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=<?php echo $uiScale; ?>,maximum-scale=<?php echo $uiScale; ?>, user-scalable=no"/>
		<title>ElectroPi Security</title>

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
			<tr><td><a href="index.php" style="color:<?php echo $offColor; ?>;">ELECTROPI</a> >> <a href="setup.php" style="color:<?php echo $offColor; ?>;">CONFIG</a> >> <a href="security.php" style="color:<?php echo $offColor; ?>;">SECURITY</a> >> TRUSTED DEVICE LIST</td></tr>
                        <tr id="verticalSpace"></tr>
			<tr id="verticalSpace"></tr>
			<?php echo $deviceTable;?>
		</table>
		<br>


		<?php include("footer.php");?>

		</div>
		<div id="dummy" style="display:none"></div>
	</body>
</html>
