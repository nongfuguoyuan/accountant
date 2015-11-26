<?php
	class Employee extends Model{

		function updateStatus($employee_id,$status){
			return $this->db->exec('update `employee` set status=:status where employee_id=:employee_id',array(
				'employee_id'=>$employee_id,
				'status'=>$status
			));
		}

		function findByName($name){
			return $this->db->query('select * from `employee` where name=?',$name);
		}
		function findByPhone($phone){
			return $this->db->query('select * from `employee` where phone=?',$phone);
		}
		function login($phone,$pass){
			return $this->db->queryOne('select employee_id,name,phone from `employee` where phone=? and password=?',array($phone,$pass));
		}
		
		function findByDepartmentid($department_id){
			return $this->db->query('select 
				e.name,
				e.sex,
				date_format(e.create_time,"%Y-%m-%d") create_time,
				e.employee_id,
				d.name d_name,
				e.department_id,
				e.phone
				 from 
				`employee` e,
				`department` d 
				where
				e.department_id=d.department_id
				and d.department_id= ?
				and e.status = 1',$department_id);
		}
		public function find($page){
			$result = $this->db->query('select 
				e.name,
				e.sex,
				date_format(e.create_time,"%Y-%m-%d") create_time,
				e.employee_id,
				d.name d_name,
				e.department_id,
				e.phone
				 from 
				`employee` e
				left join 
				`department` d 
				on
				e.department_id=d.department_id
				where e.status = 1
				'
			,array(),$page);

			$count = $this->db->count;
			return array('count'=>$count,'data'=>$result);
		}
		public function findById($employee_id){
			return $this->db->queryOne('select 
				e.name,
				e.sex,
				date_format(e.create_time,"%Y-%m-%d") create_time,
				e.employee_id,
				d.name d_name,
				e.department_id,
				e.phone
				 from 
				`employee` e
				left join 
				`department` d 
				on
				e.department_id=d.department_id
				where employee_id=?
				',$employee_id);
		}
		public function search($array){
			$sql='select name,employee_id from `employee` where 
				';
			foreach ($array as $key => $value) {
				$sql .= " $key like '%$value%'";
			}
			return $this->db->query($sql,array(),null);
		}
		public function add($params){
			$result = $this->db->exec('insert into `employee` set 
				name=:name,
				password=:pass,
				sex=:sex,
				phone=:phone,
				status=1,
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