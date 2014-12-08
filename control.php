<?php

include("settings.php");
$uiScale = floatval(readSetting("UI_SCALE"));
$uiScale = $uiScale + 0.2;
$animations = readSetting("ANIMATIONS");
$notifications = readSetting("NOTIFICATIONS");
$onColor = readSetting("ONCOLOR");
$offColor = readSetting("OFFCOLOR");
$hostname = $_SERVER['REMOTE_ADDR'];
$maxWidth = readSetting("MAX_WIDTH");
$wemoSupport = readSetting("WEMO_SUPPORT");
$freqAttached = readSetting("FREQ_ATTACHED");

$notification = trim(file_get_contents("misc/notification.txt"));

$order = file_get_contents("conf/app.order");

$order = explode("\n",$order);

$tableString = " ";
$jsString = " ";
$ajaxString = " ";

$count = 0;

foreach($order as &$line){
	$UID = $line;
	$subject = file_get_contents("conf/appliances.txt");
	foreach(preg_split("/((\r?\n)|(\r\n?))/", $subject) as $line){
		$pieces = explode("|",$line);
		$appName = $pieces[0];
		$onCode = $pieces[2];
		$offCode = $pieces[3];
		$repeat = $pieces[7];
		$sUID = trim($pieces[8],"'");
		$freq = $pieces[9];
		if($sUID == $UID){
			$appOnCode = "'" . $onCode . "'"; // APP ON CODE
			$appOffCode = "'" . $offCode . "'";// APP OFF CODE
			$appRepeat = $repeat; // APP TX REPEAT
			$appUID = "'".$sUID."'"; // APP UNIQUE ID
			$appUIDnoQUOTE = str_replace("'","",$appUID);
			$appFreq = $freq;
			$opacity = 0;
			$opacity2 = 1;

		        $stateString = file_get_contents("conf/applianceStates.txt");
        		$apps = explode("\n",$stateString);
        		foreach ($apps as &$app) {
                		$pieces = explode("|",$app);
                		if($pieces[2] == $appUID){
                        		$appState = $pieces[1];
                		}
        		}


			if($appState == "1"){
				$opacity = 0;
				$opacity2 = 1;
			}
			else{
				$opacity = 1;
				$opacity2 = 0;
			}

			if($appFreq == $freqAttached){
				$rowOpacity = "1";
				$pointerEvents = "auto";
			}
			else{
				$slaveList = file_get_contents("conf/slave.list");
				$slaveList = explode("\n",$slaveList);
				$freqFound = 0;
				foreach($slaveList as &$slaveLine){
					$slaveLine = explode("|",$slaveLine);
					if($appFreq == $slaveLine[1]){
						$freqFound = 1;
					}
				}
				if($freqFound == 1){
					$rowOpacity = "1";
					$pointerEvents = "auto";
				}
				else{
					$rowOpacity = "0.3";
					$pointerEvents = "none";
					$appName = $appName . " | DISABLED";
				}
			}

			if(strlen($appName) > 1){ // IF LINE IS VALID
				// $tableString IS THE HTML TABLE RENDERED ON THE PAGE
				$countA = "'A" . $count . "state'";
				$image = "url('conf/appImages/" . $appUIDnoQUOTE . ".jpg');";
				$tableString = $tableString . '<li><table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width:'.$maxWidth.'px;">
					<tr id="applianceRow" style="opacity:'.$rowOpacity.'">
					<td class="beta" id="appPreview" style="background-image:' . $image . '"></td>
	                                <td id="applianceName" valign="middle">' . $appName . '<a href="editApp.php?uid=' . $appUIDnoQUOTE . '" style="color:#242424;"><div id="editButton" style="display: inline-block;float: right;margin-right: 15px;width: 25px;height: 25px;background-image:url(images/edit.png);background-size:   cover;background-repeat: no-repeat;background-position: center center;margin-top: 5px;"></div></a></td>
	                                <td id="horizontalSpace"></td>
	                                <td id="powerBtn" style="pointer-events:'.$pointerEvents.';" onclick="powerApp(' . $count . ',' . $appState . ',' . $countA . ',0,' . $appOnCode . ',' . $appOffCode . ',' . $appRepeat . ',' . $appUID . ',' . $appFreq . ');"><div id="A' . $count . 'green" class="greenBack" style="opacity:' . $opacity2 . ';"></div><div id="A' . $count . 'red" class="redBack" style="opacity:' . $opacity . ';"></div><div class="powerIcon"><img id="A' . $count . 'pwr" src="images/power.png" width="64px" height="64px"></div></td>
		                        </tr>
		                        <tr id="verticalSpace"></tr>
					</table></li>';

				// $jsString IS THE JAVASCRIPT TIED TO EACH APPLIANCE
				$jsString = $jsString . "var A" . $count . "state = " . $appState . ";";
				$ajaxString = $ajaxString . "function ajaxFunctionApp" . $count . "(){
					var ajaxRequest;  // The variable that makes Ajax possible!

					try{
					// Opera 8.0+, Firefox, Safari
						ajaxRequest = new XMLHttpRequest();
					} catch (e){
					// Internet Explorer Browsers
					try{
						ajaxRequest = new ActiveXObject('Msxml2.XMLHTTP');
					} catch (e) {
					try{
						ajaxRequest = new ActiveXObject('Microsoft.XMLHTTP');
					} catch (e){
						// Something went wrong
						alert('Your browser broke!');
						return false;
					}
					}
					}
					// Create a function that will receive data sent from the server
					ajaxRequest.onreadystatechange = function(){
						if(ajaxRequest.readyState == 4){
							if(ajaxRequest.responseText != A" . $count. "state){
								if(window.ajaxPause == 0){
									powerApp(" . $count . ",ajaxRequest.responseText," . $countA . ",1,9,9,9,9,9,9);
								}
							}
						}
					};
					ajaxRequest.open('POST', 'stateListen.php?uid=" . $appUIDnoQUOTE . "', true);
					ajaxRequest.send(null);
					}\n\n";

				$commencerString = $commencerString . "setInterval(ajaxFunctionApp" . $count . ", 500);\n";

				$count = $count + 1;
			}
		}
	}
}

$actionTable = "";
$subject = file_get_contents("conf/actions/actions.list");
$subject = explode("\n",$subject);
foreach($subject as &$action){
	if(strlen($action) > 5){
		$action = explode("|",$action);
		$nickname = $action[0];
		$AID = $action[1];
		$link = '"system.php?type=ACTION&AID=' . $AID . '"';
		$link2 = '"eraseAction.php?AID=' . $AID . '"';
		$actionTable = $actionTable . "<tr id='actionName' style='cursor:pointer;'><td onclick='haptic();$(window.dummy).load(" . $link . ");' style='padding-left: 20px;'>" . $nickname . "</td><td onclick='$(window.dummy).load(" . $link2 . "); location.reload();' style='display: inline-block;float: right;margin-right: 20px;width: 25px;height: 25px;background-image: url(images/delete.png);background-size: cover;background-repeat: no-repeat;background-position: center center;margin-top: 20px;opacity: 0.2;'></td></tr><tr id='verticalSpace'></tr>";
	}
}

if($wemoSupport == "ENABLED"){
	$wemoString = "";
	$subject = file_get_contents("conf/wemo.list");
	if(strlen($subject)<5){
		$wemoString = '<tr id="wemoRow">
	                <td id="wemoName" valign="middle" style="text-align:center;padding-top: 10px;">WEMO SWITCHES WILL POPULATE HERE AUTOMATICALLY!&nbsp &nbsp;<a href="#" style="color:'.$offColor.';" onclick="wemoRepop();">CLICK&nbsp;HERE&nbsp;TO&nbsp;MANUALLY&nbsp;UPDATE</a></td>
	        </tr>
	        <tr id="verticalSpace"></tr>';
	}
	else{
	$subject = explode("\n",$subject);
	foreach($subject as &$wemo){
	        if(strlen($wemo) > 5){
			$wemo = explode("|",$wemo);
			$wemoID = $wemo[0];
			$wemoIDAlt = "'".$wemo[0]."'";
			$wemoName = $wemo[1];
			$wemoNameAlt = "'".$wemo[1]."'";
			$wemoState = $wemo[2];
			if($wemoState == "1"){
				$opacity = 0;
				$opacity2 = 1;
			}
			else if($wemoState == "0"){
				$opacity = 1;
				$opacity2 = 0;
			}
			$wemoString = $wemoString . '<tr id="applianceRow">
					<td class="beta" id="appPreview" style="background-image:' . $image . '"></td>
					<td id="applianceName" valign="middle">' . strtoupper($wemoName) . '</td>
					<td id="horizontalSpace"></td>
					<td id="powerBtn" onclick="powerWemo('.preg_replace('/\s+/', '_', $wemoNameAlt).','.$wemoIDAlt.',0);"><div id="A' . $wemoID . 'green" class="greenBack" style="opacity:' . $opacity2 . ';"></div><div id="A' . $wemoID . 'red" class="redBack" style="opacity:' . $opacity . ';"></div><div class="powerIcon"><img id="A' . $wemoID . 'pwr" src="images/power.png" width="64px" height="64px"></div></td>
				</tr>
				<tr id="verticalSpace"></tr>';
	
			$jsString = $jsString .
					"window." .preg_replace('/\s+/', '_', $wemoName) . " = " . $wemoState . ";\n";
	
			$ajaxString = $ajaxString . "function ajaxFunctionApp" . $wemoID . "(){
						var ajaxRequest;  // The variable that makes Ajax possible!
	
						try{
						// Opera 8.0+, Firefox, Safari
							ajaxRequest = new XMLHttpRequest();
						} catch (e){
						// Internet Explorer Browsers
						try{
							ajaxRequest = new ActiveXObject('Msxml2.XMLHTTP');
						} catch (e) {
						try{
							ajaxRequest = new ActiveXObject('Microsoft.XMLHTTP');
						} catch (e){
							// Something went wrong
							alert('Your browser broke!');
							return false;
						}
						}
						}
						// Create a function that will receive data sent from the server
						ajaxRequest.onreadystatechange = function(){
							if(ajaxRequest.readyState == 4){
								state = window.".preg_replace('/\s+/', '_', $wemoName).";
								if(parseInt(ajaxRequest.responseText) != parseInt(state)){
									if(parseInt(ajaxRequest.responseText) == 1 || parseInt(ajaxRequest.responseText) == 0){
										if(parseInt(state) == 0){
											window.".preg_replace('/\s+/', '_', $wemoName)." == 1;
										}
										else if(parseInt(state) == 1){
											window.".preg_replace('/\s+/', '_', $wemoName)." == 0;
										}
										if(window.wemoSkip == 0){
											powerWemo(".preg_replace('/\s+/', '_', $wemoNameAlt).",".$wemoIDAlt.",1);
										}
										else{
											window.wemoSkip = 0;
										}
									}
								}
							}
						};
						ajaxRequest.open('POST', 'stateListen.php?wemo=" . $wemoID . "', true);
						ajaxRequest.send(null);
						}\n\n";
			$commencerString = $commencerString . "setInterval(ajaxFunctionApp" . $wemoID . ", 1000);\n";
		}
	}
	$wemoString = $wemoString . '<tr id="wemoRow">
                        <td id="wemoName" valign="middle">&nbsp;<a href="#" style="color:'.$offColor.';" onclick="wemoRepop();">REPOPULATE WEMO LIST</a></td>
                </tr>
                <tr id="verticalSpace"></tr>';
	}
}

?>

<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=<?php echo $uiScale; ?>,maximum-scale=<?php echo $uiScale; ?>, user-scalable=no"/>

		<?php include("header.php");?>

		<title>ElectroPi Control</title>

		<script type="text/javascript">

			window.ajaxPause = 0;
			window.dummy = "#dummy";
			window.repop = 0;
			easterEggs = "1";

			Image1= new Image(64,64);
			Image1.src = "images/logostatic.png";

			var logoColor = "green";
			window.setTimeout(function () {
				var intervalID = window.setInterval(function () {
					if(easterEggs == "1"){
						if(logoColor == "red"){
							logoColor = "green";
							$("#logoText").animate({color: "<?php echo $onColor; ?>" },300);
						}
						else if(logoColor == "green"){
		                                        logoColor = "red";
		                                        $("#logoText").animate({color: "<?php echo $offColor; ?>" },300);
		                                }
					}
				}, 10800);
			}, 400);


			$(function(){  // $(document).ready shorthand
				if(<?php echo json_encode($animations);?> == "ENABLED"){
	                                $('#subtitle').hide().fadeIn('slow');
					window.setTimeout(function () {
						$("#logoText").animate({color: "<?php echo $onColor; ?>" },300);
					}, 400);
				}
				else{
					$("#logoText").animate({color: "<?php echo $onColor; ?>" },0);
				}
                        });

			var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
			var h = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);

function ajaxFunctionRepop(){

        var ajaxRequestB;  // The variable that makes Ajax possible!

        try{
                // Opera 8.0+, Firefox, Safari
                ajaxRequestB = new XMLHttpRequest();
        } catch (e){
                // Internet Explorer Browsers
        try{
                ajaxRequestB = new ActiveXObject('Msxml2.XMLHTTP');
        } catch (e) {
        try{
                ajaxRequestB = new ActiveXObject('Microsoft.XMLHTTP');
        } catch (e){
                // Something went wrong
                alert('Your browser broke!');
                return false;
        }
        }
        }
        //Create a function that will receive data sent from the server
        ajaxRequestB.onreadystatechange = function(){
                if(ajaxRequestB.readyState == 4){
                        if(ajaxRequestB.responseText == "1" || ajaxRequestB.responseText == "1\n"){
                                document.getElementById("wemoWarning").style.display = "block";
                                document.getElementById("wemoContainer").style.display = "none";
                                window.repop = 1;
                        }
                        else{
                                document.getElementById("wemoWarning").style.display = "none";
                                document.getElementById("wemoContainer").style.display = "block";
                                if(window.repop == 1){
                                        location.reload();
                                }
                        }
                }
        };
        ajaxRequestB.open('POST', 'conf/wemo.state', true);
        ajaxRequestB.send(null);
}

			<?php echo $jsString;?>

			function loadingLogo(){
				easterEggs = "0";
				var image = document.querySelectorAll("img")[0];
				var source = image.src = image.src.replace("images/tx_animation_slow.gif","images/loading.gif");
				var source = image.src = image.src.replace("images/logostatic.png","images/loading.gif");
				var source = image.src = image.src.replace("images/loading.gif","images/loading.gif");
				window.setTimeout(function () {
					var image = document.querySelectorAll("img")[0];
	                                var source = image.src = image.src.replace("images/loading.gif","images/logostatic.png");
				}, 600);
				//clearInterval(intervalID);
			}

			function powerWemo(wemoName,ID,mode){
				window.wemoSkip = 1;
				state = window[wemoName];
				if(parseInt(state) == 1){
					window[wemoName] = 0;
					document.getElementById("A" + ID + "red").style.opacity = "1";
					document.getElementById("A" + ID + "green").style.opacity = "0";
					if(mode == 0){
						$('#dummy').load("system.php?type=WEMO&name="+wemoName+"&state=0");
					}
				}
				else if(parseInt(state) == 0){
					window[wemoName] = 1;
					document.getElementById("A" + ID + "red").style.opacity = "0";
					document.getElementById("A" + ID + "green").style.opacity = "1";
					if(mode == 0){
						$('#dummy').load("system.php?type=WEMO&name="+wemoName+"&state=1");
					}
				}
			}

			function wemoRepop(){
				$('#dummy').load("system.php?type=WEMO-REPOP");
			}

			function powerApp(appliance, state, varName, mode, onCode, offCode, repeat, uid, freq){
				uid = String(uid);
				oldState = window[varName];

				if(oldState == 0){
					document.getElementById("A" + appliance + "red").style.opacity = "0";
					document.getElementById("A" + appliance + "green").style.opacity = "1";
					a = varName;
	                                str = a + ' = ' + '1';
       	        	                eval(str);
					if(mode == 0){
						haptic();
						$('#dummy').load("system.php?uid=" + uid + "&on=" + onCode + "&off=" + offCode + "&repeat=" + repeat + "&freq=" + freq + "&state=1&type=CONTROL");
						$('#dummy').load("http://connor-n.com/electropi/count/switchAdd.php");
						//$('#dummy').load("control.php?appnum=" + appliance + "&state=1&IP=" + <?php echo json_encode($IP);?>);                     DEPRECATED!
					}
				}
				else if(oldState == 1){
                                        document.getElementById("A" + appliance + "red").style.opacity = "1";
                                        document.getElementById("A" + appliance + "green").style.opacity = "0";
					a = varName;
                                        str = a + ' = ' + '0';
                                        eval(str);
					if(mode == 0){
						haptic();
						$('#dummy').load("system.php?uid=" + uid + "&on=" + onCode + "&off=" + offCode + "&repeat=" + repeat + "&freq=" + freq + "&state=0&type=CONTROL");
						//$('#dummy').load("control.php?appnum=" + appliance + "&state=0&IP=" + <?php echo json_encode($IP);?>);                     DEPRECATED!
					}
                                }
				loadingLogo();
				if(mode == 0){
					window.ajaxPause = 1;
					setTimeout(function(){
						window.ajaxPause = 0;
					},1500);
				}
			}

			function loadIframe(iframeName, url) {
				var $iframe = $('#' + iframeName);
				if ( $iframe.length ) {
					$iframe.attr('src',url);
					return true;
    				}
    				return false;
			}

// AJAX LISTENERS HERE ----------------------------------------------------------------------------------------------
<?php echo $ajaxString;?>
// AJAX LISTENERS END -----------------------------------------------------------------------------------------------

function commencerMain() {
$("#notify").hide();
setInterval(ajaxFunctionRepop, 250);
<?php echo $commencerString;?>
}

		</script>

	</head>

	<body id="body" onLoad="commencerMain();">

		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: <?php echo $maxWidth;?>px;">
			<tr id="headerRow">
				<td id="headerCell"><a href="index.php"><img id="logo" src="images/tx_animation_slow.gif?<?php echo date('Ymdgis');?>"></a><font id="logoText" style="color:<?php echo $offColor; ?>;padding-top: 10px;vertical-align: top;">ELECTRO</font>PI</td>
				<td id="horizontalSpace"></td>
				<td id="settingsBtn"><a href="setup.php"><span style="width: 64px;height: 64px;position: absolute;margin-top: -32px;"></span></a></td>
			</tr>
			<tr id="verticalSpace"></tr>
		</table>
		<div id="contain">
		<ul id="slippylist" style="list-style-type: none;padding: 0px;">
			<?php echo $tableString;?>
		</ul>
		<div id="wemoContainer">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: <?php echo $maxWidth;?>px;margin: 0px auto;">
				<?php echo $wemoString;?>
			</table>
		</div>
		<div id="wemoWarning" style="display:none;">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: <?php echo $maxWidth;?>px;margin: 20px auto;">
				<tr>
                                	<td style="text-align:center;">WEMO SWITCHES ARE POPULATING...</td>
				</tr>
                        </table>
		</div>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: <?php echo $maxWidth;?>px;margin: 10px auto;">
			<?php echo $actionTable;?>
		</table>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom:25px;margin-left:auto;margin-right:auto;max-width: <?php echo $maxWidth;?>px;height: 30px;margin-top: -10px;">
			<tr>
		                <td style="padding: 10px;font-size: 18px;margin-bottom: -32px;height: 40px;float: left;" valign="middle">+ <a href="addApp.php">ADD SWITCH</a></td>
		                <td style="padding: 10px;font-size: 18px;margin-bottom: -32px;height: 40px;float: left;" valign="middle">+ <a href="addAction.php" style="color:<?php echo $offColor;?>;">ADD ACTION</a></td>
		                <td style="padding: 10px;font-size: 18px;margin-bottom: -32px;height: 40px;float: left;" valign="middle">+ <a href="addTracking.php" style="color:#cccccc;">ADD TRACKING</a></td>
	                </tr>
		</table>

		</div>
		<div id="dummy" style="display:none"></div>
	</body>
	<script type="text/javascript">
		var ol = document.getElementById('slippylist');
		ol.addEventListener('slip:beforereorder', function(e){
			if (/demo-no-reorder/.test(e.target.className)) {
				e.preventDefault();
			}
		}, false);

		ol.addEventListener('slip:beforeswipe', function(e){
			if (e.target.nodeName == 'INPUT' || /demo-no-swipe/.test(e.target.className)) {
				e.preventDefault();
			}
		}, false);

		ol.addEventListener('slip:beforewait', function(e){
			if (e.target.className.indexOf('instant') > -1) e.preventDefault();
		}, false);

		ol.addEventListener('slip:afterswipe', function(e){
			e.target.parentNode.appendChild(e.target);
		}, false);

		ol.addEventListener('slip:reorder', function(e){
			e.target.parentNode.insertBefore(e.target, e.detail.insertBefore);
			var optionTexts = [];
			$("ul li").each(function() { optionTexts.push($(this).text()) });
			var quotedCSV = '"' + optionTexts.join('"|"') + '"';
			quotedCSV = quotedCSV.replace(/\n\s*\n\s*\n/g, '');
			quotedCSV = encodeURI(quotedCSV);
			$('#dummy').load("reorderApp.php?string=" + quotedCSV);
			return false;
		}, false);

		new Slip(ol);
	</script>
</html>

<?php include("footer.php");?>
