<?php
	class Taxcollect extends Model{

		function add($params){
			$result = $this->db->exec('insert into `tax_collect` set
				tax_type_id=:tax_type_id,
				year=:year,
				month=:month,
				fee=:fee,
				guest_id=:guest_id,
				create_time=now()
			',$params);

			if($result) return $this->db->lastId();
			else return false;
		}

		function update($params){

			return $this->db->exec('
				update
				`tax_collect` t
				inner join 
				( 
				select 
				t.tax_collect_id
				from
				`tax_collect` t,
				`accounting` a,
				`employee` e
				where
				t.guest_id = a.guest_id and
				e.employee_id = a.employee_id and
				e.employee_id = :employee_id and
				t.tax_collect_id = :tax_collect_id and
				t.guest_id = :guest_id
				) s
				on 
				s.tax_collect_id = t.tax_collect_id
				set
				t.fee = :fee
			',$params);
		}

		function findByGuestid($year,$month,$guest_id){
			return $this->db->query("select
				t.tax_collect_id,
				t.guest_id,
				y.name,
				t.fee
				from
				`tax_collect`t
				left join `tax_type` y
				on
				t.tax_type_id = y.tax_type_id
				where t.year = ? and
				t.month = ? and
				t.guest_id = ?",array($year,$month,$guest_id));
		}
		
		function delete($tax_collect_id){
			return $this->db->exec('delete from `tax_collect` where tax_collect_id=?',$tax_collect_id);
		}

		function findById($tax_collect_id){
			return $this->db->queryOne("select 
				t.tax_collect_id,
				y.name,
				t.fee
				from 
				`tax_collect` t,
				`tax_type` y
				where
				t.tax_type_id = y.tax_type_id
				and 
				t.tax_collect_id = ?",
				$tax_collect_id);
		}
	}
?>