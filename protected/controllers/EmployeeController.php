<?php
// class EmployeeController extends \BasicController {

// 	public function __construct($tableName=__CLASS__){
		
// 		$this->_role=new RoleController("role");
// 		parent::__construct($tableName);
// 	}
	
// 	//重写了父类方法因为需要添加字段
// 	public function findAll(){
		
// 		$select='select employee.*,department.name as d_name,role.name as r_name';
// 		$tables=array('employee','department','role');
// 		$objs=$this->dao->leftJoin($select, $tables);
		
//  		return $objs;

// 	}
// 	public function save($arr){
// 		if(isset($arr['password'])){
// 			$arr['password']=sha1($arr['password']);
// 		}
// 		$this->dao->save($arr);
		
// 	}
// 	public function login($arr){
		
// 		if(isset($arr['phone'])&&isset($arr['password'])){
// 			$phone = $arr['phone'];
// 			$password = sha1($arr['password']);
// 			$obj = $this->findByPhone($arr);
// 			$obj = $obj['data'][0];

// 			if($password==$obj['password']){
// 				$_SESSION['employee']=$obj;
		
// 				return true;
// 			}else{
// 				return false;
// 			}
// 		}
// 	}
	
// 	public function logout(){
		
// 	}

// }
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
	
	function islogin(){
		return var_dump($this->session);
	}
	function session(){
		if(empty($this->session)){
			return false;
		}else{
			return $this->session;
		}
	}
	public function logout(){
		session_unset();
		session_destroy();
		header('Location:'._HOST.'login.html');
	}

	function permission(){
		if(empty($this->session['user']['permissions'])){
			return array();
		}else{
			return $this->session['user']['permissions'];
		}
	}

	public function login(){
		$post = $this->post;
		$phone = $post['phone'];
		$pass = $post['pass'];

		if(!validate('phone',$phone)){
			return false;
		}
		if(strlen($pass) < 5){
			return false;
		}

		$result = $this->load('employee')->login($phone,secret($pass));

		if($result){
			$per = $result['permissions'];
			
			if(!empty($per)){
				$result['permissions'] = unserialize($per);
			}
			
			$this->session['user'] = $result;
			
			header('Location:'._DASHBOARD);
		}else{
			header('Location:'._HOST.'login.html');
		}
	}

	public function find(){

		return $this->load('employee')->find(array($this->page()));
	}

	public function findByDepartmentid(){
		$department_id = (int)$this->post['department_id'];
		return $this->load('employee')->findByDepartmentid($department_id);
	}

	public function save(){
		$post = $this->post;
		$name = $post['name'];
		$phone = $post['phone'];
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