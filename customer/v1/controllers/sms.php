<?php 
	require CUSTOMER."engine/sendsms.php";

	class SmsController extends CommonController{
		function get(){
			$phone = $this->post["phone"];
			if(validate("phone",$phone)){
				$result = $this->load("guest")->findPhone($phone);
				if($result){
					//send sms
					$sms = create_sms_code(6);
					$this->session["sms"] = $sms;
					$send_result = send_sms_code($phone,"验证码".$sms);// ok == 1
					if($send_result == 1){
						$this->session['sms'] = $sms;
						$this->session['phone'] = $phone;
						return json_encode(array(
							'error_code'	=> 0
						));
					}else{
						return json_encode(array(
							'error_code'	=> 4
						)); 
					}
				}else{
					return json_encode(array(
						"error_code"	=> 2
					));
				}
			}else{
				return json_encode(array(
					"error_code"	=> 3
				));
			}
		}
	}