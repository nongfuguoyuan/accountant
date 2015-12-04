<?php
	// require('./protected/base/function.php');
	// header("Content-type: text/html; charset=utf8"); 
	
	// $url = "http://localhost/accountant/";
	// ob_start();
	// require("login.html");

	// $output = ob_get_contents();
	// ob_end_clean();
	// echo $output;
	if(version_compare(PHP_VERSION, "5.3.0", "<")) die("require PHP > 5.3.0!");
	error_reporting(E_ERROR);
	session_start();

	function init(){
		$url = "http://localhost/accountant/";
		extract(array(1,2));
		ob_start();
		require("login.html");
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	// echo init();
	// echo json_encode(array(1,2,3,4));
	$arr = array(1);
	echo end($arr);
	echo $arr[0];





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