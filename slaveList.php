<?php
	include("password_protect.php");
	$title = "SLAVE NODES";
	$standbyColor = readSetting("STANDBY_COLOR");
        $txColor = readSetting("TX_COLOR");
        $errorColor = readSetting("ERROR_COLOR");

	$onColor = readSetting("ONCOLOR");
	$offColor = readSetting("OFFCOLOR");
	$uiScale = readSetting("UI_SCALE");
	$pass = readSetting("PASSMD5");

	$slaveList = file_get_contents("conf/slave.list");
	$slaveList = explode("\n",$slaveList);
	foreach($slaveList as $slave){
		if(strlen($slave) > 3){
			$pieces = explode("|",$slave);
			$slaveIP = $pieces[0];
			$slaveFreq = $pieces[1];
			$link = "'slaveList.php?reboot=".$slaveFreq."'";
			$slaveTable = $slaveTable . '<tr id="slaveName" style="cursor:pointer;"><td style="padding-left: 20px;">' . $slaveIP . ' | <font style="color:' . $offColor . ';">' . $slaveFreq . 'MHz</font></td><td onclick="window.location='.$link.'" style="text-align:right;padding-right:20px;">REBOOT</td></tr><tr id="verticalSpace"></tr>';
		}
	}

	if(isset($_GET["reboot"])){
		$freqReboot = $_GET["reboot"];
		file_put_contents("misc/command.".$freqReboot.".list","RBT\n",FILE_APPEND);
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
			<tr><td><a href="index.php" style="color:<?php echo $offColor; ?>;">ELECTROPI</a> >> <a href="setup.php" style="color:<?php echo $offColor; ?>;">CONFIG</a> >> <a href="security.php" style="color:<?php echo $offColor; ?>;">SECURITY</a> >> SLAVE NODE LIST</td></tr>
                        <tr id="verticalSpace"></tr>
			<tr id="verticalSpace"></tr>
			<?php echo $slaveTable;?>
		</table>
		<br>


		<?php include("footer.php");?>

		</div>
		<div id="dummy" style="display:none"></div>
	</body>
</html>
