<?php


$file = "latLong.txt";
if ( isset($_GET["lat"]) && isset($_GET["lon"])) {
	$f = fopen($file,"w");
	fwrite($f, date("Y-m-d H:i:s")."_".$_GET["lat"]."_".$_GET["lon"]);
	fclose($f);
    echo "OK";
} else {
}

	echo file_get_contents("latLong.txt");

?>

<html>
	<head>
		<meta http-equiv="refresh" content="5">
	</head>
</html>
