<?php
	class ProcessController extends ZjhController{
		function getList(){
			$process_group_id = $this->post['process_group_id'];
			return $this->load('process')->findByGroupid($process_group_id);
		}
		function delete(){
			$process_id = (int)$this->post['process_id'];
			$obj = $this->load('process');
			$result = $obj->delete($process_id);
			if($result){
				return $this->find();
			}else{
				return false;
			}
		}
		function find(){
			$result = $this->load('process')->find();
			if($result){
				$keys = array();
				foreach($result as $key => $value){
					array_push($keys,$value['process_group_id']);
				}
				$arr = array();
				$keys = array_unique($keys);
				foreach($keys as $value){
					$str_arr = array();
					$g_name = '';
					foreach($result as $k => $v){
						if($v['process_group_id'] == $value){
							array_push($str_arr,$v['name']);
							$g_name = $v['g_name'];
						}
					}
					array_push($arr,array(
						'process_group_id'=>$value,
						'name'=>join('/',$str_arr),
						'g_name'=>$g_name
					));
				}
				return $arr;
			}else{
				return false;
			}
		}
		function save(){
			
			$post = $this->post;
			$process_group_id = (int)$post['process_group_id'];
			$values = $post['values'];
			
			if($process_group_id == 0){
				return false;
			}

			foreach($values as $k => $v){
				if(strlen($v) > 0){
					$this->load('process')->add(array(
						'process_group_id'=>$process_group_id,
						'name'=>$v
					));
				}
			}
			return $this->find();
		}

		function update(){
			$post = $this->post;
			$process_group_id = (int)$post['process_group_id'];
			$values = $post['values'];
			$obj = $this->load('process');

			foreach($values as $k => $v){
				if(strlen($v['name']) > 0){
					if($v['process_id'] == 0){
						//save
						$obj->add(array(
							'process_group_id'=>(int)$process_group_id,
							'name' => $v['name']
						));
					}else{
						//update
						$obj->update(array(
							'process_id'=>(int)$v['process_id'],
							'name' => $v['name']
						));
					}
				}
			}

			return $this->find();
		}
	}
?>