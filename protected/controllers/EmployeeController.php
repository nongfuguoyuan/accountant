<?php
/*
*modify author:zjh
*data:2015-11-19
*/
class EmployeeController extends ZjhController{

	function updateStatus(){
		$employee_id = (int)$this->post['employee_id'];
		$status = (int)$this->post['status'];

		if(empty($employee_id)){
			return '请指定员工';
		}

		return $this->load('employee')->updateStatus($employee_id,$status);
	}

	function updatePass(){
		$old_pass = $this->post['old_pass'];
		$new_pass = $this->post['new_pass'];

		if(!validate('pass',$old_pass)){
			return '密码不符合要求';
		}
		if(!validate('pass',$new_pass)){
			return '密码不符合要求';
		}

		return $this->load('employee')->updatePass(array(
			'old_pass'		=> secret($old_pass),
			'new_pass'		=> secret($new_pass),
			'employee_id'	=> $this->session['user']['employee_id']
		));
	}
	//查询所有的会计
	function findAccountings(){
		return $this->load('employee')->findByTag("accounting");
	}

	function session(){
		if(empty($this->session)){
			return '请登录';
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
	//not using right now
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
			return '姓名不符合要求';
		}
		if(!validate('phone',$phone)){
			return '电话不符合要求';
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
			return 0;//insert fail
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
			return '姓名不符合要求';
		}
		if(!validate('phone',$phone)){
			return '电话不符合要求';
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
			return 0;//update fail
		}
	}
	public function delete(){
		$post = $this->post;
		$employee_id = (int)$post['employee_id'];
		return $this->load('employee')->delete($employee_id);
	}
}
?>