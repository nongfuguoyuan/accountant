<?php
	class PayrecordController extends ZjhController{
		
		function findList(){
			$accounting_id = (int)$this->post['accounting_id'];
			if($accounting_id == 0) return false;
			else return $this->load('payrecord')->findList($accounting_id);
		}

		function save($params){
			$post = $this->post;
			$accounting_id = (int)$post['accounting_id'];
			$money = (float)$post['money'];
			$deadline = $post['deadline'];

			if($money <= 0){
				return false;
			}
			if($accounting_id == 0){
				return false;
			}
			if(strtotime(date('Y-m-d H:m:s'))>=strtotime($deadline)){
				return false;
			}

			$lastid =  $this->load('payrecord')->add(array(
				'accounting_id'=>$accounting_id,
				'money'=>$money,
				'deadline'=>$deadline
			));

			if($lastid) return $this->load('payrecord')->findOneList($lastid);
			else return false;

		}

		function find(){
			return $this->load('payrecord')->find();
		}

		function update($params){

		}

		function delete($pay_record_id){
			$pay_record_id = (int)$this->post['pay_record_id'];
			if($pay_record_id == 0) return false;
			else return $this->load('payrecord')->delete($pay_record_id);
		}

		function findById($pay_record_id){
			
		}
	}