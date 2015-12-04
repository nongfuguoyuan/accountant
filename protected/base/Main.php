<?php
class Main {
	
	function start($clazz,$method){
		

		// if($this->filter($clazz,$method) == false){
		// 	// return 'No Permission';
		// 	return json_encode(array());
		// }

		try{

			$clazz = ucfirst($clazz)."Controller";
			$controller = $this->getControll($clazz);
			$result = $controller->$method();
			return json_encode($result);

		}catch(Exception $e){
			return "No This Url Exists";
			exit;
		}
	}

	function filter($clazz,$method){
		$clazz = strtolower($clazz);
		$method = strtolower($method);
		$permission = $_SESSION['user']['permissions'];

		if($clazz == 'employee' && ($method == 'login' || $method == 'logout')){
			return true;
		}else{
			if(empty($_SESSION['user'])){
				return 'Please Login First';
			}else{
				return (in_array($clazz,$permission) || in_array($clazz."/".$method,$permission));
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