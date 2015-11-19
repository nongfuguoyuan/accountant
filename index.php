<?php
session_start();
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');

include_once 'autoload.php';

preg_match("/(=?accountant\/(?:index.php\/))([a-zA-Z_0-9-\/]+)/i", $_SERVER['REQUEST_URI'],$matches);

$ca=explode("/",$matches[2]);//controller and action id

$main = new Main();
$main->start($ca);




