<?php
	class DepartmentController extends ZjhController {

		function findByEmpolyee(){

			$employee_id = (int)$this->post['employee_id'];
			return $this->load('department')->findByEmpolyee($employee_id);
			
		}

		public function findAll(){
			$result = $this->load('department')->findAll();
			return $result;
		}

		private function bubbleSort($arr){
			$l = count($arr)-2;
			$i = 0;
			while($i<$l){
				$j = $l;
				while($j<$i){
					if($arr[$j+1]['parent_id']<$arr[$j]['parent_id']){
						$tmp = $arr[$j];
						$arr[$j] = $arr[$j+1];
						$arr[$j+1] = $tmp;
					}
					$j -= 1;
				}
				$i += 1;
			}
			return $arr;
		}
		public function findMenu(){
			$arr = $this->load('department')->findAll();
			if($arr){
				$arr = $this->bubbleSort($arr);
				for($i = count($arr)-1;$i>=0;$i--){
					$obj = $arr[$i];
					foreach($arr as $key => $value){
						if($value['department_id'] == $obj['parent_id']){
							if(!isset($value['sub'])){
								$arr[$key]['sub'] = array();
							}
							array_push($arr[$key]['sub'],$obj);
						}
					}
				}
				$tree = array();
				foreach($arr as $key => $value){
					if($value['parent_id'] == 0) $tree[] = $value;
				}
				return $tree;
			}else{
				return false;
			}
		}
		public function update(){
			$post = $this->post;
			$department_id = (int)$post['department_id'];
			$name = $post['name'];
			if(!validate('name',$name)){
				$ret['tag'] = 'fail';
				$ret['info'] = '部门名称不符合要求';
				return json_encode($ret);
			}
			return $this->load('department')->update(array(
				'department_id'=>$department_id,
				'name'=>$name
			));

		}
		public function delete(){
			$department_id = (int)$this->post['department_id'];
			return $this->load('department')->delete($department_id);
		}
		public function save(){
			$post = $this->post;
			$parent_id = (int)$post['parent_id'];
			$name = $post['name'];
			$ret = array();
			if(!validate('name',$name)){
				return false;
			}
			return $this->load('department')->add(array(
				'parent_id'=>$parent_id,
				'name'=>$name,
				'create_time'=>timenow()
			));
		}
	}

?>