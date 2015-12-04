<?php
/*
*modify author:zjh
*data:2015-11-19
*/
class EmployeeController extends ZjhController{

	function updateStatus(){
		$employee_id = (int)$this->post['employee_id'];
		$status = (int)$this->post['status'];

		if(empty($employee_id)) return false;
		else return $this->load('employee')->updateStatus($employee_id,$status);
	}
	
	function session(){
		if(empty($this->session)){
			return false;
		}else{
			return $this->session;
		}
	}

	function permission(){
		if(empty($this->session['user']['permissions'])){
			return array();
		}else{
			return $this->session['user']['permissions'];
		}
	}

	/*author:zgj 
	*date:2015-12-3
	*修改密码
	*/
	public function changePass(){
		$post = $this->post;
		$oldPass = isset($post['oldPass'])?$post['oldPass']:null;
		$newPass = isset($post['newPass'])?$post['newPass']:null;
		$employee_id=$this->session['user']['employee_id'];
		$user = $this->load('employee')->findPass($employee_id);
		$pass = $user[0]['password'];
		if($pass == secret($oldPass)){
			$r = $this->load('employee')->changePass(secret($newPass),$employee_id);
		}else{
			$r = array('info'=>'老密码不对!');
		}
		return $r;
	}

	public function find(){

		return $this->load('employee')->find(array($this->page()));
	}

	public function findByDepartmentid(){
		$department_id = (int)$this->post['department_id'];
		return $this->load('employee')->findByDepartmentid($department_id);
	}

	public function search(){
		$post = $this->post;

		$result = $this->load('employee')->search($post);
		if(!$result){
			$result = array();
		}
		return $result;
	}
	public function save(){
		$post = $this->post;
		$name = isset($post['name'])?$post['name']:null;
		$phone = isset($post['phone'])?$post['phone']:null;
		$sex = (int)$post['sex'];
		$department_id = (int)$post['department_id'];
		$roles_id = (int)$post['roles_id'];

		if(!validate('name',$name)){
			return false;
		}
		if(!validate('phone',$phone)){
			return false;
		}


		$lastid = $this->load('employee')->add(array(
			'name'=>$name,
			'pass'=>secret('000000'),
			'phone'=>$phone,
			'sex'=>$sex,
			'roles_id'=>$roles_id,
			'create_time'=>timenow(),
			'department_id'=>$department_id
		));

		if($lastid){
			return $this->load('employee')->findById($lastid);
		}else{
			return false;
		}

	}
	public function update(){
		$post = $this->post;
		$name = $post['name'];
		$phone = $post['phone'];
		$sex = (int)$post['sex'];
		$department_id = (int)$post['department_id'];
		$employee_id = (int)$post['employee_id'];
		$roles_id = (int)$post['roles_id'];

		if(!validate('name',$name)){
			return false;
		}
		if(!validate('phone',$phone)){
			return false;
		}

		$result =  $this->load('employee')->update(array(
			'name'=>$name,
			'phone'=>$phone,
			'sex'=>$sex,
			'department_id'=>$department_id,
			'roles_id'=>$roles_id,
			'employee_id'=>$employee_id
		));
		if($result){
			return $this->load('employee')->findById($employee_id);
		}else{
			return false;
		}
	}
	public function delete(){
		$post = $this->post;
		$employee_id = (int)$post['employee_id'];
		return $this->load('employee')->delete($employee_id);
	}
}
?>