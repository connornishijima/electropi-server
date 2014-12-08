<?php $lines = file_get_contents("update.log"); $lines = 
explode("\n",$lines); foreach($lines as &$line){
	echo $line . "<br>";
}
?>
