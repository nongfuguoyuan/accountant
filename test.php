<?php
	require('./protected/base/function.php');
<<<<<<< HEAD
	session_start();
	$url = "http://192.168.10.35/accountant/";
=======
	$url = "http://localhost/accountant/index.php/";
	echo phppost($url."todo/findAll",array(
		
	));
>>>>>>> 4d85906003b21a61f5d3a6f1c06008b5a8408678

	// echo phppost($url."business/find",array(
	// 	'employee_id'=>33
	// ));
	echo strcmp('中','中');
	// echo $url;
	// echo phppost($url."progress/save",array(
	// 	'business_id'=>2,
	// 	'process_id'=>22,
	// 	'note'=>'因为事故而延期',
	// 	'day'=>5
	// ));
	// $pass = "000000";
	// echo md5(crypt($pass,substr($pass,0,2)));
	// echo date('Y-m-d H:m:s');
	// echo strtotime(date('Y-m-d H:m:s'))<strtotime('2016-1-1');
?>