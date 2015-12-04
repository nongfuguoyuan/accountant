<?php
	class Department extends Model{
		
		function findByEmployee($employee_id){

			return $this->db->queryOne('select d.name from `department` d,`employee` e where e.department_id=d.department_id and e.employee_id=?',$employee_id);
		}

		function findall(){
			return $this->db->query('select department_id,name,parent_id from `department`');
		}
		function findByPid($parent_id){
			return $this->db->query('select * from `department` where parent_id=?',$parent_id);
		}
		function findByid($department_id){
			return $this->db->queryOne('select * from department where department_id=?',$department_id);
		}
		function add($params){
			return $this->db->exec('insert into `department` set 
				parent_id=:parent_id,
				name=:name,
				create_time=:create_time
			',$params);
		}
		function update($params){
			return $this->db->exec('update `department` set 
				name=:name where 
				department_id=:department_id
			',$params);
		}
		function delete($department_id){
			return $this->db->exec('delete from `department` where department_id=?',$department_id);
		}
	}
?>