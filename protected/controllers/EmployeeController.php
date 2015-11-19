<?php
class EmployeeController extends \BasicController {

	public function __construct($tableName=__CLASS__){
		
		$this->_role=new RoleController("role");
		parent::__construct($tableName);
	}
	
	//重写了父类方法因为需要添加字段
	public function findAll(){
		
		$select='select employee.*,department.name as d_name,role.name as r_name';
		$tables=array('employee','department','role');
		$objs=$this->dao->leftJoin($select, $tables);
		
 		return $objs;

	}
	public function save($arr){
		if(isset($arr['password'])){
			$arr['password']=sha1($arr['password']);
		}
		$this->dao->save($arr);
		
	}
	public function login($arr){
		
		if(isset($arr['phone'])&&isset($arr['password'])){
			$phone = $arr['phone'];
			$password = sha1($arr['password']);
			$obj = $this->findByPhone($arr);
			$obj = $obj['data'][0];

			if($password==$obj['password']){
				$_SESSION['employee']=$obj;
		
				return true;
			}else{
				return false;
			}
		}
	}
	
	public function logout(){
		
	}

}

?>