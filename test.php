<?php
	require('./protected/base/function.php');
	$url = "http://localhost/accountant/index.php/";
	echo phppost($url."todo/findById",array(
			'todo_id'=>'38'
		
	));

?>