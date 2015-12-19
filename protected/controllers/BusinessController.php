<?php
	class BusinessController extends ZjhController{

		/*查询已经开通工商类服务*/
		function findOpen(){
			$guest_id = (int)$this->post['guest_id'];

			if($guest_id == 0){
				return '请先指定用户';
			}
			
			//已经开通服务

			$opens = $this->load('business')->findOpen($guest_id);

			foreach($opens as $key => $value){
				//进度
				$progress = $this->load('progress')->findName((int)$value['business_id']);
				if(empty($progress)){
					$opens[$key]['progress'] = '';
				}else{
					$opens[$key]['progress'] = $progress['name'];
				}
				//开通服务名称
				$name = $this->load('processgroup')->findNameById((int)$value['process_group_id']);
				if(empty($name)){
					$opens[$key]['name'] = '';
				}else{
					$opens[$key]['name'] = $name['name'];
				}
				//全部流程
				$whole = $this->load('processgroup')->findWhole((int)$value['process_group_id']);
				if(empty($whole)){
					$opens[$key]['whole'] = '';
				}else{
					$opens[$key]['whole'] = $whole;
				}
				if(!empty($progress) && !empty($whole)){
					$progress_value = 20;
					foreach($whole as $k => $v){
						if($v['name'] == $progress['name']){
							$progress_value = (int)(($k+1)*100/count($whole));
						}
					}
					$opens[$key]['progress_value'] = $progress_value;
				}
			}
			return $opens;
		}

		function updateProcess(){
			$post = $this->post;
			$process_id = (int)$post["process_id"];
			$business_id = (int)$post['business_id'];
			if($business_id == 0 || $process_id == 0){
				return false;
			}
			return $this->load('business')->updateProcess(array(
				'process_id'=>$process_id,
				'business_id'=>$business_id
			));
		}

		function updateFee(){
			$post = $this->post;
			$should_fee = (float)$post['should_fee'];
			$have_fee = (float)$post['have_fee'];
			$business_id = (int)$post['business_id'];

			if($business_id == 0){
				return '请指定记录';
			}
			if($should_fee <= 0){
				return '应收款必须为正数';
			}
			if($have_fee < 0){
				return '已收款必须为正数';
			}

			//查询业务是否是当前登录员工负责
			$result = $this->load("business")->findByEmployee($this->session['user']['employee_id'],$business_id);
			if(!$result){
				return "只有负责员工才能操作";
			}
			return $this->load('business')->updateFee(array(
				'business_id'	=> $business_id,
				'should_fee'	=> $should_fee,
				'have_fee'		=> $have_fee
			));
		}

		function updateStatus(){
			$post = $this->post;
			$business_id = (int)$post['business_id'];
			if($business_id == 0){
				return '请先指定记录';
			}
			//查询当前登录用户是否负责
			$result = $this->load("business")->findByEmployee($this->session['user']['employee_id'],$business_id);
			if(!$result){
				return '只有负责此用户员工才能操作';
			}
			return $this->load('business')->updateStatus(array(
				'status'=>1,
				'business_id'=>$business_id
			));
		}

		//查询客服自己名下
		private function server_find($params){
			return $this->load("business")->server_find($params,array($this->page()));
		}
		//查询会计自己负责
		private function accounting_find($params){
			return $this->load("business")->accounting_find($params,array($this->page()));
		}
		//查询所有客服名下
		private function server_admin_find($params){
			return $this->load("business")->server_admin_find($params,array($this->page()));
		}
		//查询所有会计负责
		private function accounting_admin_find($params){
			return $this->load("business")->accounting_admin_find($params,array($this->page()));
		}
		//管理员
		private function admin_find($params){
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
				return $this->load('business')->admin_find($params,array($this->page()));
			}
		}

		function find(){
			$post = $this->post;
			$tag = $this->session['user']['tag'];
			$level = $this->session['user']['level'];

			$params = array(
				"status"   		=> (int)$post['status'],
				'com'			=> $post['com'],
				'phone'			=> $post['phone'],
				'year'			=> (int)$post['year'],
				'month'			=> (int)$post['month'],
				'process_id'	=> (int)$post['process_id']
			);

			if($level == 2){

				$params['employee_id'] = (int)$post['employee_id'];
				$params['department_id'] = (int)$post['department_id'];

				if($tag == 'server_admin'){
					return $this->server_admin_find($params);
				}
				if($tag == 'accounting_admin'){
					return $this->accounting_admin_find($params);
				}

			}else if($level == 1){
				$params['employee_id'] = (int)$this->session['user']['employee_id'];

				if($tag == 'server'){
					return $this->server_find($params);
				}
				if($tag == 'accounting'){
					return $this->accounting_find($params);
				}
			}else if($level > 2){
				//管理员
				$params['employee_id'] = (int)$post['employee_id'];
				return $this->admin_find($params);
			}

		}
		
		function save(){

			$post = $this->post;
			$guest_id = (int)$post["guest_id"];
			$employee_id = (int)$post['employee_id'];
			$should_fee = (float)$post['should_fee'];
			$have_fee = (float)$post['have_fee'];
			$process_group_id = (int)$post['process_group_id'];
			
			if($process_group_id == 0){
				return '请指定工商类型';
			}
			if($guest_id == 0){
				return '请指定用户';
			}
			if($should_fee <= 0){
				return '应付款必须大于0';
			}
			if($have_fee < 0){
				return '已付款不能为负数';
			}
			if($employee_id == 0){
				return '请指定负责会计';
			}

			//查询此用户是否已经开通了指定类型的业务
			$result = $this->load('business')->findByProcessGroupid($process_group_id,$guest_id);
			if($result){
				return '不能重复开通此业务';
			}
			//查询当前的登录员工是否负责此用户
			$result = $this->load('guest')->findByEmployee($this->session['user']['employee_id'],$guest_id);
			if($result){
				return $this->load('business')->add(array(
					'guest_id' => $guest_id,
					'process_group_id'=>$process_group_id,
					'employee_id' => $employee_id,
					'should_fee' => $should_fee,
					'have_fee' => $have_fee
				));
			}else{
				return '只能负责此用户员工才能操作';
			}
		}

		function update(){
			$post = $this->post;
			$employee_id = (int)$post['employee_id'];
			$should_fee = $post['should_fee'];
			$have_fee = $post['have_fee'];
			$status = (int)$post['status'];
			$business_id = (int)$post['business_id'];
			$process_group_id = (int)$post['process_group_id'];
			
			if($process_group_id == 0 || $business_id == 0 || $employee_id == 0 || $should_fee == 0 || $have_fee == 0){
				return false;
			}			
			return $this->load('business')->update(array(
				'process_group_id'=>$process_group_id,
				'employee_id' => $employee_id,
				'should_fee' => $should_fee,
				'have_fee' => $have_fee,
				'status' => $status,
				'business_id' => $business_id
			));
		}
		function delete(){
			$business_id = (int)$this->post['business_id'];
			return $this->load('business')->delete($business_id);
		}
		/*
			add by zgj 2015-12-8
			查询工商代办事项
		*/
		function waitProcess(){
			$page = $this->page();
			$employee_id = (int)$this->session['user']['employee_id'];
			return $this->load('business')->waitProcess($employee_id,array($page));	

		}
	}
?>