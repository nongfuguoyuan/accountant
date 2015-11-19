<?php
class Main {
	
	function start($ca){
		
		$controll=ucfirst($ca[0])."Controller";
		$arr = $_POST;
		$controller=$this->getControll($controll);
		$objs = $controller->$ca[1]($arr);
		echo json_encode($objs);
		
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