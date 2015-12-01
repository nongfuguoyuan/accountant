<?php
require_once 'zjhcontroller.php';

class FinanceController extends Zjhcontroller{
	public function findAll(){
		$result=$this->load('finance')->find();
		return $result;
	}

	public function save(){
		$post=$this->post;
		$guest_name=isset($post['guest_name'])?$post['guest_name']:null;//客户名称，没有和guest表绑定
		$charge=isset($post['charge'])?$post['charge']:null;//负责人
		$type=isset($post['type'])?$post['type']:null;//收费类型
		$pay_type=isset($post['pay_type'])?$post['pay_type']:null;//支付方式
		$payee=isset($post['payee'])?$post['payee']:null;
		$cost=isset($post['cost'])?$post['cost']:0.0;//费用
		$receive=isset($post['receive'])?$post['receive']:0.0;//已收费用
		$review=isset($post['review'])?$post['review']:null;//审核内容
		$create_time=isset($post['create_time'])?$post['create_time']:date('Y-m-d H:i:s');//创建时间默认当前时间
		$result=$this->load('finance')->add(array(
				'guest_name'=>$guest_name,
				'charge'=>$charge,
				'type'=>$type,
				'pay_type'=>$pay_type,
				'payee'=>$payee,
				'cost'=>$cost,
				'receive'=>$receive,
				'review'=>$review,
				'create_time'=>$create_time
			));
		return $result;

	}

	public function delete(){
		$post=$this->post;
		$finance_id=isset($post['finance_id'])?$post['finance_id']:null;
		$result=$this->load('finance')->delete(array("finance_id"=>$finance_id));
		return $result;
	}

	public function update(){
		$post=$this->post;
		$finance_id=isset($post['finance_id'])?$post['finance_id']:null;
		$guest_name=isset($post['guest_name'])?$post['guest_name']:null;//客户名称，没有和guest表绑定
		$charge=isset($post['charge'])?$post['charge']:null;//负责人
		$type=isset($post['type'])?$post['type']:null;//收费类型
		$pay_type=isset($post['pay_type'])?$post['pay_type']:null;//支付方式
		$payee=isset($post['payee'])?$post['payee']:null;
		$cost=isset($post['cost'])?$post['cost']:0.0;//费用
		$receive=isset($post['receive'])?$post['receive']:0.0;//已收费用
		$review=isset($post['review'])?$post['review']:null;//审核内容
		$create_time=isset($post['create_time'])?$post['create_time']:date('Y-m-d H:i:s');//创建时间默认当前时间
		$result=$this->load('finance')->update(array(
				'finance_id'=>$finance_id,
				'guest_name'=>$guest_name,
				'charge'=>$charge,
				'type'=>$type,
				'pay_type'=>$pay_type,
				'payee'=>$payee,
				'cost'=>$cost,
				'receive'=>$receive,
				'create_time'=>$create_time,
				'review'=>$review
			));
		return $result;
	}
}