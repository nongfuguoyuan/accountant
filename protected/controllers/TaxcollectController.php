<?php
	
	class TaxcollectController extends ZjhController{

		// 1== 国税 2==地税
		function find(){
			$post = $this->post;
			$year = (int)$post['year'];
			$month = (int)$post['month'];
			$guest_id = (int)$post['guest_id'];

			if(!validate('year',$year)){
				return false;
			}
			
			if(!validate('month',$month)){
				return false;
			}

			if($guest_id == 0){
				return false;
			}
			return $this->load('taxcollect')->findByGuestid($year,$month,$guest_id);
		}
		
		function update(){
			$tax_collect_id = (int)$this->post['tax_collect_id'];
			$guest_id = (int)$this->post['guest_id'];
			$fee = (float)$this->post['fee'];

			if($tax_collect_id == 0){
				return "请指定要编辑的记录";
			}
			if($guest_id == 0){
				return '请指定用户';
			}

			if($fee < 0){
				return "费用格式不正确";
			}

			return $this->load('taxcollect')->update(array(
				'tax_collect_id'	=> $tax_collect_id,
				'guest_id'			=> $guest_id,
				'employee_id'		=> $this->session['user']['employee_id'],
				'fee'				=> $fee
			));
		}

		function save(){

			$post = $this->post;
			$post = $post['data'];

			$guest_id = (int)$post['guest_id'];
			$year = (int)$post['year'];
			$month = (int)$post['month'];
			$nation_data = $post['nationData'];
			$local_data = $post['localData'];


			if($guest_id == 0){
				return '请指定用户';
			}

			if(!validate('year',$year)){
				return '请输入正确年份';
			}

			if(!validate('month',$month)){
				return '请输入正确月份';
			}

			//查询是否由当前登录用户负责
			$acc = $this->load('accounting')->findByGuestid($guest_id,$this->session['user']['employee_id']);
			if(!$acc){
				return '只有负责员工才能操作';
			}
			
			if(!is_array($nation_data)) $nation_data = array();
			if(!is_array($local_data)) $local_data = array();
			//插入国税类型
			foreach($nation_data as $key => $value){
				$tax_type_id = (int)$value['tax_type_id'];
				$fee = (float)$value['fee'];
				$this->load('taxcollect')->add(array(
					'tax_type_id'=>$tax_type_id,
					'year'=> $year,
					'month'=>$month,
					'fee' =>$fee,
					'guest_id'=>$guest_id
				));
			}
			//插入地税类型
			foreach($local_data as $key => $value){
				$tax_type_id = (int)$value['tax_type_id'];
				$fee = (float)$value['fee'];
				$this->load('taxcollect')->add(array(
					'tax_type_id'=>$tax_type_id,
					'year'=> $year,
					'month'=>$month,
					'fee' =>$fee,
					'guest_id'=>$guest_id
				));
			}
			
			return 1;

		}

		function delete(){

			return $this->load('taxcollect')->delete((int)$this->post['tax_collect_id']);
		}
	}