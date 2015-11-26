<?php
error_reporting(E_ERROR);
session_start();
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');

require_once 'autoload.php';
require 'protected/base/model.php';
require 'protected/controllers/zjhcontroller.php';
require 'protected/base/function.php';

$ca = explode("/",$_SERVER['REQUEST_URI']);

if($ca[3] && $ca[4]){

	$clazz = strtolower($ca[3]);
	$method = strtolower($ca[4]);

	if($clazz == 'employee' && ($method == 'login' || $method == 'logout')){

	}else{
		// if(empty($_SESSION['user'])){
		// 	echo '请登录';
		// 	return;
		// }
	}
	
	$main = new Main();
	
	echo $main->start($clazz,$method);
	
}else{

	echo 'class or method you call is not exists';

}

