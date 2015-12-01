<?php
	
	class TaxcollectController extends ZjhController{

		// 1== 国税 2==地税

		function findList(){
			$post = $this->post;
			$year = (int)$post['year'];
			$month = (int)$post['month'];
			$guest_id = (int)$post['guest_id'];
			
			return $this->load('taxcollect')->findByGuestid($year,$month,$guest_id);
		}
		function update(){
			$tax_collect_id = (int)$this->post['tax_collect_id'];
			$fee = (float)$this->post['fee'];

			if($tax_collect_id == 0){
				return false;
			}

			if(!preg_match('/^\d+[.]?\d$/',$fee)){
				return false;
			}

			
			$result = $this->load('taxcollect')->updateById($tax_collect_id,$fee);

			if($result){
				return $this->load('taxcollect')->findById($tax_collect_id);
			}else{
				return false;
			}
		}
		function find(){
			//找出所有代理记账用户
			$page = $this->page();
			$guests = $this->load('accounting')->findHasAccounting(array($page));
			// return $guests;
			if($guests){
				$total = $guests['total'];
				$guests = $guests['data'];
			}

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

			return array('total'=>$total,'data'=>$guests);

		}

		function _find(){
			//找出所有代理记账用户
			$page = $this->page();
			$employee_id = (int)$this->session['user']['employee_id'];

			$guests = $this->load('accounting')->_findHasAccounting($employee_id,array($page));
			return $guests;
			if($guests){
				$total = $guests['total'];
				$guests = $guests['data'];
			}

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

			return array('total'=>$total,'data'=>$guests);

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
				return false;
			}

			if(!preg_match('/^20\d{2}$/',$year)){
				return false;
			}

			if(!preg_match('/^\d{1,2}$/',$month)){
				return false;
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