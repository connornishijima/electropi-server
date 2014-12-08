<?php
	if(isset($_GET["uid"])){
		$searchUID = "'" . $_GET["uid"] . "'";

		$appString = file_get_contents("conf/appliances.txt");
		$lines = explode("\n",$appString);
		foreach($lines as &$line){
			$pieces = explode("|",$line);
			$nickname = $pieces[0];
			$state = $pieces[1];
			$onCode = $pieces[2];
			$offCode = $pieces[3];
			$repeat = $pieces[7];
			$uid = $pieces[8];
			if($uid != $searchUID){
				$outString = $outString . $line . "\n";
			}
		}
		file_put_contents("conf/appliances.txt",$outString);

		$outString = "";

		$stateString = file_get_contents("conf/applianceStates.txt");
		$lines = explode("\n",$stateString);
		foreach($lines as &$line){
			$pieces = explode("|",$line);
			$nickname = $pieces[0];
			$state = $pieces[1];
			$uid = $pieces[2];
			if($uid != $searchUID){
				$outString = $outString . $line . "\n";
			}
		}
		file_put_contents("conf/applianceStates.txt",$outString);
		echo file_get_contents("conf/appliances.txt");

		$outString = "";

                $orderString = file_get_contents("conf/app.order");
                $lines = explode("\n",$orderString);
                foreach($lines as &$line){
                        $uid = trim($line,"\n");
			$sUID = str_replace("'", "",$searchUID);
			echo "UID:".$uid." sUID: ".$sUID."<br>";
                        if($uid != $sUID){
                                $outString = $outString . $line . "\n";
                        }
                }
                file_put_contents("conf/app.order",$outString);
                echo file_get_contents("conf/app.order");
	}

?>

<html>
	<head>
	<script type="text/javascript">
		function commencer(){
			document.getElementById("content").innerHTML='<object type="text/html" data="http://connor-n.com/electropi/count/appAdd.php?subtract" ></object>';
			setTimeout(function(){
				window.location = "index.php";
			}, 500);
		}
	</script>
	</head>
	<body onload="commencer();">
		<div id="content"></div>
	</body>
</html>
