<?php
class ResourceController {
	
	public function __construct($tableName=__CLASS__){
	
		parent::__construct($tableName);
	}
	
	public function findAll(){
	
		$objs=parent::findAll();
		$objs=$objs['data'];
		return $objs;
	}
}

?>