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

		function updateById($tax_collect_id,$fee){

			return $this->db->exec('update `tax_collect` set fee=? where tax_collect_id=?',array($fee,$tax_collect_id));

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
		//最近缴纳的月份
		// function currentMonth($guest_id){
		// 	return $this->db->queryOne("select max(t.month) month,t.year from
		// 		`tax_collect` t,
		// 		(select max(year) year
		// 		from
		// 		`tax_collect`
		// 		where guest_id = ?
		// 		) s
		// 		where s.year = t.year
		// 		and t.guest_id = ?",
		// 		array($guest_id,$guest_id));
		// }

		// function findSumByParentid($guest_id,$parent_id){
		// 	return $this->db->queryOne("select 
		// 		sum(c.fee) sumfee
		// 		from
		// 		`tax_type`t ,
		// 		`tax_collect` c
		// 		where t.tax_type_id = c.tax_type_id 
		// 		and t.parent_id = ?
		// 		and c.guest_id = ?",
		// 		array($parent_id,$guest_id));
		// }

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