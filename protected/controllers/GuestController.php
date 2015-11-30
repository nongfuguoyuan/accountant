<?php
class GuestController extends ZjhController {

	function searchByPhone(){

		$phone = $this->post['phone'];
		return $this->load('guest')->searchByPhone($phone);

	}

	function searchByCom(){

		$com = $this->post['com'];
		return $this->load('guest')->searchByCom($com);
		
	}

	function searchById(){
		$guest_id = (int)$this->post['guest_id'];
		return $this->load('guest')->searchById($guest_id);
	}

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
		
		if($lastid){
			return $this->load('guest')->findById($lastid);
		}else{
			return false;
		}

	}
	
	function find(){
		$page = $this->page();
		$result =  $this->load('guest')->find(array($page));
		foreach($result['data'] as $key => $value){
			$guest_id = (int)$value['guest_id'];
			$result2 = $this->load('record')->findCount($guest_id);
			$result['data'][$key]['record_count'] = empty($result2) ? 0:$result2['count'];
		}
		
		return $result;
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
	
}

?>