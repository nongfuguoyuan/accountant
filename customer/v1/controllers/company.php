<?php
	class CompanyController extends CommonController{
		function get(){
			$guest_id = isset($this->session["guest"]) ? $this->session['guest'] : 0;
			if(!empty($guest_id)){
				$result = $this->load("guest")->findCompany($guest_id);
				return json_encode(array(
					"error_code"	=> 0,
					"data"			=> $result
				));
			}else{
				return json_encode(array(
					"error_code"	=> 0
				));
			}
		}
	}