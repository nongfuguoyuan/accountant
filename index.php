<?php
error_reporting(E_ERROR);
session_start();
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');

define('_HOST','http://192.168.10.105/accountant/');
define('_DASHBOARD',_HOST.'protected/tmp/index.html');
// define('CONFIG',"D:/dev/");

require 'autoload.php';
require 'protected/base/model.php';
require 'protected/controllers/zjhcontroller.php';
require 'protected/base/function.php';

$ca = explode("/",$_SERVER['REQUEST_URI']);

// return var_dump($ca);

if($ca[2] && $ca[3]){

	$clazz = strtolower($ca[2]);
	$method = strtolower($ca[3]);
	$main = new Main();
	echo $main->start($clazz,$method);

}else{

	echo 'class or method you call is not exists';

}





