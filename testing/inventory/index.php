<?php
	$error = "";
	$errorState = "none";

	$orderCount = intval(file_get_contents("order.count"));
	$countColor = "#484848";
	if($orderCount > 0){
		$countColor = "#00FFBE";
	}


	$pcbBack = "#111111";
	$pcbColor = "#cccccc";
	$headerBack = "#111111";
	$headerColor = "#cccccc";
	$txBack = "#111111";
	$txColor = "#cccccc";
	$rgbBack = "#111111";
	$rgbColor = "#cccccc";
	$capBack = "#111111";
	$capColor = "#cccccc";
	$resBack = "#111111";
	$resColor = "#cccccc";
	$antBack = "#111111";
	$antColor = "#cccccc";
	$smaBack = "#111111";
	$smaColor = "#cccccc";
	$volBack = "#111111";
	$volColor = "#cccccc";
	$woodsBack = "#111111";
	$woodsColor = "#cccccc";
	$helBack = "#111111";
	$helColor = "#cccccc";
	$tx433Back = "#111111";
	$tx433Color = "#cccccc";
	$hel433Back = "#111111";
	$hel433Color = "#cccccc";

//----------------------------------------------------------------------------
//	PCB

	$pcbOnHand = intval(file_get_contents("pcb.onhand"));
	$pcbInTransit = intval(file_get_contents("pcb.intransit"));
	$pcbNeeded = intval(file_get_contents("pcb.needed"));

	if(isset($_POST["pcbOFFSET"])){
		$pcbOFFSET = intval($_POST["pcbOFFSET"]);
		$pcbORDERED = intval($_POST["pcbORDERED"]);
		$pcbOnHand = $pcbOnHand + $pcbOFFSET;
		$pcbInTransit = $pcbInTransit - $pcbOFFSET;
		$pcbInTransit = $pcbInTransit + $pcbORDERED;

		file_put_contents("pcb.onhand",$pcbOnHand);
		file_put_contents("pcb.intransit",$pcbInTransit);
	}

	if(($pcbOnHand + $pcbInTransit) < $pcbNeeded){
		$error = $error . "NOT ENOUGH PCBs TO FULFILL ORDERS!<br>";
		$pcbBack = "#FF539C";
		$pcbColor = "#242424";
	}

//----------------------------------------------------------------------------
//----------------------------------------------------------------------------
//	HEADER

	$headerOnHand = intval(file_get_contents("header.onhand"));
	$headerInTransit = intval(file_get_contents("header.intransit"));
	$headerNeeded = intval(file_get_contents("header.needed"));

	if(isset($_POST["headerOFFSET"])){
		$headerOFFSET = intval($_POST["headerOFFSET"]);
		$headerORDERED = intval($_POST["headerORDERED"]);
		$headerOnHand = $headerOnHand + $headerOFFSET;
		$headerInTransit = $headerInTransit - $headerOFFSET;
		$headerInTransit = $headerInTransit + $headerORDERED;

		file_put_contents("header.onhand",$headerOnHand);
		file_put_contents("header.intransit",$headerInTransit);
	}

	if(($headerOnHand + $headerInTransit) < $headerNeeded){
		$error = $error . "NOT ENOUGH HEADERS TO FULFILL ORDERS!<br>";
		$headerBack = "#FF539C";
		$headerColor = "#242424";
	}

//----------------------------------------------------------------------------
//----------------------------------------------------------------------------
//	TX

	$txOnHand = intval(file_get_contents("tx.onhand"));
	$txInTransit = intval(file_get_contents("tx.intransit"));
	$txNeeded = intval(file_get_contents("tx.needed"));

	if(isset($_POST["txOFFSET"])){
		$txOFFSET = intval($_POST["txOFFSET"]);
		$txORDERED = intval($_POST["txORDERED"]);
		$txOnHand = $txOnHand + $txOFFSET;
		$txInTransit = $txInTransit - $txOFFSET;
		$txInTransit = $txInTransit + $txORDERED;

		file_put_contents("tx.onhand",$txOnHand);
		file_put_contents("tx.intransit",$txInTransit);
	}

	if(($txOnHand + $txInTransit) < $txNeeded){
		$error = $error . "NOT ENOUGH TXs TO FULFILL ORDERS!<br>";
		$txBack = "#FF539C";
		$txColor = "#242424";
	}

//----------------------------------------------------------------------------
//----------------------------------------------------------------------------
//	RGB LED

	$rgbOnHand = intval(file_get_contents("rgb.onhand"));
	$rgbInTransit = intval(file_get_contents("rgb.intransit"));
	$rgbNeeded = intval(file_get_contents("rgb.needed"));

	if(isset($_POST["rgbOFFSET"])){
		$rgbOFFSET = intval($_POST["rgbOFFSET"]);
		$rgbORDERED = intval($_POST["rgbORDERED"]);
		$rgbOnHand = $rgbOnHand + $rgbOFFSET;
		$rgbInTransit = $rgbInTransit - $rgbOFFSET;
		$rgbInTransit = $rgbInTransit + $rgbORDERED;

		file_put_contents("rgb.onhand",$rgbOnHand);
		file_put_contents("rgb.intransit",$rgbInTransit);
	}

	if(($rgbOnHand + $rgbInTransit) < $rgbNeeded){
		$error = $error . "NOT ENOUGH RGB LEDs TO FULFILL ORDERS!<br>";
		$rgbBack = "#FF539C";
		$rgbColor = "#242424";
	}

//----------------------------------------------------------------------------
//----------------------------------------------------------------------------
//	CAPACITOR

	$capOnHand = intval(file_get_contents("cap.onhand"));
	$capInTransit = intval(file_get_contents("cap.intransit"));
	$capNeeded = intval(file_get_contents("cap.needed"));

	if(isset($_POST["capOFFSET"])){
		$capOFFSET = intval($_POST["capOFFSET"]);
		$capORDERED = intval($_POST["capORDERED"]);
		$capOnHand = $capOnHand + $capOFFSET;
		$capInTransit = $capInTransit - $capOFFSET;
		$capInTransit = $capInTransit + $capORDERED;

		file_put_contents("cap.onhand",$capOnHand);
		file_put_contents("cap.intransit",$capInTransit);
	}

	if(($capOnHand + $capInTransit) < $capNeeded){
		$error = $error . "NOT ENOUGH CAPACITORS TO FULFILL ORDERS!<br>";
		$capBack = "#FF539C";
		$capColor = "#242424";
	}

//----------------------------------------------------------------------------
//----------------------------------------------------------------------------
//	RESISTOR

	$resOnHand = intval(file_get_contents("res.onhand"));
	$resInTransit = intval(file_get_contents("res.intransit"));
	$resNeeded = intval(file_get_contents("res.needed"));

	if(isset($_POST["resOFFSET"])){
		$resOFFSET = intval($_POST["resOFFSET"]);
		$resORDERED = intval($_POST["resORDERED"]);
		$resOnHand = $resOnHand + $resOFFSET;
		$resInTransit = $resInTransit - $resOFFSET;
		$resInTransit = $resInTransit + $resORDERED;

		file_put_contents("res.onhand",$resOnHand);
		file_put_contents("res.intransit",$resInTransit);
	}

	if(($resOnHand + $resInTransit) < $resNeeded){
		$error = $error . "NOT ENOUGH RESISTORS TO FULFILL ORDERS!<br>";
		$resBack = "#FF539C";
		$resColor = "#242424";
	}

//----------------------------------------------------------------------------
//----------------------------------------------------------------------------
//	VOLT BOOST

	$volOnHand = intval(file_get_contents("vol.onhand"));
	$volInTransit = intval(file_get_contents("vol.intransit"));
	$volNeeded = intval(file_get_contents("vol.needed"));

	if(isset($_POST["volOFFSET"])){
		$volOFFSET = intval($_POST["volOFFSET"]);
		$volORDERED = intval($_POST["volORDERED"]);
		$volOnHand = $volOnHand + $volOFFSET;
		$volInTransit = $volInTransit - $volOFFSET;
		$volInTransit = $volInTransit + $volORDERED;

		file_put_contents("vol.onhand",$volOnHand);
		file_put_contents("vol.intransit",$volInTransit);
	}

	if(($volOnHand + $volInTransit) < $volNeeded){
		$error = $error . "NOT ENOUGH VOLTAGE CONVERTORS TO FULFILL ORDERS!<br>";
		$volBack = "#FF539C";
		$volColor = "#242424";
	}

//----------------------------------------------------------------------------
//----------------------------------------------------------------------------
//	ANTENNA

	$antOnHand = intval(file_get_contents("ant.onhand"));
	$antInTransit = intval(file_get_contents("ant.intransit"));
	$antNeeded = intval(file_get_contents("ant.needed"));

	if(isset($_POST["antOFFSET"])){
		$antOFFSET = intval($_POST["antOFFSET"]);
		$antORDERED = intval($_POST["antORDERED"]);
		$antOnHand = $antOnHand + $antOFFSET;
		$antInTransit = $antInTransit - $antOFFSET;
		$antInTransit = $antInTransit + $antORDERED;

		file_put_contents("ant.onhand",$antOnHand);
		file_put_contents("ant.intransit",$antInTransit);
	}

	if(($antOnHand + $antInTransit) < $antNeeded){
		$error = $error . "NOT ENOUGH ANTENNAS TO FULFILL ORDERS!<br>";
		$antBack = "#FF539C";
		$antColor = "#242424";
	}

//----------------------------------------------------------------------------
//----------------------------------------------------------------------------
//	SMA CONNECTOR

	$smaOnHand = intval(file_get_contents("sma.onhand"));
	$smaInTransit = intval(file_get_contents("sma.intransit"));
	$smaNeeded = intval(file_get_contents("sma.needed"));

	if(isset($_POST["smaOFFSET"])){
		$smaOFFSET = intval($_POST["smaOFFSET"]);
		$smaORDERED = intval($_POST["smaORDERED"]);
		$smaOnHand = $smaOnHand + $smaOFFSET;
		$smaInTransit = $smaInTransit - $smaOFFSET;
		$smaInTransit = $smaInTransit + $smaORDERED;

		file_put_contents("sma.onhand",$smaOnHand);
		file_put_contents("sma.intransit",$smaInTransit);
	}

	if(($smaOnHand + $smaInTransit) < $smaNeeded){
		$error = $error . "NOT ENOUGH SMA CONNECTORS TO FULFILL ORDERS!<br>";
		$smaBack = "#FF539C";
		$smaColor = "#242424";
	}

//----------------------------------------------------------------------------
//----------------------------------------------------------------------------
//	WOODS 13569

	$woodsOnHand = intval(file_get_contents("woods.onhand"));
	$woodsInTransit = intval(file_get_contents("woods.intransit"));
	$woodsNeeded = intval(file_get_contents("woods.needed"));

	if(isset($_POST["woodsOFFSET"])){
		$woodsOFFSET = intval($_POST["woodsOFFSET"]);
		$woodsORDERED = intval($_POST["woodsORDERED"]);
		$woodsOnHand = $woodsOnHand + $woodsOFFSET;
		$woodsInTransit = $woodsInTransit - $woodsOFFSET;
		$woodsInTransit = $woodsInTransit + $woodsORDERED;

		file_put_contents("woods.onhand",$woodsOnHand);
		file_put_contents("woods.intransit",$woodsInTransit);
	}

	if(($woodsOnHand + $woodsInTransit) < $woodsNeeded){
		$error = $error . "NOT ENOUGH WOODS 13569s TO FULFILL ORDERS!<br>";
		$woodsBack = "#FF539C";
		$woodsColor = "#242424";
	}

//----------------------------------------------------------------------------
//----------------------------------------------------------------------------
//	433 TX

	$tx433OnHand = intval(file_get_contents("tx433.onhand"));
	$tx433InTransit = intval(file_get_contents("tx433.intransit"));
	$tx433Needed = intval(file_get_contents("tx433.needed"));

	if(isset($_POST["tx433OFFSET"])){
		$tx433OFFSET = intval($_POST["tx433OFFSET"]);
		$tx433ORDERED = intval($_POST["tx433ORDERED"]);
		$tx433OnHand = $tx433OnHand + $tx433OFFSET;
		$tx433InTransit = $tx433InTransit - $tx433OFFSET;
		$tx433InTransit = $tx433InTransit + $tx433ORDERED;

		file_put_contents("tx433.onhand",$tx433OnHand);
		file_put_contents("tx433.intransit",$tx433InTransit);
	}

	if(($tx433OnHand + $tx433InTransit) < $tx433Needed){
		$error = $error . "NOT ENOUGH 433MHz TX's TO FULFILL ORDERS!<br>";
		$tx433Back = "#FF539C";
		$tx433Color = "#242424";
	}

//----------------------------------------------------------------------------
//----------------------------------------------------------------------------
//	433 HELICAL ANT

	$hel433OnHand = intval(file_get_contents("hel433.onhand"));
	$hel433InTransit = intval(file_get_contents("hel433.intransit"));
	$hel433Needed = intval(file_get_contents("hel433.needed"));

	if(isset($_POST["hel433OFFSET"])){
		$hel433OFFSET = intval($_POST["hel433OFFSET"]);
		$hel433ORDERED = intval($_POST["hel433ORDERED"]);
		$hel433OnHand = $hel433OnHand + $hel433OFFSET;
		$hel433InTransit = $hel433InTransit - $hel433OFFSET;
		$hel433InTransit = $hel433InTransit + $hel433ORDERED;

		file_put_contents("hel433.onhand",$hel433OnHand);
		file_put_contents("hel433.intransit",$hel433InTransit);
	}

	if(($hel433OnHand + $hel433InTransit) < $hel433Needed){
		$error = $error . "NOT ENOUGH 433MHz HELICALS TO FULFILL ORDERS!<br>";
		$hel433Back = "#FF539C";
		$hel433Color = "#242424";
	}

//----------------------------------------------------------------------------
//----------------------------------------------------------------------------
//	315 HELICAL ANT

	$helOnHand = intval(file_get_contents("hel.onhand"));
	$helInTransit = intval(file_get_contents("hel.intransit"));
	$helNeeded = intval(file_get_contents("hel.needed"));

	if(isset($_POST["helOFFSET"])){
		$helOFFSET = intval($_POST["helOFFSET"]);
		$helORDERED = intval($_POST["helORDERED"]);
		$helOnHand = $helOnHand + $helOFFSET;
		$helInTransit = $helInTransit - $helOFFSET;
		$helInTransit = $helInTransit + $helORDERED;

		file_put_contents("hel.onhand",$helOnHand);
		file_put_contents("hel.intransit",$helInTransit);
	}

	if(($helOnHand + $helInTransit) < $helNeeded){
		$error = $error . "NOT ENOUGH 315MHz HELICALS TO FULFILL ORDERS!<br>";
		$helBack = "#FF539C";
		$helColor = "#242424";
	}

//----------------------------------------------------------------------------


if(isset($_POST["new"])){
	$newOrder = $_POST["new"];
	if($newOrder != "X"){
		if($newOrder == "315-H"){
			$orderCount = $orderCount + 1;

			$pcbNeeded = $pcbNeeded + 1;
			$headerNeeded = $headerNeeded + 1;
			$txNeeded = $txNeeded + 1;
			$rgbNeeded = $rgbNeeded + 1;
			$capNeeded = $capNeeded + 1;
			$resNeeded = $resNeeded + 1;
			$volNeeded = $volNeeded + 1;
			$helNeeded = $smaNeeded + 1;
		}
		else if($newOrder == "315-S"){
			$orderCount = $orderCount + 1;

			$pcbNeeded = $pcbNeeded + 1;
			$headerNeeded = $headerNeeded + 1;
			$txNeeded = $txNeeded + 1;
			$rgbNeeded = $rgbNeeded + 1;
			$capNeeded = $capNeeded + 1;
			$resNeeded = $resNeeded + 1;
			$volNeeded = $volNeeded + 1;
			$antNeeded = $antNeeded + 1;
			$smaNeeded = $smaNeeded + 1;
		}
		if($newOrder == "433-H"){
			$orderCount = $orderCount + 1;

			$pcbNeeded = $pcbNeeded + 1;
			$headerNeeded = $headerNeeded + 1;
			$tx433Needed = $tx433Needed + 1;
			$rgbNeeded = $rgbNeeded + 1;
			$capNeeded = $capNeeded + 1;
			$resNeeded = $resNeeded + 1;
			$volNeeded = $volNeeded + 1;
			$hel433Needed = $hel433Needed + 1;
		}
		else if($newOrder == "433-S"){
			$orderCount = $orderCount + 1;

			$pcbNeeded = $pcbNeeded + 1;
			$headerNeeded = $headerNeeded + 1;
			$tx433Needed = $tx433Needed + 1;
			$rgbNeeded = $rgbNeeded + 1;
			$capNeeded = $capNeeded + 1;
			$resNeeded = $resNeeded + 1;
			$volNeeded = $volNeeded + 1;
			$antNeeded = $antNeeded + 1;
			$smaNeeded = $smaNeeded + 1;
		}

		file_put_contents("order.count",$orderCount);
		file_put_contents("pcb.needed",$pcbNeeded);
		file_put_contents("header.needed",$headerNeeded);
		file_put_contents("tx.needed",$txNeeded);
		file_put_contents("rgb.needed",$rgbNeeded);
		file_put_contents("cap.needed",$capNeeded);
		file_put_contents("res.needed",$resNeeded);
		file_put_contents("vol.needed",$volNeeded);
		file_put_contents("ant.needed",$antNeeded);
		file_put_contents("sma.needed",$smaNeeded);
		file_put_contents("hel.needed",$helNeeded);
		file_put_contents("woods.needed",$woodsNeeded);
		file_put_contents("tx433.needed",$helNeeded);
		file_put_contents("hel433.needed",$helNeeded);
	}
}
if(isset($_POST["fulfill"])){
	$fulfillOrder = $_POST["fulfill"];
	if($fulfillOrder != "X"){
		if($fulfillOrder == "315-H"){
			$orderCount = $orderCount - 1;

			$pcbNeeded = $pcbNeeded - 1;
			$headerNeeded = $headerNeeded - 1;
			$txNeeded = $txNeeded - 1;
			$rgbNeeded = $rgbNeeded - 1;
			$capNeeded = $capNeeded - 1;
			$resNeeded = $resNeeded - 1;
			$volNeeded = $volNeeded - 1;
			$helNeeded = $helNeeded - 1;

			$pcbOnHand = $pcbOnHand - 1;
			$headerOnHand = $headerOnHand - 1;
			$txOnHand = $txOnHand - 1;
			$rgbOnHand = $rgbOnHand - 1;
			$capOnHand = $capOnHand - 1;
			$resOnHand = $resOnHand - 1;
			$volOnHand = $volOnHand - 1;
			$helOnHand = $helOnHand - 1;
		}
		else if($fulfillOrder == "315-S"){
			$orderCount = $orderCount - 1;

			$pcbNeeded = $pcbNeeded - 1;
			$headerNeeded = $headerNeeded - 1;
			$txNeeded = $txNeeded - 1;
			$rgbNeeded = $rgbNeeded - 1;
			$capNeeded = $capNeeded - 1;
			$resNeeded = $resNeeded - 1;
			$volNeeded = $volNeeded - 1;
			$antNeeded = $antNeeded - 1;
			$smaNeeded = $smaNeeded - 1;

			$pcbOnHand = $pcbOnHand - 1;
			$headerOnHand = $headerOnHand - 1;
			$txOnHand = $txOnHand - 1;
			$rgbOnHand = $rgbOnHand - 1;
			$capOnHand = $capOnHand - 1;
			$resOnHand = $resOnHand - 1;
			$volOnHand = $volOnHand - 1;
			$antOnHand = $antOnHand - 1;
			$smaOnHand = $smaOnHand - 1;
		}
		if($fulfillOrder == "433-H"){
			$orderCount = $orderCount - 1;

			$pcbNeeded = $pcbNeeded - 1;
			$headerNeeded = $headerNeeded - 1;
			$tx433Needed = $tx433Needed - 1;
			$rgbNeeded = $rgbNeeded - 1;
			$capNeeded = $capNeeded - 1;
			$resNeeded = $resNeeded - 1;
			$volNeeded = $volNeeded - 1;
			$hel433Needed = $hel433Needed - 1;

			$pcbOnHand = $pcbOnHand - 1;
			$headerOnHand = $headerOnHand - 1;
			$tx433OnHand = $tx433OnHand - 1;
			$rgbOnHand = $rgbOnHand - 1;
			$capOnHand = $capOnHand - 1;
			$resOnHand = $resOnHand - 1;
			$volOnHand = $volOnHand - 1;
			$hel433OnHand = $hel433OnHand - 1;
		}
		else if($fulfillOrder == "433-S"){
			$orderCount = $orderCount - 1;

			$pcbNeeded = $pcbNeeded - 1;
			$headerNeeded = $headerNeeded - 1;
			$tx433Needed = $tx433Needed - 1;
			$rgbNeeded = $rgbNeeded - 1;
			$capNeeded = $capNeeded - 1;
			$resNeeded = $resNeeded - 1;
			$antNeeded = $antNeeded - 1;
			$volNeeded = $volNeeded - 1;
			$smaNeeded = $smaNeeded - 1;

			$pcbOnHand = $pcbOnHand - 1;
			$headerOnHand = $headerOnHand - 1;
			$tx433OnHand = $tx433OnHand - 1;
			$rgbOnHand = $rgbOnHand - 1;
			$capOnHand = $capOnHand - 1;
			$antOnHand = $antOnHand - 1;
			$resOnHand = $resOnHand - 1;
			$volOnHand = $volOnHand - 1;
			$smaOnHand = $smaOnHand - 1;
		}

		file_put_contents("order.count",$orderCount);
		file_put_contents("pcb.needed",$pcbNeeded);
		file_put_contents("header.needed",$headerNeeded);
		file_put_contents("tx.needed",$txNeeded);
		file_put_contents("rgb.needed",$rgbNeeded);
		file_put_contents("cap.needed",$capNeeded);
		file_put_contents("res.needed",$resNeeded);
		file_put_contents("vol.needed",$volNeeded);
		file_put_contents("ant.needed",$antNeeded);
		file_put_contents("sma.needed",$smaNeeded);
		file_put_contents("woods.needed",$woodsNeeded);
		file_put_contents("hel.needed",$helNeeded);
		file_put_contents("tx433.needed",$tx433Needed);
		file_put_contents("hel433.needed",$hel433Needed);

		file_put_contents("pcb.onhand",$pcbOnHand);
		file_put_contents("header.onhand",$headerOnHand);
		file_put_contents("tx.onhand",$txOnHand);
		file_put_contents("rgb.onhand",$rgbOnHand);
		file_put_contents("cap.onhand",$capOnHand);
		file_put_contents("res.onhand",$resOnHand);
		file_put_contents("vol.onhand",$volOnHand);
		file_put_contents("ant.onhand",$antOnHand);
		file_put_contents("sma.onhand",$smaOnHand);
		file_put_contents("woods.onhand",$woodsOnHand);
		file_put_contents("hel.onhand",$helOnHand);
		file_put_contents("tx433.onhand",$tx433OnHand);
		file_put_contents("hel433.onhand",$hel433OnHand);

	}
}

if(strlen($error) > 0){
	$errorState = "block";
}

$totalCount = $pcbOnHand + $headerOnHand + $txOnHand + $rgbOnHand + $capOnHand + $resOnHand + $volOnHand + $antOnHand + $smaOnHand + $woodsOnHand + $helOnHand + tx433OnHand + hel433OnHand;
?>

<html>
	<head>
		<title>ElectroPi Inventory Tracking</title>

		<link href='http://fonts.googleapis.com/css?family=Oswald:400,700,300' rel='stylesheet' type='text/css'>

		<style type="text/css">
			body{
				background-color:#242424;
				color:#cccccc;
				font-family:Oswald;
				margin:45px;
			}
			#header{
				font-size: 36px;
				margin-bottom: 10px;
				margin-top: -10px;
			}
			.item{
				width:150px;
				height:475px;
				background-color: #484848;
				display:inline-block;
				margin-right:10px;
				margin-bottom:10px;
			}
			.itemIMG{
				width:150px;
				height:150px;
				background-color: #141414;
			}
			.itemTITLE{
				width: 130px;
				padding: 10px;
				font-size: 18px;
				background-color:#111;
			}
			.itemONHAND {
				width: 150px;
				padding: 10px;
				font-size: 18px;
				border: none;
				background-color: #111;
				color: #00ffbe;
			}
			.itemINTRANSIT {
				width: 150px;
				padding: 10px;
				font-size: 18px;
				border: none;
				background-color: #111;
				color: #FBDB00;
			}
			.itemNEEDED {
				width: 150px;
				padding: 10px;
				font-size: 18px;
				border: none;
				background-color: #111;
				color: #ff539c;
			}
			.itemOFFSET {
				width: 150px;
				padding: 10px;
				font-size: 18px;
				border: none;
				background-color: #111;
				color: #666;
			}
			.itemORDERED {
				width: 150px;
				padding: 10px;
				font-size: 18px;
				border: none;
				background-color: #111;
				color: #666;
			}
			#orderCount{
				display:inline-block;
				float:right;
				margin-right:10px;
				color:<?php echo $countColor;?>;
			}
			#stats{
				width:550px;
				float:right;
				margin-right:10px;
				font-size:18px;
				text-align:right;
			}
			#update{
				position: absolute;
				bottom: 10px;
				right: 10px;
			}
			#updateBUTTON{
				width:150px;
				height:50px;
				background-color:#ff5c93;
				border:none;
				font-family:Oswald;
				font-size:18px;
				cursor:pointer;
			}
			#error{
				text-align:right;
				font-size:24px;
				background-color:#ff5c93;
				color:#242424;
				padding: 10px;
				margin-top: 20px;
				display:<?php echo $errorState;?>;
			}
			#fulfill{
				text-align:right;
			}
			#new{
				text-align:right;
			}
			#note{
				color:#666;

			}
		</style>
	</head>
	<body>
		<div id="header">
			<font style="color:#ff5c93;">ELECTRO</font>PI <font style="color:#777">INVENTORY TRACKING</font><div id="orderCount"><?php echo $orderCount;?> ACTIVE ORDERS</div>
		</div>
		<div id="stats">
			<div id="note">You have <?php echo $totalCount;?> components on hand!</div>
			<div id="error"><?php echo $error;?></div>
			<br>
			<div id="new">
				<form action="index.php" method="POST">
					NEW ORDER:<br>
					TYPE: <select name="new">
						<option value="X"> </option>
						<option value="315-H">315MHz ELECTROPI HELICAL</option>
						<option value="315-S">315MHz ELECTROPI SMA</option>
						<option value="433-H">433MHz ELECTROPI HELICAL</option>
						<option value="433-S">433MHz ELECTROPI SMA</option>
					</select>
			</div>
			<div id="fulfill">
				FULFILL ORDER:<br>
				<form action="index.php" method="POST">
					TYPE: <select name="fulfill">
						<option value="X"> </option>
						<option value="315-H">315MHz ELECTROPI HELICAL</option>
						<option value="315-S">315MHz ELECTROPI SMA</option>
						<option value="433-H">433MHz ELECTROPI HELICAL</option>
						<option value="433-S">433MHz ELECTROPI SMA</option>
					</select><br><br>
					<input id="updateBUTTON" type="submit" value="SUBMIT"></input>
				</form>
			</div>
		</div>
		<form action="index.php" method="POST">
		<div id="main">
			<div class="item">
				<div id="PCB">
					<div class="itemTITLE" style="color:<?php echo $pcbColor;?>;background-color:<?php echo $pcbBack;?>;">
						PCB (BASIC)
					</div>
					<div class="itemIMG">
						<img src="pcb.jpg"/>
					</div>
					<input type="text" class="itemONHAND" value="<?php echo $pcbOnHand;?> ON HAND">
					</input>
					<input type="text" class="itemINTRANSIT" value="<?php echo $pcbInTransit;?> IN TRANSIT">
					</input>
					<input type="text" class="itemNEEDED" value="<?php echo $pcbNeeded;?> NEEDED">
					</input>
					<font style="margin-left:10px;">ORDERED:</font>
					<input type="text" class="itemORDERED" value="0" name="pcbORDERED">
					</input>
					<font style="margin-left:10px;">RECEIVED:</font>
					<input type="text" class="itemOFFSET" value="0" name="pcbOFFSET">
					</input>
				</div>
			</div>
			<div class="item">
				<div id="HEADR">
					<div class="itemTITLE" style="color:<?php echo $headerColor;?>;background-color:<?php echo $headerBack;?>;">
						SMD HEADER
					</div>
					<div class="itemIMG">
						<img src="header.jpg"/>
					</div>
					<input type="text" class="itemONHAND" value="<?php echo $headerOnHand;?> ON HAND">
					</input>
					<input type="text" class="itemINTRANSIT" value="<?php echo $headerInTransit;?> IN TRANSIT">
					</input>
					<input type="text" class="itemNEEDED" value="<?php echo $headerNeeded;?> NEEDED">
					</input>
					<font style="margin-left:10px;">ORDERED:</font>
					<input type="text" class="itemORDERED" value="0" name="headerORDERED">
					</input>
					<font style="margin-left:10px;">RECEIVED:</font>
					<input type="text" class="itemOFFSET" value="0" name="headerOFFSET">
					</input>
				</div>
			</div>
			<div class="item">
				<div id="TX">
					<div class="itemTITLE" style="color:<?php echo $txColor;?>;background-color:<?php echo $txBack;?>;">
						315MHz TX
					</div>
					<div class="itemIMG">
						<img src="tx.jpg"/>
					</div>
					<input type="text" class="itemONHAND" value="<?php echo $txOnHand;?> ON HAND">
					</input>
					<input type="text" class="itemINTRANSIT" value="<?php echo $txInTransit;?> IN TRANSIT">
					</input>
					<input type="text" class="itemNEEDED" value="<?php echo $txNeeded;?> NEEDED">
					</input>
					<font style="margin-left:10px;">ORDERED:</font>
					<input type="text" class="itemORDERED" value="0" name="txORDERED">
					</input>
					<font style="margin-left:10px;">RECEIVED:</font>
					<input type="text" class="itemOFFSET" value="0" name="txOFFSET">
					</input>
				</div>
			</div>
			<div class="item">
				<div id="RGB">
					<div class="itemTITLE" style="color:<?php echo $rgbColor;?>;background-color:<?php echo $rgbBack;?>;">
						5050 RGB LED
					</div>
					<div class="itemIMG">
						<img src="rgb.jpg"/>
					</div>
					<input type="text" class="itemONHAND" value="<?php echo $rgbOnHand;?> ON HAND">
					</input>
					<input type="text" class="itemINTRANSIT" value="<?php echo $rgbInTransit;?> IN TRANSIT">
					</input>
					<input type="text" class="itemNEEDED" value="<?php echo $rgbNeeded;?> NEEDED">
					</input>
					<font style="margin-left:10px;">ORDERED:</font>
					<input type="text" class="itemORDERED" value="0" name="rgbORDERED">
					</input>
					<font style="margin-left:10px;">RECEIVED:</font>
					<input type="text" class="itemOFFSET" value="0" name="rgbOFFSET">
					</input>
				</div>
			</div>
			<div class="item">
				<div id="CAP">
					<div class="itemTITLE" style="color:<?php echo $capColor;?>;background-color:<?php echo $capBack;?>;">
						10uF CAPACITOR
					</div>
					<div class="itemIMG">
						<img src="cap.jpg"/>
					</div>
					<input type="text" class="itemONHAND" value="<?php echo $capOnHand;?> ON HAND">
					</input>
					<input type="text" class="itemINTRANSIT" value="<?php echo $capInTransit;?> IN TRANSIT">
					</input>
					<input type="text" class="itemNEEDED" value="<?php echo $capNeeded;?> NEEDED">
					</input>
					<font style="margin-left:10px;">ORDERED:</font>
					<input type="text" class="itemORDERED" value="0" name="capORDERED">
					</input>
					<font style="margin-left:10px;">RECEIVED:</font>
					<input type="text" class="itemOFFSET" value="0" name="capOFFSET">
					</input>
				</div>
			</div>
			<div class="item">
				<div id="RES">
					<div class="itemTITLE" style="color:<?php echo $resColor;?>;background-color:<?php echo $resBack;?>;">
						1Kohm RESISTOR
					</div>
					<div class="itemIMG">
						<img src="res.jpg"/>
					</div>
					<input type="text" class="itemONHAND" value="<?php echo $resOnHand;?> ON HAND">
					</input>
					<input type="text" class="itemINTRANSIT" value="<?php echo $resInTransit;?> IN TRANSIT">
					</input>
					<input type="text" class="itemNEEDED" value="<?php echo $resNeeded;?> NEEDED">
					</input>
					<font style="margin-left:10px;">ORDERED:</font>
					<input type="text" class="itemORDERED" value="0" name="resORDERED">
					</input>
					<font style="margin-left:10px;">RECEIVED:</font>
					<input type="text" class="itemOFFSET" value="0" name="resOFFSET">
					</input>
				</div>
			</div>
			<div class="item">
				<div id="VOL">
					<div class="itemTITLE" style="color:<?php echo $volColor;?>;background-color:<?php echo $volBack;?>;">
						12 VOLT BOOST
					</div>
					<div class="itemIMG">
						<img src="vol.jpg"/>
					</div>
					<input type="text" class="itemONHAND" value="<?php echo $volOnHand;?> ON HAND">
					</input>
					<input type="text" class="itemINTRANSIT" value="<?php echo $volInTransit;?> IN TRANSIT">
					</input>
					<input type="text" class="itemNEEDED" value="<?php echo $volNeeded;?> NEEDED">
					</input>
					<font style="margin-left:10px;">ORDERED:</font>
					<input type="text" class="itemORDERED" value="0" name="volORDERED">
					</input>
					<font style="margin-left:10px;">RECEIVED:</font>
					<input type="text" class="itemOFFSET" value="0" name="volOFFSET">
					</input>
				</div>
			</div>
			<div class="item">
				<div id="ANT">
					<div class="itemTITLE" style="color:<?php echo $antColor;?>;background-color:<?php echo $antBack;?>;">
						315MHz ANTENNA
					</div>
					<div class="itemIMG">
						<img src="ant.jpg"/>
					</div>
					<input type="text" class="itemONHAND" value="<?php echo $antOnHand;?> ON HAND">
					</input>
					<input type="text" class="itemINTRANSIT" value="<?php echo $antInTransit;?> IN TRANSIT">
					</input>
					<input type="text" class="itemNEEDED" value="<?php echo $antNeeded;?> NEEDED">
					</input>
					<font style="margin-left:10px;">ORDERED:</font>
					<input type="text" class="itemORDERED" value="0" name="antORDERED">
					</input>
					<font style="margin-left:10px;">RECEIVED:</font>
					<input type="text" class="itemOFFSET" value="0" name="antOFFSET">
					</input>
				</div>
			</div>
			<div class="item">
				<div id="SMA">
					<div class="itemTITLE" style="color:<?php echo $smaColor;?>;background-color:<?php echo $smaBack;?>;">
						SMA CONNECTOR
					</div>
					<div class="itemIMG">
						<img src="sma.jpg"/>
					</div>
					<input type="text" class="itemONHAND" value="<?php echo $smaOnHand;?> ON HAND">
					</input>
					<input type="text" class="itemINTRANSIT" value="<?php echo $smaInTransit;?> IN TRANSIT">
					</input>
					<input type="text" class="itemNEEDED" value="<?php echo $smaNeeded;?> NEEDED">
					</input>
					<font style="margin-left:10px;">ORDERED:</font>
					<input type="text" class="itemORDERED" value="0" name="smaORDERED">
					</input>
					<font style="margin-left:10px;">RECEIVED:</font>
					<input type="text" class="itemOFFSET" value="0" name="smaOFFSET">
					</input>
				</div>
			</div>
			<div class="item">
				<div id="WOODS">
					<div class="itemTITLE" style="color:<?php echo $woodsColor;?>;background-color:<?php echo $woodsBack;?>;">
						WOODS 13569
					</div>
					<div class="itemIMG">
						<img src="woods.jpg"/>
					</div>
					<input type="text" class="itemONHAND" value="<?php echo $woodsOnHand;?> ON HAND">
					</input>
					<input type="text" class="itemINTRANSIT" value="<?php echo $woodsInTransit;?> IN TRANSIT">
					</input>
					<input type="text" class="itemNEEDED" value="<?php echo $woodsNeeded;?> NEEDED">
					</input>
					<font style="margin-left:10px;">ORDERED:</font>
					<input type="text" class="itemORDERED" value="0" name="woodsORDERED">
					</input>
					<font style="margin-left:10px;">RECEIVED:</font>
					<input type="text" class="itemOFFSET" value="0" name="woodsOFFSET">
					</input>
				</div>
			</div>
			<div class="item">
				<div id="433TX">
					<div class="itemTITLE" style="color:<?php echo $tx433Color;?>;background-color:<?php echo $tx433Back;?>;">
						433MHz TX
					</div>
					<div class="itemIMG">
						<img src="tx433.jpg"/>
					</div>
					<input type="text" class="itemONHAND" value="<?php echo $tx433OnHand;?> ON HAND">
					</input>
					<input type="text" class="itemINTRANSIT" value="<?php echo $tx433InTransit;?> IN TRANSIT">
					</input>
					<input type="text" class="itemNEEDED" value="<?php echo $tx433Needed;?> NEEDED">
					</input>
					<font style="margin-left:10px;">ORDERED:</font>
					<input type="text" class="itemORDERED" value="0" name="tx433ORDERED">
					</input>
					<font style="margin-left:10px;">RECEIVED:</font>
					<input type="text" class="itemOFFSET" value="0" name="tx433OFFSET">
					</input>
				</div>
			</div>
			<div class="item">
				<div id="433HEL">
					<div class="itemTITLE" style="color:<?php echo $hel433Color;?>;background-color:<?php echo $hel433Back;?>;">
						433MHz Helical
					</div>
					<div class="itemIMG">
						<img src="hel433.jpg"/>
					</div>
					<input type="text" class="itemONHAND" value="<?php echo $hel433OnHand;?> ON HAND">
					</input>
					<input type="text" class="itemINTRANSIT" value="<?php echo $hel433InTransit;?> IN TRANSIT">
					</input>
					<input type="text" class="itemNEEDED" value="<?php echo $hel433Needed;?> NEEDED">
					</input>
					<font style="margin-left:10px;">ORDERED:</font>
					<input type="text" class="itemORDERED" value="0" name="hel433ORDERED">
					</input>
					<font style="margin-left:10px;">RECEIVED:</font>
					<input type="text" class="itemOFFSET" value="0" name="hel433OFFSET">
					</input>
				</div>
			</div>
			<div class="item">
				<div id="HEL">
					<div class="itemTITLE" style="color:<?php echo $helColor;?>;background-color:<?php echo $helBack;?>;">
						315MHz Helical
					</div>
					<div class="itemIMG">
						<img src="hel.jpg"/>
					</div>
					<input type="text" class="itemONHAND" value="<?php echo $helOnHand;?> ON HAND">
					</input>
					<input type="text" class="itemINTRANSIT" value="<?php echo $helInTransit;?> IN TRANSIT">
					</input>
					<input type="text" class="itemNEEDED" value="<?php echo $helNeeded;?> NEEDED">
					</input>
					<font style="margin-left:10px;">ORDERED:</font>
					<input type="text" class="itemORDERED" value="0" name="helORDERED">
					</input>
					<font style="margin-left:10px;">RECEIVED:</font>
					<input type="text" class="itemOFFSET" value="0" name="helOFFSET">
					</input>
				</div>
			</div>

		</div>
		<div id="update" style="display:none;">
			<input id="updateBUTTON" type="submit" value="UPDATE"></input>
		</div>
		</form>
	</body>
</html>
