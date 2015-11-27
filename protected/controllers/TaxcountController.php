<?php 
	class TaxcountController extends ZjhController{
		function save(){
			$post = $this->post;

			$guest_id = (int)$post['guest_id'];
			$year = (int)$post['year'];
			$month = (int)$post['month'];
			$nation_count = (float)$post['nation'];
			$local_count = (float)$post['local'];
			

			if($guest_id == 0){
				return false;
			}

			if(!preg_match('/^20\d{2}$/',$year)){
				return false;
			}

			if(!preg_match('/^\d{1,2}$/',$month)){
				return false;
			}

			if(!preg_match('/^\d+[.]?\d$/',$nation_count)){
				return false;
			}

			if(!preg_match('/^\d+[.]?\d$/',$local_count)){
				return false;
			}

			

			//插入国税总额
			$nation_id = $this->load('taxcount')->add(array(
				'guest_id'=>$guest_id,
				'fee'=>$nation_count,
				'year'=>$year,
				'month'=>$month,
				'parent_id'=> 1
			));
			// 插入地税总额
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

		}
	}
 ?>