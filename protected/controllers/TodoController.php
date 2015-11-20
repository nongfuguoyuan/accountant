<?php
require 'zjhcontroller.php';
class TodoController extends ZjhController {
	
	public function findAll(){
		$post=$this->post;
		$page = isset($post['page'])? $post['page']:1;
		$pagenum = isset($post['pageNum'])?$post['pageNum']:1;
		
		$result = $this->load('todo')->find(array($page,$pagenum));
		if(!$result){
			$result = array();
		}
		return $result;
		// return json_encode($result);
	}
}

?>