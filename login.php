<?php
session_start();
include_once 'autoload.php';
$ca=array('employee','findbywhere','phone='.$_POST['user']);
$main=new Main();
$user=$main->start($ca);

var_dump($user);





