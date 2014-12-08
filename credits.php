<?php
	include("settings.php");
	$uiScale = readSetting("UI_SCALE");
	$onColor = readSetting("ONCOLOR");
	$offColor = readSetting("OFFCOLOR");

	if(isset($_GET["logo"])){
		$logoVisible = "block";
	}
	else{
		$logoVisible = "none";
	}

?>

<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=<?php echo $uiScale; ?>,maximum-scale=<?php echo $uiScale; ?>, user-scalable=no"/>
		<title>ElectroPi</title>

		<?php include("header.php");?>

		<script type="text/javascript">

			$(function(){  // $(document).ready shorthand
				$("#notify").hide();
			});
			var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
			var h = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);


		</script>

	</head>

	<body id="body">
		<div style="display:<?php echo $logoVisible;?>;position: absolute;top: 0px;bottom: 0px;width: 100%;text-align: center;background-color: #242424;z-index: 10;">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="height:100%;">
			<tr>
				<td style="font-size:24px;">WELCOME TO <font style="color:#00ffbe;">ELECTRO</font>PI</td>
			</tr>
		</table>
		</div>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: 800px;">
			<tr id="headerRow">
				<td id="headerCell"><a href="index.php"><img id="logo" src="images/tx_animation_slow.gif?<?php echo date('Ymdgis');?>"></a><font id="logoText" style="color:<?php echo $offColor; ?>;padding-top: 10px;vertical-align: top;">ELECTRO</font>PI CREDITS</td>
				<td id="horizontalSpace"></td>
				<td id="settingsBtn"><a href="setup.php"><span style="width: 64px;height: 64px;position: absolute;margin-top: -32px;"></span></a></td>
			</tr>
			<tr id="verticalSpace"></tr>
		</table>
		<div id="main">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: 800px;">
			<tr id="creditRow">
				<td id="creditTitle">HARDWARE DESIGN <br>& SOFTWARE DEVELOPMENT</td><td id="creditName">CONNOR NISHIJIMA</td>
			</tr>
			<tr id="verticalSpace"></tr>
			<tr id="verticalSpace"></tr>
			<tr id="creditRow">
				<td id="creditTitle">BETA TESTING</td><td id="creditName">FITZ LAWRENCE<br>GABRIEL BALINT<br>MARK PEREZ<br>OLGA LAVROVA<br>JAY BURNESS</td>
			</tr>
			<tr id="verticalSpace"></tr>
			<tr id="verticalSpace"></tr>
			<tr id="creditRow">
				<td id="creditTitle">PASSWORD PROTECTION</td><td id="creditName">ZUBRAG</td>
			</tr>
			<tr id="verticalSpace"></tr>
			<tr id="verticalSpace"></tr>
			<tr id="creditRow">
                                <td id="creditTitle">JS COLOR</td><td id="creditName">JAN ODVARKO</td>
			</tr>
			<tr id="verticalSpace"></tr>
			<tr id="verticalSpace"></tr>
			<tr id="creditRow">
				<td id="creditTitle">INFESTATION</td><td id="creditName">AUZ</td>
			</tr>
			<tr id="verticalSpace"></tr>
			<tr id="verticalSpace"></tr>
			<tr id="creditRow">
				<td id="creditTitle">PCB FABRICATION</td><td id="creditName">JAMES "LAEN FINEHACK" NEAL</td>
			</tr>
			<tr id="verticalSpace"></tr>
			<tr id="verticalSpace"></tr>
			<tr id="creditRow">
				<td id="creditTitle">SMOOTHIE CHARTS</td><td id="creditName">JOE WALNES<br>DREW NOAKES</td>
			</tr>
			<tr id="verticalSpace"></tr>
			<tr id="verticalSpace"></tr>
			<tr id="creditRow">
				<td id="creditTitle">OUIMEAUX</td><td id="creditName">IAN MCCRACKEN</td>
			</tr>
			<tr id="verticalSpace"></tr>
			<tr id="verticalSpace"></tr>
			<tr id="verticalSpace"></tr>
			<tr id="verticalSpace"></tr>
		</table>
		</div>
		<?php include("footer.php");?>
	</body>
</html>
