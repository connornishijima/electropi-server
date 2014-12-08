<?php
	$fileList = file_get_contents("presets.list");
	$fileList = explode("\n",$fileList);
	foreach($fileList as &$file){
		if(strlen($file) > 3){
			$fileString = file_get_contents("presets/".$file);
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
						$frequency = $value;
					}
				}
			}
			$image = '"presets/images/' . $fccid . '.jpg"';
			$listHTML = $listHTML . "<a href='presetAdd.php?fccid=" . $fccid . "'><div class='preset'><div class='presetImage'  style='background-image:url(" . $image . ");background-size: 150px 150px;'></div><div class='presetName'>" . $nickname . "</div><font style='font-size: 12px;'>FCC ID: " . $fccid . "</font><br>FREQ: " . $frequency . "MHz</div></a>";
		}
	}
?>

<html>
	<head>
		<link href='http://fonts.googleapis.com/css?family=Oswald:400,700,300' rel='stylesheet' type='text/css'>

		<style type="text/css">
			.preset{width: 150px;height: 250px;background-color: #333;color: #888;padding: 10px;margin-bottom:10px;margin-right:10px;display:inline-block;}
			.presetName{color:#ccc;margin-bottom:10px}
			.presetImage{width: 150px;height: 150px;background-color: #333;color: #ccc;background-image:url("missing.jpg"); margin-bottom:10px;}
			body{background-color:#242424;padding:0px;margin:10px;font-family: Oswald;text-align: center;}
		</style>
		<script type="text/javascript">
		</script>

	</head>
	<body>
		<div id="container">
			<?php echo $listHTML; ?>
		</div>
	</body>
</html>
