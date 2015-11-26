<?php 
	class Taxcount extends Model{

		function add($params){
			$result = $this->db->exec('insert into `tax_count` set
				guest_id=:guest_id,
				fee=:fee,
				year=:year,
				month=:month,
				parent_id=:parent_id,
				create_time=now()
			',$params);

			if($result) return $this->db->lastId();
			else return false;
		}

		function delete($tax_count_id){
			return $this->db->exec('delete from `tax_count` where tax_count_id=?',$tax_count_id);
		}

		/*查询某用户最近月份国税或地税*/
		function findCount($guest_id,$parent_id){
			return $this->db->queryOne("select 
				t.tax_count_id,
				t.guest_id,
				t.year,
				t.month,
				t.fee
				from 
				`tax_count` t,
				(select 
				s.year,
				max(t.month) month,
				t.guest_id,
				t.parent_id
				from
				`tax_count` t,
				(select max(year) year,
				parent_id,
				guest_id 
				from 
				`tax_count`
				where guest_id = ?
				and parent_id = ?
				) s
				where 
				t.guest_id = s.guest_id and
				s.year = t.year and
				s.parent_id = t.parent_id
				) p
				where 
				p.year = t.year and
				p.month = t.month and
				p.guest_id = t.guest_id and
				p.parent_id = t.parent_id
				"
			,array($guest_id,$parent_id));
		}

	}
 ?>