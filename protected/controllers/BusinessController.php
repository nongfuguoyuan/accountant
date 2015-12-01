<?php
	class BusinessController extends ZjhController{

		/*查询已经开通服务*/
		function findOpen(){
			$guest_id = (int)$this->post['guest_id'];

			if($guest_id == 0){
				return false;
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
				// //全部流程
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
		function updateStatus(){
			$post = $this->post;
			$status = (int)$post["status"];
			$business_id = (int)$post['business_id'];
			if($business_id == 0){
				return false;
			}
			return $this->load('business')->updateStatus(array(
				'status'=>$status,
				'business_id'=>$business_id
			));
		}
		function _find(){

			$page = $this->page();
			$employee_id = (int)$this->session['user']['employee_id'];
			return $this->load('business')->_find($employee_id,array($page));
			
		}

		function find(){
			$page = $this->page();
			return $this->load('business')->find(array($page));
		}
		
		function save(){

			$post = $this->post;
			$guest_id = (int)$post["guest_id"];
			$employee_id = (int)$post['employee_id'];
			$should_fee = $post['should_fee'];
			$have_fee = $post['have_fee'];
			$status = 0;
			$process_group_id = (int)$post['process_group_id'];
			
			if($process_group_id == 0 || $guest_id == 0 || $employee_id == 0 || $should_fee == 0 || $have_fee == 0){
				return false;
			}
			return $this->load('business')->add(array(
				'guest_id' => $guest_id,
				'process_group_id'=>$process_group_id,
				'employee_id' => $employee_id,
				'should_fee' => $should_fee,
				'have_fee' => $have_fee,
				'status' => $status
			));

		}
		function update(){
			$post = $this->post;
			// $guest_id = (int)$post["guest_id"];
			// $process_id = (int)$post["process_id"];
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
	}
?>