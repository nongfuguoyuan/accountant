<?php
	require CUSTOMER."engine/sendsms.php";

	class NewpasswordController extends CommonController{
		function get(){
			if($this->session['sms']){
				$sms = $this->post["sms"];
				if($this->session['sms'] == $sms){
					$phone = $this->session['phone'];
					$new_pass = randomPass(6);
					$s_pass = secret($new_pass);
					$ret = $this->load("guest")->updatePassByPhone($phone,$s_pass);
					if($ret){
						$result = send_sms_code($phone,"新密码:".$new_pass.",请妥善保存");
						return json_encode(array(
							"error_code"	=> 0
						));	
					}else{
						//update fail
						return json_encode(array(
							"error_code"	=> 13
						));
					}
				}else{
					return json_encode(array(
						"error_code"	=> 7
					));
				}
			}else{
				return json_encode(array(
					"error_code"	=> 6
				));
			}
		}
	}
?>