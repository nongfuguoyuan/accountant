<?php
	class Guest extends Model{
		function findById($guest_id){
			return $this->db->queryOne('select 
				g.guest_id,
				g.name,
				g.address,
				g.phone,
				g.company,
				g.tel,
				g.status,
				g.create_time,
				r.description,
				e.name e_name,
				a.name a_name
				from `guest` g
				left join `employee` e
				on e.employee_id = g.employee_id
				left join `area` a
				on a.area_id = g.area_id
				left join `resource` r
				on r.resource_id = g.resource_id
				where g.guest_id = ?
				',$guest_id);
		}
		function find($page){
			$result =  $this->db->query("
				select 
				g.guest_id,
				g.name,
				g.phone,
				g.address,
				g.company,
				g.tel,
				g.status,
				g.create_time,
				r.description,
				e.name e_name,
				a.name a_name
				from `guest` g
				left join `employee` e
				on e.employee_id = g.employee_id
				left join `area` a
				on a.area_id = g.area_id
				left join `resource` r
				on r.resource_id = g.resource_id
			",array(),$page);
			if($result){
				return array('total'=>$this->db->count,'data'=>$result);
			}else{
				return false;
			}
		}
		function add($params){
			$result = $this->db->exec('insert into `guest` set 
				company=:company,
				name=:name,
				phone=:phone,
				job=:job,
				area_id=:area_id,
				address=:address,
				resource_id=:resource_id,
				employee_id=:employee_id,
				tel=:tel,
				status=:status,
				create_time=now()
			',$params);
			if($result){
				return $this->db->lastId();	
			}else{
				return false;
			}
		}
		function update($params){
			return $this->db->exec('update `guest` set 
				company=:company,
				name=:name,
				phone=:phone,
				area_id=:area_id,
				address=:address,
				tel=:tel,
				status=:status
				where guest_id=:guest_id
			',$params);
		}
		function delete($guest_id){
			return $this->db->exec('delete from `guest` where guest_id=?',$guest_id);
		}
	}
?>