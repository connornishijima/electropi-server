<?php
	$beta = readSetting("BETA_MODE");
	$maxWidth = readSetting("MAX_WIDTH");
	$notifications = readSetting("NOTIFICATIONS");
	$notification = trim(file_get_contents("misc/notification.txt"));
	$theTime = trim(file_get_contents("misc/time.txt"));
	$IP = $_SERVER['REMOTE_ADDR'];

	if($beta == "ENABLED"){
		$betaVisibility = "x";
	}
	else{
		$betaVisibility = "none";
	}
?>

<link rel="icon" type="image/png" href="images/favicon.png" />

<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />
<link href='http://fonts.googleapis.com/css?family=Oswald:400,700,300' rel='stylesheet' type='text/css'>
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="mobile-web-app-capable" content="yes">
<link rel="shortcut icon" sizes="196x196" href="images/icon-196x196.png">

<script src="js/jquery.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script type="text/javascript">

window.notification = "<?php echo $notification?>";
window.watchdog = "<?php echo $theTime?>";
$("#notify").hide();

function notify(inputText){
	window.notification = inputText;
	document.getElementById("notification").innerHTML = inputText;
	$("#notify").fadeIn(0);
        window.setTimeout(function(){
	        $("#notify").fadeOut("slow");
        }, 1000);
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
        ajaxRequestW.open('POST', 'timeListen.php', true);
        ajaxRequestW.send(null);

}
$(function(){  // $(document).ready shorthand
	if(<?php echo json_encode($notifications);?> == "ENABLED"){
	        setInterval(ajaxFunctionNotification, 200);
	}
        setInterval(ajaxFunctionWatchdog, 1000);
	document.body.requestFullscreen();
});
</script>

<STYLE type="text/css">
	a{text-decoration:none;color:<?php echo $onColor;?>;}
	.greenBack{background-color:<?php echo $onColor;?>;margin-bottom: -64px;z-index:-2;}
	.redBack{background-color:<?php echo $offColor;?>;margin-bottom: -64px;z-index:-2;}
	#linkButton{color:<?php echo $offColor;?>;}
        .beta{display:<?php echo $betaVisibility;?>;}
</STYLE>
