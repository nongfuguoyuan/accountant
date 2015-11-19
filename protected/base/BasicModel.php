<?php
/*
 * Model类的基类，封装set/get操作
 */
class BasicModel {
	
	public $_map=array();

	//初始化
	function __construct(){	
		
	}
	
	public function init($arr){
		$obj = new BasicModel();
		foreach ($arr as $key=>$value){
			$key=orm($key);
			$obj->$key=$value;
		}
		return $obj;
	}
	function __set($key,$value){
		
		$this->_map[$key] = $value;
	}
	
	function __get($key){
		
		return $this->_map[$key];
	}
	
	function __call($name,$arguments){
		
		if(substr($name, 0, 3)=='set'){
			
			$this->__set(strtolower(substr($name, 3)), $arguments[0]);
			
		}else if (substr($name, 0, 3)=='get'){
			
			return $this->__get(strtolower(substr($name, 3)));
			
		}
	}
	
}

?>