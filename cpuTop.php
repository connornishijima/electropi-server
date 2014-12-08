<?php
	include("settings.php");
	$uiScale = floatval(readSetting("UI_SCALE"));
	writeSetting("CPU_MON","ENABLED");
	if(isset($_GET["return"])){
		writeSetting("CPU_MON","DISABLED");
		header("Location: security.php");
	}
?>
<html>
	<head>
		<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />
		<link href='http://fonts.googleapis.com/css?family=Oswald:400,700,300' rel='stylesheet' type='text/css'>
		<meta name="viewport" content="width=device-width, initial-scale=<?php echo $uiScale; ?>,maximum-scale=<?php echo $uiScale; ?>, user-scalable=no"/>

		<script type="text/javascript">
			function ajaxFunctionTop(){
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
						str = ajaxRequestN.responseText;
						str = str.replace(/(?:\r\n|\r|\n)/g, '<br />');
						document.getElementById("top").innerHTML = str;
			                }
			        };
			        ajaxRequestN.open('POST', 'misc/top.txt', true);
			        ajaxRequestN.send(null);
			}
			function ready(){
				setInterval(ajaxFunctionTop,1000);
			}
		</script>
	<head>
	<body onload="ready();" id="body" style="margin:10px;">
		<div id="title">
			<a href="cpuTop.php?return=true" style="color:#fbdb00;">RETURN TO SECURITY</a>
		</div>
		<div id="desc" style="font-family:courier;margin-top:20px;color:#fff;">
			I am a live view of your Raspberry Pi's CPU usage by process. Ironically,<br>
			this process takes quite a bit of CPU. If you see a process called "top" in<br>
			the list, that's me. I only run "top" when you're on this page, don't worry!
		</div>
		<pre>
			<div id="top">LOADING...</div>
		</pre>
	</body>
<html>
