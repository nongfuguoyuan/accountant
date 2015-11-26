<?php
// class TaxTypeController extends \BasicController {
// 	public function __construct($tableName=__CLASS__){
	
// 		parent::__construct($tableName);
// 	}
	
// 	public function findAll(){
	
// 		$objs=parent::findAll();
// 		$objs=$objs['data'];
// 		return $objs;
// 	}
// }


//modify by zjh

	class TaxtypeController extends ZjhController{
		function find(){
			return $this->load('taxtype')->find();
		}
		function findByParentid(){
			$parent_id = (int)$this->post['parent_id'];
			return $this->load('taxtype')->findByParentid($parent_id);
		}
		function save(){
			$post = $this->post;
			$name = $post['name'];
			$parent_id = (int)$post['parent_id'];
			if(strlen($name)<1){
				return false;
			}
			$lastid = $this->load('taxtype')->add(array(
				'name'=>$name,
				'parent_id'=>$parent_id
			));
			if($lastid){
				return $this->load('taxtype')->findById($lastid);
			}else{
				return false;
			}
		}
		function update(){
			$post = $this->post;
			$name = $post['name'];
			$tax_type_id = (int)$post['tax_type_id'];
			if(strlen($name) < 1){
				return false;
			}
			$result = $this->load('taxtype')->update(array(
				'name'=>$name,
				'tax_type_id'=>$tax_type_id
			));
			if($result){
				return $this->load('taxtype')->findById($tax_type_id);
			}else{
				return false;
			}
		}
		function delete(){
			return $this->load('taxtype')->delete((int)$this->post['tax_type_id']);
		}
	}
?>
