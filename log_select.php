<?php
include("password_protect.php");

$onColor = readSetting("ONCOLOR");
$offColor = readSetting("OFFCOLOR");
$uiScale = readSetting("UI_SCALE");

?>

<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=<?php echo $uiScale;?>,maximum-scale=<?php echo $uiScale;?>, user-scalable=no"/>
		<title>ElectroPi Logs</title>

		<?php include("header.php");?>

		<STYLE type="text/css">
                        #linkButton{color:<?php echo $offColor;?>;}
                        a{text-decoration:none;color:<?php echo $onColor;?>;}
                </STYLE>
	</head>

	<body id="body">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: 800px;">
                        <tr id="headerRow">
                                <td id="headerCell"><a href="index.php"><img id="logo" src="images/tx_animation.gif?<?php echo date('Ymdgis');?>"></a><div id="logoText" style="display: inline;color:<?php echo $offColor; ?>;padding-top: 10px;vertical-align: top;">ELECTRO</div>PI <font id="subtitle" style="color:#707070;padding-top: 10px;vertical-align: top;font-size: 24px;">LOGS</font></td>
                        </tr>
			<tr id="verticalSpace"></tr>
			<tr id="verticalSpace"></tr>
                </table>

		<div id="main">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: 800px;">
			<tr><td><a href="index.php" style="color:<?php echo $offColor; ?>;">ELECTROPI</a> >> <a href="config.php" style="color:<?php echo $offColor; ?>;">CONFIG</a> >> <a href="config.php" style="color:<?php echo $offColor; ?>;">SECURITY</a> >> LOGS</td></tr>
                        <tr id="verticalSpace"></tr>
                        <tr id="verticalSpace"></tr>
                        <tr>
                        <td><a href="logView.php?log=master"><div id="linkButton">MASTER LOG</div></a></td>
                        </tr>
                        <tr id="verticalSpace"></tr>
                        <tr>
                        <td><a href="logView.php?log=notifications"><div id="linkButton">NOTIFICATION LOG</div></a></td>
                        </tr>
                        <tr id="verticalSpace"></tr>
                        <tr>
                        <td><a href="logView.php?log=watchdog"><div id="linkButton">WATCHDOG LOG</div></a></td>
                        </tr>
                        <tr id="verticalSpace"></tr>
                        <tr>
                        <td><a href="logView.php?log=ip"><div id="linkButton">IP LOG</div></a></td>
                        </tr>
                        <tr id="verticalSpace"></tr>
                </table>

		</div>
		<div id="notify" style="position: fixed;bottom: 0px;">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width: 800px;height: 100%;"><tr><td valign="bottom" style="padding: 20px;background-color: #1a1a1a;"><div id="notification"></div></td></tr></table>
                </div>
		<div id="dummy" style="display:none"></div>
	</body>
</html>
