<?php
	include("password.php");
	$ip = $_SERVER['REMOTE_ADDR'];
	checkIP($ip);

	if(!isset($logoColor)){
		$logoColor = $sets['SETTINGS']["onColor"];
	}
	else{
		if($logoColor == "off"){
			$logoColor = $sets['SETTINGS']["offColor"];
		}
	}

	if($titleType == "hide"){
		$titleLine = "<title>ElectroPi</title>";
	}
	else{
		$titleLine = "<title>ElectroPi | ".$title."</title>";
	}

	if(!isset($gear)){
		$gearLink = "<td align='right'><a href='config.php'><img src='images/settings.png' style='width:32px;height:32px;opacity: 0.3;'/></a></td>";
	}

	if($passMD5 == "4dfcb7e47d53ff431f231f8bfc51c32d"){
		$warningString = "Access password is default 'electropi'. <a href='config.php?view=confPassword'>Change it now?</a>";
	}

	echo $titleLine;

?>
<head>
	&nbsp;
	<link rel="manifest" href="manifest.json">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="app-mobile-web-app-capable" content="yes">

	<link rel="icon" sizes="192x192" href="images/icon-192x192.png">
	<link rel="icon" sizes="144x144" href="images/icon-144x144.png">
	<link rel="icon" sizes="120x120" href="images/icon-120x120.png">
	<link rel="icon" sizes="96x96" href="images/icon-96x96.png">
	<link rel="icon" sizes="48x48" href="images/icon-48x48.png">
	<link rel="icon" sizes="36x36" href="images/icon-36x36.png">

	<link rel="apple-touch-icon" sizes="192x192" href="images/icon-192x192.png">
	<link rel="apple-touch-icon" sizes="144x144" href="images/icon-144x144.png">
	<link rel="apple-touch-icon" sizes="120x120" href="images/icon-120x120.png">
	<link rel="apple-touch-icon" sizes="96x96" href="images/icon-96x96.png">
	<link rel="apple-touch-icon" sizes="48x48" href="images/icon-48x48.png">
	<link rel="apple-touch-icon" sizes="36x36" href="images/icon-36x36.png">

	<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" type="text/css" href="css/tooltipster.css" />

	<link href='http://fonts.googleapis.com/css?family=Oswald:400,700' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Dosis' rel='stylesheet' type='text/css'>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />

	<script src="js/jquery.js"></script>
	<script src="js/jquery-ui.js"></script>

	<script type="text/javascript">
		$( document ).ready(function() {
			$( "#headWrap" ).hide();
			$( "#wrapper" ).hide();
		});
        </script>

	<style type="text/css">
		a{color:<?php echo $sets['SETTINGS']["onColor"];?>;text-decoration:none;}
		.h1{color:<?php echo $sets['SETTINGS']["onColor"];?>;}
		.freqLeft,.freqRight{color:<?php echo $sets['SETTINGS']["offColor"];?>;}
		#confBack{
			color:<?php echo $sets['SETTINGS']["offColor"];?>;
		}
		.linkRow{
		        background-color: #080808;
		        padding: 9px;
		        font-size: 20px;
		        color: #ff5c93;
		}
		.highlightOn{
			color:<?php echo $sets['SETTINGS']["onColor"];?>;
		}
		.highlightOff{
			color:<?php echo $sets['SETTINGS']["offColor"];?>;
		}

	</style>

	<div id="warning">
		<div id="warningHead" style="color:#ff5c93">
			WHOOPS! ONE SEC.
		</div>
		<div id="warningTail">
			We've lost connection to the watchdog backend.<br>Reboot your Pi if this warning doesn't subside within a few moments...
		</div>
	</div>
	<div id="whole">
	<div id="wholeInner">

	<div id="headWrap">
		<table <?php echo $tabStretch;?>>
			<tr>
				<td style="padding-top:8px;width:32px;"><a href="index.php"><img src="images/logostatic.png" id="logo" style="width:64px;height:64px;"/></a></td>
				<td id="epTitle"><a href="index.php" style="color:<?php echo $logoColor;?>;">ELECTRO<div style="display:inline-block;color:#CCC;">PI</div></a><br><div id="epSubtitle"><?php echo $title;?></div></td>
				<?php echo $gearLink;?>
			</tr>
		</table>
		<table <?php echo $tabStretch;?>>
			<tr>
				<td style="padding-bottom: 15px;"></td>
			</tr>
			<tr>
				<td style="background-image:url('images/gradient.png');background-repeat: no-repeat;height: 2px;padding-bottom:10px;"></td>
			</tr>
		</table>
	</div>
	<div id="bannerWrap" style="display:none;">
		<table <?php echo $tabStretch;?>>
                        <tr>
				<td>
					<div id="bannerInner">
						BANNER TEXT
					</div>
				</td>
			</tr>
		</table>
	</div>
</head>
