<?php
	
	class TaxcollectController extends ZjhController{

		function findTotal($parent_id){// 1== 国税 2==地税

		}
		function find(){
			//找出所有代理记账用户
			$guests = $this->load('accounting')->findHasAccounting();
			foreach($guests as $key => $value){
				$guest_id = (int)$value['guest_id'];
				//国税
				$nation = $this->load('taxcount')->findCount($guest_id,1);
				//地税
				$local = $this->load('taxcount')->findCount($guest_id,2);

				$guests[$key]['nation'] = empty($nation) ? 0 : $nation['fee'];
				$guests[$key]['local'] = empty($local) ? 0 : $local['fee'];
				$guests[$key]['month'] = empty($nation) ? null : $nation['month'];
				$guests[$key]['year'] = empty($nation) ? null : $nation['year'];
			}

			return $guests;
		}
		//最近缴纳的月份
		function currentMonth(){

		}

		function findById(){

		}

		function save(){

			$post = $this->post;
			$post = $post['data'];

			$guest_id = (int)$post['guest_id'];
			$year = (int)$post['year'];
			$month = (int)$post['month'];
			$nation_count = (float)$post['nationCount'];
			$local_count = (float)$post['localCount'];
			$nation_data = $post['nationData'];
			$local_data = $post['localData'];

			if($guest_id == 0){
				return false;
			}

			if(!preg_match('/^20\d{2}$/',$year)){
				return false;
			}

			if(!preg_match('/^\d{1,2}$/',$month)){
				return false;
			}

			if(!preg_match('/(^\d+$)|(^\d+[.]\d+$)/',$nation_count)){
				return false;
			}

			if(!preg_match('/(^\d+$)|(^\d+[.]\d+$)/',$local_count)){
				return false;
			}

			if(!is_array($nation_data) || !is_array($local_data)){
				return false;
			}

			// return array($guest_id,$year,$month,$nation_count,$local_count,$nation_data,$local_data);

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
			//插入国税总额
			$nation_id = $this->load('taxcount')->add(array(
				'guest_id'=>$guest_id,
				'fee'=>$nation_count,
				'year'=>$year,
				'month'=>$month,
				'parent_id'=> 1
			));
			//插入地税总额
			$local_id = $this->load('taxcount')->add(array(
				'guest_id'=>$guest_id,
				'fee'=>$local_count,
				'year'=>$year,
				'month'=>$month,
				'parent_id'=>2
			));
			
			if(!empty($nation_id) && !empty($local_id)){
				return 1;
			}else{
				return false;
			}

		}

		function delete(){
			return $this->load('taxcollect')->delete((int)$this->post['tax_collect_id']);
		}
	}