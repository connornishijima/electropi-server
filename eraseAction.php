<?php
	$AID = $_GET["AID"];
	$fileString = "";
	unlink("conf/actions/$AID.txt");
	$subject = file_get_contents("conf/actions/actions.list");
	$lines = explode("\n",$subject);
	foreach($lines as &$line){
		$pieces = explode("|",$line);
		$nick = $pieces[0];
		$sAID = $pieces[1];
		if($AID == $sAID){
			//NOTHING
		}
		else{
			$fileString = $fileString . $line . "\n";
		}
	}
	file_put_contents("conf/actions/actions.list",$fileString);
	echo $fileString;
?>
