<?php
	include("includes/configFile.php");
	include("includes/functions.php");
        include("includes/strings.php");

	function generateRandomString($length = 10) {
		$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

	if(isset($_POST["actionFile"])){
		$AID = generateRandomString();
		file_put_contents("data/actions/".$AID.".action",$_POST["actionFile"]);
	}

	$switchesPresent = "false";
        $files = scandir("data/switches");
        if(count($files) < 3){
                $warningString = "Actions require at least one Switch to function. <a href='learn.php'>Click here to set up a switch now.</a>";
        }
        else{
                $switchesPresent = "true";
                $switchNickList = [];
                $switchIDList = [];
                $switchOnList = [];
                $switchOffList = [];
                $switchRepeatList = [];
                $switchFreqList = [];
		$jsSwitchArray = "";
		$jsVariables = "";
		$actionsTable = "";
		$sCount = 1;
		$sFound = 0;
		while($sFound < (count($files)-2)){
                foreach($files as &$switchN){
                        if($switchN != "." && $switchN != ".."){
                                $data = file_get_contents("data/switches/".$switchN."/info.ini");
				$dataLines = explode("\n",$data);
				foreach($dataLines as &$dataLine){
					$dataLine = explode(" = ",$dataLine);
					$key = $dataLine[0];
					$val = $dataLine[1];
					if($key == "nickname"){
						$switchNick = $val;
					}
					if($key == "oncodedata"){
						$onData = file_get_contents("data/switches/".$switchN."/".$val);
						$onData = explode("\n",$onData);
						$onDataOut = "";
						foreach($onData as &$onDataLine){
							if(substr($onDataLine,0,1) != "*"){
								$onDataOut .= $onDataLine;
							}
						}
					}
					if($key == "offcodedata"){
						$offData = file_get_contents("data/switches/".$switchN."/".$val);
						$offData = explode("\n",$offData);
						$offDataOut = "";
						foreach($offData as &$offDataLine){
							if(substr($offDataLine,0,1) != "*"){
								$offDataOut .= $offDataLine;
							}
						}
					}
					if($key == "repeat"){
						$switchRepeat = $val;
					}
					if($key == "freq"){
						$switchFreq = $val;
					}
					if($key == "position"){
						$switchPos = $val;
					}
				}

				if($switchPos == $sCount){
					$sCount++;
					$sFound++;
					array_push($switchIDList,$switchN);
					array_push($switchNickList,$switchNick);
					array_push($switchOnList,$onDataOut);
					array_push($switchOffList,$offDataOut);
					array_push($switchRepeatList,$switchRepeat);
					array_push($switchFreqList,$switchFreq);
	
					${$switchN."onCode"} = $onDataOut;
					${$switchN."offCode"} = $offDataOut;
					${$switchN."freq"} = $switchFreq;
					${$switchN."repeat"} = $switchRepeat;
	
					$jsVariables .=
						"window['".$switchN."onCode'] = '".${$switchN."onCode"}."';\n".
						"window['".$switchN."offCode'] = '".${$switchN."offCode"}."';\n".
						"window['".$switchN."freq'] = '".${$switchN."freq"}."';\n".
						"window['".$switchN."repeat'] = '".${$switchN."repeat"}."';\n\n";
	
					$jsSwitchArray .= '"'.$switchN.'",';
	
					$func = '"'.$switchN.'","'.$switchN.'-S"';
	
					$actionsTable .=
						"<table width='100%' border='0' cellspacing='0' cellpadding='0' style='margin-left:auto;margin-right:auto;margin: 0px auto;margin-bottom: 5px;max-width:".$sets['SETTINGS']['maxWidth']."px;'>
	                                                <tr class='actionRow'>
	                                                        <td style='width:50%'>
	                                                                <div class='actionLeft' id='".$switchN."-S' onclick='switchState(".$func.");'>DO NOTHING</div>
	                                                        </td>
	                                                        <td>
	                                                                <div class='actionRight'>".str_replace('_',' ',$switchNick)."</div>
	                                                        </td>
	                                                </tr>
	                                        </table>";
				}

                        }
                }
		}

		$jsSwitchArray .= "'end'";

		$count = 0;
		$length = count($switchIDList);
		while($count < $length){
			$count++;
		}
        }

	if(isset($_GET["view"])){
		$currentView = "#".$_GET["view"];
	}
	else{
		$currentView = "#actionAdd";
	}

	$title="ACTIONS";
	$logoColor = "off";
	$noWatchCheck = "1";
	$gear = "link";
        $gearLink = "<td align='right'><a href='index.php'><img src='images/home.png' id='homeIcon'></a></td>";

?>
<html>
	<!-- Include Header -->
	<?php include("header.php"); ?>

	<body id="bodyMain">
	<!------------------------------------------------------->
		<div id="wrapper"> <!-- We remove the header margin to make the control list fit flush. -->
			<div id="actionsConfirmWrap">
				<table <?php echo $tabStretch;?>>
					<tr>
						<td id="actionAddConfirm">Action added. <a href="index.php" style="color:#ffffff;">Return to control?</a></td>
					</tr>
				</table>
			</div>
				<div id="actionMenu" style="display:none;">
					<table <?php echo $tabStretch;?>>
						<tr>
							<td>
								<div class="section group">
									<div class="col span1conf" id="addActionButton" onclick="switchView('#actionAdd');">
										<table style="width:100%;height:100%;">
											<tr>
												<td class="confIcon"><img src="images/action.png" style="width:45px;height:45px;margin-bottom: 5px;"><br>ADD</td>
											</tr>
										</table>
									</div>
									<div class="col span1conf" id="editActionButton" onclick="switchView('#actionEdit');">
										<table style="width:100%;height:100%;">
											<tr>
												<td class="confIcon"><img src="images/edit.png" style="width:45px;height:45px;margin-bottom: 5px;"><br>EDIT</td>
											</tr>
										</table>
									</div>
								</div>
							</td>
						</tr>
					</table>
				</div>
				<div id="actionAdd" style="display:none;">

					<table <?php echo $tabStretch;?>>
						<tr>
							<td id="confSubtitle"><img src="images/action.png" style="width: 26px;height: 26px;margin-right: 5px;">ADD ACTION</td>
						</tr>
					</table>

					<table <?php echo $tabStretch;?>>
                        		        <tr>
                        		                <td>
                        		                        <div class="h2" style="text-align:left;margin-top:0px;margin-bottom:20px;font-family: 'Dosis',sans-serif;font-size: 18px;"><span class="highlightOff" style="font-size: 24px;">"Actions" can control many predefined Switches at once.</span><br><br>Use an action to "<span class="highlightOff">turn all lights on</span>", or alternate which lights are on to "<span class="highlightOff">watch a movie</span>". Public Actions are shown on the control page, Private Actions are available only to software control, like <span class="highlightOff">Scheduled Events</span>.</div>
                        		                </td>
                        		        </tr>
                        		</table>

					<?php echo $actionsTable;?>

					<table <?php echo $tabStretch;?>>
						<tr class="settingRow">
							<td class="settingLeft" style="padding-right:0px;">
								<div class="section group">
                                                                        <div class="col span1conf2">
										<div class="settingInlineNameWrap">
											<input type="text" class="settingText" name="actionName" style="font-size: 18px;" placeholder="MAIN LIGHTS ON"></input><div class="settingInlineName">ACTION NAME</div>
										</div>
                                                                        </div>
                                                                        <div class="col span1conf2">
										<div class="settingInlineNameWrap">
											<select name="actionType" class="settingText">
                                                                                                <option value="$SHOW">PUBLIC</option>
                                                                                                <option value="$HIDE">PRIVATE</option>
                                                                                        </select>
                                                                                        <div class="settingInlineName">ACTION TYPE</div>
										</div>
                                                                        </div>
                                                                </div>
								<div class="section group">
                                                                        <div class="col span1conf2">
										<div class="settingInlineNameWrap">
										</div>
                                                                        </div>
                                                                        <div class="col span1conf2">
										<div class="settingInlineNameWrap">
											<button class="button" style="background-color:<?php echo $sets['SETTINGS']['offColor'];?>;width:100%;font-size: 17px;" onclick="sendDataActionAdd();">SUBMIT</button><br>
										</div>
                                                                        </div>
                                                                </div>
							</td>
						</tr>
					</table>

				</div>
				<div id="actionEdit" style="display:none;">
					<table <?php echo $tabStretch;?>>
						<tr>
							<td id="confSubtitle"><img src="images/edit.png" style="width: 26px;height: 26px;margin-right: 5px;">EDIT ACTIONS</td>
						</tr>
						<tr>
							<td id="confBack" onclick="switchView('#actionMenu');"> < BACK TO MENU</td>
						</tr>
					</table>
					<table <?php echo $tabStretch;?>>
						<tr class="settingRow">
							<td class="settingLeft">
								<div class="section group">
                                                                        <div class="col span1conf2">
										<div class="settingInlineNameWrap">
											<input type="text" class="settingText" name="rgbLed" value="<?php echo $sets['SETTINGS']['rgbLed'];?>"></input><div class="settingInlineName">RGB LED</div>
										</div>
                                                                        </div>
                                                                        <div class="col span1conf2">
										<div class="settingInlineNameWrap">
											<input type="text" class="settingText" name="masterFreq" value="<?php echo $sets['SETTINGS']['masterFreq'];?>"></input><div class="settingInlineName">MASTER FREQUENCY</div>
										</div>
                                                                        </div>
                                                                </div>
								<div class="section group">
                                                                        <div class="col span1conf2">
										<div class="settingInlineNameWrap">
										</div>
                                                                        </div>
                                                                        <div class="col span1conf2">
										<div class="settingInlineNameWrap">
											<button class="button" style="background-color:<?php echo $sets['SETTINGS']['offColor'];?>;width:100%;" onclick="sendData();">SUBMIT</button><br>
										</div>
                                                                        </div>
                                                                </div>
							</td>
						</tr>
					</table>
					<input type="hidden" name="updated" value="true"></input>
				</div>
			</form>
		</div>
	<!------------------------------------------------------->

	<script>

		window.currentView = <?php echo json_encode($currentView);?>;
		window.switches = [<?php echo $jsSwitchArray;?>];

		<?php echo $jsVariables;?>

		$(document).ready(function(){
			$(window.currentView).fadeIn("fast");
		});

		$(document).on("keypress", function (e) {
			if (e.which == 13 && window.currentView != "#actionMenu" && window.currentView == "#actionAdd" ) {
				sendDataActionAdd();
			}
		});

		function sScroll(div){
                        $('html, body').animate({
                                scrollTop: $(div).offset().top-5
                        }, 500);
                }

		function switchView(newView){
			window.history.replaceState('page2', 'Title', '/actions.php?view='+newView.slice(1));
			$("#confConfirmWrap").fadeOut("fast");
			$("#confConfirm").fadeOut("fast");
			$(window.currentView).fadeOut("fast",function(){
				$(newView).fadeIn("fast",function(){
					if(newView == "#confMenu"){
						sScroll("#whole");
					}
					else{
						sScroll(newView);
					}
				});
			});
			window.currentView = newView;
		}

		function switchState(id,idc){
			window[id+"state"] = document.getElementById(idc).innerHTML;
			if(window[id+"state"] == "DO NOTHING"){
				window[id+"state"] = "TURN ON";
				document.getElementById(idc).innerHTML = window[id+"state"];
				document.getElementById(idc).style.color = <?php echo json_encode($sets['SETTINGS']['onColor']);?>;
			}
			else if(window[id+"state"] == "TURN ON"){
				window[id+"state"] = "TURN OFF";
				document.getElementById(idc).innerHTML = window[id+"state"];
				document.getElementById(idc).style.color = <?php echo json_encode($sets['SETTINGS']['offColor']);?>;
			}
			else if(window[id+"state"] == "TURN OFF"){
				window[id+"state"] = "DO NOTHING";
				document.getElementById(idc).innerHTML = window[id+"state"];
				document.getElementById(idc).style.color = "#666666";
			}

			sumSwitches();
		}

		function sumSwitches(){
			outString = "";
			window.switches.forEach(function(entry){
				if(entry != "end"){
					if(window[entry+"state"] == "TURN ON"){
						outString += entry+" | 1 | COM-RF:"+window[entry+"freq"]+" python/tx "+window[entry+"onCode"]+" "+window[entry+"repeat"]+"\n";
					}
					if(window[entry+"state"] == "TURN OFF"){
						outString += entry+" | 0 | COM-RF:"+window[entry+"freq"]+" python/tx "+window[entry+"offCode"]+" "+window[entry+"repeat"]+"\n";
					}
				}
			});
			return outString;
		}

		function sendDataActionAdd(){
			actionData = "*"+document.getElementsByName("actionName")[0].value+"\n"+document.getElementsByName("actionType")[0].value+"\n"+sumSwitches();
			actionDataURL = encodeURIComponent(actionData);
			$.ajax({
				url: "actions.php",
				type:'POST',
				data:
				{
					actionFile : actionData,
					updated : "true"
				},
				success: function(msg)
				{
					sScroll("#whole");
					$("#actionConfirmWrap").fadeIn("fast");
					$("#actionAddConfirm").fadeIn("fast");
				}
			});
		}

	</script>

	<!-- Include Footer -->
	<?php include("footer.php");?>
	</body>
</html>
