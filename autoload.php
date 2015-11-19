<?php
define("ROOT",__DIR__);
define("APP_PATH",ROOT."/protected");
$config=require_once ROOT.'/protected/config/config.php';

date_default_timezone_set("Etc/GMT-8");//设置时区

spl_autoload_register("my_autoload");
function my_autoload($class){
	foreach (array(BASE_PATH,M_PATH,C_PATH,V_PATH) as $path){
		$file=$path.DIRECTORY_SEPARATOR.$class.".php";
		if(is_file($file)){
			include_once $file;
		}
	}
}