<?php
	class PayrecordController extends ZjhController{
		
		function findList(){
			$accounting_id = (int)$this->post['accounting_id'];
			$year = $this->post['year'];

			if($accounting_id == 0){
				return '请先指定记录';
			}
			if(!validate('year',$year)){
				return '请先选定日期';
			}
			return $this->load('payrecord')->findList($accounting_id,$year);
		}

		function update(){
			$post = $this->post;

			$pay_record_id = (int)$post['pay_record_id'];
			$money = (float)$post['money'];
			$deadline = $post['deadline'];

			if(empty($pay_record_id)){
				return '请先指定记录';
			}
			if($money < 0){
				return '请输入正确金额';
			}
			if(!validate('date',$deadline)){
				return '请输入正确日期';
			}
			
			$result = $this->load('payrecord')->update(array(
				'pay_record_id'	=> $pay_record_id,
				'money'			=> $money,
				'employee_id'	=> $this->session['user']['employee_id'],
				'deadline'		=> $deadline
			));
			return $result;
		}

		function save(){
			$post = $this->post;
			$accounting_id = (int)$post['accounting_id'];
			$money = (float)$post['money'];
			$deadline = $post['deadline'];

			if($money <= 0){
				return '金额不正确';
			}
			if($accounting_id == 0){
				return '请先指定记录';
			}
			
			$acc = $this->load('accounting')->findStatus($accounting_id);
			if(!$acc || $acc['status'] == 0){
				return '请先受理此任务';
			}

			//查询是否由当前登录用户负责
			$acc = $this->load('accounting')->findByEmployee($this->session['user']['employee_id'],$accounting_id);
			if(!$acc){
				return '只有负责员工才能操作';
			}

			if(!validate('date',$deadline) || strtotime(date('Y-m-d H:m:s'))>=strtotime($deadline)){
				return '期限需要大于今天';
			}

			$lastid =  $this->load('payrecord')->add(array(
				'accounting_id'=>$accounting_id,
				'money'=>$money,
				'deadline'=>$deadline
			));

			if($lastid){
				return $this->load('payrecord')->findOneList($lastid);	
			}else{
				return 0;//save fail
			}
		}
		//按指定日期
		private function server_find_date($params){
			return $this->load('payrecord')->server_find_date($params,array($this->page()));
		}
		private function accounting_find_date($params){
			// return $params
			// return array();
			return $this->load('payrecord')->accounting_find_date($params,array($this->page()));
		}
		private function server_admin_find_date($params){
			return $this->load('payrecord')->server_admin_find_date($params,array($this->page()));
		}
		private function accounting_admin_find_date($params){
			return $this->load('payrecord')->accounting_admin_find_date($params,array($this->page()));
		}
		private function admin_find_date($params){
			return $this->load('payrecord')->admin_find_date($params,array($this->page()));
		}

		//按最近日期
		private function server_find($params){
			return $this->load('payrecord')->server_find($params,array($this->page()));
		}
		private function accounting_find($params){
			return $this->load('payrecord')->accounting_find($params,array($this->page()));
		}
		private function server_admin_find($params){
			return $this->load('payrecord')->server_admin_find($params,array($this->page()));
		}
		private function accounting_admin_find($params){
			return $this->load('payrecord')->accounting_admin_find($params,array($this->page()));
		}
		private function admin_find($params){
			// return $this->load('payrecord')->admin_find($params,array($this->page()));
			if($params['employee_id']){
				$tag = $this->load("employee")->findTag($params['employee_id']);	
				if($tag){
					$tag = $tag['tag'];
					if($tag == 'server'){
						return $this->server_find($params);
					}
					if($tag == 'accounting'){
						return $this->accounting_find($params);
					}
				}else{
					return array();
				}
			}else{
				unset($params['employee_id']);
				return $this->load('payrecord')->admin_find($params,array($this->page()));
			}

		}
		function find(){
			$post = $this->post;
			$tag = $this->session['user']['tag'];
			$level = $this->session['user']['level'];

			$year = $post['year'];
			$month = $post['month'];
			$as_date = (!empty($year) || !empty($month));

			if(!empty($year)){
				if(!validate('year',$year)){
					return '年份不符合要求';
				}
			}
			if(!empty($month)){
				if(!validate('month',$month)){
					return '月份不符合要求';
				}
			}

			$params = array(
				'com'			=> $post['com'],
				'phone'			=> $post['phone'],
				'owe'			=> (int)$post['owe']
			);

			if($level == 2){
				$params['employee_id'] = (int)$post['employee_id'];
				$params['department_id'] = (int)$post['department_id'];
				if($as_date){
					$params['year'] = $year;
					$params['month'] = $month;
					unset($params['owe']);
					if($tag == 'server_admin'){
						return $this->server_admin_find_date($params);
					}
					if($tag == 'accounting_admin'){
						return $this->accounting_admin_find_date($params);
					}
				}else{
					if($tag == 'server_admin'){
						return $this->server_admin_find($params);
					}
					if($tag == 'accounting_admin'){
						return $this->accounting_admin_find($params);
					}
				}

			}else if($level == 1){
				$params['employee_id'] = $this->session['user']['employee_id'];				
				if($as_date){

					$params['year'] = $year;
					$params['month'] = $month;
					unset($params['owe']);

					if($tag == 'server'){
						return $this->server_find_date($params);
					}
					if($tag == 'accounting'){
						// return 1;
						return $this->accounting_find_date($params);
					}
				}else{
					if($tag == 'server'){
						return $this->server_find($params);
					}
					if($tag == 'accounting'){
						return $this->accounting_find($params);
					}
				}
			}else if($level > 2){
				$params['employee_id'] = (int)$post['employee_id'];
				if($as_date){
					$params['year'] = $year;
					$params['month'] = $month;
					unset($params['owe']);
					return $this->admin_find_date($params);	
				}else{
					return $this->admin_find($params);
				}
			}
		}

		function delete($pay_record_id){
			$pay_record_id = (int)$this->post['pay_record_id'];
			if($pay_record_id == 0){
				return '请先指定记录';
			}else{
				return $this->load('payrecord')->delete($pay_record_id);
			}
		}

	}