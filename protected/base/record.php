<?php
	/*跟踪记录
	author:zjh
	date:2015-11-23*/

	class Record extends Model{

		function add($params){
			$result = $this->db->exec('insert into `record` set
				guest_id=:guest_id,
				content=:content,
				create_time=now()
			',$params);

			if($result) return $this->db->lastId();
			else return false;
		}
		//根据用户查询其有几条跟进记录
		function findCount($guest_id){
			return $this->db->queryOne("select count(*) count from `record` where guest_id = ?",$guest_id);
		}

		function findById($record_id){
			return $this->db->queryOne("select 
				r.create_time,
				r.content,
				g.guest_id,
				r.record_id
				from 
				`record` r,
				`guest` g
				where 
				r.guest_id = g.guest_id and
				r.record_id=?",$record_id);
		}

		function find($guest_id){
			return $this->db->query("select 
				date_format(r.create_time,'%Y-%m-%d') create_time,
				r.content,
				g.guest_id,
				r.record_id
				from 
				`record` r,
				`guest` g
				where 
				r.guest_id = g.guest_id and
				g.guest_id = ?
				order by r.record_id desc"
				,$guest_id);
		}

		function delete($record_id){
			return $this->db->exec('delete from `record` where record_id =?',$record_id);
		}
	}
?>