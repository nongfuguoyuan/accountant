<?php
	//modify by zjh
	class RecordController extends ZjhController{

		function find(){
			$guest_id = (int)$this->post['guest_id'];
			if($guest_id == 0) return false;

			return $this->load('record')->find($guest_id);
		}

		function save(){
			$post = $this->post;
			$guest_id = (int)$post['guest_id'];
			$content = $post['content'];

			if($guest_id == 0){
				return '请指定用户';
			}

			if(strlen($content) < 1){
				return '追踪记录长度不够';
			}
			
			//检查权限
			$result = $this->load('guest')->findByEmployee($this->session['user']['employee_id'],$guest_id);

			if(!$result){
				return '只有添加此用户的员工可以操作';
			}else{
				$lastid = $this->load('record')->add(array(
					'guest_id'=>$guest_id,
					'content'=>$content
				));
				if($lastid){
					$this->load('record')->findById($lastid);
				}else{
					return 0;
				}
			}
		}
		function update(){
			$post = $this->post;
			$record_id = (int)$post['record_id'];
			$content = $post['content'];
			if(empty($record_id)){
				return false;
			}
			if(strlen($content) < 1){
				return false;
			}

			return $this->load('record')->update($record_id,$content);
		}

		function delete(){
			$record_id = (int)$this->post['record_id'];
			if($record == 0){
				return '请选择记录';
			}else{
				return $this->load('record')->delete($record_id);
			}
		}
	}

?>