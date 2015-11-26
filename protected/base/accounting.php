<?php
	class Accounting extends Model{


		//办理代理记账并且受理过（有人负责）的用户
		function findHasAccounting(){
			return $this->db->query("
				select 
				g.guest_id,
				g.name,
				g.company,
				g.phone,
				a.accounting_id,
				e.name accounting
				from
				`guest` g,
				`accounting` a
				left join
				employee e
				on 
				e.employee_id = a.employee_id
				where g.guest_id = a.guest_id and
				a.status = 1
			");
		}

		function updateStatus($accounting_id){
			return $this->db->exec('update `accounting` set status=1 where accounting_id=?',$accounting_id);
		}
		
		function find($page){
			return $this->db->query("
				select
				g.guest_id,
				g.company,
				g.name,
				g.phone,
				a.accounting_id,
				a.status,
				e2.name server,
				e.name accounting,
				DATE_FORMAT(b.deadline,'%Y-%m-%d') deadline,
				DATE_FORMAT(a.create_time,'%Y-%m-%d') create_time
				from 
				`guest` g,
				`employee` e,
				`employee` e2,
				`accounting` a
				left join 
				(select max(deadline) deadline,money,pay_record_id,accounting_id from `pay_record` group by accounting_id) b
				on b.accounting_id = a.accounting_id
				where 
				a.guest_id = g.guest_id and
				e.employee_id = a.employee_id and
				g.employee_id = e2.employee_id
			",array(),$page);
		}

		function add($params){
			$result = $this->db->exec('insert into `accounting` set 
				guest_id=:guest_id,
				employee_id=:employee_id,
				status=:status,
				create_time=now()
			',$params);

			if($result) return $this->db->lastId();
			else return false;
		}

		function update($params){
			return $this->db->exec('update `accounting` set 
				guest_id=:guest_id,
				employee_id=:employee_id
				where accounting_id=:accounting_id
			',$params);
		}

		function delete($accounting_id){
			return $this->db->exec('delete from `accounting` where accounting_id=?',$accounting_id);
		}

		function findById($accounting_id){
			return $this->db->queryOne("
				select
				g.guest_id,
				g.name,
				a.accounting_id,
				a.status,
				e2.name server,
				e.name accounting,
				b.deadline,
				a.create_time
				from 
				`guest` g,
				`employee` e,
				`employee` e2,
				`accounting` a
				left join 
				(select max(deadline) deadline,money,pay_record_id,accounting_id from `pay_record` group by accounting_id) b
				on b.accounting_id = a.accounting_id
				where 
				a.guest_id = g.guest_id and
				e.employee_id = a.employee_id and
				g.employee_id = e2.employee_id and
				a.accounting_id=?
			",$accounting_id);
		}
	}
