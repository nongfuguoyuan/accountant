<?php
	class Business extends Model{


		function findOpen($guest_id){
			return $this->db->query("select 
				b.business_id,
				b.process_group_id
				from
				`guest` g,
				`business` b
				where 
				g.guest_id = b.guest_id
				and g.guest_id=?
				and b.status = 1",$guest_id);
		}
		
		function findById($business_id){
			
		}
		function find($page){
			return $this->db->query('
				select * from (
				select 
				b.business_id,
				pg.process_group_id,
				pg.name pg_name,
				g.company,
				g.name,
				g.phone,
				g.tel,
				e2.name server,
				e.name accounting,
				b.status,
				b.should_fee,
				b.have_fee,
				p.name p_name,
				DATE_FORMAT(b.create_time ,"%Y-%m-%d") create_time
				from 
				`guest` g,
				`employee` e2,
				`business` b
				left join 
				`process_group` pg
				on b.process_group_id = pg.process_group_id
				left join `employee` e
				on b.employee_id = e.employee_id 
				left join `progress` pr 
				on pr.business_id = b.business_id 
				left join  `process` p
				on p.process_id = pr.process_id
				where 
				g.employee_id = e2.employee_id
				and b.guest_id = g.guest_id 
				order by pr.progress_id desc) b 
				group by b.business_id
				',
				array(),$page);
		}
		function add($params){
			$result =  $this->db->exec('insert into `business` set 
				process_group_id=:process_group_id,
				guest_id=:guest_id,
				employee_id=:employee_id,
				should_fee=:should_fee,
				have_fee=:have_fee,
				status=:status,
				create_time=now()
			',$params);
			return $result;
			if($result){
				return $this->db->lastId();
			}
		}
		function update($params){
			return $this->db->exec('update `business` set 
				process_group_id=:process_group_id,
				employee_id=:employee_id,
				should_fee=:should_fee,
				have_fee=:have_fee,
				status=:status
				where business_id=:business_id
			',$params);
		}
		function delete($business_id){
			return $this->db->exec('delete from `business` where business_id=?',$business_id);
		}
		function updateStatus($params){
			return $this->db->exec('update `business` set status=:status where business_id=:business_id',$params);
		}
		function updateProcess($params){
			return $this->db->exec('update `business` set process_id=:process_id where business_id=:business_id',$params);
		}
	}
?>