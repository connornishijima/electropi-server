<?php
	include("includes/configFile.php");
	include("includes/functions.php");
        include("includes/strings.php");

	if(isset($_POST["eventName"])){
		$eventName = $_POST["eventName"];
		$eventTime = $_POST["eventTime"];
		$eventType = $_POST["eventType"];
		$eventAID = $_POST["eventAID"];

		$eventTime = explode(":",$eventTime);
		$hour = $eventTime[0];
		$min = $eventTime[1];
		$rest = $eventTime[2];
		$suffix = "AM";
		if($rest == " PM" || $rest == "00 PM"){
			$suffix = "PM";
			if(intval($hour) != 12){
				$hour = intval($hour) + 12; 
			}
		}
		else{
			if(intval($hour) == 12){
				$hour = "00";
			}
		}

		$eventString = $eventName."|".$eventType."|".$hour."|".$min."|".$eventAID."\n";

		file_put_contents("python/event.list",$eventString,FILE_APPEND);
	}

	$actionsPresent = "false";
	$actionsDrop = "";
	$actions = scandir("data/actions");
	if(count($actions) < 3){
		$actionsDrop = "<option value='noactions'>NO ACTIONS!</option>";
		$warningString = "Events require at least one Action to function. <a href='actions.php'>Click here to set up an action now.</a>";
	}
	else{
		$actionsPresent = "true";
		$actionNickList = [];
		$actionIDList = [];
		foreach($actions as &$action){
			if($action != "." && $action != ".."){
				$data = file_get_contents("data/actions/".$action);
				$array = explode("\n", $data);
				$actionNick = $array[0];
				$actionNick = substr($actionNick,1);
				$actionID = explode(".",$action)[0];
				array_push($actionNickList,$actionNick);
				array_push($actionIDList,$actionID);
			}
		}
		$count = 0;
		$length = count($actionIDList);
		while($count < $length){
			$actionsDrop .= "<option value='".$actionIDList[$count]."'>".$actionNickList[$count]."</option>";
			$count++;
		}
	}

	if(isset($_GET["view"])){
		$currentView = "#".$_GET["view"];
	}
	else{
		$currentView = "#eventAdd";
	}

	$title="EVENTS";
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
			<div id="eventConfirmWrap">
				<table <?php echo $tabStretch;?>>
					<tr>
						<td id="eventAddConfirm">Event added. <a href="index.php" style="color:#ffffff;">Return to control?</a></td>
					</tr>
				</table>
			</div>
				<div id="eventMenu" style="display:none;">
					<table <?php echo $tabStretch;?>>
						<tr>
							<td>
								<div class="section group">
									<div class="col span1conf" id="addEventButton" onclick="switchView('#eventAdd');">
										<table style="width:100%;height:100%;">
											<tr>
												<td class="confIcon"><img src="images/event.png" style="width:45px;height:45px;margin-bottom: 5px;"><br>ADD</td>
											</tr>
										</table>
									</div>
									<div class="col span1conf" id="editEventButton" onclick="switchView('#eventEdit');">
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
				<div id="eventAdd" style="display:none;">
					<table <?php echo $tabStretch;?>>
						<tr>
							<td id="confSubtitle"><img src="images/event.png" style="width: 26px;height: 26px;margin-right: 5px;">ADD EVENT</td>
						</tr>
					</table>

					<table <?php echo $tabStretch;?>>
                                                <tr>
                                                        <td>
                                                                <div class="h2" style="text-align:left;margin-top:0px;margin-bottom:20px;font-family: 'Dosis',sans-serif;font-size: 18px;"><span class="highlightOff" style="font-size:24px;">"Events" are used to execute the chosen Action at the specified time.</span><br><br>You could schedule the lights to come on just before you are home, or govern the power to a child's television. <span class="highlightOff">Events can be daily or non-recurring</span>.
                                                        </td>
                                                </tr>
                                        </table>

					<table <?php echo $tabStretch;?>>
						<tr class="settingRow">
							<td class="settingLeft">
								<div class="section group">
                                                                        <div class="col span1conf2">
										<div class="settingInlineNameWrap">
											<input type="text" class="settingText" name="eventName" placeholder="JOHN BEDTIME"></input><div class="settingInlineName">EVENT NAME</div>
										</div>
                                                                        </div>
                                                                        <div class="col span1conf2">
										<div class="settingInlineNameWrap">
											<input type="time" class="settingText" name="eventTime" value="00:00"></input><div class="settingInlineName">EVENT TIME</div>
										</div>
                                                                        </div>
                                                                </div>
								<div class="section group">
                                                                        <div class="col span1conf2">
										<div class="settingInlineNameWrap">
											<select name="eventAID" class="settingText">
												<?php echo $actionsDrop;?>
											</select>
											<div class="settingInlineName">EVENT ACTION</div>
										</div>
                                                                        </div>
                                                                        <div class="col span1conf2">
										<div class="settingInlineNameWrap">
											<select name="eventType" class="settingText">
												<option value="PERM">EVERY DAY</option>
												<option value="TEMP">ONCE</option>
											</select>
											<div class="settingInlineName">EVENT TYPE</div>
										</div>
                                                                        </div>
                                                                </div>
								<div class="section group">
                                                                        <div class="col span1conf2">
										<div class="settingInlineNameWrap">
											<button class="button" style="background-color:<?php echo $sets['SETTINGS']['offColor'];?>;width:100%;" onclick="sendDataEventAdd();">SUBMIT</button><br>
										</div>
                                                                        </div>
                                                                        <div class="col span1conf2">
										<div class="settingInlineNameWrap">
										</div>
                                                                        </div>
                                                                </div>
							</td>
						</tr>
					</table>
					<input type="hidden" name="updated" value="true"></input>
				</div>
				<div id="eventEdit" style="display:none;">
					<table <?php echo $tabStretch;?>>
						<tr>
							<td id="confSubtitle"><img src="images/edit.png" style="width: 26px;height: 26px;margin-right: 5px;">EDIT EVENTS</td>
						</tr>
						<tr>
							<td id="confBack" onclick="switchView('#eventMenu');"> < BACK TO MENU</td>
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

		$(document).ready(function(){
			$(window.currentView).fadeIn("fast");
		});

		$(document).on("keypress", function (e) {
			if (e.which == 13 && window.currentView != "#eventMenu" && window.currentView == "#eventAdd" ) {
				sendDataEventAdd();
			}
		});

		function sScroll(div){
                        $('html, body').animate({
                                scrollTop: $(div).offset().top-5
                        }, 500);
                }

		function switchView(newView){
			window.history.replaceState('page2', 'Title', '/events.php?view='+newView.slice(1));
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

		function sendDataEventAdd(){
			if(<?php echo json_encode($actionsPresent);?> == "true"){
				$.ajax({
					url: "events.php",
					type:'POST',
					data:
					{
						eventName : document.getElementsByName("eventName")[0].value,
						eventType : document.getElementsByName("eventType")[0].value,
						eventTime : document.getElementsByName("eventTime")[0].value,
						eventAID : document.getElementsByName("eventAID")[0].value,
						updated : "true"
					},
					success: function(msg)
					{
						sScroll("#whole");
						$("#eventConfirmWrap").fadeIn("fast");
						$("#eventAddConfirm").fadeIn("fast");
					}
				});
			}
		}

	</script>

	<!-- Include Footer -->
	<?php include("footer.php");?>
	</body>
</html>
