<?php
	class Employee extends Model{

		function updatePass($params){
			return $this->db->exec('update `employee` set 
				password=:new_pass
				where
				password=:old_pass
				and
				employee_id=:employee_id
			',$params);
		}

		function updateStatus($employee_id,$status){
			return $this->db->exec('update `employee` set status=:status where employee_id=:employee_id',array(
				'employee_id'=>$employee_id,
				'status'=>$status
			));
		}
		//按角色标签查找
		
		function findByTag($tag){
			return $this->db->query('select 
				e.employee_id,
				e.name
				 from 
				`employee` e,
				`roles` r
				where e.roles_id = r.roles_id
				and e.status = 1
				and r.tag like "%"?"%"',$tag);
		}
		// 查询某个用户的角色
		function findTag($employee_id){
			return $this->db->queryOne("
				select tag from `employee` e,`roles` r
				where e.roles_id = r.roles_id
				and employee_id=?
			",$employee_id);
		}
		
		function findByName($name){
			return $this->db->query('select * from `employee` where name=?',$name);
		}
		function findByPhone($phone){
			return $this->db->query('select * from `employee` where phone=?',$phone);
		}
		function login($phone,$pass){
			return $this->db->queryOne('select 
				e.employee_id,
				e.name,
				r.name r_name,
				r.tag,
				r.level,
				r.permission permissions,
				e.phone
				from 
				`employee` e
				left join
				`roles` r
				on r.roles_id = e.roles_id
				where e.phone=? and e.password=?',array($phone,$pass));
		}
		
		function findByDepartmentid($department_id){
			return $this->db->query('select 
				e.name,
				e.sex,
				date_format(e.create_time,"%Y-%m-%d") create_time,
				e.employee_id,
				e.roles_id,
				r.name r_name,
				d.name d_name,
				e.department_id,
				e.phone
				 from 
				`employee` e,
				`roles` r,
				`department` d 
				where
				e.department_id=d.department_id
				and r.roles_id = e.roles_id
				and d.department_id= ?
				and e.status = 1',$department_id);
		}
		public function find($page){
			$result = $this->db->query('select 
				e.name,
				e.sex,
				r.roles_id,
				r.name r_name,
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
				left join 
				`roles` r
				on 
				r.roles_id = e.roles_id
				where e.status = 1
				'
			,array(),$page);

			if($result) return array('total'=>$this->db->count,'data'=>$result);
			else return false;
		}
		public function findById($employee_id){
			return $this->db->queryOne('select 
				e.name,
				e.sex,
				r.roles_id,
				r.name r_name,
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
				left join
				`roles` r
				on r.roles_id = e.roles_id
				where employee_id=?
				',$employee_id);
		}
		/*author:zgj 
		*date:2015-12-1
		*/
		public function search($array){
			$sql='select name,employee_id from `employee` where 
				';
			foreach ($array as $key => $value) {
				$sql .= " $key like '%$value%'";
			}
			return $this->db->query($sql);
		}
		/*author:zgj 
		*date:2015-12-3
		*查询密码
		*/
		public function findPass($employee_id){
			$sql='select password from employee where employee_id='.$employee_id;
			return $this->db->query($sql);
		}
		/*author:zgj 
		*date:2015-12-3
		*修改密码
		*/
		public function changePass($newPass,$employee_id){
			$sql='update employee set password =\''.$newPass.'\' where employee_id='.$employee_id;
			return $this->db->exec($sql);
		}
		public function add($params){
			$result = $this->db->exec('insert into `employee` set 
				name=:name,
				password=:pass,
				sex=:sex,
				phone=:phone,
				roles_id=:roles_id,
				status=1,
				create_time=:create_time,
				department_id=:department_id
			',$params);

			if($result){
				return $this->db->lastId();
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
				roles_id=:roles_id,
				department_id=:department_id
				where employee_id=:employee_id
			',$params);
		}
	}
?>