<?php
	$UID = basename(__DIR__);
	$switchUID = $UID;

	include("/var/www/includes/configFile.php");
	include("/var/www/includes/strings.php");

	$switchInfo = file_get_contents("/var/www/data/switches/".$UID."/info.ini");
	$switchInfo = explode("\n",$switchInfo);
	foreach($switchInfo as &$line){
		if(strlen($line) > 1){
			if($line[0] != "["){
				$line = explode(" = ",$line);
				$key = $line[0];

				$val = $line[1];
				if($key == "nickname"){
					$switchNick = $val;
					$switchNickPretty = str_replace("_"," ",$switchNick);
				}
				if($key == "state"){
					$switchState = $val;
					if($switchState == "1"){
						$switchColor = $sets['SETTINGS']["onColor"];
					}
					if($switchState == "0"){
						$switchColor = $sets['SETTINGS']["offColor"];
					}
				}
				if($key == "repeat"){
					${$UID."repeat"} = $val;
				}
				if($key == "freq"){
					${$UID."freq"} = $val;
				}
			}
		}
	}

	$onCodeData = file_get_contents("/var/www/data/switches/".$UID."/on.bin");
	$offCodeData = file_get_contents("/var/www/data/switches/".$UID."/off.bin");

	${"onCode".$UID} = $onCodeData;
	${"onCom".$UID} = "COM-RF:".${$UID."freq"}." python/tx ".${"onCode".$UID}." ".${$UID."repeat"};

	${"offCode".$UID} = $offCodeData;
	${"offCom".$UID} = "COM-RF:".${$UID."freq"}." python/tx ".${"offCode".$UID}." ".${$UID."repeat"};

	${"ajaxOn".$UID} = "AJAX-UPDATE:".$UID.":1";
	${"ajaxOff".$UID} = "AJAX-UPDATE:".$UID.":0";

?>

<script type="text/javascript">
	window[<?php echo json_encode($switchUID);?>+"state"] = <?php echo json_encode($switchState);?>;

</script>

<div id="switch<?php echo $UID;?>" style="-moz-user-select: none;-khtml-user-select: none;-webkit-user-select: none;-o-user-select: none; ">
<table <?php echo $tabStretch;?> class="switchRow">
	<tr>
		<td class="switchState"><div class="switchStateColor" id="<?php echo $switchNick;?>-C" style="background-color:<?php echo $switchColor;?>;"></div></td>
		<td class="switchTitleWrap" id="switchTitleWrap<?php echo $UID;?>" onclick="switchClick<?php echo $UID;?>('<?php echo $switchNick;?>','<?php echo $switchNick;?>-C','<?php echo $switchUID;?>','user');"><div id="<?php echo $switchNick;?>" class="switchTitle"><?php echo $switchNickPretty;?></div></td>
	</tr>
</table>
</div>
<div class="editMenu" id="editMenu<?php echo $UID;?>">
<table <?php echo $tabStretch;?> class="switchRow">
	<tr class="editRow">
		<td class="editPiece" style="color:<?php echo $sets['SETTINGS']['onColor'];?>">EDIT</td>
		<td class="editPiece"><a href="control.php?remove=<?php echo $UID;?>" style="color:<?php echo $sets['SETTINGS']['offColor'];?>">REMOVE</a></td>
		<td class="editPiece" onclick="hideMenu<?php echo $UID;?>();">CANCEL</td>
	</tr>
</table>
</div>
<script type="text/javascript">

function showMenu<?php echo $UID;?>(){
	$("#switch<?php echo $UID;?>").css("pointer-events","none");
	window.<?php echo $UID;?>restricted = 1;
	$("#editMenu<?php echo $UID;?>").fadeIn(200);
	$("#switch"+<?php echo json_encode($UID);?>).css("opacity","0.1");
}

function hideMenu<?php echo $UID;?>(){
	$("#switch<?php echo $UID;?>").css("pointer-events","all");
	window.<?php echo $UID;?>restricted = 0;
	$("#editMenu<?php echo $UID;?>").fadeOut(200);
	$("#switch"+<?php echo json_encode($UID);?>).css("opacity","1");
}

jQuery(function($){
    // how many milliseconds is a long press?
    var longpress = 300;
    // holds the start time
    var start;

    window.<?php echo $UID;?>restricted = 0;

    jQuery( "#switchTitleWrap"+<?php echo json_encode($UID);?> ).on( 'mousedown', function( e ) {
        start = new Date().getTime();
	$("#switch"+<?php echo json_encode($UID);?>).css("opacity","0.1");
    } );

    jQuery( "#switchTitleWrap"+<?php echo json_encode($UID);?> ).on( 'mouseleave', function( e ) {
        start = 0;
    } );

    jQuery( "#switchTitleWrap"+<?php echo json_encode($UID);?> ).on( 'mouseup', function( e ) {
        if ( new Date().getTime() >= ( start + longpress )  ) {
		showMenu<?php echo $UID;?>();
        } else {
		$("#switch"+<?php echo json_encode($UID);?>).css("opacity","1");
        }
    } );



    jQuery( "#switchTitleWrap"+<?php echo json_encode($UID);?> ).on( 'touchstart', function( e ) {
        start = new Date().getTime();
	$("#switch"+<?php echo json_encode($UID);?>).css("opacity","0.1");
    } );

    jQuery( "#switchTitleWrap"+<?php echo json_encode($UID);?> ).on( 'touchend', function( e ) {
        if ( new Date().getTime() >= ( start + longpress )  ) {
		showMenu<?php echo $UID;?>();
        } else {
		$("#switch"+<?php echo json_encode($UID);?>).css("opacity","1");
        }
    } );

});

function sendMessage(msg){
    // Wait until the state of the socket is not ready and send the message when it is...
    waitForSocketConnection(window.wsocket<?php echo $UID;?>, function(){
        console.log("message sent!!!");
        window.wsocket<?php echo $UID;?>.send(msg);
    });
}

// Make the function wait until the connection is made...
function waitForSocketConnection(socket, callback){
    setTimeout(
        function () {
            if (socket.readyState === 1) {
                console.log("Connection is made")
                if(callback != null){
                    callback();
                }
                return;

            } else {
                console.log("wait for connection...")
                waitForSocketConnection(socket, callback);
            }

        }, 5); // wait 5 milisecond for the connection...
}

	function parseMessage<?php echo $UID;?>(message){
		message = message.split(":");
		type = message[0];
		if(type == "AJAX"){
			uidUpdated = message[1];
			newState = message[2];
			if(uidUpdated == <?php echo json_encode($UID);?>){
				switchClick<?php echo $UID;?>('<?php echo $switchNick;?>','<?php echo $switchNick;?>-C','<?php echo $switchUID;?>','ajax',newState);
			}
		}
	}

	function switchClick<?php echo $UID;?>(div,divColor,switchUID,type,ajaxState){
		state = window[switchUID+"state"];

		divToChange1 = document.getElementById(div);
		divToChange2 = document.getElementById(divColor);
		if(type=="user"){
			if(window.<?php echo $UID;?>restricted == 0){
				if(state == 0){
					divToChange1.style.color = "#000000";
					divToChange1.style.backgroundColor = <?php echo json_encode($sets['SETTINGS']["pendingColor"]);?>;
					divToChange2.style.backgroundColor = <?php echo json_encode($sets['SETTINGS']["pendingColor"]);?>;
					sendMessage("<?php echo ${'ajaxOn'.$UID};?>");
					sendMessage("<?php echo ${'onCom'.$UID};?>");
				}
				else if(state == 1){
					divToChange1.style.color = "#000000";
					divToChange1.style.backgroundColor = <?php echo json_encode($sets['SETTINGS']["pendingColor"]);?>;
					divToChange2.style.backgroundColor = <?php echo json_encode($sets['SETTINGS']["pendingColor"]);?>;
					sendMessage("<?php echo ${'ajaxOff'.$UID};?>");
					sendMessage("<?php echo ${'offCom'.$UID};?>");
					}
				else{
					alert("STATE MISSING FROM "+div);
				}
			}
		}
		else if(type=="ajax"){
			if(ajaxState != state){
				if(state == 0){
					window[switchUID+"state"] = 1;
					divToChange1.style.color = <?php echo json_encode($sets['SETTINGS']["onColor"]);?>;
					divToChange1.style.backgroundColor = <?php echo json_encode($sets['SETTINGS']["onColor"]);?>;
					divToChange2.style.backgroundColor = <?php echo json_encode($sets['SETTINGS']["onColor"]);?>;
				}
				else if(state == 1){
					window[switchUID+"state"] = 0;
					divToChange1.style.color = <?php echo json_encode($sets['SETTINGS']["offColor"]);?>;
					divToChange1.style.backgroundColor = <?php echo json_encode($sets['SETTINGS']["offColor"]);?>;
					divToChange2.style.backgroundColor = <?php echo json_encode($sets['SETTINGS']["offColor"]);?>;
					}
				else{
					alert("STATE MISSING FROM "+div);
				}
				outState = window[switchUID+"state"];
				$("#"+div).animate({backgroundColor: "#181818" }, 400);
				$("#"+div).animate({color: "#AAA" }, 500);
			}
		}
	}

</script>
