<?php
	include("includes/configFile.php");
	include("includes/functions.php");
        include("includes/strings.php");

	$title="Switches";

	function includeSources(){
		$count = 0;
		$switches = scandir("data/switches");
		$switchCount = count($switches)-2;
		while($count < $switchCount){
			foreach($switches as &$switchUIDn){
				if(strlen($switchUIDn) == 5){
					$switchInfo = file_get_contents("data/switches/".$switchUIDn."/info.ini");
					$lines = explode("\n",$switchInfo);
					foreach($lines as &$line){
						if(strlen($line) > 1){
							$line = explode(" = ",$line);
							$key = $line[0];
							$val = $line[1];
							if($key == "position"){
								$switchPos = intval($val);
							}
						}
					}
					if($switchPos == $count){
						include("data/switches/".$switchUIDn."/source.php");
					}
				}
			}
		$count++;
		}
	}
?>

<html>
	<!-- Include Header -->
	<?php include("header.php"); ?>

	<body id="bodyMain">
	<!------------------------------------------------------->
		<div id="wrapper" style="margin-top: -10px;"> <!-- We remove the header margin to make the control list fit flush. -->
			<?php includeSources();?>
		</div>
	<!------------------------------------------------------->

	<!-- Include Footer -->
	<?php include("footer.php");?>
	</body>
</html>
