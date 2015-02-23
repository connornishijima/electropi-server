<?php
	include("includes/configFile.php");
	include("includes/functions.php");
        include("includes/strings.php");

	$title="Home Automation";
	$titleType="hide";
	$noWatchCheck = "1";
	$controlPage = "true";
	$maxWidth = $sets["SETTINGS"]["maxWidth"];

	function deleteDir($dirPath) {
	    if (! is_dir($dirPath)) {
	        throw new InvalidArgumentException("$dirPath must be a directory");
	    }
	    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
	        $dirPath .= '/';
	    }
	    $files = glob($dirPath . '*', GLOB_MARK);
	    foreach ($files as $file) {
	        if (is_dir($file)) {
	            self::deleteDir($file);
	        } else {
	            unlink($file);
	        }
	    }
	    rmdir($dirPath);
	}

	if(isset($_GET["remove"])){
		$UID = $_GET["remove"];
		deleteDir("data/switches/".$UID);
	}

	function includeSources(){
		$count = 0;
		$switches = scandir("data/switches");
		$switchCount = count($switches)-2;
		$switchCount = 99;
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
						$parseMessageList .= "parseMessage".$switchUIDn."(msg.data);\n";
						include("data/switches/".$switchUIDn."/source.php");
					}
				}
			}
		$count++;
		}
	}

	function includeActions($max){
		$actions = scandir("data/actions");
		$actionString = "";
		foreach($actions as &$action){
			if($action != "." && $action != ".."){
				$data = file_get_contents("data/actions/".$action);
				$data = explode("\n",$data);
				$actionID = explode(".",$action)[0];
				$actionNick = ltrim($data[0],"*");
				$actionType = ltrim($data[1],"$");
				if($actionType == "SHOW"){
					$message = '"'.$actionID.'"'.',"ACTION:'.$actionID.'"';
					$actionString .=
						"<table width='100%' border='0' cellspacing='0' cellpadding='0' style='margin-left:auto;margin-right:auto;margin: 0px auto;margin-top: 5px;max-width:".$max."px;'>
	                                                <tr class='actionControl' id='".$actionID."'>
	                                                        <td>
	                                                                <div class='action' onclick='sendAction(".$message.");'><img src='images/action.png' style='width:20px;height:20px;margin-right: 10px;opacity: 0.5;'>".$actionNick."</div>
	                                                        </td>
	                                                </tr>
	                                        </table>";
				}
			}
		}
		echo $actionString;
	}

?>
<html>
	<style>
		#addSwitch{
			background-color:#282828;
			text-align: center;
		}
		#addAction{
			background-color:#282828;
			text-align: center;
		}
		#addTrack{
			background-color:#282828;
			text-align: center;
		}
		#addEvent{
			background-color:#282828;
			text-align: center;
		}
		#addPlugin{
			background-color:#282828;
			text-align: center;
		}
		#lock{
			background-color: <?php echo $sets['SETTINGS']["offColor"];?>;
		}
	</style>

	<!-- Include Header -->
	<?php include("header.php"); ?>

	<body id="bodyMain">
	<!------------------------------------------------------->
		<div id="wrapper" style="margin-top: -10px;"> <!-- We remove the header margin to make the control list fit flush. -->
			<table <?php echo $tabStretch;?>>
				<tr class="addButtons">
					<td style="padding-right: 5px;"><a href="learn.php"><div id="addSwitch" class="addButtons"><img src="images/addSwitch.png" width="64px" height="64px" style="margin-top: 8px;"/></div></a></td>
					<td style="padding-right: 5px;"><a href="actions.php"><div id="addAction" class="addButtons"><img src="images/addAction.png" width="64px" height="64px" style="margin-top: 8px;"/></div></a></td>
					<td style="padding-right: 5px;"><div id="addTrack" class="addButtons"><img src="images/addTracking.png" width="64px" height="64px" style="margin-top: 8px;"/></div></td>
					<td style="padding-right: 5px;"><a href="events.php"><div id="addEvent" class="addButtons"><img src="images/addEvent.png" width="64px" height="64px" style="margin-top: 8px;"/></div></a></td>
					<td><div id="addPlugin" class="addButtons"><img src="images/addPlugin.png" width="64px" height="64px" style="margin-top: 8px;"/></div></td>
				</tr>
			</table>
			<?php includeSources();?>
			<div class="spacer"></div>
			<?php includeActions($maxWidth);?>
			<div class="spacer"></div>
		</div>
	<!------------------------------------------------------->

	<!-- Include Footer -->
	<?php include("footer.php");?>
	</body>
</html>
