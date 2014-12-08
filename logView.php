<?php
include("password_protect.php");
$onColor = readSetting("ONCOLOR");
$offColor = readSetting("OFFCOLOR");
$uiScale = readSetting("UI_SCALE");
?>

<?php
	$log = $_GET["log"];
	$filename = "logs/" . $log . ".log";

	$file = popen("tac $filename",'r');
	while ($line = fgets($file)) {
		$logString = $logString . $line;
	}

?>

<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=<?php echo $uiScale; ?>,maximum-scale=<?php echo $uiScale; ?>, user-scalable=no"/>
		<title>ElectroPi Logs</title>

		<?php include("header.php"); ?>

		<STYLE type="text/css">
                        #linkButton{color:<?php echo $offColor;?>;}
                        a{text-decoration:none;color:<?php echo $onColor;?>;}
                </STYLE>
	</head>

	<body id="body">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: 800px;">
                        <tr id="headerRow">
                                <td id="headerCell"><a href="index.php"><img id="logo" src="images/tx_animation.gif?<?php echo date('Ymdgis');?>"></a><div id="logoText" style="display: inline;color:<?php echo $offColor; ?>;padding-top: 10px;vertical-align: top;">ELECTRO</div>PI <font id="subtitle" style="color:#707070;padding-top: 10px;vertical-align: top;font-size: 24px;">LOG VIEW</font></td>
                        </tr>
			<tr id="verticalSpace"></tr>
			<tr id="verticalSpace"></tr>
                </table>

		<div id="main">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-size:24px;margin-left:auto;margin-right:auto;max-width: 800px;">
			<tr><td><a href="log_select.php" style="color:<?php echo $offColor; ?>;">RETURN</a></td></tr>
                        <tr id="verticalSpace"></tr>
                        <tr id="verticalSpace"></tr>
                        <tr>
                        <td style="font-size:14px;"><pre><?php echo $logString;?></td></pre></tr>
                        <tr id="verticalSpace"></tr>
                </table>

		<div id="notify" style="position: fixed;bottom: 0px;">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width: 800px;height: 100%;"><tr><td valign="bottom" style="padding: 20px;background-color: #1a1a1a;"><div id="notification"></div></td></tr></table>
                </div>
		</div>
		<div id="dummy" style="display:none"></div>
	</body>
</html>
