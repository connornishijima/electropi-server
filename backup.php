<?php
	include("password_protect.php");
	$title = "BACKUPS";
	$hideSettings = True;

	$onColor = readSetting("ONCOLOR");
	$offColor = readSetting("OFFCOLOR");
	$updated = "FALSE";

	if(isset($_POST["updated"])){
		$updated = "TRUE";
		file_put_contents("misc/notification.txt","CONFIGURATION UPDATED! | " . $_SERVER['REMOTE_ADDR']);
		$command = file_get_contents("misc/command.list");
		file_put_contents("misc/command.list",$command . "\nRST-FAST\n");
	}
?>

<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=<?php echo $uiScale;?>,maximum-scale=<?php echo $uiScale;?>, user-scalable=no"/>
		<title>ElectroPi Config</title>

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

		</script>

	</head>

	<body id="body">

		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: <?php echo $maxWidth;?>px;">
		<tr><td><div id="alert">Settings updated. <a href="index.php" style="white-space: nowrap;color: <?php echo $offColor; ?>;">Return to control?</a></div></td></tr>
		</table>

		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: <?php echo $maxWidth;?>px;">
			<tr><td><a href="index.php" style="color:<?php echo $offColor; ?>;">ELECTROPI</a> >> <a href="setup.php" style="color:<?php echo $offColor; ?>;">CONFIG</a> >> <a href="security.php" style="color:<?php echo $offColor; ?>;">SECURITY</a> >> BACKUP / RESTORE</td></tr>
			<tr id="verticalSpace"></tr>
		</table>
		<br>
		<form action="setup.php" method="POST">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: <?php echo $maxWidth;?>px;background-color: #181818;">
			<tr>
				<td><div id="settingHeader">EXPORT</td>
			</tr>
			<tr>
				<td style="padding: 10px;">Use this to export a *.epc (ElectroPi Configuration) file.</td>
			</tr>

		</table>
		<input type="hidden" name="updated" value="TRUE">
		</form>

		<br>

		<form action="setup.php" method="POST">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: <?php echo $maxWidth;?>px;background-color: #181818;">
			<tr>
				<td><div id="settingHeader">IMPORT</td>
			</tr>
			<tr>
				<td style="padding: 10px;">Use this to import a *.epc (ElectroPi Configuration) file.</td>
			</tr>

                </table>
		<input type="hidden" name="updated" value="TRUE">
                </form>

		<div id="dummy" style="display:none"></div>

		<?php include("footer.php");?>
	</body>
</html>
