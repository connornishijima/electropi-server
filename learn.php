<?php
	include("includes/configFile.php");
	include("includes/functions.php");
        include("includes/strings.php");

	$title="Learn";

	$noWatchCheck = "1";
	file_put_contents("python/decode.ON","");
	file_put_contents("python/decode.OFF","");

	function get_random_string($valid_chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ", $length = 5)
	{
	    // start with an empty random string
	    $random_string = "";

	    // count the number of chars in the valid chars string so we know how many choices we have
	    $num_valid_chars = strlen($valid_chars);

	    // repeat the steps until we've created a string of the right length
	    for ($i = 0; $i < $length; $i++)
	    {
	        // pick a random number from 1 up to the number of valid chars
	        $random_pick = mt_rand(1, $num_valid_chars);

	        // take the random character out of the string of valid chars
	        // subtract 1 from $random_pick because strings are indexed starting at 0, and we started picking at 1
	        $random_char = $valid_chars[$random_pick-1];

	        // add the randomly-chosen char onto the end of our string so far
	        $random_string .= $random_char;
	    }

	    // return our finished random string
	    return $random_string;
	}

	if(isset($_POST["updated"])){
		$updated = "true";
		$nick = str_replace(" ","_",$_POST["nick"]);
		$freq = $_POST["freq"];
		$onCode = $_POST["onCode"];
		$offCode = $_POST["offCode"];

		$UID = get_random_string();

		mkdir("data/switches/".$UID, 0777, true);
		copy("presets/learned-default/source.php", "data/switches/".$UID."/source.php");

		file_put_contents("data/switches/".$UID."/info.ini","[HTML]\nposition = ".(count(scandir("data/switches"))-2)."\nstate = 0\n\n[ID]\nnickname = ".$nick."\n\n[CONTROL]\noncodedata = on.bin\noffcodedata = off.bin\nrepeat = 10\nfreq = ".$freq."\n");
		file_put_contents("data/switches/".$UID."/on.bin",$onCode);
		file_put_contents("data/switches/".$UID."/off.bin",$offCode);
		header("Location: index.php");
	}
?>
<html>
	<!-- Include Header -->
	<?php include("header.php"); ?>

	<body id="bodyMain">
	<!------------------------------------------------------->
		<div id="wrapper" style="margin-top: -10px;"> <!-- We remove the header margin to make the control list fit flush. -->
			<div id="setup">
			<table <?php echo $tabStretch;?>>
				<tr>
					<td>
						<div class="h1" style="text-align:left;margin-top:20px;">LEARN RF SWITCH</div>
						<div class="h2" style="text-align:left;margin-top:10px;margin-bottom:20px;font-family: 'Dosis',sans-serif;font-size: 18px;">ElectroPi is a learning system! Just <span class="highlightOn">stand near the ElectroPi board</span>, hold a button on your RF remote, and you'll have a switch set up in no time.</div>
					</td>
				</tr>
			</table>
			<table <?php echo $tabStretch;?>>
				<tr>
					<td class="freqContainer">
						<div id="315" class="freqLeft" onclick="setFreq('315');">
							315MHz
						</div>
					</td>
					<td class="freqContainer">
						<div id="433" class="freqRight" onclick="setFreq('433');">
							433MHz
						</div>
					</td>
				</tr>
			</table>
			<div id="codes" style="display:none;margin-top:20px;">
				<table <?php echo $tabStretch;?>>
					<tr>
						<td>
							<div class="section group">
								<div id="onCode" class="col span1">
									<div class="colHeader" style="font-size:18px;color:<?php echo $sets['SETTINGS']['onColor'];?>">
										ON CODE
									</div>
									<div class="colContent">
										<div id="learnON">
											<button class="button" style="background-color:<?php echo $sets['SETTINGS']['onColor'];?>;width:100%;" onclick="learnCode('ON');">LEARN</button><br>
										</div>
										<div id="infoON">
												Hold the ON button on your remote that you'd like to record, then press LEARN!
											</div>
										<div id="loadingON" style="display:none;">
											LEARNING...KEEP THAT BUTTON HELD!
										</div>
										<div id="gotitON" style="display:none;background-color: <?php echo $sets['SETTINGS']['onColor'];?>;">
											GOT IT!
										</div>
										<div id="codeON" style="display:none;">
											<iframe id="iframeON" scrolling="no" seamless="seamless"></iframe>
										</div>
									</div>
								</div>
								<div id="offCode" class="col span1" style="display:none;">
									<div class="colHeader" style="font-size:18px;color:<?php echo $sets['SETTINGS']['offColor'];?>">
										OFF CODE
									</div>
									<div class="colContent">
										<div id="learnOFF">
											<button class="button" style="background-color:<?php echo $sets['SETTINGS']['offColor'];?>;width:100%;" onclick="learnCode('OFF');">LEARN</button><br>
										</div>
										<div id="infoOFF">
												Hold the OFF button on your remote that you'd like to record, then press LEARN!
											</div>
										<div id="loadingOFF" style="display:none;">
											LEARNING...KEEP THAT BUTTON HELD!
										</div>
										<div id="gotitOFF" style="display:none;background-color: <?php echo $sets['SETTINGS']['offColor'];?>;">
											GOT IT!
										</div>
										<div id="codeOFF" style="display:none;">
											<iframe id="iframeOFF" scrolling="no" seamless="seamless"></iframe>
										</div>
									</div>
								</div>
							</div>
							<div id="nickname" style="display:none;">
								<div class="h3">
									Now nickname the appliance you're switching:
								</div>
								<form action="learn.php" method="POST">
									<input type="hidden" name="updated" value="true"></input>
									<input type="hidden" id="onCodeForm" name="onCode" value="X"></input>
									<input type="hidden" id="offCodeForm" name="offCode" value="X"></input>
									<input type="hidden" id="freqForm" name="freq" value="X"></input>

									<input type="text" class="settingText" name="nick" style="margin-top:10px;width:100%;text-align:center;font-size: 24px;"></input><br>
									<div style="width:100%;max-width:480px;margin-left: auto;margin-right: auto;">
										<input type="submit" value="ADD SWITCH" class="button" style="margin-top:10px;width:100%;"></input>
									</div>
									<input type="hidden" id="onCodeForm" value="X"></input>
								</form>
							</div>
						</td>
					</tr>
				</table>
			</div>
			</div>
		</div>
	<!------------------------------------------------------->

	<script type="text/javascript">

		$(function() {
			if(<?php echo json_encode($updated);?> == "true"){
				$("#setup").hide();
			}
		});

		onColor = <?php echo json_encode($sets["SETTINGS"]["onColor"]);?>;
		offColor = <?php echo json_encode($sets["SETTINGS"]["offColor"]);?>;
		window.frequency = 'x';
		window.learning = 0;
		window.currentState = "ON";
		window.onCode = 'X';
		window.offCode = 'X';

		function setFreq(freq){
			if(window.learning == 0){
				$("#codes").fadeIn(300);
				sScroll("#onCode");
				if(freq == "315"){
					window.frequency = "315";
					document.getElementById("freqForm").value = freq;
					$("#315").animate({color: "#242424" }, 0);
					$("#433").animate({color: offColor }, 0);
					$("#315").animate({backgroundColor: onColor }, 200);
					$("#433").animate({backgroundColor: "#242424" }, 200);
				}
				if(freq == "433"){
					window.frequency = "433";
					document.getElementById("freqForm").value = freq;
					$("#433").animate({color: "#242424" }, 0);
					$("#315").animate({color: offColor }, 0);
					$("#433").animate({backgroundColor: onColor }, 200);
					$("#315").animate({backgroundColor: "#242424" }, 200);
				}
			}
		}

		function learnCode(state){
			window.currentState = state;
			window.learning = 1;
			$("#info"+state).hide();
			$("#learn"+state).hide();
			$("#loading"+state).show();
			sendMessage("LEARN:"+window.frequency+":"+state);

			$(".freqLeft").animate({opacity:0.2 }, 200);
			$(".freqRight").animate({opacity:0.2 }, 200);
		}

		function learnedCode(message){
			s = window.currentState;
			msg = String(message)
			firstLine = msg.split('\n')[0];
			lines = msg.split('\n');
			lines.splice(0,2);
			code = lines.join('\n');
			code = code.replace(/(\r\n|\n|\r)/gm,"");
			if(firstLine == "GOOD!"){
				$("#iframe"+s).get()[0].contentWindow.document.write("<div onclick='this.focus();this.select();' style='width:100%; word-wrap: break-word;color:#484848;font-family:monospace;'>"+code+"</div>")
				$("#code"+s).show();
				$("#gotit"+s).show();
				$("#loading"+s).hide();
				if(s == "ON"){
					$("#offCode").fadeIn(300);
					sScroll("#offCode");
					document.getElementById("onCodeForm").value = code;
					window.onCode = code;
				}
				if(s == "OFF"){
					$("#nickname").fadeIn(300);
					sScroll("#nickname");
					document.getElementById("offCodeForm").value = code;
					window.offCode = code;
					sendMessage("FIX-SWITCHES");
					setTimeout(function(){
						testSwitch();
					}, 2000);
				}
			}
			else{
				$("#info"+s).show();
				$("#learn"+s).show();
				$("#loading"+s).hide();
				message = message.replace(/(\r\n|\n|\r)/gm,"");
				alert(message);
				if(s == "ON"){
					sScroll("#onCode");
				}
				if(s == "OFF"){
					sScroll("#offCode");
				}
			}
		}

		function sScroll(div){
			$('html, body').animate({
				scrollTop: $(div).offset().top
			}, 1000);
		}

		function testSwitch(){
			comOn = "COM-RF:"+window.frequency+" python/tx "+window.onCode+" 5";
			comOff = "COM-RF:"+window.frequency+" python/tx "+window.offCode+" 5";
			sendMessage(comOn);
			setTimeout(function(){
				sendMessage(comOff);
			},500);
			setTimeout(function(){
				sendMessage(comOn);
			},1000);
			setTimeout(function(){
				sendMessage(comOff);
			},1500);
			setTimeout(function(){
				sendMessage(comOn);
			},2000);
			setTimeout(function(){
				sendMessage(comOff);
			},2500);
		}

	</script>

	<!-- Include Footer -->
	<?php include("footer.php");?>
	</body>
</html>
