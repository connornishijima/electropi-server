<?php
	include("settings.php");

	$offColor = readSetting("OFFCOLOR");

	$fccid = $_GET["fccid"];
	$fileString = file_get_contents("presets/pre.".$fccid);
        $fileLines = explode("\n",$fileString);
        foreach($fileLines as &$line){
        	if($line[0] != "#" && strlen($line) > 3){
			$line = explode("=",$line);
        		$name = $line[0];
        		$value = $line[1];
        		if($name == "NICKNAME"){
        			$nickname = $value;
        		}
        		if($name == "FCC_ID"){
        			$fccid = $value;
        		}
        		if($name == "FREQUENCY"){
        			$freq = $value;
        		}
        	}
        }
	$htmlString = "";

	$fileLines = explode("\n",$fileString);
	foreach($fileLines as &$line){
                if($line[0] == "*" && strlen($line) > 3){
                        $line = explode("=",$line);
                        $name = $line[0];
                        $value = $line[1];
			$codes = explode("|",$value);
			$htmlString = $htmlString . "<div class='channel'>" . substr($name,1) . "<br><br>";
			foreach($codes as &$code){
				$code = explode("+",$code);
				$binaries = $code[1];
				$binaries = explode("-",$binaries);
				$onCode = '"' . $binaries[0] . '"';
				$offCode = '"' . $binaries[1] . '"';
				$div = '"' . $binaries[1] . $code[0] . '"';
				$htmlString = $htmlString . "<div class='subchannel' id=" . $div . " onclick='pickChannel(" . $div . "," . $onCode . "," . $offCode . ",". $freq . ")'>SUBCHANNEL " .$code[0]."</div>";
			}
			$htmlString = $htmlString . "</div>";
                }
        }

?>

<html>
	<head>
		<link href='http://fonts.googleapis.com/css?family=Oswald:400,700,300' rel='stylesheet' type='text/css'>
		<style type="text/css">
			.subchannel{font-size: 18px;width: 300px;background-color: #444;padding: 20px;margin-bottom: 10px;cursor:pointer;}
			.channel{font-size: 24px;background-color: #181818;width: 350px;padding: 20px;margin-bottom: 20px;margin-left: auto;margin-right: auto;}
			.title{width: 380px;margin-left: auto;margin-right: auto;margin-bottom: 20px;}
			.title-name{font-size: 36px;}
			.title-info{font-size: 18px; color:#777;}
		</style>
		<script type="text/javascript">
			function setMode(){
				parent.preset = "true";
			}
			function pickChannel(div,onI,offI,freqI){
				document.getElementById(div).style.cssText = 'background:<?php echo $offColor;?>;color:#242424;';
				parent.onCode = onI;
				parent.offCode = offI;
				parent.repeat = 10;
				parent.freq = freqI;
				parent.presetDone = "true";
			}
			function resize(){
				window.parent.iframeLoaded();
				window.parent.parent.iframeLoaded();
			}
		</script>
	</head>
	<body style="background-color:#242424; color:#ccc;font-family:Oswald;width: 380px;margin-left: auto;margin-right: auto;" onload="setMode();resize();"></body>
		<div class="title">
			<div class="title-name"><?php echo $nickname;?></div>
			<div class="title-info">FCC ID: <?php echo $fccid;?></div>
			<div class="title-info">FREQUENCY: <?php echo $freq;?>MHz</div>
		</div>
		<?php echo $htmlString?>
	</body>
</html>
