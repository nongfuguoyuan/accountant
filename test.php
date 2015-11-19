<?php
	require('./protected/base/function.php');
	$url = "http://localhost/accountant/index.php/";
	echo phppost($url."employee/findall",array(
		'employee_id'=>19,
		'name'=>'晶辉',
		'sex'=>'1',
		'phone'=>'15899659685',
		'department_id'=>29
	));

?>