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
				
				if(is_array($ret)){
					if(isset($ret['info'])){
						$ret['error_code'] = 120;
						return json_encode($ret);
					}else{
						return json_encode(array(
							'error_code'	=>	0,
							'list'			=> 	$ret
						));
					}
				}else if(is_numeric($ret)){
					if($ret == 0){
						return json_encode(array('error_code'=>5));//insert or update fail
					}else{
						return json_encode(array('error_code'=>0,'info'=>'操作成功'));
					}
				}else if(is_string($ret)){
					if(mb_strlen($ret,"utf-8") < 40){
						return json_encode(array('info'=>$ret,"error_code"=>120));//return warning or notice
					}else{
						return $ret;//return html tmplate
					}
				}else if($ret == false){
					return json_encode(array());
				}
				else{
					// return $ret;
					return "what's this?";
				}
			}else{
				// return "can not call method you request";
				return json_encode(array('error_code'=>4));
			}
		}else{
			// trigger_error("can not find controller file");
			return json_encode(array('error_code'=>3));
		}

	}

	function start(){
		
		if(empty($this->clazz) || empty($this->method)){
			// return 'can not find file you request!';
			return json_encode(array('error_code'=>3));
		}else{
			$filter_result = $this->filter($this->clazz,$this->method);
			if($filter_result === true){
				return $this->dispatch();
			}else if($filter_result === false){
				return json_encode(array('error_code'=>1));//no permisssion
			}
			else{
				return json_encode(array('error_code'=>$filter_result));//session timeout
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
				return 2;//session timeout
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