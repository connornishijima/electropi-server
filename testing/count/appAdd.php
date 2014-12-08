<?php
	if(isset($_GET["subtract"])){
		$count = intval(file_get_contents("app.count"));
		$count = $count - 1;
		file_put_contents("app.count",$count);
	}
	else{
		$count = intval(file_get_contents("app.count"));
		$count = $count + 1;
		file_put_contents("app.count",$count);
	}
	echo $count;
?>
