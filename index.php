<?php 
	if(version_compare(PHP_VERSION, "5.3.0", "<")) die("require PHP > 5.3.0!");
	// header('Access-Control-Allow-Origin: *');
	// header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	// header('Access-Control-Allow-Methods: GET, POST, PUT');
	// header("Content-type:text/plain");
	error_reporting(E_ERROR | E_WARNING);
	 // error_reporting(E_ALL);
	session_start();
	require "config.php";
	require "log.php";
	require M_PATH.'function.php';

	if(preg_match("/.*\/v\d+\//",$_SERVER['REQUEST_URI'])){
		require(CUSTOMER."/engine/commonmodel.php");
		require(CUSTOMER."/engine/dispatch.php");
		$dispatch = new Dispatch();
		echo $dispatch->start();
	}else{
		require M_PATH.'model.php';
		require C_PATH.'zjhcontroller.php';
		require CONFIG_PATH."permission.php";
		require M_PATH."Main.php";
		$main = new Main;
		$result = $main->start();
		echo $result;
	}
	