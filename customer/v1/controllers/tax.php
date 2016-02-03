<?php
	class TaxController extends CommonController{
		function get(){
			// sleep(5);
			$guest_id = $this->session['guest'];
			if(!empty($guest_id)){
				$post = $this->post;
				$year = $post['year'];
				$month = $post["month"];
				$arr = array(
					"guest_id"	=> $guest_id,
					"year"		=> $year,
					"month"		=> $month
				);
				$result = $this->load("tax")->find($arr);
				$count = $this->load("tax")->findCount($arr);
				if(!empty($count)){
					$nation = $count["nation"];
					$local = $count["local"];
				}else{
					$nation = 0;
					$local = 0;
				}
				return json_encode(array(
					"error_code"	=> 0,
					"data"			=> $result,
					"nation"		=> $nation,
					"local"			=> $local
				));
			}else{
				return json_encode(array(
					"error_code"	=> 1
				));
			}
		}
	}
?>