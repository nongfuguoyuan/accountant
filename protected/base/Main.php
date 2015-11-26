<?php
class Main {
	
	function start($clazz,$method){
		
		// $controll=ucfirst($ca[0])."Controller";
		// $arr = $_POST;
		// $controller=$this->getControll($controll);
		// $objs = $controller->$ca[1]($arr);
		// echo json_encode($objs);
		$clazz = ucfirst($clazz)."Controller";
		$controller = $this->getControll($clazz);
		$result = $controller->$method();
		return json_encode($result);
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