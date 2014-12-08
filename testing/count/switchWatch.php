<?php
?>
<html>
	<head>
		<script type="text/javascript">

function ajaxFunctionSwitch(){
        var ajaxRequestS;  // The variable that makes Ajax possible!

        try{
                // Opera 8.0+, Firefox, Safari
                ajaxRequestS = new XMLHttpRequest();
        } catch (e){
                // Internet Explorer Browsers
        try{
                ajaxRequestS = new ActiveXObject('Msxml2.XMLHTTP');
        } catch (e) {
        try{
                ajaxRequestS = new ActiveXObject('Microsoft.XMLHTTP');
        } catch (e){
                // Something went wrong
                alert('Your browser broke!');
                return false;
        }
        }
        }
        // Create a function that will receive data sent from the server
        ajaxRequestS.onreadystatechange = function(){
                if(ajaxRequestS.readyState == 4){
                                document.getElementById("switch").innerHTML = ajaxRequestS.responseText;
                }
        };
        ajaxRequestS.open('POST', 'switchCount.php', true);
        ajaxRequestS.send(null);

}
function ajaxFunctionApp(){
        var ajaxRequestA;  // The variable that makes Ajax possible!

        try{
                // Opera 8.0+, Firefox, Safari
                ajaxRequestA = new XMLHttpRequest();
        } catch (e){
                // Internet Explorer Browsers
        try{
                ajaxRequestA = new ActiveXObject('Msxml2.XMLHTTP');
        } catch (e) {
        try{
                ajaxRequestA = new ActiveXObject('Microsoft.XMLHTTP');
        } catch (e){
                // Something went wrong
                alert('Your browser broke!');
                return false;
        }
        }
        }
        // Create a function that will receive data sent from the server
        ajaxRequestA.onreadystatechange = function(){
                if(ajaxRequestA.readyState == 4){
                                document.getElementById("app").innerHTML = ajaxRequestA.responseText;
                }
        };
        ajaxRequestA.open('POST', 'appCount.php', true);
        ajaxRequestA.send(null);

}

function commencer(){
        setInterval(ajaxFunctionSwitch, 1000);
        setInterval(ajaxFunctionApp, 1000);
}

		</script>
	</head>
	<body onload="commencer();">
		<div id="app" style="display:inline">X</div> appliances have been effortlessly switched <div id="switch" style="display:inline">X</div> times.
	</body>
</html>
