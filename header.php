<?php
	$beta = readSetting("BETA_MODE");
	$maxWidth = readSetting("MAX_WIDTH");
	$notifications = readSetting("NOTIFICATIONS");
	$notification = trim(file_get_contents("misc/notification.txt"));
	$theTime = trim(file_get_contents("misc/time.txt"));
	$IP = $_SERVER['REMOTE_ADDR'];
	$haptic = readSetting("HAPTIC");

	if($beta == "ENABLED"){
		$betaVisibility = "x";
	}
	else{
		$betaVisibility = "none";
	}

	$specialColorEnabled = trim(file_get_contents("misc/special/colorEnabled"));
	if($specialColorEnabled == "ENABLED"){
		$onColor = trim(file_get_contents("misc/special/onColor"));
		$offColor = trim(file_get_contents("misc/special/offColor"));
	}

		$trusted = 0;

		$deviceList = file_get_contents("conf/device.list");
		$deviceList = explode("\n",$deviceList);
		foreach($deviceList as $device){
			if(strlen($device) > 3){
				$pieces = explode("|",$device);
				$nick = $pieces[0];
				$mac = $pieces[1];
				$ipS = $pieces[2];
				if($ipS == $IP && $trusted == 0){
					$trusted = 1;
				}
			}
		}

		if($trusted == 0){
			header("Location: addDevice.php");
		}

?>

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
<link href='http://fonts.googleapis.com/css?family=Oswald:400,700,300' rel='stylesheet' type='text/css'>

<script src="js/jquery.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script src="js/slip.js"></script>
<script type="text/javascript">

window.supportsVibrate = "vibrate" in window.navigator;
window.notification = "<?php echo $notification?>";
window.notificationTime = 0;
window.notificationPresent = 0;
window.watchdog = "<?php echo $theTime?>";
window.server = "<?php echo $theTime?>";
window.uState = "FALSE";
window.repop = 0;

$("#notify").hide();

function notify(inputText){
	window.notification = inputText;
	document.getElementById("notification").innerHTML = inputText;
	$("#notify").fadeIn(0);
	if(window.notificationTime < 5){
		window.notificationTime = window.notificationTime + 5;
	}
	window.notificationPresent = 1;
}

function notifyTimeout(){
	window.notificationTime = window.notificationTime - 1
	if(window.notificationTime <= 0 && window.notificationPresent == 1){
		$("#notify").fadeOut("fast");
		window.notificationPresent = 0;
	}
}

function ajaxFunctionNotification(){
        var ajaxRequestN;  // The variable that makes Ajax possible!

        try{
                // Opera 8.0+, Firefox, Safari
                ajaxRequestN = new XMLHttpRequest();
        } catch (e){
                // Internet Explorer Browsers
        try{
                ajaxRequestN = new ActiveXObject('Msxml2.XMLHTTP');
        } catch (e) {
        try{
                ajaxRequestN = new ActiveXObject('Microsoft.XMLHTTP');
        } catch (e){
                // Something went wrong
                alert('Your browser broke!');
                return false;
        }
        }
        }
        // Create a function that will receive data sent from the server
        ajaxRequestN.onreadystatechange = function(){
                if(ajaxRequestN.readyState == 4){
                        if(ajaxRequestN.responseText != window.notification){
				notify(ajaxRequestN.responseText);
                        }
                }
        };
        ajaxRequestN.open('POST', 'misc/notification.txt', true);
        ajaxRequestN.send(null);
}

function ajaxFunctionWatchdog(){
        var ajaxRequestW;  // The variable that makes Ajax possible!

        try{
                // Opera 8.0+, Firefox, Safari
                ajaxRequestW = new XMLHttpRequest();
        } catch (e){
                // Internet Explorer Browsers
        try{
                ajaxRequestW = new ActiveXObject('Msxml2.XMLHTTP');
        } catch (e) {
        try{
                ajaxRequestW = new ActiveXObject('Microsoft.XMLHTTP');
        } catch (e){
                // Something went wrong
                alert('Your browser broke!');
                return false;
        }
        }
        }
        // Create a function that will receive data sent from the server
        ajaxRequestW.onreadystatechange = function(){
                if(ajaxRequestW.readyState == 4){
                        if(ajaxRequestW.responseText == window.watchdog){
                                window.watchdog = ajaxRequestW.responseText;
                                document.getElementById("wstatus").innerHTML = "OFFLINE";
                        }
			else{
                                window.watchdog = ajaxRequestW.responseText;
				document.getElementById("wstatus").innerHTML = "ONLINE";
			}
                }
        };
        ajaxRequestW.open('POST', 'misc/time.txt', true);
        ajaxRequestW.send(null);
}

function ajaxFunctionCPU(){
        var ajaxRequestC;  // The variable that makes Ajax possible!

        try{
                // Opera 8.0+, Firefox, Safari
                ajaxRequestC = new XMLHttpRequest();
        } catch (e){
                // Internet Explorer Browsers
        try{
                ajaxRequestC = new ActiveXObject('Msxml2.XMLHTTP');
        } catch (e) {
        try{
                ajaxRequestC = new ActiveXObject('Microsoft.XMLHTTP');
        } catch (e){
                // Something went wrong
                alert('Your browser broke!');
                return false;
        }
        }
        }
        // Create a function that will receive data sent from the server
        ajaxRequestC.onreadystatechange = function(){
                if(ajaxRequestC.readyState == 4){
                        document.getElementById("cstatus").innerHTML = ajaxRequestC.responseText;
                }
        };
        ajaxRequestC.open('POST', 'misc/cpu.txt', true);
        ajaxRequestC.send(null);
}

function ajaxFunctionUpdate(){
        var ajaxRequestU;  // The variable that makes Ajax possible!

        try{
                // Opera 8.0+, Firefox, Safari
                ajaxRequestU = new XMLHttpRequest();
        } catch (e){
                // Internet Explorer Browsers
        try{
                ajaxRequestU = new ActiveXObject('Msxml2.XMLHTTP');
        } catch (e) {
        try{
                ajaxRequestU = new ActiveXObject('Microsoft.XMLHTTP');
        } catch (e){
                // Something went wrong
                alert('Your browser broke!');
                return false;
        }
        }
        }
        // Create a function that will receive data sent from the server
        ajaxRequestU.onreadystatechange = function(){
                if(ajaxRequestU.readyState == 4){
			reply = ajaxRequestU.responseText;
			reply = reply.replace("\n","");

			updating = document.getElementById("updating");
			if(reply.split(' ')[0] == "Not"){
				alert("EMPTY");
			}
			else{
				if(reply == "TRUE" && window.uState == "FALSE"){
					window.uState = reply;
					$(".shade").fadeIn("fast");
					$("#updating").slideDown("fast");
				}
				if(reply == "FALSE" && window.uState == "TRUE"){
					window.uState = reply;
					$(".shade").fadeOut("fast")
					$("#updating").slideUp("fast", function() {
						location.reload();
					});
				}
			}
                }
        };
        ajaxRequestU.open('POST', 'conf/updating.state', true);
        ajaxRequestU.send(null);

}

function ajaxFunctionBrief(){
	if(window.uState == "TRUE"){

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
			replyB = ajaxRequestB.responseText;
			document.getElementById("brief").innerHTML = replyB;
                }
        };
        ajaxRequestB.open('POST', 'conf/updateBreif.php', true);
        ajaxRequestB.send(null);
	}
}

function ping(){
	$.ajax({
		url: 'index.php',
		success: function(result){
			if(window.uState == "FALSE"){
				document.getElementById("warning").innerHTML = "SYSTEM UPDATING...";
                                document.getElementById("brief").innerHTML = "Please wait...";
                                document.getElementById("sstatus").innerHTML = "ONLINE";
                                $(".shade").fadeOut("fast");
                                $("#updating").slideUp("fast");
			}
		},
		error: function(result){
			if(window.uState == "FALSE"){
                                document.getElementById("warning").innerHTML = "ELECTROPI IS OFFLINE!";
                                document.getElementById("brief").innerHTML = "Please wait...";
                                document.getElementById("sstatus").innerHTML = "OFFLINE";
                                $(".shade").fadeIn("fast");
                                $("#updating").slideDown("fast");
                        }

		}
	});
}

function haptic(){
	if(window.supportsVibrate == true && <?php echo json_encode($haptic);?> == "ENABLED"){
		window.navigator.vibrate(50);
	}
}

function heightShift(){
	var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
	var maxW = <?php echo json_encode($maxWidth);?>;
	spacer = document.getElementById("headerSpace");
	currentHeight = spacer.style.height;
	currentHeight = currentHeight.replace("px","");
	currentHeight = currentHeight.valueOf() - 10;
	if(w.valueOf() > maxW.valueOf()){
		h = (w.valueOf() - maxW.valueOf()) / 10;
		if(h > 50){
			h = 50;
		}
		if(currentHeight <= 50){
			spacer.style.height = (h+10) + "px";
		}
	}
}

$(window).resize(function() {
	heightShift();
});

$(function(){  // $(document).ready shorthand
	if(<?php echo json_encode($notifications);?> == "ENABLED"){
	        setInterval(ajaxFunctionNotification, 200);
	        setInterval(notifyTimeout, 1000);
	}
	ajaxFunctionUpdate();
	ajaxFunctionWatchdog();
	heightShift();

        setInterval(ajaxFunctionWatchdog, 1000);
        setInterval(ajaxFunctionCPU, 500);
        setInterval(ajaxFunctionUpdate, 1000);
        setInterval(ajaxFunctionBrief, 250);
	setInterval(ping, 1000);
});

</script>

<STYLE type="text/css">
	a{text-decoration:none;color:<?php echo $onColor;?>;}
	.greenBack{background-color:<?php echo $onColor;?>;margin-bottom: -64px;z-index:-2;-webkit-box-shadow: 0px 0px 23px 0px <?php echo $onColor;?>;-moz-box-shadow:0px 0px 23px 0px <?php echo $onColor;?>;box-shadow:0px 0px 23px 0px <?php echo $onColor;?>;}
	.redBack{background-color:<?php echo $offColor;?>;margin-bottom: -64px;z-index:-2;}
	#linkButton{color:<?php echo $offColor;?>;}
        .beta{display:<?php echo $betaVisibility;?>;}
	#appPreview{width: 64px;height: 64px;background-position: center;background-size: cover;}
	#actionName{color:<?php echo $offColor;?>;}
</STYLE>

<div id="updating" style="display:none;">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #FFEB9B;color: #242424;padding: 20px;margin-bottom: 20px;font-size: 20px;margin-left:auto;margin-right:auto;max-width: <?php echo $maxWidth;?>px;">
		<tr>
			<td id="warning" style="padding:0px 10px;padding-top:10px;text-align:left;">SYSTEM UPDATING...</td>
		</tr>
		<tr>
			<td id="brief" style="padding:0px 10px;font-size:16px;">LOADING...</td>
		</tr>
	</table>
</div>
<div class="shade"></div>
<div id="wrapper">
<div id="headerSpace"></div>
