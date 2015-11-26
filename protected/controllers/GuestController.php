<?php
class GuestController extends ZjhController {

	function save(){
		$post = $this->post;
		$company = $post['company'];
		$name = $post['name'];
		$tel = $post['tel'];
		$phone = $post['phone'];
		$job = $post['job'];
		$area_id = (int)$post['area_id'];
		$address = $post['address'];
		$resource_id = (int)$post['resource_id'];
		$employee_id = $this->session['user']['employee_id'];
		$status = (int)$post['status'];

		if(!validate('phone',$phone)){
			return false;
		}
		if(!validate('name',$name)){
			return false;
		}
		if(empty($employee_id)){
			return false;
		}
		// return array(
		// 	"company"=>$company,
		// 	"name"=>$name,
		// 	"phone"=>$phone,
		// 	"job"=>$job,
		// 	"area_id"=>$area_id,
		// 	"address"=>$address,
		// 	"resource_id"=>$resource_id,
		// 	"employee_id"=>$employee_id,
		// 	"tel"=>$tel,
		// 	"status"=>$status
		// );
		$lastid = $this->load('guest')->add(array(
			"company"=>$company,
			"name"=>$name,
			"phone"=>$phone,
			"job"=>$job,
			"area_id"=>$area_id,
			"address"=>$address,
			"resource_id"=>$resource_id,
			"employee_id"=>$employee_id,
			"tel"=>$tel,
			"status"=>$status
		));
		// return $lastid;
		if($lastid){
			return $this->load('guest')->findById($lastid);
		}else{
			return false;
		}

	}
	
	function find(){
		$post = $this->post;
		$page = $post['page'];
		$pagenum = $post['pageNum'];
		if(empty($page)) $page = 1;
		if(empty($pagenum)) $pagenum = 20;
		return $this->load('guest')->find(array($page,$pagenum));

	}
	function update(){
		$post = $this->post;
		$company = $post['company'];
		$name = $post['name'];
		$tel = $post['tel'];
		$phone = $post['phone'];
		// $job = $post['job'];
		$area_id = (int)$post['area_id'];
		$address = $post['address'];
		// $employee_id = $this->session['user']['employee_id'];
		$status = (int)$post['status'];
		$guest_id = (int)$post['guest_id'];

		if(!validate('phone',$phone)){
			return false;
		}
		if(!validate('name',$name)){
			return false;
		}
		$result = $this->load('guest')->update(array(
			'guest_id'=>$guest_id,
			"company"=>$company,
			"name"=>$name,
			"phone"=>$phone,
			"area_id"=>$area_id,
			"address"=>$address,
			"tel"=>$tel,
			"status"=>$status
		));
		// return $lastid;
		if($result){
			return $this->load('guest')->findById($guest_id);
		}else{
			return false;
		}

	}
	function delete(){
		return $this->load('guest')->delete((int)$this->post['guest_id']);
	}
	function findName(){

	}
	function search(){

	}
	
}

?>