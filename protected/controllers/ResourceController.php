<?php
class ResourceController extends \BasicController {
	public function __construct($tableName=__CLASS__){
		$this->_model=new BasicModel();
		parent::__construct($tableName);
	}
}

?>