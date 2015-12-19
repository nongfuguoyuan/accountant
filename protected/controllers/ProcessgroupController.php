<?php
	class ProcessgroupController extends ZjhController{
		
		function find(){
			return $this->load('processgroup')->find();
		}
		function save(){
			$post = $this->post;
			$name = $post['name'];

			if(strlen($name)<1){
				return false;
			}
			if($this->load('processgroup')->findByName($name)){
				return false;
			}
			return $this->load('processgroup')->add(array(
				'name'=>$name
			));
		}
		function update(){
			$post = $this->post;
			$name = $post['name'];
			$process_group_id = (int)$post['process_group_id'];

			if(strlen($name)<1){
				return false;
			}
			if($this->load('processgroup')->findByName($name)){
				return false;
			}
			$result =  $this->load('processgroup')->update(array(
				'process_group_id'=>$process_group_id,
				'name'=>$name
			));
			if($result){
				return $this->load('processgroup')->findById($process_group_id);
			}else{
				return false;
			}
		}
		function delete(){
			$post = $this->post;
			$process_group_id = (int)$post['process_group_id'];
			return $this->load('processgroup')->delete($process_group_id);
		}

	}
?>