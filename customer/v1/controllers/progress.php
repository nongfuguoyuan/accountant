<?php
	class ProgressController extends CommonController{
		function get(){
			// sleep(5);
			if(!empty($this->session['guest'])){
				$business_id = $this->post["business_id"];
				if(empty($business_id)){
					return json_encode(array(
						"error_code"	=> 10
					));
				}else{
					$obj = $this->load("progress");
					$result = $obj->findProcessed($business_id);
					$rest = $obj->findRest(end($result)['process_id']);
					if(!empty($rest)){
						foreach($rest as $key => $value){
							array_push($result,array(
								"name"		=> $value['name'],
								'note'		=> "请稍候...",
								"date_end"	=>	""
							));
						}
					}
					return json_encode(array(
						"error_code"	=> 0,
						"data"			=> $result
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