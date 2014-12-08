<?php
	$count = intval(file_get_contents("switch.count"));
	$count = $count + 1;
	file_put_contents("switch.count",$count);

	echo $count;
?>
