<?php
	include("password_protect.php");
	$title = "EDIT";

	$alertText = 'Settings updated. <a href="index.php" style="white-space: nowrap;color: <?php echo $offColor; ?>;">Return to control?</a>';
	$onColor = readSetting("ONCOLOR");
	$offColor = readSetting("OFFCOLOR");
	$uiScale = readSetting("UI_SCALE");
	$maxWidth = readSetting("MAX_WIDTH");
	$storedPass = readSetting("PASSMD5");

	$oldPass = "";
	$newPass = "";
	$confPass = "";
	$ERR = "";

	if(isset($_POST["oldPass"])){
		$oldPass = md5($_POST["oldPass"]);
	}
	if(isset($_POST["newPass"])){
		$newPass = md5($_POST["newPass"]);
	}
	if(isset($_POST["confPass"])){
		$confPass = md5($_POST["confPass"]);

		if($newPass == $confPass){
			if($oldPass == $storedPass){
				writeSetting("PASSMD5",$newPass);
			}
			else{
				$alertText = "<font style='color:" . $offColor . ";'>'OLD PASSWORD' DOES NOT MATCH STORED PASSWORD!</font>";
			}
		}
		else{
			$alertText = "<font style='color:" . $offColor . ";'>NEW PASSWORD CONFIRMATION DOES NOT MATCH!</font>";
		}
	}

	if(isset($_POST["updated"])){
		$updated = "TRUE";
		file_put_contents("misc/notification.txt","PASSWORD UPDATED! | " . $_SERVER['REMOTE_ADDR']);
		//WRITE APP CHANGES HERE
	}

	if($storedPass == "4dfcb7e47d53ff431f231f8bfc51c32d"){
		$ERR = "Password is 'electropi'. PLEASE change this default password immediately!";
	}
?>

<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=<?php echo $uiScale; ?>,maximum-scale=<?php echo $uiScale; ?>, user-scalable=no"/>
		<title>ElectroPi Edit Password</title>

		<?php include("header.php");?>

		<STYLE type="text/css">
                        #linkButton{color:<?php echo $offColor;?>;}
                        a{text-decoration:none;color:<?php echo $onColor;?>;}
                </STYLE>

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
		<tr><td><div id="alert"><?php echo $alertText;?></div></td></tr>
		</table>

		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: <?php echo $maxWidth;?>px;">
			<tr><td><a href="index.php" style="color:<?php echo $offColor; ?>;">ELECTROPI</a> >> EDIT PASSWORD</td></tr>
                        <tr id="verticalSpace"></tr>
			<tr id="verticalSpace"></tr>
		</table>

		<form action="editPassword.php" method="POST">
		<input type="hidden" name="updated" value="TRUE"></input>
		<input type="hidden" name="uid" value="<?php echo $uidNoQuote;?>"></input>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: <?php echo $maxWidth;?>px;background-color: #181818;">
			<tr id="verticalSpace"></tr>
			<tr>
			<td><div id="settingHeader">EDIT PASSWORD</div></td>
			<td id="horizontalSpaceConfig"></td>
                        <td style="text-align: right;"><input type="submit" value="APPLY" style="width: 100px;height: 50px;border: none;background-color: <?php echo $offColor; ?>;font-family: 'Oswald', sans-serif;font-size: 24px;margin-bottom: 5px;margin-right: 5px;"></input></td>
			</tr>

			<tr id="settingRow">
                                        <td id="settingName">OLD PASSWORD</td>
                                        <td id="horizontalSpaceConfig"></td>
					<td class="settingValue"><input id="setText" type="password" name="oldPass" value=""></input></td>
                        </tr>
			<tr id="verticalSpace"></tr>
			<tr id="settingRow">
                                        <td id="settingName">NEW PASSWORD</td>
                                        <td id="horizontalSpaceConfig"></td>
					<td class="settingValue"><input id="setText" type="password" name="newPass" value=""></input></td>
                        </tr>
			<tr id="verticalSpace"></tr>
			<tr id="settingRow">
                                        <td id="settingName">CONFIRM PASSWORD</td>
                                        <td id="horizontalSpaceConfig"></td>
					<td class="settingValue"><input id="setText" type="password" name="confPass" value=""></input></td>
                        </tr>
		</table>
		</form>
		<br>

		<?php include("footer.php");?>

		<div id="notify" style="position: fixed;bottom: 0px;">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width: <?php echo $maxWidth;?>px;height: 100%;"><tr><td valign="bottom" style="padding: 20px;background-color: #1a1a1a;"><div id="notification"></div></td></tr></table>
                </div>
		</div>
		<div id="dummy" style="display:none"></div>
	</body>
</html>
