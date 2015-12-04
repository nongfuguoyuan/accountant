<?php

class AreaController extends ZjhController{
	
	function find(){
		return $this->load("area")->find();
	}
}

?>