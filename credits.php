<?php
	include("settings.php");
	$title = "CREDITS";

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
