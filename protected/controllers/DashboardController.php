<?php
	
	class DashboardController extends ZjhController{

		function findOverview(){
			$tag = $this->session['user']['tag'];
			$employee_id = $this->session['user']['employee_id'];

			if($tag == 'server'){
				$obj = $this->load('guest');
				return array(
					'ing' 	=> $obj->findCount(0,$employee_id),
					'deal' 	=> $obj->findCount(1,$employee_id),
					'lose' 	=> $obj->findCount(2,$employee_id)
				);
			}
			if($tag == 'accounting'){
				
				$business = $this->load('business');
				$accounting = $this->load('accounting');

				$result = array(
					'accept_business'		=> $business->findCount(1,$employee_id),
					'unaccept_business'		=> $business->findCount(0,$employee_id),
					'accept_accounting'		=> $accounting->findCount(1,$employee_id),
					'unaccept_accounting'	=> $accounting->findCount(0,$employee_id),
					'owe_business'			=> $business->findOwe($employee_id),//未及时更新状态工商用户
					'owe_accounting'		=> $accounting->findOwe($employee_id)//15天后欠费用户
				);
				
				return $result;
			}
			if($tag == 'server_admin'){
				$obj = $this->load('guest');
				return array(
					'ing' 	=> $obj->findCount(0,$employee_id),
					'deal' 	=> $obj->findCount(1,$employee_id),
					'lose' 	=> $obj->findCount(2,$employee_id),
					'd_ing'	=> $obj->findCount(0),
					'd_deal'=> $obj->findCount(1),
					'd_lose'=> $obj->findCount(2)
				);
			}
			
			if($tag == 'accounting_admin'){
				$business = $this->load('business');
				$accounting = $this->load('accounting');

				$result = array(
					'accept_business'		=> $business->findCount(1,$employee_id),
					'unaccept_business'		=> $business->findCount(0,$employee_id),
					'accept_accounting'		=> $accounting->findCount(1,$employee_id),
					'unaccept_accounting'	=> $accounting->findCount(0,$employee_id),
					'owe_business'			=> $business->findOwe($employee_id),//未及时更新状态工商用户
					'owe_accounting'		=> $accounting->findOwe($employee_id),//15天后欠费用户
					'd_accept_business'		=> $business->findCount(1),
					'd_unaccept_business'	=> $business->findCount(0),
					'd_accept_accounting'	=> $accounting->findCount(1),
					'd_unaccept_accounting'	=> $accounting->findCount(0),
					'd_owe_business'		=> $business->findOwe(),//未及时更新状态工商用户
					'd_owe_accounting'		=> $accounting->findOwe()//15天后欠费用户
				);

				return $result;
			}

			if($tag == 'admin'){
				$business = $this->load('business');
				$accounting = $this->load('accounting');
				$obj = $this->load('guest');

				$result = array(
					'ing' 					=> $obj->findCount(0),
					'deal' 					=> $obj->findCount(1),
					'lose' 					=> $obj->findCount(2),
					'accept_business'		=> $business->findCount(1),
					'unaccept_business'		=> $business->findCount(0),
					'accept_accounting'		=> $accounting->findCount(1),
					'unaccept_accounting'	=> $accounting->findCount(0),
					'owe_business'			=> $business->findOwe(),//未及时更新状态工商用户
					'owe_accounting'		=> $accounting->findOwe()//15天后欠费用户
				);
				return $result;
			}
		}

	}