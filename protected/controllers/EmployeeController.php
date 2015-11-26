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
require 'zjhcontroller.php';

class EmployeeController extends ZjhController{

	public function findAll(){
		$post = $this->post;
		$page = isset($post['page'])? $post['page']:1;
		$pagenum = isset($post['pageNum'])?$post['pageNum']:1;
		
		$result = $this->load('employee')->find(array($page,$pagenum));
		if(!$result){
			$result = array();
		}
		return $result;
		// return json_encode($result);
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
		if(!validate('name',$name)){
			return false;
		}
		if(!validate('phone',$phone)){
			return false;
		}
		return $this->load('employee')->add(array(
			'name'=>$name,
			'phone'=>$phone,
			'sex'=>$sex,
			'create_time'=>timenow(),
			'department_id'=>$department_id
		));

	}
	public function update(){
		$post = $this->post;
		$name = $post['name'];
		$phone = $post['phone'];
		$sex = (int)$post['sex'];
		$department_id = (int)$post['department_id'];
		$employee_id = (int)$post['employee_id'];

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