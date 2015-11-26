<?php
	
	class DashboardController extends ZjhController{
		function findCount(){
			// return 2;
			$employee_id = (int)$this->post['employee_id'];

			if(empty($employee_id)){
				return false;
			}

			return $this->load('dashboard')->findCount($employee_id);
		}

		function findDeal(){
			$employee_id = (int)$this->post['employee_id'];

			if(empty($employee_id)){
				return false;
			}

			return $this->load('dashboard')->findDeal($employee_id);
		}

		function findLose(){

			$employee_id = (int)$this->post['employee_id'];

			if(empty($employee_id)){
				return false;
			}

			return $this->load('dashboard')->findLose($employee_id);

		}

		function findIng(){
			$employee_id = (int)$this->post['employee_id'];

			if(empty($employee_id)){
				return false;
			}

			return $this->load('dashboard')->findIng($employee_id);
		}
	}