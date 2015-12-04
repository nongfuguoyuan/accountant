<?php 
	if(version_compare(PHP_VERSION, "5.3.0", "<")) die("require PHP > 5.3.0!");
	// header('Access-Control-Allow-Origin: *');
	// header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	// header('Access-Control-Allow-Methods: GET, POST, PUT');
	// header("Content-type:text/plain");
	error_reporting(E_ERROR | E_WARNING);
	session_start();
	require "config.php";
	require M_PATH.'model.php';
	require C_PATH.'zjhcontroller.php';
	require M_PATH.'function.php';
	require CONFIG_PATH."permission.php";
	require M_PATH."Main.php";

	$main = new Main;
	$result = $main->start();
	echo $result;