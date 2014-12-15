<?php
	include("password_protect.php");
	$title="TRACKING";
	$hideSettings=True;

	$onColor = readSetting("ONCOLOR");
	$offColor = readSetting("OFFCOLOR");
        $animations = readSetting("ANIMATIONS");
        $notifications = readSetting("NOTIFICATIONS");
	$uiScale = readSetting("UI_SCALE");
	$maxWidth = readSetting("MAX_WIDTH");
	$haptic = readSetting("HAPTIC");
	//ADVANCED
	$beta = readSetting("BETA_MODE");

	$specialColorEnabled = trim(file_get_contents("misc/special/colorEnabled"));
        if($specialColorEnabled == "ENABLED"){
                $onColor = trim(file_get_contents("misc/special/onColor"));
                $offColor = trim(file_get_contents("misc/special/offColor"));
        }

	$deviceDrop = '';
	$deviceList = file_get_contents("conf/device.list");
	$deviceList = explode("\n",$deviceList);
	foreach($deviceList as $device){
		if(strlen($device) > 3){
			$pieces = explode("|",$device);
			$nick = str_replace(" ","-",$pieces[0]);
			$mac = str_replace(":","-",$pieces[1]);
			$mac = strtoupper($mac);
			$ip = $pieces[2];
			$deviceDrop = $deviceDrop . "<option class='dropContainer' value='" . $nick . "|". $mac . "'>".$nick." | ".$mac."</option>";
		}
	}

	$actionDrop = '';
	$actionList = file_get_contents("conf/actions/actions.list");
	$actionList = explode("\n",$actionList);
	foreach($actionList as $action){
		if(strlen($action) > 3){
			$pieces = explode("|",$action);
			$nick = $pieces[0];
			$act = $pieces[1];
			$actionDrop = $actionDrop . "<option class='dropContainer' value='" . $act . "|" . $nick . "'>".$nick."</option>";
		}
	}

	if(isset($_GET["updated"])){
		$deviceGet = $_GET["device"];
		$deviceGet = explode("|",$deviceGet);
		$deviceNick = $deviceGet[0];
		$device = $deviceGet[1];
		$device = strtolower(str_replace("-",":",$device));
		$jActionGet = $_GET["jAction"];
		$lActionGet = $_GET["lAction"];

		$jActionGet = explode("|",$jActionGet);
		$jAction = $jActionGet[0];
		$jActionNick = $jActionGet[1];

		$lActionGet = explode("|",$lActionGet);
		$lAction = $lActionGet[0];
		$lActionNick = $lActionGet[1];

		$trackList = file_get_contents("conf/track.list");
		$desc = "When ".$deviceNick." joins, do action '".$jActionNick."'+When leaves, do action '".$lActionNick."'.";
		$trackList = $trackList . $device."|".$jAction."|".$lAction."|".$desc."\n";
		file_put_contents("conf/track.list",$trackList);
		echo $trackList;
		header("Location: index.php");
	}

?>

<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=<?php echo $uiScale;?>,maximum-scale=<?php echo $uiScale;?>, user-scalable=no"/>
		<title>ElectroPi Add Tracking</title>

		<?php include("header.php");?>

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
    			});
			function help(id){
				if(id == "device"){
                                        alert("DEVICE\n\nThis is the device that we're adding MAC Tracking to.");
                                }
				if(id == "jAction"){
                                        alert("'JOIN' ACTION\n\nThis is what 'Action' ElectroPi should carry out if the device in question joins the WiFi network.");
                                }
				if(id == "lAction"){
                                        alert("'LEAVE' ACTION\n\nThis is what 'Action' ElectroPi should carry out if the device in question leaves the WiFi network.");
                                }

			}
		</script>

	</head>

	<body id="body">

		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: <?php echo $maxWidth;?>px;">
			<tr><td><a href="index.php" style="color:<?php echo $offColor; ?>;">ELECTROPI</a> >> ADD TRACKING</td></tr>
			<tr id="verticalSpace"></tr>
		</table>
		<br>
		<form action="addTracking.php" method="GET">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: <?php echo $maxWidth;?>px;background-color: #242424;">
			<tr>
				<td style="font-size:24px;color:<?php echo $offColor;?>;">
					WHEN THE DEVICE...
				</td>
			</tr>
		</table>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: <?php echo $maxWidth;?>px;background-color: #242424;">
			<tr id="verticalSpace"></tr>
			<tr id="settingRow">
                                        <td id="settingName">DEVICE</td>
                                        <td id="horizontalSpaceHelp" onclick="help('device');">?</td>
                                        <td class="settingValue">
						<select id="setText" name="device">
							<option class="dropContainer" value="NONE">SELECT A DEVICE...</option>
							<?php echo $deviceDrop;?>
						</select>
					</td>
                        </tr>
			<tr id="verticalSpace"></tr>
		</table>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: <?php echo $maxWidth;?>px;background-color: #242424;">
			<tr>
				<td style="font-size:24px;color:<?php echo $offColor;?>;">
					JOINS THE NETWORK, DO THIS:
				</td>
			</tr>
		</table>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: <?php echo $maxWidth;?>px;background-color: #242424;">
			<tr id="verticalSpace"></tr>
			<tr id="settingRow">
                                        <td id="settingName">ACTION</td>
                                        <td id="horizontalSpaceHelp" onclick="help('lAction');">?</td>
                                        <td class="settingValue">
						<select id="setText" name="jAction">
							<option class="dropContainer" value="SELECT">SELECT AN ACTION...</option>
							<option class="dropContainer" value="NOTHING">DO NOTHING</option>
							<?php echo $actionDrop;?>
						</select>
					</td>
                        </tr>
			<tr id="verticalSpace"></tr>
		</table>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: <?php echo $maxWidth;?>px;background-color: #242424;">
			<tr>
				<td style="font-size:24px;color:<?php echo $offColor;?>;">
					LEAVES THE NETWORK, DO THIS:
				</td>
			</tr>
		</table>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: <?php echo $maxWidth;?>px;background-color: #242424;">
			<tr id="verticalSpace"></tr>
			<tr id="settingRow">
                                        <td id="settingName">ACTION</td>
                                        <td id="horizontalSpaceHelp" onclick="help('jAction');">?</td>
                                        <td class="settingValue">
						<select id="setText" name="lAction">
							<option class="dropContainer" value="SELECT">SELECT AN ACTION...</option>
							<option class="dropContainer" value="NOTHING">DO NOTHING</option>
							<?php echo $actionDrop;?>
						</select>
					</td>
                        </tr>
			<tr id="verticalSpace"></tr>
			<tr id="verticalSpace"></tr>
			<tr id="verticalSpace"></tr>
			<tr id="verticalSpace"></tr>
			<tr>
				<td>
					<input type="submit" value="ADD TRACKING" style="width: 150px;height: 50px;border: none;background-color: <?php echo $offColor; ?>;font-family: 'Oswald', sans-serif;font-size: 24px;margin-bottom: 5px;margin-right: 5px;"></input>
				</td>
			</tr>
		</table>
		<input type="hidden" name="updated" value="TRUE">
		</form>

		<div id="dummy" style="display:none"></div>
	</body>
</html>

<?php include("footer.php");?>
