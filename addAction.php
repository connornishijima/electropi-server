<?php

$title = "NOTITLE";

// include settings.php to access conf/settings.conf
include("settings.php");
$wemoSupport = readSetting("WEMO_SUPPORT");

// This generates a unique ID for each action of 10 characters
function generateUID($length = 10){
                $chars = '0123456789ABCDEFGHIJIKLMNOPQRSTUVWXYZ';
                $randomString = '';
                for($i = 0; $i < $length; $i++){
                        $randomString .= $chars[rand(0,strlen($chars) - 1)];
                }
                return $randomString;
        }

$actionUID = generateUID();
$actionString = "#--$actionUID--\n";

if(isset($_GET["actionName"])){
	$actionName = $_GET["actionName"];
	foreach ($_GET as $key => $value){
		if($key != "actionName"){
			$actionString = $actionString . $value . "\n";
		}
	}
	touch("conf/actions/$actionUID.txt");
	// Add actions data to file
	file_put_contents("conf/actions/$actionUID.txt", (string)$actionString, LOCK_EX);
	$actionsList = file_get_contents("conf/actions/actions.list");
	$actionsList = $actionsList . $actionName . "|" . $actionUID . "\n";
	// Add action file to list
	file_put_contents("conf/actions/actions.list", $actionsList, LOCK_EX);
	header("Location: index.php");
}

$uiScale = floatval(readSetting("UI_SCALE"));
$uiScale = $uiScale + 0.2;
$notifications = readSetting("NOTIFICATIONS");
$onColor = readSetting("ONCOLOR");
$offColor = readSetting("OFFCOLOR");

$notification = trim(file_get_contents("misc/notification.txt"));

// Read list of all RF switches
$subject = file_get_contents("conf/appliances.txt");


// If no switches are set up, let's add a new one.
if(strlen($subject) < 10){
	header("Location: addApp.php");
}

$tableString = " ";
$formString = " ";

$count = 0;

// READ EACH LINE OF APPLIANCE LIST and populate table.
foreach(preg_split("/((\r?\n)|(\r\n?))/", $subject) as $line){
	if($line[0] != "#"){
	$subLine = explode("|",$line); // EXPLODE LINE INTO PROPERTIES
	$appName = trim($subLine[0]); // APP NAME
	$appState = trim($subLine[1]); // APP POWER STATE
	$appOnCode = "'" . trim($subLine[2]) . "'"; // APP ON CODE
	$appOffCode = "'" . trim($subLine[3]) . "'";// APP OFF CODE
	$appRepeat = trim($subLine[7]); // APP TX REPEAT
	$appUID = trim($subLine[8]); // APP UNIQUE ID
	$appUIDnoQUOTE = str_replace("'","",$appUID);
	$opacity = 0;
	$opacity2 = 1;

        $stateString = file_get_contents("conf/applianceStates.txt");
        $apps = explode("\n",$stateString);
        foreach ($apps as &$app) {
                $pieces = explode("|",$app);
                if($pieces[2] == $appUID){
                        $appState = $pieces[1];
                }
        }


	if($appState == "1"){
		$opacity = 0;
		$opacity2 = 1;
	}
	else{
		$opacity = 1;
		$opacity2 = 0;
	}

	if(strlen($appName) > 1){ // IF LINE IS VALID
		// $tableString IS THE HTML TABLE RENDERED ON THE PAGE
		$countA = "'A" . $count . "state'";
		$countB = "'A" . $count . "'";
		$countC = "'A" . $count . "F'";
		$image = "url('conf/appImages/" . $appUIDnoQUOTE . ".jpg');";
		$tableString = $tableString . '<tr id="applianceRow">
					<td class="beta" id="appPreview" style="background-image:' . $image . '"></td>
	                                <td id="applianceName" valign="middle">' . $appName . '</td>
	                                <td id="horizontalSpace"></td>
	                                <td id="powerBtn" onclick=""><div id="A' . $count . '" onclick="haptic();cycleState(' . $countB . ');" style="background-color:#777777;"><div class="powerIcon"><img id="A' . $count . 'pwr" src="images/power.png" width="64px" height="64px"></div></td>
	                        </tr>
	                        <tr id="verticalSpace"></tr>';

		$formString = $formString . '<input type="hidden" id=' . $countC . ' name="' . $appUIDnoQUOTE . '" value="' . $appUIDnoQUOTE . '-X"></input>';
	}

	$count = $count + 1;
	}
}

// GET LIST OF ALL CURRENT ACTIONS
$subject = file_get_contents("conf/actions.txt");
$subject = explode("\n",$subject);
foreach($subject as &$action){
	if(substr($action,0,1) != "#" && strlen($action) > 5){ // IF NOT COMMENT AND VALID
		$action = explode("|",$action);
		foreach($action as &$pieces){
			$aid = $pieces[0];
			$nickname = $pieces[1];
			$todo = $pieces[2];
		}
	}
}

// POPULATE LIST OF WEMO SWITCHES
if($wemoSupport == "ENABLED"){
	$wemoString = "";
	$subject = file_get_contents("conf/wemo.list");
	if(strlen($subject)<5){
		$wemoString = '<tr id="wemoRow">
	                <td id="wemoName" valign="middle" style="text-align:center;">WEMO SWITCHES WILL POPULATE HERE AUTOMATICALLY!&nbsp &nbsp;<a href="#" style="color:'.$offColor.';" onclick="wemoRepop();">CLICK&nbsp;HERE&nbsp;TO&nbsp;MANUALLY&nbsp;UPDATE</a></td>
	        </tr>
	        <tr id="verticalSpace"></tr>';
	}
	else{
	$subject = explode("\n",$subject);
	foreach($subject as &$wemo){
	        if(strlen($wemo) > 5){ // IF LINE IS VALID
			$countA = "'A" . $count . "state'";
			$countB = "'A" . $count . "'";
			$countC = "'A" . $count . "F'";

			$wemo = explode("|",$wemo);
			$wemoID = $wemo[0];
			$wemoIDAlt = "'".$wemo[0]."'";
			$wemoName = $wemo[1];
			$wemoNameAlt = "'".$wemo[1]."'";
			$wemoState = $wemo[2];
			if($wemoState == "1"){
				$opacity = 0;
				$opacity2 = 1;
			}
			else if($wemoState == "0"){
				$opacity = 1;
				$opacity2 = 0;
			}
			$wemoString = $wemoString . '<tr id="applianceRow">
					<td class="beta" id="appPreview" style="background-image:' . $image . '"></td>
					<td id="applianceName" valign="middle">' . strtoupper($wemoName) . '</td>
					<td id="horizontalSpace"></td>
	                                <td id="powerBtn" onclick=""><div id="A' . $count . '" onclick="haptic();cycleState(' . $countB . ');" style="background-color:#777777;"><div class="powerIcon"><img id="A' . $count . 'pwr" src="images/power.png" width="64px" height="64px"></div></td>
				</tr>
				<tr id="verticalSpace"></tr>';

			$jsString = $jsString .	"window." .preg_replace('/\s+/', '_', $wemoName) . " = " . $wemoState . ";\n";

			$formString = $formString . '<input type="hidden" id=' . $countC . ' name="' . $wemoID . '" value="' . $wemoID . '-X"></input>';
		}
		$count = $count + 1;
	}
	}
}

?>

<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=<?php echo $uiScale; ?>,maximum-scale=<?php echo $uiScale; ?>, user-scalable=no"/>
		<title>ElectroPi Control</title>

		<?php include("header.php");?>

		<script type="text/javascript">

			window.onColor = <?php echo json_encode($onColor);?>;
			window.offColor = <?php echo json_encode($offColor);?>;
			window.ajaxPause = 0;
			easterEggs = "1";

			Image1= new Image(64,64);
			Image1.src = "images/logostatic.png";

			var logoColor = "green";
			window.setTimeout(function () {
				var intervalID = window.setInterval(function () {
					if(easterEggs == "1"){
						if(logoColor == "red"){
							logoColor = "green";
							$("#logoText").animate({color: "<?php echo $onColor; ?>" },300);
						}
						else if(logoColor == "green"){
		                                        logoColor = "red";
		                                        $("#logoText").animate({color: "<?php echo $offColor; ?>" },300);
		                                }
					}
				}, 10800);
			}, 400);


			$(function(){  // $(document).ready shorthand
				if(<?php echo json_encode($animations);?> == "ENABLED"){
	                                $('#subtitle').hide().fadeIn('slow');
					window.setTimeout(function () {
						$("#logoText").animate({color: "<?php echo $onColor; ?>" },300);
					}, 400);
				}
				else{
					$("#logoText").animate({color: "<?php echo $onColor; ?>" },0);
				}
                        });

			var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
			var h = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);

			<?php echo $jsString;?>

			//Function to convert hex format to a rgb color
			function rgb2hex(rgb){
				rgb = rgb.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i);
				return (rgb && rgb.length === 4) ? "#" +
				("0" + parseInt(rgb[1],10).toString(16)).slice(-2) +
				("0" + parseInt(rgb[2],10).toString(16)).slice(-2) +
				("0" + parseInt(rgb[3],10).toString(16)).slice(-2) : '';
			}

			function cycleState(div){
				app = document.getElementById(div);
				appForm = document.getElementById(div + "F");
				color = rgb2hex(app.style.backgroundColor).toUpperCase();
				colorB = app.style.backgroundColor;
				v = appForm.value;

				if(color.toUpperCase() == "#777777"){
					color = window.onColor;
					v = v.substring(0, v.length - 1) + "1";
				}
				else if(color.toUpperCase() == window.onColor.toUpperCase()){
					color = window.offColor;
					v = v.substring(0, v.length - 1) + "0";
				}
				else if(color.toUpperCase() == window.offColor.toUpperCase()){
					color = "#777777";
					v = v.substring(0, v.length - 1) + "X";
				}
				app.style.backgroundColor = color;
				appForm.value = v;
			}


			function commencerMain() {
				$("#notify").hide();
			}

		</script>

	</head>

	<body id="body" onLoad="commencerMain();">

		<div id="contain">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: <?php echo $maxWidth;?>px;">
			<tr><td style="font-size: 36px;padding-bottom: 19px;text-align: center;">ADD ACTION</td></tr>
		</table>
		<table class="beta" width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: <?php echo $maxWidth;?>px;">
			<tr><td style="font-size: 36px;padding-bottom: 19px;text-align: right;">ON</td><td style="font-size: 36px;padding-bottom: 19px;text-align: center;">OFF</td><td style="font-size: 36px;padding-bottom: 19px;text-align: left;">NONE</td></tr>
		</table>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: <?php echo $maxWidth;?>px;">
			<?php echo $tableString;?>
			<?php echo $wemoString;?>
		</table>
		<br>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:auto;margin-right:auto;max-width: <?php echo $maxWidth;?>px;">
			<tr>
				<td>
					<form action="addAction.php" method="GET">
						<input type="text" id="setText" name="actionName" style="background-color: #181818;width: 80%;height: 50px;padding-left:10px;" placeholder="ENTER A NAME..." required></input>
				</td>
				<td>
						<?php echo $formString;?>
						<input class="button" type="submit" value="Save Action" style="float:right;height: 50px;border: none;background-color: <?php echo $offColor; ?>;font-family: 'Oswald', sans-serif;font-size: 24px;"></input>
					</form>
				</td>
			</tr>
		</table>

		</div>
		<div id="dummy" style="display:none"></div>
	</body>
</html>

<?php include("footer.php");?>


