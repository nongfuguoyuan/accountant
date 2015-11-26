<?php
	//by zjh
	class Progress extends Model{


		function findName($business_id){
			return $this->db->queryOne("select 
				pr.name
				from
				`progress` p
				left join 
				`process` pr
				on pr.process_id = p.process_id
				where p.business_id = ?
				order by p.progress_id desc",$business_id);
		}
		function add($params){
			return $this->db->exec("insert into `progress` set 
				business_id=:business_id,
				process_id=:process_id,
				note=:note,
				date_end=:date_end,
				create_time=now()
			",$params);
		}
		function delete($progress_id){
			return $this->db->exec('delete from `progress` where progress_id=?',$progress_id);
		}
		function find($business_id){
			return $this->db->query("select
				pr.progress_id,
				pr.business_id,
				pr.note,
				p.name,
				pr.date_end,
				pr.create_time
				from
				`progress` pr,
				`process`  p,
				`business` b
				where
				pr.business_id = b.business_id and
				pr.process_id = p.process_id and
				pr.business_id=?",$business_id);
		}
	}
?>