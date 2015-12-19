<?php
class GuestController extends ZjhController {

	// function searchByPhone(){

	// 	$phone = $this->post['phone'];
	// 	return $this->load('guest')->searchByPhone($phone);

	// }

	// function searchByCom(){

	// 	$com = $this->post['com'];
	// 	return $this->load('guest')->searchByCom($com);
		
	// }

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
		$employee_id = (int)$this->session['user']['employee_id'];
		$status = (int)$post['status'];


		if(!validate('phone',$phone)){
			// zlog($phone);
			return false;
		}
		if(!validate('name',$name)){
			// zlog($name);
			return false;
		}
		if(empty($employee_id)){
			// zlog($employee_id);
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

	/*分两种情况	
	*1 只查询当前用户对应的客户
	*2 查询所有的客户
	*第1种情况可以过滤employee_id和department_id
	*第2种不能，只能去session里拿employee_id
	*判断的依据是登录用户的level
	*/
	function find(){
		$page = $this->page();
		$post = $this->post;

		$status = (int)$post['status'];
		$phone = $post['phone'];
		$com = $post['com'];//公司名或者姓名
		$year = (int)$post['year'];
		$month = (int)$post['month'];

		$params = array(
			'status' 	=> $status,
			'phone'		=> $phone,
			'com'		=> $com,
			'year'		=> $year,
			'month'		=> $month
		);

		if($this->session['user']['level'] > 1){

			$params['employee_id'] = (int)$post['employee_id'];
			$params['department_id'] = (int)$post['department_id'];

		}else{
			$params['employee_id'] = (int)$this->session['user']['employee_id'];
		}

		$result =  $this->load('guest')->find($params,array($page));
		// return $result;
		if($result){
			foreach($result['data'] as $key => $value){
				$guest_id = (int)$value['guest_id'];
				//查询有几次追踪记录
				$result2 = $this->load('record')->findCount($guest_id);
				$result['data'][$key]['record_count'] = empty($result2) ? 0:$result2['count'];
				//查询开通工商类服务
				$opens = array();
				$business = $this->load("guest")->findBusiness($guest_id);
				if($business){
					foreach($business as $k => $v){
						array_push($opens,$v['name']);
					}
				}
				//查询代理记账类服务
				if($this->load('guest')->findAccounting($guest_id)){
					array_push($opens,'代理记账');
				}
				// $result['data'][$key]['opens'] = array(1,2,3);
				$result['data'][$key]['opens'] = $opens;
			}
		}
		
		return $result;
	}
	// function _find(){
	// 	$page = $this->page();
	// 	$employee_id = (int)$this->session['user']['employee_id'];
		
	// 	$result =  $this->load('guest')->_find($employee_id,array($page));
		
	// 	foreach($result['data'] as $key => $value){
	// 		$guest_id = (int)$value['guest_id'];
	// 		$result2 = $this->load('record')->findCount($guest_id);
	// 		$result['data'][$key]['record_count'] = empty($result2) ? 0:$result2['count'];
	// 	}
		
	// 	return $result;
	// }

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
			return '电话不符合要求';
		}
		if(!validate('name',$name)){
			return '姓名不符合要求';
		}
		if(!$this->load('guest')->findByEmployee($this->session['user']['employee_id'],$guest_id)){
			return '只有负责员工才能操作';
		}
		
		$result = $this->load('guest')->update(array(
			'guest_id'		=>	$guest_id,
			"company"		=>	$company,
			"name"			=>	$name,
			"phone"			=>	$phone,
			"area_id"		=>	$area_id,
			"address"		=>	$address,
			'employee_id'	=>	$this->session['user']['employee_id'],
			"tel"			=>	$tel,
			"status"		=>	$status
		));
		// return $lastid;
		if($result){
			return $this->load('guest')->findById($guest_id);
		}else{
			return 0;//update fail
		}
	}
	function delete(){
		return $this->load('guest')->delete((int)$this->post['guest_id']);
	}
	
}

?>