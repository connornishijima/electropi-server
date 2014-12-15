<?php
	include("password_protect.php");
	include("appLookup.php");
	$title = "EDIT";

	if(isset($_GET["uid"])){
		$appUID = $_GET["uid"];
		$appName = getAppName($appUID);
		$appLine = readApp($appName);
		$appSettings = explode("|",$appLine);
		$nickname = $appSettings[0];
		$state = "1";
		$onCode = $appSettings[2];
		$offCode = $appSettings[3];
		$repeat = $appSettings[7];
		$uid = "'" . $appUID . "'";
		$uidNoQuote = $appUID;
		$freq = $appSettings[9];
	}

	$onColor = readSetting("ONCOLOR");
	$offColor = readSetting("OFFCOLOR");
	$uiScale = readSetting("UI_SCALE");
	$animations = readSetting("ANIMATIONS");
	$maxWidth = readSetting("MAX_WIDTH");

	$updated = "FALSE";

	if(isset($_GET["nickname"])){
		$nickname = $_GET["nickname"];
	}
	if(isset($_GET["repeat"])){
		$repeat = $_GET["repeat"];
	}
	if(isset($_GET["oncode"])){
		$onCode = $_GET["oncode"];
	}
	if(isset($_GET["offcode"])){
		$offCode = $_GET["offcode"];
	}
	if(isset($_GET["freq"])){
		$freq = $_GET["freq"];
	}

	if(isset($_GET["updated"])){
		$updated = "TRUE";
		file_put_contents("misc/notification.txt","APPLIANCE '" . $nickname . "' UPDATED! | " . $_SERVER['REMOTE_ADDR']);
		//WRITE APP CHANGES HERE
		$outString = $nickname . "|D|" . $onCode . "|" . $offCode . "|D|D|D|" . $repeat . "|" . $uid . "|". $freq . "\n";
		writeApp($appName,$outString);
	}
?>

<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=<?php echo $uiScale; ?>,maximum-scale=<?php echo $uiScale; ?>, user-scalable=no"/>
		<title>ElectroPi Edit Appliance</title>

		<?php include("header.php");?>

		<STYLE type="text/css">
                        #linkButton{color:<?php echo $offColor;?>;}
                        a{text-decoration:none;color:<?php echo $onColor;?>;}
                </STYLE>

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
		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: <?php echo $maxWidth;?>px;">
		<tr><td><div id="alert">Settings updated. <a href="index.php" style="white-space: nowrap;color: <?php echo $offColor; ?>;">Return to control?</a></div></td></tr>
		</table>

		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: <?php echo $maxWidth;?>px;">
			<tr><td><a href="index.php" style="color:<?php echo $offColor; ?>;">ELECTROPI</a> >> EDIT "<?php echo $nickname; ?>"</td></tr>
                        <tr id="verticalSpace"></tr>
			<tr id="verticalSpace"></tr>
			<tr>
			<td class="beta"><a href="password_check.php"><div id="linkButton">APPLY PRESET</div></a></td>
			</tr>
			<tr id="verticalSpace"></tr>
			<tr id="verticalSpace"></tr>
			<tr>
			<td><a href="eraseApp.php?uid=<?php echo $uidNoQuote;?>"><div id="linkButton">DELETE APPLIANCE</div></a></td>
			</tr>
			<tr id="verticalSpace"></tr>
			<tr id="verticalSpace"></tr>
		</table>

		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: <?php echo $maxWidth;?>px;">
			<tr>
				<td>
					<iframe src="code.php?code=on&uid=<?php echo $uidNoQuote;?>" width="100%" style="height:50px;border:none;"></iframe>
				</td>
			</tr>
                        <tr id="verticalSpace"></tr>
			<tr>
				<td>
					<iframe src="code.php?code=off&uid=<?php echo $uidNoQuote;?>" width="100%" style="height:50px;border:none;"></iframe>
				</td>
			</tr>
                        <tr id="verticalSpace"></tr>
                </table>

		<form action="editApp.php" method="GET">
		<input type="hidden" name="updated" value="TRUE"></input>
		<input type="hidden" name="uid" value="<?php echo $uidNoQuote;?>"></input>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: <?php echo $maxWidth;?>px;background-color: #181818;">
			<tr id="verticalSpace"></tr>
			<tr>
			<td><div id="settingHeader">EDIT <font style="color:<?php echo $offColor;?>;">"<?php echo $nickname; ?>"</font></td>
			<td id="horizontalSpaceConfig"></td>
                        <td style="text-align: right;"><input type="submit" value="APPLY" style="width: 100px;height: 50px;border: none;background-color: <?php echo $offColor; ?>;font-family: 'Oswald', sans-serif;font-size: 24px;margin-bottom: 5px;margin-right: 5px;"></input></td>
			</tr>

			<tr id="settingRow">
                                        <td id="settingName">NICKNAME</td>
                                        <td id="horizontalSpaceConfig"></td>
					<td class="settingValue"><input id="setText" type="text" name="nickname" value="<?php echo $nickname;?>"></input></td>
                        </tr>
			<tr id="verticalSpace"></tr>
			<tr id="settingRow">
                                        <td id="settingName">REPEAT</td>
                                        <td id="horizontalSpaceConfig"></td>
					<td class="settingValue"><input id="setText" type="text" name="repeat" value="<?php echo $repeat;?>"></input></td>
                        </tr>
			<tr id="verticalSpace"></tr>
			<tr id="settingRow">
                                        <td id="settingName">"ON" CODE</td>
                                        <td id="horizontalSpaceConfig"></td>
					<td class="settingValue" style="background-color:<?php echo $onColor;?>;"><input id="setText" style="color:#242424;" type="text" name="oncode" value="<?php echo $onCode;?>"></input></td>
                        </tr>
			<tr id="verticalSpace"></tr>
			<tr id="settingRow">
                                        <td id="settingName">"OFF" CODE</td>
                                        <td id="horizontalSpaceConfig"></td>
					<td class="settingValue" style="background-color:<?php echo $offColor;?>;"><input id="setText" style="color:#242424;" type="text" name="offcode" value="<?php echo $offCode;?>"></input></td>
                        </tr>
			<tr id="verticalSpace"></tr>
			<tr id="settingRow">
                                        <td id="settingName">FREQUENCY (MHz)</td>
                                        <td id="horizontalSpaceConfig"></td>
					<td class="settingValue"><input id="setText" type="text" name="freq" value="<?php echo $freq;?>"></input></td>
                        </tr>
			<tr id="verticalSpace"></tr>
		</table>
		</form>

		<?php include("footer.php");?>

		</div>
		<div id="dummy" style="display:none"></div>
	</body>
</html>
