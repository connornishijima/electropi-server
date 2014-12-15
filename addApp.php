<?php
	include("password_protect.php");
	$title = "ADD";

	$onColor = readSetting("ONCOLOR");
        $offColor = readSetting("OFFCOLOR");
        $uiScale = readSetting("UI_SCALE");
        $animations = readSetting("ANIMATIONS");
        $maxWidth = readSetting("MAX_WIDTH");
?>

<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=<?php echo $uiScale; ?>,maximum-scale=<?php echo $uiScale; ?>, user-scalable=no"/>
		<title>ElectroPi Add Appliance</title>

		<?php include("header.php");?>

		<STYLE type="text/css">
                        #linkButton{color:<?php echo $offColor;?>;}
                        a{text-decoration:none;color:<?php echo $onColor;?>;}
                </STYLE>

		<script type="text/javascript">
			$(function(){  // $(document).ready shorthand
				$("#notify").hide();
                                if(<?php echo json_encode($animations);?> == "ENABLED"){
                                        $('#subtitle').hide().fadeIn('slow');
                                        $("#logoText").animate({color: "<?php echo $offColor; ?>" });
                                }
                                else{
                                        $("#logoText").animate({color: "<?php echo $offColor; ?>" },0);
                                }
                                if(<?php echo json_encode($updated);?> == "TRUE"){
                                        $( "#alert" ).toggle();
                                }
    			});
			function booleanSwitch(name){
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
			function iframeLoaded() {
      				var iFrameID = document.getElementById('iframe');
      				if(iFrameID) {
            				// here you can make the height, I delete it first, then I make it again
            				iFrameID.height = "";
            				iFrameID.height = iFrameID.contentWindow.document.body.scrollHeight + "px";
				}
			}
		</script>

	</head>

	<body id="body">

		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: <?php echo $maxWidth;?>px;">
		<tr><td><div id="alert">Settings updated. <a href="index.php" style="white-space: nowrap;color: <?php echo $offColor; ?>;">Return to control?</a></div></td></tr>
		</table>

		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: <?php echo $maxWidth;?>px;">
			<tr>
				<td><iframe src="addAppSlides.php" id="iframe" onload="iframeLoaded();" width="100%" height="600px" scrolling="no" style="border:none;"></iframe></td>
			</tr>
		</table>

		<br>

		<?php include("footer.php");?>

		<div id="dummy" style="display:none"></div>
	</body>
</html>
