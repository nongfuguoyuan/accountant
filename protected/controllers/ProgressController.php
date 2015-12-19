<?php
	//zjh
	class ProgressController extends ZjhController{
		
		function find(){
			$business_id = (int)$this->post['business_id'];
			return $this->load('progress')->find($business_id);
		}
		
		function save(){
			$post = $this->post;
			$business_id = (int)$post['business_id'];
			$process_id = (int)$post['process_id'];
			$note = $post['note'];
			$date_end = $post['date_end'];

			if($business_id == 0){
				return '请指定任务';
			}
			
			if($process_id == 0){
				return '请指定现在进度';
			}
			if(strlen($note) < 3){
				return '字符太短';
			}
			if(!validate('date',$date_end)){
				return '请指定日期';
			}
			//是否当前登录员工负责
			$result = $this->load('business')->findByEmployee($this->session['user']['employee_id'],$business_id);
			if(!$result){
				return '只有负责员工才能操作';
			}
			return $this->load('progress')->add(array(
				'business_id'=>$business_id,
				'process_id'=>$process_id,
				'note'=>$note,
				'date_end'=>$date_end
			));
		}
		function update(){
			$post = $this->post;
			$progress_id = (int)$post['progress_id'];
			$business_id = (int)$post['business_id'];
			$process_id = (int)$post['process_id'];
			$note = $post['note'];
			$date_end = $post['date_end'];

			if($business_id == 0){
				return '请指定客服';
			}

			if($progress_id == 0){
				return "请指定记录";
			}

			if($process_id == 0){
				return '请指定现在进度';
			}
			if(strlen($note) < 3){
				return '字符太短';
			}
			if(!validate('date',$date_end)){
				return '请指定日期';
			}
			//是否当前登录员工负责
			$result = $this->load('business')->findByEmployee($this->session['user']['employee_id'],$business_id);
			if(!$result){
				return '只有负责员工才能操作';
			}
			return $this->load('progress')->update(array(
				'progress_id'=>$progress_id,
				'process_id'=>$process_id,
				'note'=>$note,
				'date_end'=>$date_end
			));
		}
		function delete(){
			$progress_id = (int)$this->post['progress_id'];
			return $this->load('progress')->delete($progress_id);
		}
	}
?>