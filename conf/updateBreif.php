<?php

function tailFile($filepath, $lines = 1) {
		return trim(implode("", array_slice(file($filepath), -$lines)));
	}

$line = tailFile("update.log");

$line = str_replace("<br>"," - ",$line);
$line = strip_tags($line);
echo $line;

?>
