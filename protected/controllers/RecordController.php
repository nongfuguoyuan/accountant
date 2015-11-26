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

			if($guest_id == 0) return false;

			if(strlen($content) < 1){
				return false;
			}

			$lastid = $this->load('record')->add(array(
				'guest_id'=>$guest_id,
				'content'=>$content
			));

			if($lastid) return $this->load('record')->findById($lastid);
			else return false;
		}

		function delete(){

			$record_id = (int)$this->post['record_id'];

			if($record_id == 0) return false;
			else return $this->load('record')->delete($record_id);
		}
	}

?>