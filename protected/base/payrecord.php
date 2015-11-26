<?php
	class Payrecord extends Model{

		function add($params){

			$result = $this->db->exec('insert into `pay_record` set
				accounting_id=:accounting_id,
				money=:money,
				deadline=:deadline,
				create_time=now()
			',$params);

			if($result) return $this->db->lastId();
			else return false;
		}

		function findOneList($pay_record_id){
			return $this->db->queryOne("
				select
				p.pay_record_id,
				p.accounting_id,
				g.company,
				g.name,
				DATE_FORMAT(p.create_time,'%Y-%m-%d') create_time,
				DATE_FORMAT(p.deadline,'%Y-%m-%d') deadline,
				p.money
				from
				`accounting` a,
				`guest` g,
				`pay_record` p
				where 
				a.accounting_id = p.accounting_id and
				a.guest_id = g.guest_id and 
				p.pay_record_id = ?
			",$pay_record_id);
		}

		function findList($accounting_id){
			return $this->db->query("
				select
				p.pay_record_id,
				p.accounting_id,
				g.company,
				g.name,
				DATE_FORMAT(p.create_time,'%Y-%m-%d') create_time,
				DATE_FORMAT(p.deadline,'%Y-%m-%d') deadline,
				p.money
				from
				`accounting` a,
				`guest` g,
				`pay_record` p
				where 
				a.accounting_id = p.accounting_id and
				a.guest_id = g.guest_id and 
				a.accounting_id = ?
				order by p.deadline desc
			",$accounting_id);
		}

		function find(){
			return $this->db->query("
				select 
				g.company,
				g.name,
				g.phone,
				s.money,
				s.pay_record_id,
				e.name server,
				e2.name accounting,
				DATE_FORMAT(s.create_time,'%Y-%m-%d') create_time,
				DATE_FORMAT(s.deadline,'%Y-%m-%d') deadline,
				a.accounting_id
				 from
				`guest` g,
				`employee` e,
				`employee` e2,
				`accounting` a
				left join
				(select max(deadline) deadline,accounting_id,money,create_time,pay_record_id from `pay_record` group by accounting_id) s
				on
				s.accounting_id = a.accounting_id
				where
				a.guest_id = g.guest_id and
				a.employee_id = e2.employee_id and 
				e.employee_id = g.employee_id
				order by s.pay_record_id desc
			");
		}

		function update($params){

		}

		function delete($pay_record_id){
			return $this->db->exec('delete from `pay_record` where pay_record_id=?',$pay_record_id);
		}

		function findById($pay_record_id){

		}
	}