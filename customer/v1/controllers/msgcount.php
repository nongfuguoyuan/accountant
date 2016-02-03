<?php
	class MsgcountController extends CommonController{
		function get(){
			$guest_id = isset($this->session['guest']) ? $this->session["guest"] : 0;
			if(!empty($guest_id)){
				$result = $this->load("msg")->findCount($guest_id,0);	
				if($result){
					return json_encode(array(
						"error_code"	=> 0,
						"data"			=> $result['count']
					));
				}else{
					return json_encode(array(
						"error_code"	=> 0,
						"data"			=> 0
					));
				}
			}else{
				return json_encode(array(
					"error_code"	=> 0
				));
			}
		}
	}