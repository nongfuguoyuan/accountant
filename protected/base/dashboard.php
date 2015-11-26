<?php
	class Dashboard extends Model{
		function findCount($employee_id){
			return $this->db->queryOne('select count(*) count
				from 
				`employee` e
				left join 
				`guest` g
				on g.employee_id = e.employee_id
				where e.employee_id = ?',$employee_id);
		}

		function findDeal($employee_id){
			return $this->db->queryOne('select count(*) count
				from 
				`employee` e
				left join 
				`guest` g
				on g.employee_id = e.employee_id
				where 
				g.status = 1 and
				e.employee_id = ?',$employee_id);
		}

		function findLose($employee_id){
			return $this->db->queryOne('select count(*) count
				from 
				`employee` e
				left join 
				`guest` g
				on g.employee_id = e.employee_id
				where 
				g.status = 2 and
				e.employee_id = ?',$employee_id);
		}

		function findIng($employee_id){
			return $this->db->queryOne('select count(*) count
				from 
				`employee` e
				left join 
				`guest` g
				on g.employee_id = e.employee_id
				where 
				g.status = 0 and
				e.employee_id = ?',$employee_id);
		}
	}