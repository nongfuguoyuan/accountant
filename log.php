<?php 
	function zlog($str){
		$file = fopen(date("Y-m-d").".log",'a') or die("can not create log file!");
		fwrite($file,$str."\n");	
		fclose($file);
	}
 ?>