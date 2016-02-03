<?php
	class Business extends Model{
		function findEmployee($guest_id){
			return $this->db->queryOne("select
				e.name,
				e.phone
				from 
				`business`b,
				`employee`e
				where
				b.employee_id = e.employee_id and
				b.guest_id = ?",$guest_id);
		}
		function findOpen($guest_id){
			return $this->db->query("select 
				business_id,
				process_group_id,
				should_fee,
				have_fee,
				status,
				create_time,
				employee_id
				from `business` 
				where guest_id = ?
				and status=1
				order by process_group_id asc
				",$guest_id);
		}
		function findServer($phone,$pass){
			return $this->db->queryOne("select 
				e.name,
				g.guest_id,
				e.phone from 
				`guest` g,
				`employee` e
				where g.phone = ?
				and g.pass = ?
				and g.employee_id = e.employee_id",array($phone,$pass));
		}

	}
?>