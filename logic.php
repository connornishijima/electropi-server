<?php include("password_protect.php");?>
<?php

	$onColor = readSetting("ONCOLOR");
	$offColor = readSetting("OFFCOLOR");
	$updated = "FALSE";

        // settings.php allows us to read and write from the configuration via functions
        //include("settings.php");

        //USER
        $passmd5 = readSetting("PASSMD5");
        //HARDWARE
        $board = readSetting("BOARD");
        $jam = readSetting("JAM");
        //WATCHDOG
        $rgbled = readSetting("RGBLED");
        $standbyColor = readSetting("STANDBY_COLOR");
        $txColor = readSetting("TX_COLOR");
        $errorColor = readSetting("ERROR_COLOR");
        //WEB UI
        $animations = readSetting("ANIMATIONS");
        $notifications = readSetting("NOTIFICATIONS");
        $debug = readSetting("DEBUG");
	$uiScale = readSetting("UI_SCALE");

	if(isset($_POST["updated"])){
		$updated = "TRUE";
		file_put_contents("misc/notification.txt","CONFIGURATION UPDATED! | " . $_SERVER['REMOTE_ADDR']);
	}
	if(isset($_POST["standbycolor"])){
		$standbyColor = $_POST["standbycolor"];
		writeSetting("STANDBY_COLOR",$standbyColor);
	}
	if(isset($_POST["txcolor"])){
                $txColor = $_POST["txcolor"];
                writeSetting("TX_COLOR",$txColor);
        }
	if(isset($_POST["errorcolor"])){
                $errorColor = $_POST["errorcolor"];
                writeSetting("ERROR_COLOR",$errorColor);
        }
	if(isset($_POST["oncolor"])){
                $onColor = $_POST["oncolor"];
                writeSetting("ONCOLOR",$onColor);
        }
	if(isset($_POST["offcolor"])){
                $offColor = $_POST["offcolor"];
                writeSetting("OFFCOLOR",$offColor);
        }
	if(isset($_POST["ui_scale"])){
                $uiScale = $_POST["ui_scale"];
                writeSetting("UI_SCALE",$uiScale);
        }
	if(isset($_POST["animations"])){
                $animations = $_POST["animations"];
                writeSetting("ANIMATIONS",$animations);
        }
	if(isset($_POST["notifications"])){
                $notifications = $_POST["notifications"];
                writeSetting("NOTIFICATIONS",$notifications);
        }

	//SET BOOLEAN COLORS
	if($animations == "ENABLED"){
		$animationsColor = $onColor;
	}
	else{
		$animationsColor = $offColor;
	}
        if($notifications == "ENABLED"){
                $notificationsColor = $onColor;
        }
        else{
                $notificationsColor = $offColor;
        }


?>

<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=<?php echo $uiScale;?>,maximum-scale=<?php echo $uiScale;?>, user-scalable=no"/>
		<title>ElectroPi Config</title>

		<?php include("header.php");?>

		<STYLE type="text/css">
                        #linkButton{color:<?php echo $offColor;?>;}
			a{text-decoration:none;color:<?php echo $onColor;?>;}
                </STYLE>

		<script type='text/javascript' src='js/bug-min.js'></script>
		<script type="text/javascript">
			$(function(){  // $(document).ready shorthand
				if(<?php echo json_encode($animations);?> == "ENABLED"){
					$('#subtitle').hide().fadeIn('slow');
					$("#logoText").animate({color: "<?php echo $offColor; ?>" });
				}
				else{
                                        $("#logoText").animate({color: "<?php echo $offColor; ?>" },0);
                                }
				if(<?php echo json_encode($updated);?> == "TRUE"){
					$( "#alert" ).toggle();
				}
				setTimeout(function(){
					setInterval(function(){
						new BugController({'minBugs':1, 'maxBugs':1, 'mouseOver':'die'});
					}, 6000);
				}, 6000);
    			});
			function booleanSwitch(name){
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
                        <tr id="headerRow">
                                <td id="headerCell"><a href="index.php"><img id="logo" src="images/tx_animation.gif?<?php echo date('Ymdgis');?>"></a><div id="logoText" style="display: inline;color:<?php echo $onColor; ?>;padding-top: 10px;vertical-align: top;">ELECTRO</div>PI <font id="subtitle" style="color:#707070;padding-top: 10px;vertical-align: top;font-size: 24px;">LOGIC</font></td>
                        </tr>
			<tr id="verticalSpace"></tr>
			<tr id="verticalSpace"></tr>
                </table>

		<div id="main">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: 800px;">
		<tr><td><div id="alert">Settings updated. <a href="index.php" style="white-space: nowrap;color: <?php echo $offColor; ?>;">Return to control?</a></div></td></tr>
		</table>

		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: 800px;">
			<tr><td><a href="index.php" style="color:<?php echo $offColor; ?>;">ELECTROPI</a> >> <a href="config.php" style="color:<?php echo $offColor; ?>;">CONFIG</a> >> LOGIC</td></tr>
			<tr id="verticalSpace"></tr>
			<tr id="verticalSpace"></tr>
			<tr>
			<td><a href="addLogic.php"><div id="linkButton">ADD NEW RULE</div></a></td>
			</tr>
			<tr id="verticalSpace"></tr>
		</table>
		<br>

		<?php include("footer.php");?>

		<div id="notify" style="position: fixed;bottom: 0px;">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width: 800px;height: 100%;"><tr><td valign="bottom" style="padding: 20px;background-color: #1a1a1a;"><div id="notification"></div></td></tr></table>
                </div>
		</div>
		<div id="dummy" style="display:none"></div>
	</body>
</html>
