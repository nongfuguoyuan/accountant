<?php
class BasicController {
	public $dao;
	public $_model;
	
	public function __construct($tableName){	
		
		$this->dao=new BasicDAO($tableName);	
	}
	
	//添加
	public function save($arr){
		$tag=$this->dao->save($arr);
		return $tag;
	}
	//删除
	function delete($id){
		$tag = $this->dao->delete($id);
		return $tag;
	}
	//更新
	public function update($arr){	
		$tag=$this->dao->update($arr);
		return $tag;
	}
	//查找所有
	public function findAll(){
		$where='1=1';
		$objs=$this->dao->findByWhere($where);
		return $objs;
	}
	
	//方法不存在调用此方法
	public function __call($name,$arguments){	
			
		if (preg_match("/^findby/i", $name)){
			$name=BasicController::orm(lcfirst(substr($name, 6)));
			$value=$arguments[0];
			$where='';
			foreach ($value as $key=>$val){
				if(preg_match('/$[0-9]^/', $val)){
					$where.=$key."=".$val;
				}else {
					$where.=$key."='".$val."'";
				}
				break;	
			}
			
			$objs=$this->dao->findByWhere($where);
			
			return $objs;
		}
		return false;
	}
	//模糊查询
	public function search($arr){
		$where='';
		foreach ($arr as $key=>$val){
			$where.="`".$key."` like '%".$val."%'";
			break;
		}
		$objs=$this->dao->search($where);
		if(count($objs)==0){
			return false;
		}
		return $objs;
	}
	/*
	 * 字符串转换example:modelName->model_name
	*/
	public static function orm($name){
		$reg = "/([A-Z]{1})/";
		$tableName = preg_replace($reg, "_\$1", $name);
		$tableName=strtolower($tableName);
	
		return $tableName;
	}
}

?>