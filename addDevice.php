<?php
	include("settings.php");
	$PASSMD5 = readSetting("PASSMD5");
	$error = "";

	if($PASSMD5 == "4dfcb7e47d53ff431f231f8bfc51c32d"){
	        $error = "Password is 'electropi'. PLEASE change this default password immediately!";
	}

	$uiScale = readSetting("UI_SCALE");
	$maxWidth = readSetting("MAX_WIDTH");
	$correctPass = 1;
	$youreIn = 0;
	$ifaceSet = 0;
	$onColor = readSetting("ONCOLOR");
	$offColor = readSetting("OFFCOLOR");
	file_put_contents("misc/macDebug.txt","...");
	$submitted = $_POST["submitted"];

	if(isset($_POST["interface"])){
		writeSetting("NET_INTERFACE",$_POST["interface"]);
		$ifaceSet = 1;
		$ip = $_SERVER['REMOTE_ADDR'];
		file_put_contents("conf/clients/command.list","IDENTIFY|$ip");
		file_put_contents("misc/mac.txt","PLEASE WAIT <br>\n");
	}

	if(isset($submitted)){
		$nick = strtoupper($_POST["nick"]);
		$pass = $_POST["pass"];
		$ipValue = $_POST["ipValue"];
		$macValue = $_POST["macValue"];
		if(md5($pass) == $PASSMD5){
			$deviceList = file_get_contents("conf/device.list");
			$line = $nick . "|" . $macValue . "|" . $ipValue . "\n";
			$deviceList = $deviceList . $line;
			file_put_contents("conf/device.list",$deviceList);
			file_put_contents("conf/clients/".$ipValue.".txt","1");
			file_put_contents("conf/clients/".$ipValue.".might","0");
			$youreIn = 1;
		}
		else{
			$correctPass = 0;
		}
	}

	$subject = file_get_contents("misc/network.ifaces");
        $subject = explode("\n",$subject);
        $netDrop = "";

        foreach($subject as &$line){
                if(strlen($line) > 3){
                        $netDrop = $netDrop . "<option class='dropContainer' value='" . $line . "'>" . $line . "</option>";
                }
        }

?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                <meta name="viewport" content="width=device-width, initial-scale=<?php echo $uiScale;?>,maximum-scale=<?php echo $uiScale;?>, user-scalable=no"/>

		<title>Add Device</title>

		<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />
		<link href='http://fonts.googleapis.com/css?family=Oswald:400,700,300' rel='stylesheet' type='text/css'>

		<STYLE type="text/css">
			#choose{margin-top:20px;}
                        #linkButton{color:<?php echo $offColor;?>;}
                        a{text-decoration:none;color:<?php echo $onColor;?>;}
                        .slides{position:absolute;background-color:#242424;margin:20px;padding:20px;text-align:center;}
                        .slideTitle{font-size: 36px;color:<?php echo $offColor;?>;}
                        .slideTitleSkip{margin-top:20px;font-size: 24px;color:<?php echo $offColor;?>;}
                        .button{border: none;background-color: <?php echo $offColor;?>;padding: 10px;font-family: "Oswald";font-size: 18px;}
                        .desc{font-size: 18px;padding: 10px;}
                        .text{text-transform:uppercase;width: 150px;color: #cccccc;font-family: 'Oswald', sans-serif;font-size: 16px;border: none;background-color: #1d1d1d;padding: 10px;}
                        .out{display:inline-block;}
			#macDebugOutput{max-width: 880px;margin-left: auto;margin-right: auto;color: #777;font-family: courier;}
			#macDebugOutput2{max-width: 880px;margin-left: auto;margin-right: auto;color: #777;font-family: courier;}
                </STYLE>

		<script src="js/jquery.js"></script>
		<script type="text/javascript">
			function ajaxFunctionMac(){
			        var ajaxRequestM;  // The variable that makes Ajax possible
			        try{
			                // Opera 8.0+, Firefox, Safari
			                ajaxRequestM = new XMLHttpRequest();
			        } catch (e){
			                // Internet Explorer Browsers
			        try{
			                ajaxRequestM = new ActiveXObject('Msxml2.XMLHTTP');
			        } catch (e) {
			        try{
			                ajaxRequestM = new ActiveXObject('Microsoft.XMLHTTP');
			        } catch (e){
			                // Something went wrong
			                alert('Your browser broke!');
			                return false;
			        }
			        }
			        }
			        // Create a function that will receive data sent from the server
			        ajaxRequestM.onreadystatechange = function(){
			                if(ajaxRequestM.readyState == 4){
						response = ajaxRequestM.responseText;
			                        document.getElementById("macOutput").innerHTML = response;
						good = 'GOOD';
						bad = 'BAD';
						if(response.substring(0, 4) == good){
							window.location = "index.php";
						}
						if(response.substring(0, 3) == bad){
							document.getElementById("macOutput").innerHTML = "NOT A TRUSTED DEVICE";
							response = response.split("|");
							ip = response[1];
							mac = response[2].substr(0,17);
							if(ip == <?php echo json_encode($ip);?>){
								document.getElementById("ipValue").value = ip;
								document.getElementById("macValue").value = mac;
								document.getElementById("ipReadout").innerHTML = ip;
								document.getElementById("macReadout").innerHTML = mac;
								$("#waiting").hide();
								$("#addNew").show();
							}
						}
			                }
			        };
			        ajaxRequestM.open('POST', 'misc/mac.txt', true);
			        ajaxRequestM.send(null);
			}
			function ajaxFunctionMacDebug(){
			        var ajaxRequestMD;  // The variable that makes Ajax possible
			        try{
			                // Opera 8.0+, Firefox, Safari
			                ajaxRequestMD = new XMLHttpRequest();
			        } catch (e){
			                // Internet Explorer Browsers
			        try{
			                ajaxRequestMD = new ActiveXObject('Msxml2.XMLHTTP');
			        } catch (e) {
			        try{
			                ajaxRequestMD = new ActiveXObject('Microsoft.XMLHTTP');
			        } catch (e){
			                // Something went wrong
			                alert('Your browser broke!');
			                return false;
			        }
			        }
			        }
			        // Create a function that will receive data sent from the server
			        ajaxRequestMD.onreadystatechange = function(){
			                if(ajaxRequestMD.readyState == 4){
						response = ajaxRequestMD.responseText;
			                        document.getElementById("macDebugOutput").innerHTML = response;
			                        document.getElementById("macDebugOutput2").innerHTML = response;
			                }
			        };
			        ajaxRequestMD.open('POST', 'misc/macDebug.txt', true);
			        ajaxRequestMD.send(null);
			}
			function commencer(){
				if(<?php echo json_encode($youreIn);?> == 1){
					$("#choose").hide();
					$("#waiting").hide();
                                        $("#addNew").hide();
					$("#done").show();
					setTimeout(function(){
						window.location = "index.php";
					},5000);
				}
				else{
					if(<?php echo json_encode($correctPass);?> == 1){
						if(<?php echo json_encode($ifaceSet);?> == 0){
							$("#choose").show();
							$("#waiting").hide();
							$("#addNew").hide();
							$("#done").hide();
						}
						else{
							$("#choose").hide();
							$("#waiting").show();
							$("#addNew").hide();
							$("#done").hide();
							document.getElementById("ipValue").value = <?php echo json_encode($ip);?>;
							document.getElementById("ipReadout").innerHTML = <?php echo json_encode($ip);?>;
							setInterval(ajaxFunctionMac, 250);
							setInterval(ajaxFunctionMacDebug, 250);
						}
					}
					else{
						$("#choose").hide();
						$("#waiting").hide();
						$("#done").hide();
	                                        $("#addNew").show();
//						document.getElementById("ipValue").value = <?php echo json_encode($ipValue);?>;
//	                                        document.getElementById("macValue").value = <?php echo json_encode($macValue);?>;
//	                                        document.getElementById("ipReadout").innerHTML = <?php echo json_encode($ipValue);?>;
//	                                        document.getElementById("macReadout").innerHTML = <?php echo json_encode($macValue);?>;
					}
				}
			}
			function skipMac(){
				document.getElementById("macValue").value = "XX:XX:XX:XX:XX:XX";
				document.getElementById("macReadout").innerHTML = "XX:XX:XX:XX:XX:XX";
				$("#waiting").hide();
                                $("#done").hide();
                                $("#addNew").show();
			}
		</script>
	</head>
	<body onload="commencer();" id="body" style="font-size: 20px;text-align: center;height: 90%;">
		<input type="hidden" id="ipRemoteServer" value="<?php echo $ip;?>"></input>
		<div id="choose">
			Choose the interface you're using:<br><br>
			<form action="addDevice.php" method="POST">
					<select name="interface">
						<?php echo $netDrop;?>
					</select><br><br>
					<input class="button" type="submit" value="NEXT"></input>
			</form>
		</div>
		<div id="waiting">
      			<div class="slideTitleSkip" onclick="skipMac();">CLICK HERE TO SKIP MAC SETUP</div>
			<div id="macCheck" style="margin-top:20px;">
				Just a moment while we identify your device...
			</div>
			<div id="macOutput" style="margin-top:10px;color:#666666;">
				Loading...
			</div>
			<div id="macDebugOutput" style="margin-top:10px;color:#666666;">
				Loading...
			</div>
		</div>
		<div id="addNew">
			<div id="addForm" style="margin-top:20px;">
				<form action="addDevice.php" method="POST">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;text-align:center;margin-top: 20px;padding: 0px;" id="custom" class="slides">
                        			<tr><td>
                                			<div class="slideTitle">ADD DEVICE</div>
                                			<div class="desc" style="padding:20px;">This device is not currently in the Trusted Devices list, so let's add an entry...</div>
                                	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;text-align:center;">
                                        	<tr>
                                        	        <td width="50%" style="text-align:right;font-size: 18px;padding-right: 20px;">IP ADDRESS: </td>
                                        	        <td width="50%" style="text-align:left;"><div id="ipReadout"></div></td>
                                        	</tr>
						<tr id="verticalSpace"></tr>
                                        	<tr>
                                        	        <td width="50%" style="text-align:right;font-size: 18px;padding-right: 20px;">MAC ADDRESS: </td>
                                        	        <td width="50%" style="text-align:left;"><div id="macReadout"></div></td>
                                        	</tr>
						<tr id="verticalSpace"></tr>
                                        	<tr>
                                        	        <td width="50%" style="text-align:right;font-size: 18px;padding-right: 20px;">DEVICE NICKNAME: </td>
                                        	        <td width="50%" style="text-align:left;"><input type="text" class="text" style="margin-bottom: 5px;" name="nick" placeholder="NICKNAME" required></input></td>
                                        	</tr>
                                        	<tr>
                                        	        <td width="50%" style="text-align:right;font-size: 18px;padding-right: 20px;">ADMIN PASSWORD: </td>
                                        	        <td width="50%" style="text-align:left;"><input type="password" class="text" style="margin-bottom: 5px;" name="pass" placeholder="PASSWORD" required></input></td>
                                        	</tr>
						<tr>
							<td></td>
							<td style="color:<?php echo $offColor;?>;"><?php echo $error?></td>
						</tr>
                                	</table><br>
					<input type="hidden" id="ipValue" name="ipValue" value=""></input>
					<input type="hidden" id="macValue" name="macValue" value=""></input>
					<input type="hidden" name="submitted" value="true"></input>
					<input type="submit" class="button" value="ADD DEVICE"></input>
				</form>
			</div>
			<br><br>
			DEBUG OUTPUT:
			<br><br>
			<div id="macDebugOutput2">LOADING</div>
		</div></table></form></div></div>
		<div id="done">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;text-align:center;margin-top: 20px;padding: 0px;" id="custom" class="slides">
                		<tr><td>
                		<div class="slideTitle">YOU'RE IN!</div>
                        	<div class="desc" style="padding:20px;">Your device is now authorized by ElectroPi, and it's presence on the network is known! Redirecting you to control...</div>
				</td></tr>
			</table>
		</div>
	</body>
</html>
