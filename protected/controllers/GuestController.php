<?php
class GuestController extends \BasicController {

	public function __construct($tableName=__CLASS__){
		
		parent::__construct($tableName);
	}
	
	//重写了父类方法因为需要添加字段
	public function findAll(){
		
		$select='select count(record.record_id) as r_count, guest.*,area.name as a_name,resource.description as r_name,employee.name as e_name';
		$tables=array('guest','area','resource','employee','record');
		$id = array('area','resource','employee','guest');
		$ids = array('area','resource','employee','guest');
		$groupby=' GROUP BY guest.guest_id ';
		$order=' order by guest_id desc';
		$objs=$this->dao->leftJoin($select, $tables, $ids,$groupby,$order);

		return $objs;
	}
	
	public function findOne($start,$limit){
	
		$select='select count(record.record_id) as r_count, guest.*,area.name as a_name,resource.description as r_name,employee.name as e_name';
		$tables=array('guest','area','resource','employee','record');
		$id = array('area','resource','employee','guest');
		$ids = array('area','resource','employee','guest');
		$groupby=' GROUP BY guest.guest_id ';
		$order=' order by guest_id desc';
		$objs=$this->dao->leftJoin($select, $tables, $ids,$groupby,$order,$start,$limit);
	
		return $objs;
	}
	public function save($arr){
		if((isset($arr['name'])&&isset($arr['phone']))||(isset($arr['name'])&&isset($arr['tel']))){
			$info=null;
			$arr1=array();
			if(strlen($arr['phone'])==11){//判断手机号码长度
				if(!$this->findbyphone(array('phone'=>$arr['phone']))){//判断手机号码是否存在
					$tag=$this->dao->save($arr);//保存记录
					if($tag){
						$arr1['tag']=$tag;
						//获取插入的最新数据
						$where='1=1';
						$order=' guest_id desc';
						$info=$this->findOne(0,1);
						$info=$info['data'];
						$arr1['info']=$info;
					}
					
				}else {
					$info="手机号码已存在";
					$tag=false;
				}
				
			}else {
				$info="手机号码长度不是11位";
				$tag=false;
			}
			
		}else{
			$tag=false;
		}
		$arr1=array('tag'=>$tag,'info'=>$info);
		return $arr1;
	}
	
}

?>