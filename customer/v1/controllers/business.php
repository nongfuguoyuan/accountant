<?php
	class BusinessController extends CommonController{
		function get($param){
			if(!empty($this->session['guest'])){
				//查询所有开通服务，及其进度
				$guest_id = $this->session["guest"];
				$obj = $this->load("business");
				$result = $obj->findOpen($guest_id);
				if($result){
					$ret = array();

					foreach($result as $key => $value){
						//业务名称
						// $business_name = $this->load("processgroup")->findName($value['process_group_id']);
						$pgd = $value["process_group_id"];
						if($pgd == 1){
							$business_name = "register";
						}
						if($pgd == 2){
							$business_name = "change";
						}
						if($pgd == 3){
							$business_name = "logout";
						}
						//业务各个进度名称集合
						$process_names = $this->load("process")->findName($value["process_group_id"]);
						//当前进度名称
						$current_name = $this->load("progress")->currentName($value['business_id'],$value['process_group_id']);

						$offset = 0;
						foreach($process_names as $k => $v){
							if($v['name'] == $current_name['name']){
								$offset = $k+1;
							}
						}
						$ret[] = array(
							'business_name'	=> $business_name,
							'business_id'	=> $value['business_id'],
							'percent'		=> (int)($offset*100/count($process_names))
						);
					}
					//查询负责人名名称
					$employee = $this->load("business")->findEmployee($guest_id);
					return json_encode(array(
							'error_code' => 0,
							"employee"	 => $employee,
							'data'		 => $ret
					));
				}else{
					//没有开通业务
					return json_encode(array(
						"error_code"	=> 0	
					));
				}
			}else{
				$arr = array('error_code' => 1);//需要登录
				return json_encode($arr);
			}
		}
	}
?>