<?php
	class AccountingController extends ZjhController{

		function findOpen(){
			$guest_id = $this->post['guest_id'];
			if($guest_id == 0){
				return "请先指定记录";
			}
			return $this->load('accounting')->findOpen($guest_id);
		}

		function save(){
			$post = $this->post;
			$guest_id = (int)$post['guest_id'];
			$employee_id = (int)$post['employee_id'];

			if($guest_id == 0){
				return '请先指定用户';
			}
			if($employee_id == 0){
				return '请先指定负责人';
			}
			//查看此用户是否已经开通代理记账业务
			$result = $this->load('accounting')->findOpen($guest_id);
			if($result){
				return '不能重复开通此业务';
			}
			//查看当前登录员工是否负责此用户
			$result = $this->load('guest')->findByEmployee($this->session['user']['employee_id'],$guest_id);
			if(!$result){
				return '只有负责此用户员工能操作';
			}
			// zlog($lastid);
			$lastid = $this->load('accounting')->add(array(
				'guest_id'=>$guest_id,
				'employee_id'=>$employee_id
			));
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
			$accounting_id = (int)$this->post['accounting_id'];
			//查询是否由当前登录员工负责
			$result = $this->load('accounting')->findByEmployee($this->session['user']['employee_id'],$accounting_id);
			if(!$result){
				return '只有负责此用户员工能操作';
			}
			return $this->load('accounting')->updateStatus($accounting_id);
		}

		// 会计查询自己负责的用户
		private function accounting_find($params){
			return $this->load('accounting')->accounting_find($params,array($this->page()));
		}
		//会计主管查询所有人负责的用户
		private function accounting_admin_find($params){
			return $this->load('accounting')->accounting_admin_find($params,array($this->page()));
		}
		//客服查询自己跟踪的用户
		private function server_find($params){
			return $this->load('accounting')->server_find($params,array($this->page()));
		}
		//客服主管查询所有客服跟踪的用户
		private function server_admin_find($params){
			return $this->load('accounting')->server_admin_find($params,array($this->page()));
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
				return $this->load('accounting')->admin_find($params,array($this->page()));
			}
		}

		function find(){
			$post = $this->post;
			$tag = $this->session['user']['tag'];
			$level = $this->session['user']['level'];

			//是否欠费 0==欠费 1==正常
			$params = array(
				'status' 		=> (int)$post['status'],
				'com'			=> $post['com'],
				'phone'			=> $post['phone'],
				'year'			=> (int)$post['year'],
				'month'			=> (int)$post['month'],
				'owe'			=> (int)$post['owe']
			);

			if($level == 2){
				//主管级别
				$params['employee_id'] = (int)$post['employee_id'];
				$params['department_id'] = (int)$post['department_id'];

				if($tag == 'server_admin'){
					return $this->server_admin_find($params);
				}
				if($tag == 'accounting_admin'){
					return $this->accounting_admin_find($params);
				}

			}else if($level == 1){
				//自己
				$params['employee_id'] = (int)$this->session['user']['employee_id'];

				if($tag == 'server'){
					return $this->server_find($params);
				}
				if($tag == 'accounting'){
					return $this->accounting_find($params);
				}
			}else if($level > 2){
				$params['employee_id'] = (int)$post['employee_id'];
				return $this->admin_find($params);
			}
		}

		function delete(){
			$accounting_id = (int)$this->post['accounting_id'];
			return $this->load('accounting')->delete($accounting_id);
		}
	}