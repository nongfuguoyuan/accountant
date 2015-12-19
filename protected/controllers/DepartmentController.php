<?php
	class DepartmentController extends ZjhController {

		// function findByEmployee(){

		// 	$employee_id = (int)$this->post['employee_id'];
		// 	return $this->load('department')->findByEmployee($employee_id);
			
		// }

		public function find(){
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
		/*查询菜单，只有部门*/
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
		/*查询菜单，包括部门，员工*/
		public function findWholeMenu(){
			$arr = $this->load('department')->findAll();
			if($arr){
				$arr = $this->bubbleSort($arr);
				for($i = count($arr)-1;$i>=0;$i--){
					$obj = $arr[$i];
					$obj['sub'] = $this->load('employee')->findByDepartmentid($obj['department_id']);
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
				return '部门名称不符合要求';
			}
			if($department_id == 0){
				return '请先选中编辑的部门';
			}
			
			return $this->load('department')->update(array(
				'department_id'=>$department_id,
				'name'=>$name
			));

		}
		public function delete(){
			$department_id = (int)$this->post['department_id'];

			if($department_id == 0){
				return '请先选中编辑的部门';
			}
			
			return $this->load('department')->delete($department_id);
		}

		public function save(){
			$post = $this->post;
			$department_id = (int)$post['department_id'];
			$name = $post['name'];
			
			if(!validate('name',$name)){
				return '名字不符合要求';
			}

			return $this->load('department')->add(array(
				'parent_id'=>$department_id,
				'name'=>$name
			));
		}
	}

?>