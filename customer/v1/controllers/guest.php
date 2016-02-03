<?php 
	class GuestController extends CommonController{
		function get(){
			$phone = $this->post["phone"];
			$password = $this->post['password'];
			$password = secret($password);
			$result = $this->load("guest")->login($phone,$password);
			// return 1;
			if($result){
				$this->session['guest'] = $result['guest_id'];
				return json_encode(array(
					"error_code"	=> 0,
					"company"		=> $result['company']
				));
			}else{
				return json_encode(array(
					"error_code"	=> 8
				));
			}
		}
		// 修改密码
		function put(){
			$guest_id = isset($this->session['guest']) ? $this->session['guest'] : 0;
			if(!empty($guest_id)){
				$post = $this->post;
				$old_pass = $post["old_pass"];
				$new_pass = $post["new_pass"];
				if(strlen($old_pass) < 6 || strlen($new_pass) < 6){
					return json_encode(array(
						"error_code"	=> 11
					));
				}
				$old_pass = secret($old_pass);
				$new_pass = secret($new_pass);
				$result = $this->load("guest")->updatePass(array(
					"guest_id" => $guest_id,
					"old_pass" => $old_pass,
					"new_pass" => $new_pass 
				));
				if($result){
					return json_encode(array(
						"error_code"	=> 0
					));
				}else{
					//修改密码失败 
					return json_encode(array(
						"error_code"	=> 12
					));
				}
			}else{
				return json_encode(array(
					"error_code"	=> 1
				));
			}
		}
	}
?>