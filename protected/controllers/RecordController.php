<?php
class RecordController extends \BasicController {
	private $_guest;
	public function __construct($tableName=__CLASS__){
		$this->_guest=new GuestController();
		parent::__construct($tableName);
	}
	
	public function findByGuest($arr){
		$where = "guest_id=".$arr['guest_id'];
		$arr=$this->dao->findWhereOrderBy($where, "record_time desc");
		$arr = $arr['data'];
		for($i=0;$i<count($arr);$i++){
			$arr[$i]['record_time']=substr(($arr[$i]['record_time']), 0,10);
			$arr1[$i]['record_time']=$arr[$i]['record_time'];
			$arr1[$i]['content']=$arr[$i]['content'];
		}
		return $arr1;
	}
	
	public function save($arr){
		if(isset($arr['guest_id'])){
			$arr1=array('guest_id'=>$arr['guest_id']);
			$tag=$this->_guest->dao->findById($arr1);
			if($tag){
				$tag1=$this->dao->save($arr);
			}
			return $tag1;
		}else{
			return false;
		}
	}
}

?>