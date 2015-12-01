<?php
	require('./protected/base/function.php');
	header("Content-type: text/html; charset=utf8"); 
	
	$url = "http://localhost/accountant/";
	var_dump(unserialize("a:16:{i:0;s:5:\"guest\";i:1;s:4:\"area\";i:2;s:8:\"business\";i:3;s:9:\"dashboard\";i:4;s:10:\"department\";i:5;s:8:\"employee\";i:6;s:9:\"payrecord\";i:7;s:12:\"processgroup\";i:8;s:7:\"process\";i:9;s:6:\"record\";i:10;s:8:\"resource\";i:11;s:4:\"roles\";i:12;s:3:\"tax\";i:13;s:8:\"taxcount\";i:14;s:7:\"taxtype\";i:15;s:4:\"todo\";}"
	));
	// echo phppost($url."roles/delete",array(
	// 	'roles_id'=>4
	// ));
	// echo phppost($url."business/find",array(
	// 	'employee_id'=>33
	// ));
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

	// echo empty($haha);
?>