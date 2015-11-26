<?php
	class ResourceController extends ZjhController{
		function findAll(){
			return $this->load('resource')->find();
		}
		function save(){
			$post = $this->post;
			$description = $post['description'];
			if(empty($description) || strlen($description) < 2){
				return false;
			}
			if($this->load('resource')->fingByDes($description)){
				return false;				
			}
			return $this->load('resource')->add(array(
				'description'=>$description,
				'create_time'=>timenow()
			));

		}
		function update(){
			$post = $this->post;
			$description = $post['description'];
			$resource_id = (int)$post['resource_id'];

			if(empty($description) || strlen($description) < 2){
				return false;
			}
			if($this->load('resource')->fingByDes($description)){
				return false;				
			}
			$result =  $this->load('resource')->update(array(
				'description'=>$description,
				'resource_id'=>$resource_id
			));
			if($result){
				return $this->load('resource')->findById($resource_id);
			}else{
				return false;
			}
		}
		function delete(){
			$post = $this->post;
			$resource_id = (int)$post['resource_id'];
			return $this->load('resource')->delete($resource_id);
		}
	}

?>