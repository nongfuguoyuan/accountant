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

		function delete($taxcollect_id){
			return $this->db->exec('delete from `tax_collect` where tax_collect_id=?',$tax_collect_id);
		}

		function findById(){
			// return $this->db->query();
		}
	}
?>