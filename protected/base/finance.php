<?php
require_once 'model.php';
/**
* 
*/
class Finance extends Model
{
	
	public function find($page){
		$result=$this->db->query("select * from finance",array(),$page);
		return $result;

	}
	public function add($params){
		$result=$this->db->exec("insert into finance set 
				guest_name=:guest_name,
				charge=:charge,
				type=:type,
				pay_type=:pay_type,
				payee=:payee,
				cost=:cost,
				receive=:receive,
				create_time=:create_time,
				review=:review
			",$params);
		return $result;
	}

	public function delete($finance_id){
		$result=$this->db->exec("delete from finance where finance_id=:finance_id",$finance_id);
		return $result;
	}

	public function update($params){
		$result=$this->db->exec("update finance set
				guest_name=:guest_name,
				charge=:charge,
				type=:type,
				pay_type=:pay_type,
				payee=:payee,
				cost=:cost,
				receive=:receive,
				create_time=:create_time,
				review=:review where 
				finance_id=:finance_id
			",$params);
		return $result;
	}

}
?>