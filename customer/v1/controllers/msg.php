<?php
	class MsgController extends CommonController{
		function get(){
			$guest_id = $this->session['guest'];
			// $guest_id = 79;
			if(!empty($guest_id)){
				$readed = isset($this->post['readed']) ? $this->post['readed'] : 0;
				$result = $this->load("msg")->find($guest_id,$readed);
				if(!empty($result)){
					//改变当前消息为已读
					if($readed == 0){
						$this->load("msg")->update($guest_id,1);
					}
					return json_encode(array(
						"error_code"	=> 0,
						"data"			=> $result
					));
				}else{
					return json_encode(array(
						"error_code"	=> 0,
						"data"			=> array()
					));
				}
			}else{
				return json_encode(array(
					"error_code"	=> 1
				));
			}
		}

		function delete(){
			$guest_id = $this->session['guest'];
			if(!empty($guest_id)){
				$ids = $this->post["ids"];
				$ids = preg_replace('/[\[\]]/',"",$ids,-1);
				$arr = explode(",",$ids);
				$result = $this->load("msg")->updateShow($guest_id,$arr);
				return json_encode(array(
					"error_code"	=> 0,
					"data"			=> $result
				));
			}else{
				return json_encode(array(
					"error_code"	=> 1
				));
			}
		}
	}
?>