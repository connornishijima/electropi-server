<?php
	include("password_protect.php");
	$title = "BACKUPS";
	$hideSettings = True;

	$onColor = readSetting("ONCOLOR");
	$offColor = readSetting("OFFCOLOR");
	$updated = "FALSE";

        // settings.php allows us to read and write from the configuration via functions
        //include("settings.php");

        //USER
        $passmd5 = readSetting("PASSMD5");
	$securityKick = readSetting("SECURITY_KICK");
        //HARDWARE
        $board = readSetting("BOARD");
        $jam = readSetting("JAM");
        //WATCHDOG
        $statusLed = readSetting("RGBLED");
	$brightness = intval(readSetting("BRIGHTNESS"));
        //WEB UI
        $animations = readSetting("ANIMATIONS");
        $notifications = readSetting("NOTIFICATIONS");
        $debug = readSetting("DEBUG");
	$uiScale = readSetting("UI_SCALE");
	$maxWidth = readSetting("MAX_WIDTH");
	$haptic = readSetting("HAPTIC");
	//ADVANCED
	$freqAttached = readSetting("FREQ_ATTACHED");
	$netInterface = readSetting("NET_INTERFACE");
	$beta = readSetting("BETA_MODE");
	$wemoSupport = readSetting("WEMO_SUPPORT");

	if(isset($_POST["updated"])){
		$updated = "TRUE";
		file_put_contents("misc/notification.txt","CONFIGURATION UPDATED! | " . $_SERVER['REMOTE_ADDR']);
		$command = file_get_contents("misc/command.list");
		file_put_contents("misc/command.list",$command . "\nRST-FAST\n");
	}
	if(isset($_POST["statusLed"])){
                $statusLed = $_POST["statusLed"];
                writeSetting("RGBLED",$statusLed);
        }
	if(isset($_POST["brightness"])){
                $brightness = $_POST["brightness"];
		if(intval($brightness) > 100){
        	        $brightness = "100";
	        }
	        else if(intval($brightness) < 0){
	                $brightness = "0";
	        }
                writeSetting("BRIGHTNESS",$brightness);
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
	if(isset($_POST["maxwidth"])){
                $maxWidth = $_POST["maxwidth"];
                writeSetting("MAX_WIDTH",$maxWidth);
        }
	if(isset($_POST["haptic"])){
                $haptic = $_POST["haptic"];
                writeSetting("HAPTIC",$haptic);
        }
	if(isset($_POST["animations"])){
                $animations = $_POST["animations"];
                writeSetting("ANIMATIONS",$animations);
        }
	if(isset($_POST["beta"])){
                $beta = $_POST["beta"];
                writeSetting("BETA_MODE",$beta);
        }
	if(isset($_POST["wemoSupport"])){
                $wemoSupport = $_POST["wemoSupport"];
                writeSetting("WEMO_SUPPORT",$wemoSupport);
        }
	if(isset($_POST["freqAttached"])){
                $freqAttached = $_POST["freqAttached"];
                writeSetting("FREQ_ATTACHED",$freqAttached);
        }
	if(isset($_POST["netInterface"])){
                $netInterface = $_POST["netInterface"];
                writeSetting("NET_INTERFACE",$netInterface);
        }
	if(isset($_POST["notifications"])){
                $notifications = $_POST["notifications"];
                writeSetting("NOTIFICATIONS",$notifications);
        }

	$specialColorEnabled = trim(file_get_contents("misc/special/colorEnabled"));
        if($specialColorEnabled == "ENABLED"){
                $onColor = trim(file_get_contents("misc/special/onColor"));
                $offColor = trim(file_get_contents("misc/special/offColor"));
        }



	if($securityKick == "ENABLED"){
		$securityLink = "security.php?kick";
	}
	else{
		$securityLink = "security.php";
	}


	//SET BOOLEAN COLORS
	if($statusLed == "ENABLED"){
		$statusLedColor = $onColor;
	}
	else{
		$statusLedColor = $offColor;
	}
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
        if($haptic == "ENABLED"){
                $hapticColor = $onColor;
        }
        else{
                $hapticColor = $offColor;
        }
        if($beta == "ENABLED"){
                $betaColor = $onColor;
        }
        else{
                $betaColor = $offColor;
        }
        if($wemoSupport == "ENABLED"){
                $wemoSupportColor = $onColor;
        }
        else{
                $wemoSupportColor = $offColor;
        }

	$subject = file_get_contents("misc/network.ifaces");
	$subject = explode("\n",$subject);
	$netDrop = "";

	foreach($subject as &$line){
		if(strlen($line) > 3){
			$netDrop = $netDrop . "<option class='dropContainer' value='" . $line . "'>" . $line . "</option>";
		}
	}

?>

<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=<?php echo $uiScale;?>,maximum-scale=<?php echo $uiScale;?>, user-scalable=no"/>
		<title>ElectroPi Config</title>

		<?php include("header.php");?>

		<script type="text/javascript" src="js/bug.js"></script>
		<script type="text/javascript" src="js/jscolor.js"></script>
		<script type="text/javascript">
			$(function(){  // $(document).ready shorthand
				$("#notify").hide();
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
					}, 30000);
				}, 60000);

				var textToFind = <?php echo json_encode($netInterface);?>;

				var dd = document.getElementById('netDrop');
				for (var i = 0; i < dd.options.length; i++) {
				    if (dd.options[i].text === textToFind) {
				        dd.selectedIndex = i;
				        break;
				    }
				}
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
			function help(id){
        			if(id == "brightness"){
                			alert("BRIGHTNESS\n\nThis is how bright the RGB status LED is lit. (0 - 100)");
        			}
        			else if(id == "animations"){
                			alert("ANIMATIONS\n\nDisabling animations will remove jQuery fades in UI elements and can be useful in less powerful browsers.");
        			}
        			else if(id == "notifications"){
                			alert("NOTIFICATIONS\n\nBy default, any control activity or system updates appear in the bottom-left instantly to all users as notifications.");
        			}
        			else if(id == "uiScale"){
                			alert("MOBILE UI SCALE\n\n(ONLY AVAIABLE ON MOBILE) This allows the user to scale the interface smaller or larger to suit their device or vision.");
        			}
        			else if(id == "onColor"){
                			alert("'ON' COLOR\n\nThis is the color (HEX) that appliances with power will be marked with.");
        			}
        			else if(id == "offColor"){
                			alert("'OFF' COLOR\n\nThis is the color (HEX) that appliances without power will be marked with.");
        			}
        			else if(id == "beta"){
                			alert("BETA MODE\n\nEnabling this mode allows the use of unfinished/experimental features that aren't yet included in official releases. By enabling this mode, you accept any appliance/preset/logic/schedule corruption possibilities...");
        			}
				else if(id == "maxWidth"){
					alert("MAX WIDTH\n\nThis is the maximum width the interface can stretch to horizontally.");
				}
				else if(id == "haptic"){
					alert("HAPTIC FEEDBACK\n\nIf enabled, you'll feel a soft vibration kick when you switch an appliance on or off. (Only works on Android devices, sorry. iPhones should have this feature sometime around the year 2035...)");
				}
				else if(id == "statusLed"){
					alert("STATUS LED\n\nIf enabled, the Python watchdog will write color statuses to the board's RGB LED.");
				}
				else if(id == "freqAttached"){
					alert("FREQUENCY ATTACHED\n\nThis is the frequency attached to your master ElectroPi. (This board)");
				}
				else if(id == "netInterface"){
					alert("NETWORK INTERFACE\n\nImportant to ElectroPi's Client Watchdog, this defines the network to use for arp-scanning. 'eth0' is the Ethernet port on the Pi, 'wlan0' would be a WiFi dongle. Make sure this matches your setup.");
				}
				else if(id == "wemoSupport"){
					alert("WEMO SUPPORT\n\nWhen enabled, ElectroPi will automatically populate a list of WeMo Switches on your network, and allow you to control them!");
				}
			}
		</script>

	</head>

	<body id="body">

		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: <?php echo $maxWidth;?>px;">
		<tr><td><div id="alert">Settings updated. <a href="index.php" style="white-space: nowrap;color: <?php echo $offColor; ?>;">Return to control?</a></div></td></tr>
		</table>

		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: <?php echo $maxWidth;?>px;">
			<tr><td><a href="index.php" style="color:<?php echo $offColor; ?>;">ELECTROPI</a> >> CONFIG</td></tr>
			<tr id="verticalSpace"></tr>
			<tr id="verticalSpace"></tr>
			<tr>
			<td><a href="trackList.php"><div id="linkButton">TRACKING ACTIONS</div></a></td>
			</tr>
			<tr id="verticalSpace"></tr>
			<tr>
			<td><a href="<?php echo $securityLink;?>"><div id="linkButton">SECURITY</div></a></td>
			</tr>
			<tr id="verticalSpace"></tr>
			<tr>
			<td><a href="credits.php"><div id="linkButton">CREDITS</div></a></td>
			</tr>
			<tr id="verticalSpace"></tr>
		</table>
		<br>
		<form action="setup.php" method="POST">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: <?php echo $maxWidth;?>px;background-color: #181818;">
			<tr id="verticalSpace"></tr>
			<tr>
			<td><div id="settingHeader">WATCHDOG</td>
			<td id="horizontalSpaceConfig"></td>
                        <td style="text-align: right;"><input type="submit" value="APPLY" style="width: 100px;height: 50px;border: none;background-color: <?php echo $offColor; ?>;font-family: 'Oswald', sans-serif;font-size: 20px;margin-bottom: 5px;margin-right: 5px;"></input></td>
			</tr>

                        <tr id="settingRow">
                                        <td id="settingName">STATUS LED</td>
                                        <td id="horizontalSpaceHelp" onclick="help('statusLed');">?</td>
                                        <td class="settingValue" id="statusLed" onclick="booleanSwitch('statusLed');" style="padding:10px;background-color:<?php echo $statusLedColor;?>;"><div id="statusLedName" style="font-size:20px;color:#242424"><?php echo $statusLed;?></div><input type="hidden" name="statusLed" value="<?php echo $statusLed;?>"></input></td>
                        </tr>
			<tr id="verticalSpace"></tr>
			<tr id="settingRow">
                                        <td id="settingName">LED BRIGHTNESS</td>
                                        <td id="horizontalSpaceHelp" onclick="help('brightness');">?</td>
                                        <td class="settingValue"><input id="setText" type="text" name="brightness" value="<?php echo $brightness;?>"></input></td>
                        </tr>

		</table>
		<input type="hidden" name="updated" value="TRUE">
		</form>

		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: <?php echo $maxWidth;?>px;height: 20px;background-color: #181818;margin-top: 0px;margin-bottom: 30px;"></table>

		<form action="setup.php" method="POST">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: <?php echo $maxWidth;?>px;background-color: #181818;">
                <tr id="verticalSpace"></tr>
		<tr>
			<td><div id="settingHeader">INTERFACE</td>
			<td id="horizontalSpaceConfig"></td>
                        <td style="text-align: right;"><input type="submit" value="APPLY" style="width: 100px;height: 50px;border: none;background-color: <?php echo $offColor; ?>;font-family: 'Oswald', sans-serif;font-size: 20px;margin-bottom: 5px;margin-right: 5px;"></input></td>
		</tr>

                        <tr id="settingRow">
                                        <td id="settingName">ANIMATIONS</td>
                                        <td id="horizontalSpaceHelp" onclick="help('animations');">?</td>
                                        <td class="settingValue" id="animations" onclick="booleanSwitch('animations');" style="padding:10px;background-color:<?php echo $animationsColor;?>;"><div id="animationsName" style="font-size:20px;color:#242424"><?php echo $animations;?></div><input type="hidden" name="animations" value="<?php echo $animations;?>"></input></td>
                        </tr>
			<tr id="verticalSpace"></tr>
			<tr id="settingRow">
                                        <td id="settingName">NOTIFICATIONS</td>
                                        <td id="horizontalSpaceHelp" onclick="help('notifications');">?</td>
                                        <td class="settingValue" id="notifications" onclick="booleanSwitch('notifications');" style="padding:10px;background-color:<?php echo $notificationsColor;?>;"><div id="notificationsName" style="font-size:20px;color:#242424"><?php echo $notifications;?></div><input type="hidden" name="notifications" value="<?php echo $notifications;?>"></input></td>
                        </tr>
			<tr id="verticalSpace"></tr>
                        <tr id="settingRow">
                                        <td id="settingName">MAX WIDTH (PX)</td>
                                        <td id="horizontalSpaceHelp" onclick="help('maxWidth');">?</td>
                                        <td class="settingValue"><input id="setText" type="text" name="maxwidth" value="<?php echo $maxWidth;?>"></input></td>
                        </tr>
			<tr id="verticalSpace"></tr>
                        <tr id="settingRow">
                                        <td id="settingName">MOBILE UI SCALE</td>
                                        <td id="horizontalSpaceHelp" onclick="help('uiScale');">?</td>
                                        <td class="settingValue"><input id="setText" type="text" name="ui_scale" value="<?php echo $uiScale;?>"></input></td>
                        </tr>
			<tr id="verticalSpace"></tr>
			<tr id="settingRow">
                                        <td id="settingName">HAPTIC FEEDBACK</td>
                                        <td id="horizontalSpaceHelp" onclick="help('haptic');">?</td>
                                        <td class="settingValue" id="haptic" onclick="booleanSwitch('haptic');" style="padding:10px;background-color:<?php echo $hapticColor;?>;"><div id="hapticName" style="font-size:20px;color:#242424"><?php echo $haptic;?></div><input type="hidden" name="haptic" value="<?php echo $haptic;?>"></input></td>
                        </tr>
			<tr id="verticalSpace"></tr>
                        <tr id="settingRow">
                                        <td id="settingName">"ON" COLOR</td>
                                        <td id="horizontalSpaceHelp" onclick="help('onColor');">?</td>
                                        <td class="settingValue"><input id="setText" name="oncolor" value="<?php echo $onColor;?>" class="color {hash:true,pickerPosition:'top'}"></td>
                        </tr>
			<tr id="verticalSpace"></tr>
			<tr id="settingRow">
                                        <td id="settingName">"OFF" COLOR</td>
                                        <td id="horizontalSpaceHelp" onclick="help('offColor');">?</td>
                                        <td class="settingValue"><input id="setText" name="offcolor" value="<?php echo $offColor;?>" class="color {hash:true,pickerPosition:'top'}"></td>
                        </tr>

                </table>
		<input type="hidden" name="updated" value="TRUE">
                </form>

		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: <?php echo $maxWidth;?>px;height: 20px;background-color: #181818;margin-top: 0px;margin-bottom: 30px;"></table>

		<form action="setup.php" method="POST">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: <?php echo $maxWidth;?>px;background-color: #181818;">
                <tr id="verticalSpace"></tr>
		<tr>
			<td><div id="settingHeader">ADVANCED</td>
			<td id="horizontalSpaceConfig"></td>
                        <td style="text-align: right;"><input type="submit" value="APPLY" style="width: 100px;height: 50px;border: none;background-color: <?php echo $offColor; ?>;font-family: 'Oswald', sans-serif;font-size: 20px;margin-bottom: 5px;margin-right: 5px;"></input></td>
		</tr>
			<tr id="settingRow">
                                        <td id="settingName">FREQUENCY ATTACHED (MHz)</td>
                                        <td id="horizontalSpaceHelp" onclick="help('freqAttached');">?</td>
                                        <td class="settingValue"><input id="setText" name="freqAttached" value="<?php echo $freqAttached;?>"></input></td>
                        </tr>
                        <tr id="verticalSpace"></tr>
			<tr id="settingRow">
                                        <td id="settingName">NET INTERFACE</td>
                                        <td id="horizontalSpaceHelp" onclick="help('netInterface');">?</td>
                                        <td class="settingValue">
                                                <select id="netDrop" name="netInterface">
                                                        <?php echo $netDrop;?>
                                                </select>
                                        </td>
                        </tr>
                        <tr id="verticalSpace"></tr>

                        <tr id="settingRow">
                                        <td id="settingName">WEMO SUPPORT</td>
                                        <td id="horizontalSpaceHelp" onclick="help('wemoSupport');">?</td>
                                        <td class="settingValue" id="wemoSupport" onclick="booleanSwitch('wemoSupport');" style="padding:10px;background-color:<?php echo $wemoSupportColor;?>;"><div id="wemoSupportName" style="font-size:20px;color:#242424"><?php echo $wemoSupport;?></div><input type="hidden" name="wemoSupport" value="<?php echo $wemoSupport;?>"></input></td>
                        </tr>
			<tr id="verticalSpace"></tr>
                        <tr id="settingRow">
                                        <td id="settingName">BETA MODE</td>
                                        <td id="horizontalSpaceHelp" onclick="help('beta');">?</td>
                                        <td class="settingValue" id="beta" onclick="booleanSwitch('beta');" style="padding:10px;background-color:<?php echo $betaColor;?>;"><div id="betaName" style="font-size:20px;color:#242424"><?php echo $beta;?></div><input type="hidden" name="beta" value="<?php echo $beta;?>"></input></td>
                        </tr>
			<tr id="verticalSpace"></tr>
			<tr id="verticalSpace"></tr>
			<tr id="verticalSpace"></tr>
                </table>
		<input type="hidden" name="updated" value="TRUE">
                </form>

		<div id="dummy" style="display:none"></div>

		<?php include("footer.php");?>
	</body>
</html>
