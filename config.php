<?php
	include("includes/configFile.php");
	include("includes/functions.php");
        include("includes/strings.php");

	//-----------------------------------------------------

	if(isset($_POST["onColor"])){
		$sets['SETTINGS']["onColor"] = $_POST["onColor"];
	}
	if(isset($_POST["offColor"])){
		$sets['SETTINGS']["offColor"] = $_POST["offColor"];
	}
	if(isset($_POST["pendingColor"])){
		$sets['SETTINGS']["pendingColor"] = $_POST["pendingColor"];
	}
	if(isset($_POST["extraColor1"])){
		$sets['SETTINGS']["extraColor1"] = $_POST["extraColor1"];
	}
	if(isset($_POST["extraColor2"])){
		$sets['SETTINGS']["extraColor2"] = $_POST["extraColor2"];
	}
	if(isset($_POST["uiScale"])){
		$sets['SETTINGS']["uiScale"] = $_POST["uiScale"];
	}
	if(isset($_POST["maxWidth"])){
		$sets['SETTINGS']["maxWidth"] = $_POST["maxWidth"];
	}
	if(isset($_POST["notifyHangtime"])){
		$sets['SETTINGS']["notifyHangtime"] = floatval($_POST["notifyHangtime"]) * 10;
	}
	if(isset($_POST["rgbLed"])){
		$sets['SETTINGS']["rgbLed"] = $_POST["rgbLed"];
	}
	if(isset($_POST["masterFreq"])){
		$sets['SETTINGS']["masterFreq"] = $_POST["masterFreq"];
	}
	if(isset($_POST["boardType"])){
		$sets['SETTINGS']["boardType"] = $_POST["boardType"];
	}
	if(isset($_POST["deviceInterval"])){
		$sets['SETTINGS']["deviceInterval"] = $_POST["deviceInterval"];
	}
	if(isset($_POST["deviceTimeout"])){
		$sets['SETTINGS']["deviceTimeout"] = $_POST["deviceTimeout"];
	}

	//-----------------------------------------------------

	if(isset($_POST["updated"])){
		write_ini_file($sets,"config/settings.ini",true);
	}

	if(isset($_GET["view"])){
		$currentView = "#".$_GET["view"];
	}
	else{
		$currentView = "#confMenu";
	}

	$title="Configuration";
	$logoColor = "off";
	$noWatchCheck = "1";
	$gear = "link";
	$gearLink = "<td align='right'><a href='index.php'><img src='images/home.png' id='homeIcon'></a></td>";

?>
<html>
	<!-- Include Header -->
	<?php include("header.php"); ?>
	<script src="http://crypto-js.googlecode.com/svn/tags/3.0.2/build/rollups/md5.js"></script>
	<script type="text/javascript" src="js/jscolor.js"></script>

	<body id="bodyMain">
	<!------------------------------------------------------->
		<div id="wrapper"> <!-- We remove the header margin to make the control list fit flush. -->
			<div id="confConfirmWrap">
				<table <?php echo $tabStretch;?>>
					<tr>
						<td id="confConfirm">Settings updated. <a href="index.php" style="color:#ffffff;">Return to control?</a></td>
					</tr>
				</table>
			</div>
				<div id="confMenu" style="display:none;">
					<table <?php echo $tabStretch;?>>
						<tr>
							<td>
								<div class="section group">
									<div class="col span1conf" id="generalButton" onclick="switchView('#confGeneral');">
										<table style="width:100%;height:100%;">
											<tr>
												<td class="confIcon"><img src="images/general.png" style="width:45px;height:45px;margin-bottom: 5px;"><br>GENERAL</td>
											</tr>
										</table>
									</div>
									<div class="col span1conf" id="hardwareButton" onclick="switchView('#confHardware');">
										<table style="width:100%;height:100%;">
											<tr>
												<td class="confIcon"><img src="images/hardware.png" style="width:45px;height:45px;margin-bottom: 5px;"><br>HARDWARE</td>
											</tr>
										</table>
									</div>
								</div>
								<div class="section group">
									<div class="col span1conf" id="securityButton" onclick="switchView('#confSecurity');">
										<table style="width:100%;height:100%;">
											<tr>
												<td class="confIcon"><img src="images/security.png" style="width:45px;height:45px;margin-bottom: 5px;"><br>SECURITY</td>
											</tr>
										</table>
									</div>
									<div class="col span1conf" id="creditsButton" onclick="switchView('#confCredits');">
										<table style="width:100%;height:100%;">
											<tr>
												<td class="confIcon"><img src="images/credits.png" style="width:45px;height:45px;margin-bottom: 5px;"><br>CREDITS</td>
											</tr>
										</table>
									</div>
								</div>
							</td>
						</tr>
					</table>
				</div>
				<div id="confGeneral" style="display:none;">
					<table <?php echo $tabStretch;?>>
						<tr>
							<td id="confSubtitle" onclick="switchView('#confMenu');"><img src="images/general.png" style="width: 26px;height: 26px;margin-right: 5px;">GENERAL SETTINGS</td>
						</tr>
						<tr>
							<td id="confBack" onclick="switchView('#confMenu');"> < BACK TO MENU</td>
						</tr>
					</table>
					<table <?php echo $tabStretch;?>>
						<tr class="settingRow">
							<td class="settingLeft">
								<div class="section group">
                                                                        <div class="col span1conf2">
										<div class="settingInlineNameWrap">
											<input class="color {hash:true}" name="onColor" value="<?php echo $sets['SETTINGS']['onColor'];?>"></input><div class="settingInlineName">ON COLOR</div>
										</div>
                                                                        </div>
                                                                        <div class="col span1conf2">
										<div class="settingInlineNameWrap">
											<input class="color {hash:true}" name="offColor" value="<?php echo $sets['SETTINGS']['offColor'];?>"></input><div class="settingInlineName">OFF COLOR</div>
										</div>
                                                                        </div>
                                                                </div>
								<div class="section group">
                                                                        <div class="col span1conf2">
										<div class="settingInlineNameWrap">
											<input class="color {hash:true}" name="pendingColor" value="<?php echo $sets['SETTINGS']['pendingColor'];?>"></input><div class="settingInlineName">PENDING COLOR</div>
										</div>
                                                                        </div>
                                                                        <div class="col span1conf2">
										<div class="settingInlineNameWrap">
											<input class="color {hash:true}" name="extraColor1" value="<?php echo $sets['SETTINGS']['extraColor1'];?>"></input><div class="settingInlineName">EXTRA COLOR 1</div>
										</div>
                                                                        </div>
                                                                </div>
								<div class="section group">
                                                                        <div class="col span1conf2">
										<div class="settingInlineNameWrap">
											<input class="color {hash:true}" name="extraColor2" value="<?php echo $sets['SETTINGS']['extraColor2'];?>"></input><div class="settingInlineName">EXTRA COLOR 2</div>
										</div>
                                                                        </div>
                                                                        <div class="col span1conf2">
										<div class="settingInlineNameWrap">
											<input type="text" class="settingText" name="uiScale" value="<?php echo $sets['SETTINGS']['uiScale'];?>"></input><div class="settingInlineName">UI SCALE</div>
										</div>
                                                                        </div>
                                                                </div>
								<div class="section group">
                                                                        <div class="col span1conf2">
										<div class="settingInlineNameWrap">
											<input type="text" class="settingText" name="maxWidth" value="<?php echo $sets['SETTINGS']['maxWidth'];?>"></input><div class="settingInlineName">MAX WIDTH</div>
										</div>
                                                                        </div>
                                                                        <div class="col span1conf2">
										<div class="settingInlineNameWrap">
											<input type="text" class="settingText" name="notifyHangtime" value="<?php echo floatval($sets['SETTINGS']['notifyHangtime']) / 10;?>"></input><div class="settingInlineName">NOTIFY HANGTIME</div>
										</div>
                                                                        </div>
                                                                </div>
								<div class="section group">
                                                                        <div class="col span1conf2">
										<div class="settingInlineNameWrap">
										</div>
                                                                        </div>
                                                                        <div class="col span1conf2">
										<div class="settingInlineNameWrap">
											<button class="button" style="background-color:<?php echo $sets['SETTINGS']['offColor'];?>;width:100%;" onclick="sendData();">SUBMIT</button><br>
										</div>
                                                                        </div>
                                                                </div>
							</td>
						</tr>
					</table>
					<input type="hidden" name="updated" value="true"></input>
				</div>
				<div id="confHardware" style="display:none;">
					<table <?php echo $tabStretch;?>>
						<tr>
							<td id="confSubtitle" onclick="switchView('#confMenu');"><img src="images/hardware.png" style="width: 26px;height: 26px;margin-right: 5px;">HARDWARE SETTINGS</td>
						</tr>
						<tr>
							<td id="confBack" onclick="switchView('#confMenu');"> < BACK TO MENU</td>
						</tr>
					</table>
					<table <?php echo $tabStretch;?>>
						<tr class="settingRow">
							<td class="settingLeft">
								<div class="section group">
                                                                        <div class="col span1conf2">
										<div class="settingInlineNameWrap">
											<input type="text" class="settingText" name="rgbLed" value="<?php echo $sets['SETTINGS']['rgbLed'];?>"></input><div class="settingInlineName">RGB LED</div>
										</div>
                                                                        </div>
                                                                        <div class="col span1conf2">
										<div class="settingInlineNameWrap">
											<input type="text" class="settingText" name="masterFreq" value="<?php echo $sets['SETTINGS']['masterFreq'];?>"></input><div class="settingInlineName">MASTER FREQUENCY</div>
										</div>
                                                                        </div>
                                                                </div>
								<div class="section group">
                                                                        <div class="col span1conf2">
										<div class="settingInlineNameWrap">
											<input type="text" class="settingText" name="boardType" value="<?php echo $sets['SETTINGS']['boardType'];?>"></input><div class="settingInlineName">BOARD TYPE</div>
										</div>
                                                                        </div>
                                                                        <div class="col span1conf2">
										<div class="settingInlineNameWrap">
											<button class="button" style="background-color:<?php echo $sets['SETTINGS']['offColor'];?>;width:100%;" onclick="sendData();">SUBMIT</button><br>
										</div>
                                                                        </div>
                                                                </div>
							</td>
						</tr>
					</table>
					<input type="hidden" name="updated" value="true"></input>
				</div>
				<div id="confSecurity" style="display:none;">
					<table <?php echo $tabStretch;?>>
						<tr>
							<td id="confSubtitle"><img src="images/security.png" style="width: 26px;height: 26px;margin-right: 5px;">ELECTROPI SECURITY</td>
						</tr>
						<tr>
							<td id="confBack" onclick="switchView('#confMenu');"> < BACK TO MENU</td>
						</tr>
					</table>
					<table <?php echo $tabStretch;?>>
						<tr>
							<td class="linkRow" onclick="switchView('#confPassword');">
								CHANGE PASSWORD
							</td>
						</tr>
					</table>
					<table <?php echo $tabStretch;?>>
						<tr class="settingRow">
							<td class="settingLeft">
								<div class="section group" style="padding-top: 10px;">
                                                                        <div class="col span1conf2">
										<div class="settingInlineNameWrap">
											<input type="text" class="settingText" name="deviceInterval" value="<?php echo $sets['SETTINGS']['deviceInterval'];?>"></input><div class="settingInlineName">CHECK-IN INTERVAL (SEC)</div>
										</div>
                                                                        </div>
                                                                        <div class="col span1conf2">
										<div class="settingInlineNameWrap">
											<input type="text" class="settingText" name="deviceTimeout" value="<?php echo $sets['SETTINGS']['deviceTimeout'];?>"></input><div class="settingInlineName">CHECK-IN TIMEOUT (SEC)</div>
										</div>
                                                                        </div>
                                                                </div>
								<div class="section group">
                                                                        <div class="col span1conf2">
										<div class="settingInlineNameWrap">
										</div>
                                                                        </div>
                                                                        <div class="col span1conf2">
										<div class="settingInlineNameWrap">
											<button class="button" style="background-color:<?php echo $sets['SETTINGS']['offColor'];?>;width:100%;" onclick="sendData();">SUBMIT</button><br>
										</div>
                                                                        </div>
                                                                </div>
							</td>
						</tr>
					</table>

					<input type="hidden" name="updated" value="true"></input>
				</div>
				<div id="confPassword" style="display:none;">
					<table <?php echo $tabStretch;?>>
						<tr>
							<td id="confSubtitle">CHANGE PASSWORD</td>
						</tr>
						<tr>
							<td id="confBack" onclick="switchView('#confSecurity');"> < BACK TO SECURITY</td>
						</tr>
					</table>
					<table <?php echo $tabStretch;?>>
						<tr class="settingRow">
							<td class="settingLeft">
								<input type="password" class="settingText" name="oldPass" value=""></input>
							</td>
							<td class="settingRight">
								OLD PASSWORD
							</td>
						</tr>
						<tr class="settingRow">
							<td class="settingLeft">
								<input type="password" class="settingText" name="newPass" value=""></input>
							</td>
							<td class="settingRight">
								NEW PASSWORD
							</td>
						</tr>
						<tr class="settingRow">
							<td class="settingLeft">
								<input type="password" class="settingText" name="newPassConf" value=""></input>
							</td>
							<td class="settingRight">
								CONFIRM PASSWORD
							</td>
						</tr>
						<tr class="settingRow">
							<td class="settingLeft">
								<button class="button" style="background-color:<?php echo $sets['SETTINGS']['offColor'];?>;width:100%;" onclick="sendPass();">SUBMIT</button><br>
							</td>
						</tr>
					</table>
					<input type="hidden" name="updated" value="true"></input>
				</div>
				<div id="confCredits" style="display:none;">
					<table <?php echo $tabStretch;?>>
						<tr>
							<td id="confSubtitle"><img src="images/credits.png" style="width: 26px;height: 26px;margin-right: 5px;">ELECTROPI CREDITS</td>
						</tr>
						<tr>
							<td id="confBack" onclick="switchView('#confMenu');"> < BACK TO MENU</td>
						</tr>
					</table>
					<table <?php echo $tabStretch;?>>
						<tr>
							<td>
								<div class="section group" style="margin-bottom: 10px;">
                                                                        <div class="colAlt span1cred">
                                                                                <table style="width:100%;height:100%;">
                                                                                        <tr>
                                                                                                <td class="creditLeft">HARDWARE/SOFTWARE LEAD</td>
                                                                                        </tr>
                                                                                </table>
                                                                        </div>
                                                                        <div class="colAlt span1cred">
                                                                                <table style="width:100%;height:100%;">
                                                                                        <tr>
                                                                                                <td class="creditRight">CONNOR NISHIJIMA</td>
                                                                                        </tr>
                                                                                </table>
                                                                        </div>
                                                                </div>
								<br>
								<div class="section group" style="margin-bottom: 10px;">
                                                                        <div class="colAlt span1cred">
                                                                                <table style="width:100%;height:100%;">
                                                                                        <tr>
                                                                                                <td class="creditLeft">ANDROID DEVELOPMENT</td>
                                                                                        </tr>
                                                                                </table>
                                                                        </div>
                                                                        <div class="colAlt span1cred">
                                                                                <table style="width:100%;height:100%;">
                                                                                        <tr>
                                                                                                <td class="creditRight">SCOTT JONES<br>TECHNICALLY COVERED</td>
                                                                                        </tr>
                                                                                </table>
                                                                        </div>
                                                                </div>
								<br>
								<br>
								<div class="section group" style="margin-bottom: 10px;">
                                                                        <div class="colAlt span1cred">
                                                                                <table style="width:100%;height:100%;">
                                                                                        <tr>
                                                                                                <td class="creditLeft">PASSWORD PROTECTION</td>
                                                                                        </tr>
                                                                                </table>
                                                                        </div>
                                                                        <div class="colAlt span1cred">
                                                                                <table style="width:100%;height:100%;">
                                                                                        <tr>
                                                                                                <td class="creditRight">ZUBRAG</td>
                                                                                        </tr>
                                                                                </table>
                                                                        </div>
                                                                </div>
								<br>
								<div class="section group" style="margin-bottom: 10px;">
                                                                        <div class="colAlt span1cred">
                                                                                <table style="width:100%;height:100%;">
                                                                                        <tr>
                                                                                                <td class="creditLeft">JS COLOR</td>
                                                                                        </tr>
                                                                                </table>
                                                                        </div>
                                                                        <div class="colAlt span1cred">
                                                                                <table style="width:100%;height:100%;">
                                                                                        <tr>
                                                                                                <td class="creditRight">JAN ODVÁRKO</td>
                                                                                        </tr>
                                                                                </table>
                                                                        </div>
                                                                </div>
								<br>
								<div class="section group" style="margin-bottom: 10px;">
                                                                        <div class="colAlt span1cred">
                                                                                <table style="width:100%;height:100%;">
                                                                                        <tr>
                                                                                                <td class="creditLeft">PCB FABRICATION</td>
                                                                                        </tr>
                                                                                </table>
                                                                        </div>
                                                                        <div class="colAlt span1cred">
                                                                                <table style="width:100%;height:100%;">
                                                                                        <tr>
                                                                                                <td class="creditRight">JAMES "LAEN FINEHACK" NEAL</td>
                                                                                        </tr>
                                                                                </table>
                                                                        </div>
                                                                </div>
								<br>
								<div class="section group" style="margin-bottom: 10px;">
                                                                        <div class="colAlt span1cred">
                                                                                <table style="width:100%;height:100%;">
                                                                                        <tr>
                                                                                                <td class="creditLeft">SMOOTHIE CHARTS</td>
                                                                                        </tr>
                                                                                </table>
                                                                        </div>
                                                                        <div class="colAlt span1cred">
                                                                                <table style="width:100%;height:100%;">
                                                                                        <tr>
                                                                                                <td class="creditRight">JOE WALNES<br>DREW NOAKES</td>
                                                                                        </tr>
                                                                                </table>
                                                                        </div>
                                                                </div>
								<br>
								<br>
								<div class="section group" style="margin-bottom: 10px;">
                                                                        <div class="colAlt span1cred">
                                                                                <table style="width:100%;height:100%;">
                                                                                        <tr>
                                                                                                <td class="creditLeft">OUIMEAUX</td>
                                                                                        </tr>
                                                                                </table>
                                                                        </div>
                                                                        <div class="colAlt span1cred">
                                                                                <table style="width:100%;height:100%;">
                                                                                        <tr>
                                                                                                <td class="creditRight">IAN MCCRACKEN</td>
                                                                                        </tr>
                                                                                </table>
                                                                        </div>
                                                                </div>
								<br>
								<div class="section group" style="margin-bottom: 10px;">
                                                                        <div class="colAlt span1cred">
                                                                                <table style="width:100%;height:100%;">
                                                                                        <tr>
                                                                                                <td class="creditLeft">BETA TESTERS</td>
                                                                                        </tr>
                                                                                </table>
                                                                        </div>
                                                                        <div class="colAlt span1cred">
                                                                                <table style="width:100%;height:100%;">
                                                                                        <tr>
                                                                                                <td class="creditRight">FITZ LAWRENCE<br>GABRIEL BALINT<br>MARK PEREZ<br>OLGA LAVROVA<br>JAY BURNESS</td>
                                                                                        </tr>
                                                                                </table>
                                                                        </div>
                                                                </div>
								<br>
								<br>
								<br>
								<br>
								<br>

							</td>
						</tr>
					</table>
					<input type="hidden" name="updated" value="true"></input>
				</div>
			</form>
		</div>
	<!------------------------------------------------------->

	<script>

		window.currentView = <?php echo json_encode($currentView);?>;

		$(document).ready(function(){
			$(window.currentView).fadeIn("fast");
		});

		$(document).on("keypress", function (e) {
			if (e.which == 13 && window.currentView != "#confMenu" && window.currentView != "#passMenu" && window.currentView != "#confCredits" && window.currentView != "#confPassword") {
				sendData();
			}
			if (e.which == 13 && window.currentView == "#confPassword") {
				sendPass();
			}
		});

		function sScroll(div){
                        $('html, body').animate({
                                scrollTop: $(div).offset().top-5
                        }, 500);
                }

		function switchView(newView){
			window.history.replaceState('page2', 'Title', '/config.php?view='+newView.slice(1));
			$("#confConfirmWrap").fadeOut("fast");
			$("#confConfirm").fadeOut("fast");
			$(window.currentView).fadeOut("fast",function(){
				$(newView).fadeIn("fast",function(){
					if(newView == "#confMenu"){
						sScroll("#whole");
					}
					else{
						sScroll(newView);
					}
				});
			});
			window.currentView = newView;
		}

		function sendData(){
			$.ajax({
				url: "config.php",
				type:'POST',
				data:
				{
					onColor : document.getElementsByName("onColor")[0].value,
					offColor : document.getElementsByName("offColor")[0].value,
					pendingColor : document.getElementsByName("pendingColor")[0].value,
					extraColor1 : document.getElementsByName("extraColor1")[0].value,
					extraColor2 : document.getElementsByName("extraColor2")[0].value,
					uiScale : document.getElementsByName("uiScale")[0].value,
					maxWidth : document.getElementsByName("maxWidth")[0].value,
					notifyHangtime : document.getElementsByName("notifyHangtime")[0].value,
					rgbLed : document.getElementsByName("rgbLed")[0].value,
					masterFreq : document.getElementsByName("masterFreq")[0].value,
					boardType : document.getElementsByName("boardType")[0].value,
					deviceInterval : document.getElementsByName("deviceInterval")[0].value,
					deviceTimeout : document.getElementsByName("deviceTimeout")[0].value,
					updated : "true"
				},
				success: function(msg)
				{
					sScroll("#whole");
					$("#confConfirmWrap").fadeIn("fast");
					$("#confConfirm").fadeIn("fast");
				}
			});
		}


		function sendPass(){
			oldPassData = document.getElementsByName("oldPass")[0].value;
			newPassData = document.getElementsByName("newPass")[0].value;
			newPassConfData = document.getElementsByName("newPassConf")[0].value;

			if(CryptoJS.MD5(oldPassData) == <?php echo json_encode($passMD5);?> && newPassData == newPassConfData){
				hideBanner();
				$.ajax({
					url: "password.php",
					type:'GET',
					data:
					{
						oldPass : oldPassData,
						newPass : newPassData,
						logout  : 1,
						updated : "true"
					},
					success: function(msg)
					{
						sScroll("#whole");
						$("#confConfirmWrap").fadeIn("fast");
						$("#confConfirm").fadeIn("fast");
					}
				});
			}
		}



	</script>

	<!-- Include Footer -->
	<?php include("footer.php");?>
	</body>
</html>
