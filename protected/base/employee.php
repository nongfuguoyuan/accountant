<?php
	require 'model.php';
	class Employee extends Model{
		public function find($page){
			$result = $this->db->query('select e.name,e.sex,e.create_time,e.employee_id,d.name d_name,e.department_id,e.phone from `employee` e,`department` d where 
				e.department_id=d.department_id',array(),$page);
			$count = $this->db->count;
			return array('count'=>$count,'data'=>$result);
		}
		public function findById($employee_id){
			return $this->db->queryOne('select e.name,e.sex,e.create_time,e.employee_id,d.name d_name,e.department_id,e.phone from `employee` e,`department` d where 
				e.department_id=d.department_id and e.employee_id=?',$employee_id);
		}
		public function add($params){
			$result = $this->db->exec('insert into `employee` set 
				name=:name,
				sex=:sex,
				phone=:phone,
				create_time=:create_time,
				department_id=:department_id
			',$params);
			if($result == 1){
				return $this->findById($this->db->lastId());
			}else{
				return false;
			}
		}
		public function delete($employee_id){
			return $this->db->exec('delete from `employee` where employee_id=?',$employee_id);
		}
		public function update($params){
			return $this->db->exec('update `employee` set 
				name=:name,
				phone=:phone,
				sex=:sex,
				department_id=:department_id
				where employee_id=:employee_id
			',$params);
		}
	}
?>