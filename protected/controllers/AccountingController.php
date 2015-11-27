<?php
	class AccountingController extends ZjhController{
		function save(){
			$post = $this->post;
			$guest_id = (int)$post['guest_id'];
			$employee_id = (int)$post['employee_id'];
			$status = 0;

			if($guest_id == 0 || $employee_id == 0){
				return false;
			}
						
			$lastid = $this->load('accounting')->add(array(
				'guest_id'=>$guest_id,
				'employee_id'=>$employee_id,
				'status'=>0
			));
			// if($lastid) return $this->load('accounting')->findById($lastid);
			// else return false;
			return $lastid;
		}

		function update(){
			$post = $this->post;
			$guest_id = (int)$post['guest_id'];
			$employee_id = (int)$post['employee_id'];
			$accounting_id = (int)$post['accounting_id'];

			if($guest_id == 0 || $employee_id == 0 || $accounting_id == 0){
				return false;
			}
			$result = $this->load('accounting')->update(array(
				'guest_id'=>$guest_id,
				'employee_id'=>$employee_id,
				'accounting_id'=>$accounting_id
			));

			if($result) return $this->load('accounting')->findById($accounting_id);
			else return false;
		}
		function updateStatus(){
			return $this->load('accounting')->updateStatus((int)$this->post['accounting_id']);
		}
		function find(){
			return $this->load('accounting')->find(array($this->page()));
		}
		function delete(){
			$accounting_id = (int)$this->post['accounting_id'];
			return $this->load('accounting')->delete($accounting_id);
		}
	}