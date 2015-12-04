<?php
class Main {

	private $clazz;
	private $method;

	function __construct(){

		$uri = explode("/",$_SERVER['REQUEST_URI']);

		if($uri[2] == end($uri)){

			$this->clazz = "tourist";
			$this->method = strtolower($uri[2]);

		}else if($uri[2] && $uri[3]){
			
			$this->clazz = strtolower($uri[2]);
			$this->method = strtolower($uri[3]);

		}
	}

	function dispatch(){
		$file = C_PATH.ucfirst($this->clazz)."Controller.php";
		if(file_exists($file)){
			require($file);
			$class = ucfirst($this->clazz)."Controller";
			$controller = new $class();
			if(is_callable(array($controller,$this->method))){
				$ret = call_user_func(array($controller,$this->method));
				if(is_array($ret) || is_bool($ret)){
					return json_encode($ret);
				}else{
					return $ret;//return value is string type
				}
			}else{
				return "can not call method you request";
			}
		}else{
			trigger_error("can not find controller file");
		}

	}

	function start(){
		
		if(empty($this->clazz) || empty($this->method)){
			return 'can not find file you request!';
		}else{
			
			if($this->filter($this->clazz,$this->method)){
				return $this->dispatch();
			}else{
				return "no permission";
				return json_encode(array());
			}
		}
	}

	function filter($clazz,$method){
		
		global $publicPermission;

		$clazz = strtolower($clazz);
		$method = strtolower($method);
		$permission = $_SESSION['user']['permissions'];

		if($clazz == 'tourist'){
			return true;
		}else{
			if(empty($_SESSION['user'])){
				return 'Please Login First';
			}else{

				$pub = array_map('strtolower',array_keys($publicPermission));

				if(in_array($clazz,$pub) || in_array($clazz."/".$method,$pub)){
					return true;
				}else{
					return (in_array($clazz,$permission) || in_array($clazz."/".$method,$permission));
				}
			}
		}

	}
	
	function getControll($controll){
		
		if(class_exists($controll)){
			
			$controller = new $controll();//复合对象
		}else{
			
			$controller = new BasicController($controll);
		}
		
		return $controller;
	}
}

?>