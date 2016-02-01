<?php

class Msg extends Model{
	private $guest_id;//服务id可能是工商注册也可能是代理记账
	private $type;//类型
	public function __construct($guest_id,$type){
		parent::__construct();
		$id=$type.'_id';
		$sql="select guest_id from $type where $id=$guest_id";
		$business=$this->db->query($sql);
		var_dump($sql);
		$this->guest_id=$business[0]['guest_id'];
		$this->type=$type;

	}
	/**
	* @param $title 信息标题
	* @param $content 信息内容
	* @return 返回影响行数
	*/
	public function pushMessage($title,$content){
		if($this->type=='business'){
				$sql="insert into msg(type,guest_id,title,content,readed,showed,create_date)
			values('{$this->type}','{$this->guest_id}','$title','$content',0,1,now())";

			if(!$this->check()){
				
				return $this->db->exec($sql);
			}else{
				return 0;
			}	
		}elseif($this->type=='accounting'){
			$sql="select pay_record.deadline from accounting left join pay_record on accounting.accounting_id=pay_record.accounting_id
			where accounting.guest_id={$this->guest_id} ORDER BY deadline desc limit 0,1";

			$re = $this->db->query($sql);
			$deadline = $re[0][''];
			$de = date('Y-m-d H:i:s',time()+1296000);
			if($deadline>$re&&!$this->check()){
				$sql="insert into msg(type,guest_id,title,content,readed,showed,create_date)
				values('accounting','{$this->guest_id}','$title','$content',0,1,now())";
			}
		}
		
	}

	/**
	*检查是否已经有提醒
	*/
	private function check(){
		$sql="select msg_id from msg where guest_id={$this->guest_id} and type='{$this->type}' and readed=0";
		
		return $this->db->query($sql);
	}
}