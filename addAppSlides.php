<?php
	include("password_protect.php");

	function generateUID($length = 5){
		$chars = '0123456789ABCDEFGHIJIKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for($i = 0; $i < $length; $i++){
			$randomString .= $chars[rand(0,strlen($chars) - 1)];
		}
		return $randomString;
	}

	$hideHeader = True;

        $onColor = readSetting("ONCOLOR");
        $offColor = readSetting("OFFCOLOR");
        $uiScale = readSetting("UI_SCALE");
        $animations = readSetting("ANIMATIONS");
        $maxWidth = readSetting("MAX_WIDTH");
	$updated = "false";
	if(isset($_GET["updated"])){
		$updated = $_GET["updated"];
	}

	if($updated == "true"){
		if(isset($_GET["nickname"])){
			$nickname = strtoupper($_GET["nickname"]);
			$onCode = $_GET["onCode"];
			$offCode = $_GET["offCode"];
			$repeat = $_GET["repeat"];
			$uid = "'" . generateUID() . "'";
			$freq = $_GET["freq"];
		}
	}
	if($updated == "true2"){
		if(isset($_GET["nickname"])){
			$nickname = strtoupper($_GET["nickname"]);
			$onCode = $_GET["onCode"];
			$offCode = $_GET["offCode"];
			$repeat = $_GET["repeat"];
			$uidN = generateUID();
			$uid = "'" . $uidN . "'";
			$freq = $_GET["freq"];

			$appsString = file_get_contents("conf/appliances.txt");
			$appsString = $appsString . $nickname . "|D|" . $onCode . "|" . $offCode . "|D|D|D|" . $repeat . "|" . $uid . "|" . $freq . "\n";
			file_put_contents("conf/appliances.txt",$appsString);

			$stateString = file_get_contents("conf/applianceStates.txt");
			$stateString = $stateString . $nickname . "|0|" . $uid . "\n";
			file_put_contents("conf/applianceStates.txt",$stateString);

			$appOrder = file_get_contents("conf/app.order");
			$appOrder = $appOrder . $uidN . "\n";
			file_put_contents("conf/app.order",$appOrder);
		}
	}

?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                <meta name="viewport" content="width=device-width, initial-scale=<?php echo $uiScale; ?>,maximum-scale=<?php echo $uiScale; ?>, user-scalable=no"/>
		<?php include("header.php");?>

                <STYLE type="text/css">
                        #linkButton{color:<?php echo $offColor;?>;}
                        a{text-decoration:none;color:<?php echo $onColor;?>;}
			.slides{position:absolute;background-color:#242424;margin:20px;padding:20px;text-align:center;}
			.slideTitle{font-size: 36px;color:<?php echo $offColor;?>;}
			.button{border: none;background-color: <?php echo $offColor;?>;padding: 10px;font-family: "Oswald";font-size: 18px;}
			.desc{font-size: 18px;padding: 10px;}
			.text{text-transform:uppercase;width: 150px;color: #cccccc;font-family: 'Oswald', sans-serif;font-size: 16px;border: none;background-color: #1d1d1d;padding: 10px;-webkit-box-shadow: inset 0px 0px 10px 0px rgba(0, 0, 0, 0.53);-moz-box-shadow: inset 0px 0px 10px 0px rgba(0, 0, 0, 0.53);box-shadow: inset 0px 0px 10px 0px rgba(0, 0, 0, 0.53);}
			.out{display:inline-block;}
                </STYLE>

	</head>
	<script type="text/javascript">

		var retry = 5;

		$(function(){  // $(document).ready shorthand
			$('#begin').hide();
			$('#nickname').hide();
			$('#type').hide();
			$('#preset').hide();
			$('#custom').hide();
			$('#share').hide();
			$('#share-form').hide();
			$('#confirm').hide();
			$('#done').hide();
			$('#test').hide();

			$('#nicknameOut').hide();
			$('#onCodeOut').hide();
			$('#offCodeOut').hide();
			$('#repeatOut').hide();
			$('#freqOut').hide();

			if(<?php echo json_encode($updated);?> == "false"){
				$('#begin').show();
				$('#done').hide();
			}
			else if(<?php echo json_encode($updated);?> == "true"){
				$('#test').show();
				$('#dummy').load("http://connor-n.com/electropi/count/appAdd.php");
				setInterval(switchTest, 2000);
			}
			else if(<?php echo json_encode($updated);?> == "true2"){
				$('#done').show();
			}
                });

		function slide(slideFrom,slideTo){
			$(slideFrom).hide('slide',{direction:'left'});
			$(slideTo).show('slide',{direction:'right'});
			window.parent.scrollTo(0,0);
			window.parent.iframeLoaded();
		}

		function increaseRetry(){
			retry = retry + 5;
			document.getElementById("retry").innerHTML = retry;
			document.getElementById("repeatT").value = retry;
		}

		function switchTest(s){
			if(s == "1"){
				haptic();
				$('#dummy').load("system.php?uid=" + <?php echo json_encode($uid);?> + "&on=" + <?php echo json_encode($onCode);?> + "&off=" + <?php echo json_encode($offCode);?> + "&repeat=" + retry + "&state=1&type=TEST");
			}
			if(s == "0"){
				haptic();
				$('#dummy').load("system.php?uid=" + <?php echo json_encode($uid);?> + "&on=" + <?php echo json_encode($onCode);?> + "&off=" + <?php echo json_encode($offCode);?> + "&repeat=" + retry + "&state=0&type=TEST");
			}
		}

		function iframeLoaded() {
                        var iFrameID = document.getElementById('preFrame');
                        if(iFrameID) {
                                // here you can make the height, I delete it first, then I make it again
                                iFrameID.height = "";
                                iFrameID.height = iFrameID.contentWindow.document.body.scrollHeight + "px";
                        }
                }

		function shareData(){
			var onCodeD = document.getElementById("onCodeValue").value;
			var offCodeD = document.getElementById("offCodeValue").value;
			var modelNumber = document.getElementById("modelnumber").value;
			var brand = document.getElementById("brand").value;
			var fccid = document.getElementById("fccid").value;
			var channel = document.getElementById("channel").value;
			var subchannel = document.getElementById("subchannel").value;
			var email = document.getElementById("email").value;

			var url = "http://connor-n.com/electropi/dataSubmit.php?oncode=" + onCodeD + "&offcode=" + offCodeD + "&model=" + modelNumber + "&brand=" + brand + "&fccid=" + fccid + "&channel=" + channel + "&subchannel=" + subchannel + "&email=" + email;
			alert("Thank you for submitting to the ElectroPi preset database! Your submission is under review. If an email address was provided, you will be notified when your preset is accepted!");

			$('#dummy').load(url);
			slide('#share-form','#confirm');
		}

	</script>
	<body id="body" onload="startWatch();" style="margin:0px;padding:0px;">
		<div id="master">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;text-align:center;margin-top: 0px;padding: 0px;" id="begin" class="slides">
			<tr><td>
				<div class="slideTitle">ADD NEW SWITCH</div>
				<div class="desc">This wizard will guide you through the addition of a new appliance/switch pair...</div><br>
				<input type="button" value="BEGIN" class="button" onclick="slide('#begin','#nickname')"></input>
			</td></tr>
			</table>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;text-align:center;margin-top: 0px;padding: 0px;" id="nickname" class="slides">
			<tr><td>
				<div class="slideTitle">NICKNAME</div>
				<div class="desc">PLEASE ENTER A NICKNAME FOR THIS SWITCH...</div><br>
				<input type="text" class="text" id="nicknameValue"></input><br><br>
				<input type="button" value="NEXT" class="button" onclick="slide('#nickname','#type')"></input>
			</td></tr>
			</table>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;text-align:center;margin-top: 0px;padding: 0px;" id="type" class="slides">
			<tr><td>
				<div class="slideTitle">SWITCH TYPE</div>
				<div class="desc">Would you like to add a preset, or a custom control code?</div><br>
				<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;text-align:center;">
	                        	<tr>
	                        	        <td width="50%" style="text-align:right; padding-right:10px;"><input type="button" value="PRESET" class="button" onclick="slide('#type','#preset')"></input></td>
						<td style="text-align:left; padding-left:10px;"><input type="button" value="CUSTOM" class="button" onclick="slide('#type','#custom')"></input></td>
	                        	</tr>
	                	</table>
			</td></tr>
			</table>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" height="450px" style="margin-left:auto;margin-right:auto;text-align:center;margin-top: 0px;padding: 0px;" id="preset" class="slides">
			<tr><td>
				<div class="slideTitle">SELECT A PRESET:</div>
				<div class="desc"></div><br>
				<table width="100%" border="0" cellspacing="0" cellpadding="0" height="450px" style="margin-left:auto;margin-right:auto;text-align:center;">
	                        	<tr>
	                        	        <td width="100%" valign="top" style=""><iframe id="preFrame" onload="iframeLoaded();" src="presetList.php" width="100%" height="600px" scrolling="yes" style="border:none;-webkit-box-shadow: 0px 0px 30px 0px rgba(0, 0, 0, 0.53);-moz-box-shadow: 0px 0px 30px 0px rgba(0, 0, 0, 0.53);box-shadow:0px 0px 30px 0px rgba(0, 0, 0, 0.53);"></iframe></td>
					</tr>
	                	</table>
			</td></tr>
			</table>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;text-align:center;margin-top: 0px;padding: 0px;" id="custom" class="slides">
			<tr><td>
				<div class="slideTitle">CUSTOM CODE</div>
				<div class="desc">Please include the following details...</div><br>
				<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;text-align:center;">
					<tr>
						<td width="50%" style="text-align:right;font-size: 18px;padding-right: 20px;background-color:<?php echo $onColor;?>;color:#242424;">"ON" CODE (BIN): </td>
						<td width="50%" style="text-align:left;"><input type="text" class="text" id="onCodeValue" placeholder="BINARY CODE" required></input></td>
					</tr>
					<tr>
						<td width="50%" style="text-align:right;font-size: 18px;padding-right: 20px;background-color:<?php echo $offColor;?>;color:#242424;">"OFF" CODE (BIN): </td>
						<td width="50%" style="text-align:left;"><input type="text" class="text" id="offCodeValue" placeholder="BINARY CODE" required></input></td>
					</tr>
					<tr>
						<td width="50%" style="text-align:right;font-size: 18px;padding-right: 20px;">REPEAT: </td>
						<td width="50%" style="text-align:left;"><input type="text" class="text" id="repeatValue" placeholder="INT" required></input></td>
					</tr>
					<tr>
						<td width="50%" style="text-align:right;font-size: 18px;padding-right: 20px;">FREQUENCY (MHz): </td>
						<td width="50%" style="text-align:left;"><input type="text" class="text" id="freqValue" placeholder="INT" required></input></td>
					</tr>
				</table><br>
                                <input type="button" value="NEXT" class="button" onclick="slide('#custom','#share')"></input>
			</td></tr>
		</table>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;text-align:center;margin-top: 0px;padding: 0px;" id="share" class="slides">
                        <tr><td>
                                <div class="slideTitle">SHARE DATA?</div>
                                <div class="desc">Would you like to submit this control code to be added to the preset database?<br>This would allow others to quickly use the same models of switches!</div><br>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;text-align:center;">
                                        <tr>
                                                <td width="50%" style="text-align:right; padding-right:10px;"><input type="button" value="YES" class="button" onclick="slide('#share','#share-form')"></input></td>
                                                <td style="text-align:left; padding-left:10px;"><input type="button" value="NO" class="button" onclick="slide('#share','#confirm')"></input></td>
                                        </tr>
                                </table>
                        </td></tr>
                        </table>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;text-align:center;margin-top: 0px;padding: 0px;" id="share-form" class="slides">
                        <tr><td>
                                <div class="slideTitle">THANKS!</div>
                                <div class="desc">Please fill out a quick form detailing your switch/code...</div><br>
				<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;text-align:center;">
					<tr>
						<td width="50%" style="text-align:right;font-size: 18px;padding-right: 20px;"><font style="color:<?php echo $offColor;?>;">*</font> MODEL NUMBER OF SWITCH: </td>
						<td width="50%" style="text-align:left;"><input type="text" id="modelnumber" class="text" placeholder="13569" required></input></td>
					</tr>
					<tr>
						<td width="50%" style="text-align:right;font-size: 18px;padding-right: 20px;"><font style="color:<?php echo $offColor;?>;">*</font> BRAND: </td>
						<td width="50%" style="text-align:left;"><input type="text" id="brand" class="text" placeholder="Woods" required></input></td>
					</tr>
					<tr>
						<td width="50%" style="text-align:right;font-size: 18px;padding-right: 20px;"><font style="color:<?php echo $offColor;?>;">*</font> FCC ID: </td>
						<td width="50%" style="text-align:left;"><input type="text" id="fccid" class="text" placeholder="PAGTR-016" required></input></td>
					</tr>
					<tr>
						<td width="50%" style="text-align:right;font-size: 18px;padding-right: 20px;"><font style="color:<?php echo $offColor;?>;">*</font> FREQUENCY: (MHz)</td>
						<td width="50%" style="text-align:left;"><input type="text" id="freqS" class="text" placeholder="433" required></input></td>
					</tr>
					<tr>
						<td width="50%" style="text-align:right;font-size: 18px;padding-right: 20px;"><font style="color:<?php echo $offColor;?>;">*</font> CHANNEL: </td>
						<td width="50%" style="text-align:left;"><input type="text" id="channel" class="text" placeholder="D" required></input></td>
					</tr>
					<tr>
						<td width="50%" style="text-align:right;font-size: 18px;padding-right: 20px;">SUBCHANNEL: </td>
						<td width="50%" style="text-align:left;"><input type="text" id="subchannel" class="text" placeholder="1"></input></td>
					</tr>
					<tr>
						<td width="50%" style="text-align:right;font-size: 18px;padding-right: 20px;">EMAIL: </td>
						<td width="50%" style="text-align:left;"><input type="text" id="email" class="text" placeholder="john@doe.com"></input></td>
					</tr>
				</table><br>
				<font style="color:<?php echo $offColor;?>;">* REQUIRED</font><br><br>
                                <input type="button" value="NEXT" class="button" onclick="shareData();"></input>
                        </td></tr>
                        </table>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;text-align:center;margin-top: 0px;padding: 0px;" id="confirm" class="slides">
                        <tr><td>
                                <div class="slideTitle">DOES THIS LOOK RIGHT?</div>
                                <div class="desc">If everything is correct, we'll begin a quick test run. Plug in the control unit, but NOT the appliance you'll be controlling.</div><br></div>
				<div style="color:#777;display:none;">
					<div id="nicknameOut">NICKNAME: <div class="out" id="nicknameOutV"></div><br></div>
					<div id="onCodeOut">ON CODE: <div class="out" id="onCodeOutV"></div><br></div>
					<div id="offCodeOut">OFF CODE: <div class="out" id="offCodeOutV"></div><br></div>
					<div id="repeatOut">REPEAT: <div class="out" id="repeatOutV"></div><br></div>
					<div id="freqOut">FREQ: <div class="out" id="freqOutV"></div><br></div><br>
                                </div>
				<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;text-align:center;">
                                        <tr>
						<form id="hiddenForm" action="addAppSlides.php" method="GET">
							<input type="hidden" name="nickname" id="nicknameH" value="X">
							<input type="hidden" name="onCode" id="onCodeH" value="X">
							<input type="hidden" name="offCode" id="offCodeH" value="X">
							<input type="hidden" name="repeat" id="repeatH" value="X">
							<input type="hidden" name="freq" id="freqH" value="X">

							<input type="hidden" name="updated" id="repeatH" value="true">

	                                                <td width="50%" style="text-align:right; padding-right:10px;"><input type="submit" value="TEST" class="button" style="background-color:<?php echo $onColor;?>;"></input></td>
	                                                <td style="text-align:left; padding-left:10px;"><input type="button" value="START OVER" class="button" onclick="slide('#confirm','#nickname')"></input></td>
						</form>
                                        </tr>
                                </table>
                        </table>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;text-align:center;margin-top: 0px;padding: 0px;" id="test" class="slides">
                        <tr><td>
                                <div class="slideTitle">ALL SYSTEMS GO?</div>
                                <div class="desc">ElectroPi can send on/off codes for your selected module right now.<br>If it's consistently powering on and off, we're done! If not, we'll need to increase our TX retries.</div><br></div>
				<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;text-align:center;">
                                        <tr>
                                                <td width="50%" style="text-align:right; padding-right:10px;"><button onclick="switchTest('1');" class="button" style="cursor:pointer;background-color:<?php echo $onColor;?>;">ON</button></td>
                                                <td width="50%" style="text-align:left;padding-left:10px;"><button onclick="switchTest('0');" class="button" style="cursor:pointer;background-color:<?php echo $offColor;?>;">OFF</button></td>
					</tr>
					<tr>
				</table>
				<br>
				<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;text-align:center;">
					<tr>
						<td style="text-align:center;">
						Code is sent <div id="retry" style="display:inline;color:#666666;font-size:24px;">5</div> times on switch...<br>
						<br>

						 <form id="hiddenForm" action="addAppSlides.php" method="GET">
                                                        <input type="hidden" name="nickname" id="nicknameT" value="<?php echo $nickname;?>">
                                                        <input type="hidden" name="onCode" id="onCodeT" value="<?php echo $onCode;?>">
                                                        <input type="hidden" name="offCode" id="offCodeT" value="<?php echo $offCode;?>">
                                                        <input type="hidden" name="repeat" id="repeatT" value="5">
                                                        <input type="hidden" name="freq" id="freqT" value="<?php echo $freq;?>">

                                                        <input type="hidden" name="updated" value="true2">
						</td>
					</tr>
				</table>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;text-align:center;">
					<tr>
                                                <td width="50%" style="text-align:right; padding-right:10px;"><input type="submit" value="IT WORKS!" class="button" style="background-color:<?php echo $onColor;?>;"></input></td>
                                                <td style="text-align:left; padding-left:10px;"><input type="button" value="TRY HARDER" class="button" onclick="increaseRetry();"></input></td>
                                                </form>
                                        </tr>
                                </table>
                        </table>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;text-align:center;margin-top: 0px;padding: 0px;" id="done" class="slides">
                        <tr><td>
                                <div class="slideTitle">ALL DONE!</div>
                                <div class="desc">Your switch is successfully programmed, and your appliance is ready for control! Feel free to plug it in now.</div><br></div>
				<a href="index.php" target="_top"><input type="button" value="BACK TO CONTROL" class="button" style="background-color:<?php echo $onColor;?>;"></a>
                        </table>
		<div id="sstatus" style="display:none;"></div>
		<div id="wstatus" style="display:none;"></div>
                </div>
		<div id="dummy"></div>
	</body>
	<script type="text/javascript">
		var preset = "false";
		var presetDone = "false";
		function watchForm(){
			$('.text').val().toUpperCase();

			if(preset == "true"){
				nickname = document.getElementById("nicknameValue").value;
			}
			else if(preset == "false"){
				nickname = document.getElementById("nicknameValue").value;
				onCode = document.getElementById("onCodeValue").value;
				offCode = document.getElementById("offCodeValue").value;
				repeat = document.getElementById("repeatValue").value;
				freq = document.getElementById("freqValue").value;
			}

			if(presetDone == "true"){
				slide('#preset','#confirm');
			}

			if(nickname != ""){
				$('#nicknameOut').show();
				document.getElementById("nicknameOutV").innerHTML = nickname;
				document.getElementById("nicknameH").value = nickname;
			}
			if(onCode != ""){
				$('#onCodeOut').show();
				document.getElementById("onCodeOutV").innerHTML = onCode;
				document.getElementById("onCodeH").value = onCode;
			}
			if(offCode != ""){
				$('#offCodeOut').show();
				document.getElementById("offCodeOutV").innerHTML = offCode;
				document.getElementById("offCodeH").value = offCode;
			}
			if(freq != ""){
				$('#freqOut').show();
				document.getElementById("freqOutV").innerHTML = freq;
				document.getElementById("freqH").value = freq;
			}
		}

		function startWatch(){
			setInterval(watchForm, 1000);
		}
	</script>
</html>

