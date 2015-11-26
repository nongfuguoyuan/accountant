<?php
	//zjh
	class ProgressController extends ZjhController{
		function find(){
			$business_id = (int)$this->post['business_id'];
			return $this->load('progress')->find($business_id);
		}
		// function findNote(){
		// 	return $this->load('progress')->findNote(9);
		// }
		function save(){
			$post = $this->post;
			$business_id = (int)$post['business_id'];
			$process_id = (int)$post['process_id'];
			$note = $post['note'];
			$date_end = $post['date_end'];

			return $this->load('progress')->add(array(
				'business_id'=>$business_id,
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