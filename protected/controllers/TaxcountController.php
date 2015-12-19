<?php 
	class TaxcountController extends ZjhController{

		//按日期查找
		private function server_findByDate($params){
			return $this->load("taxcount")->server_findByDate($params,array($this->page()));
		}
		private function accounting_findByDate($params){
			return $this->load("taxcount")->accounting_findByDate($params,array($this->page()));
		}
		private function server_admin_findByDate($params){
			return $this->load("taxcount")->server_admin_findByDate($params,array($this->page()));
		}
		private function accounting_admin_findByDate($params){
			return $this->load("taxcount")->accounting_admin_findByDate($params,array($this->page()));
		}
		private function admin_findByDate($params){
			return $this->load("taxcount")->admin_findByDate($params,array($this->page()));
		}
		//查找最近
		private function server_find($params){
			return $this->load("taxcount")->server_find($params,array($this->page()));
		}
		private function accounting_find($params){
			return $this->load("taxcount")->accounting_find($params,array($this->page()));
		}
		private function server_admin_find($params){
			return $this->load("taxcount")->server_admin_find($params,array($this->page()));
		}
		private function accounting_admin_find($params){
			return $this->load("taxcount")->accounting_admin_find($params,array($this->page()));
		}
		private function admin_find($params){
			return $this->load("taxcount")->admin_find($params,array($this->page()));
		}


		function find(){
			$page = $this->page();
			$post = $this->post;
			$level = $this->session['user']['level'];
			$tag = $this->session['user']['tag'];
			$year = (int)$post['year'];
			$month = (int)$post['month'];
			$as_date = !empty($year) || !empty($month);
			$department_id = (int)$post['department_id'];
			$employee_id = (int)$post['employee_id'];

			$params = array(
				'com'			=> $post['com'],
				'phone'			=> $post['phone']
			);

			if($level == 2){
				$params['department_id'] = $department_id;
				$params['employee_id'] = $employee_id;

				if($as_date){
					$params['year'] = $year;
					$params['month'] = $month;
					if($tag == 'server_admin'){
						return $this->server_admin_findByDate($params);
					}
					if($tag == 'accounting_admin'){
						return $this->accounting_admin_findByDate($params);
					}
				}else{
					if($tag == 'server_admin'){
						return $this->server_admin_find($params);
					}
					if($tag == 'accounting_admin'){
						return $this->accounting_admin_find($params);
					}
				}
			}
			if($level == 1){
				$params['employee_id'] = $this->session['user']['employee_id'];
				if($as_date){
					$params['year'] = $year;
					$params['month'] = $month;
					if($tag == 'server'){
						return $this->server_findByDate($params);
					}
					if($tag == 'accounting'){
						return $this->accounting_findByDate($params);
					}
				}else{
					if($tag == 'server'){
						return $this->server_find($params);
					}
					if($tag == 'accounting'){
						return $this->accounting_find($params);
					}
				}
			}
			if($level > 2){
				$params['employee_id'] = $employee_id;
				if($as_date){
					$params['year'] = $year;
					$params['month'] = $month;
					return $this->admin_findByDate($params);
				}else{
					return $this->admin_find($params);
				}
			}
		}

		function save(){
			$post = $this->post;

			$guest_id = (int)$post['guest_id'];
			$year = (int)$post['year'];
			$month = (int)$post['month'];
			$nation_count = (float)$post['nation'];
			$local_count = (float)$post['local'];
			

			if($guest_id == 0){
				return '请指定用户';
			}

			if(!validate('year',$year)){
				return '请选择年份';
			}

			if(!validate('month',$month)){
				return '请选择月份';
			}

			if(!is_numeric($nation_count)){
				return '国税格式不正确';
			}

			if(!is_numeric($local_count)){
				return '地税格式不正确';
			}
			//查询是否由当前登录用户负责
			$acc = $this->load('accounting')->findByGuestid($guest_id,$this->session['user']['employee_id']);
			if(!$acc){
				return '只有负责员工才能操作';
			}
			//查询指定日期、客户是否已经有记录，避免重复添加
			if($this->load('taxcount')->has(array(
				'guest_id'	=> $guest_id,
				'year'		=> $year,
				'month'		=> $month
			))){
				return "不能重复添加";
			}
			//插入总额
			$last_id = $this->load('taxcount')->add(array(
				'guest_id'=>$guest_id,
				'nation'=>$nation_count,
				'local'	=> $local_count,
				'year'=>$year,
				'month'=>$month
			));

			return $last_id;
		}

		function update(){
			$post = $this->post;
			$tax_count_id = (int)$post['tax_count_id'];
			$guest_id = (int)$post['guest_id'];
			$nation_count = (float)$post['nation'];
			$local_count = (float)$post['local'];

			if($tax_count_id == 0){
				return '请指定要编辑的记录';
			}

			if($guest_id == 0){
				return "请指定用户";
			}

			if(!is_numeric($nation_count)){
				return '国税格式不正确';
			}

			if(!is_numeric($local_count)){
				return '地税格式不正确';
			}

			//更新总额
			$result = $this->load('taxcount')->update(array(
				'tax_count_id'=>$tax_count_id,
				'guest_id'=>$guest_id,
				'nation'=>$nation_count,
				'local'	=> $local_count,
				'employee_id'=> $this->session['user']['employee_id']
			));
			return $result;
		}

		function delete(){
			$tax_count_id = (int)$this->post['tax_count_id'];
			return $this->load("taxcount")->delete($tax_count_id);
		}
	}
 ?>