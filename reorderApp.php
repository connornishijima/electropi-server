<?php
	$uidList = "";

	$string = $_GET["string"];
	$string = trim($string);
	$string = str_replace('"','',$string);
	$string = explode("|",$string);
	foreach($string as &$nick){
		$subject = file_get_contents("conf/appliances.txt");
		$subject = explode("\n",$subject);
		foreach($subject as &$app){
			$pieces = explode("|",$app);
			$sNick = $pieces[0];
			$UID = $pieces[8];
			$UID = str_replace("'","",$UID);
			if(trim($nick) == trim($sNick)){
				$uidList = $uidList . $UID . "\n";
			}
		}
	}
	echo $uidList;
	file_put_contents("conf/app.order",$uidList);
?>
