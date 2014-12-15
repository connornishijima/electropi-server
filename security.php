<?php
	include("password_protect.php");
	$title = "SECURITY";

	$onColor = readSetting("ONCOLOR");
	$offColor = readSetting("OFFCOLOR");
	$uiScale = readSetting("UI_SCALE");
	$pass = readSetting("PASSMD5");

	$updated = "FALSE";

        // settings.php allows us to read and write from the configuration via functions
        //include("settings.php");

        //USER
	$securityKick = readSetting("SECURITY_KICK");
        //HARDWARE
        $jam = readSetting("JAM");

	if(isset($_POST["updated"])){
		$updated = "TRUE";
		file_put_contents("misc/notification.txt","SECURITY UPDATED! | " . $_SERVER['REMOTE_ADDR']);
	}
	if(isset($_POST["securityKick"])){
		$securityKick = $_POST["securityKick"];
		writeSetting("SECURITY_KICK",$securityKick);
	}
	if(isset($_POST["jam"])){
		$jam = $_POST["jam"];
		writeSetting("JAM",$jam);
	}

	$specialColorEnabled = trim(file_get_contents("misc/special/colorEnabled"));
        if($specialColorEnabled == "ENABLED"){
                $onColor = trim(file_get_contents("misc/special/onColor"));
                $offColor = trim(file_get_contents("misc/special/offColor"));
        }

	//SET BOOLEAN COLORS
	if($securityKick == "ENABLED"){
		$kickColor = $onColor;
	}
	else{
		$kickColor = $offColor;
	}

	if($jam == "ENABLED"){
		$jamColor = $onColor;
	}
	else{
		$jamColor = $offColor;
	}

?>

<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=<?php echo $uiScale; ?>,maximum-scale=<?php echo $uiScale; ?>, user-scalable=no"/>
		<title>ElectroPi Security</title>

		<?php include("header.php");?>

		<STYLE type="text/css">
                        #linkButton{color:<?php echo $offColor;?>;}
                        a{text-decoration:none;color:<?php echo $onColor;?>;}
                </STYLE>

		<script type="text/javascript" src="js/smoothie.js"></script>
		<script type="text/javascript">
			$(function(){  // $(document).ready shorthand
				$("#notify").hide();
				if(<?php echo json_encode($updated);?> == "TRUE"){
					$( "#alert" ).toggle();
				}

				var smoothie = new SmoothieChart({maxValue:100,minValue:0,grid:{borderVisible:false},labels:{disabled:true}});
                                var data_from_ajax;

                                smoothie.streamTo(document.getElementById("mycanvas"),1000 /*delay*/);
                                // Data
                                var line1 = new TimeSeries();

                                // Add a random value to each line every second
                                setInterval(function() {
                                        $.get('cpuListen.php', function(data) {
                                                data_from_ajax = data;
                                        });
                                        line1.append(new Date().getTime(), parseInt(data_from_ajax));
                                }, 1000);

                                // Add to SmoothieChart
                                smoothie.addTimeSeries(line1,{lineWidth:2,strokeStyle:'#ff0080',fillStyle:'rgba(255,0,128,0.30)'});
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
		</script>

	</head>

	<body id="body">

		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: 800px;">
		<tr><td><div id="alert">Settings updated. <a href="index.php" style="white-space: nowrap;color: <?php echo $offColor; ?>;">Return to control?</a></div></td></tr>
		</table>

		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: 800px;">
			<tr><td><a href="index.php" style="color:<?php echo $offColor; ?>;">ELECTROPI</a> >> <a href="setup.php" style="color:<?php echo $offColor; ?>;">CONFIG</a> >> SECURITY</td></tr>
                        <tr id="verticalSpace"></tr>
			<tr id="verticalSpace"></tr>
			<tr>
				<td>
					<div style="color:#ccc;text-align:left;">CPU USAGE:</div>
				</td>
			</tr>
			<tr id="verticalSpace"></tr>
			<tr>
				<td width="100%">
					<div style="width:500px;margin-left:auto;margin-right:auto;">
						<canvas id="mycanvas" width="500" height="100"></canvas>
					</div>
				</td>
			</tr>
			<tr>
			<td><a href="deviceList.php"><div id="linkButton">TRUSTED DEVICE LIST</div></a></td>
			</tr>
			<tr id="verticalSpace"></tr>
			<tr>
			<td><a href="slaveList.php"><div id="linkButton">SLAVE NODES</div></a></td>
			</tr>
			<tr id="verticalSpace"></tr>
			<tr>
			<td><a href="logs"><div id="linkButton">VIEW LOGS</div></a></td>
			</tr>
			<tr id="verticalSpace"></tr>
			<tr>
			<td><a href="editPassword.php"><div id="linkButton">CHANGE PASSWORD</div></a></td>
			</tr>
			<tr id="verticalSpace"></tr>
			<tr>
			<td><a href="serverReboot.php?pass=<?php echo $pass;?>"><div id="linkButton">REBOOT SERVER</div></a></td>
			</tr>
			<tr id="verticalSpace"></tr>
		</table>
		<br>

		<form action="security.php" method="POST">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: 800px;background-color: #181818;">
			<tr id="verticalSpace"></tr>
			<tr>
			<td><div id="settingHeader">SECURITY</td>
			<td id="horizontalSpaceConfig"></td>
                        <td style="text-align: right;"><input type="submit" value="APPLY" style="width: 100px;height: 50px;border: none;background-color: <?php echo $offColor; ?>;font-family: 'Oswald', sans-serif;font-size: 24px;margin-bottom: 5px;margin-right: 5px;"></input></td>
			</tr>

			<tr id="verticalSpace"></tr>
			<tr id="settingRow">
                                        <td id="settingName">ALWAYS REQUEST SECURITY PASSWORD</td>
                                        <td id="horizontalSpaceConfig"></td>
                                        <td class="settingValue" id="securityKick" onclick="booleanSwitch('securityKick');" style="background-color:<?php echo $kickColor;?>;">
					<div id="securityKickName" style="font-size:24px;color:#242424"><?php echo $securityKick;?></div>
					<input type="hidden" name="securityKick" value="<?php echo $securityKick;?>"></input></td>
					<input type="hidden" name="updated" value="TRUE">
                        </tr>
			<tr id="verticalSpace"></tr>
			<tr id="settingRow" class="beta">
                                        <td id="settingName">JAMMING</td>
                                        <td id="horizontalSpaceConfig"></td>
                                        <td class="settingValue" id="jam" onclick="booleanSwitch('jam');" style="background-color:<?php echo $jamColor;?>;">
					<div id="jamName" style="font-size:24px;color:#242424"><?php echo $jam;?></div>
					<input type="hidden" name="jam" value="<?php echo $jam;?>"></input></td>
					<input type="hidden" name="updated" value="TRUE">
                        </tr>
			<tr id="verticalSpace"></tr>
		</table>
		</form>

		<?php include("footer.php");?>

		</div>
		<div id="dummy" style="display:none"></div>
	</body>
</html>
