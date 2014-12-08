<?php
	file_put_contents("updating.state","TRUE");
	file_put_contents("update.log","Waiting for watchdog...");
?>
<!DOCTYPE HTML>
<html>
	<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title>ElectroPi Update</title>

<link rel="icon" type="image/png" href="../images/favicon.png" />

<link rel="stylesheet" href="../css/style.css" type="text/css" media="screen" />
<link href='http://fonts.googleapis.com/css?family=Oswald:400,700,300' rel='stylesheet' type='text/css'>

<script src="../js/jquery.js"></script>

		<STYLE type="text/css">
                        #linkButton{color:<?php echo $offColor;?>;}
                        a{text-decoration:none;color:<?php echo $onColor;?>;}
			body{background-color:#242424;margin:20px;color:#ccc;}
                </STYLE>

		<script type="text/javascript">
function ajaxFunctionU(){
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
	                document.getElementById("status").innerHTML = ajaxRequestU.responseText;
                }
        };
        ajaxRequestU.open('POST', 'updateListen.php', true);
        ajaxRequestU.send(null);

}

			$(function(){  // $(document).ready shorthand
				setInterval(ajaxFunctionU,500);
    			});

		</script>

	</head>

	<body id="body">
		<a href='../index.php' style='color:Aquamarine;font-size:24px;'>RETURN TO CONTROL</a><br><br>PLEASE WAIT...<br>
		<div id="status">LOADING...</div>
	</body>
</html>
