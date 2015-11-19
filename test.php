<?php
	require('./protected/base/function.php');
	$url = "http://localhost/accountant/index.php/";
	echo phppost($url."department/findMenu",array(
		// 'department_id'=>6,
		// 'parent_id'=>1,
		// 'name'=>'客服'
	));
	
?>