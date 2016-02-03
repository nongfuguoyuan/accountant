<?php
	class Progress extends Model{
		function currentName($business_id,$process_group_id){
			return $this->db->queryOne("
				select
				p.name 
				from 
				`process` p,
				`progress`g
				where 
				g.business_id = ? and
				g.process_id = p.process_id and
				p.process_group_id = ?
				order by g.date_end desc limit 1
			",array($business_id,$process_group_id));
		}
		function findProcessed($business_id){
			return $this->db->query('select 
				pr.process_id,
				p.name,
				pr.note,
				date_format(pr.date_end,"%Y/%m/%d") date_end,
				date_format(pr.create_time,"%Y/%m/%d") create_time
				from 
				`progress` pr,
				`process` p
				where 
				pr.process_id = p.process_id and
				pr.business_id = ?
				order by pr.process_id asc',$business_id);
		}
		function findRest($process_id){
			return $this->db->query("select 
					p.name
					from
					`process` p,
					(select
					process_group_id,
					process_id
					from
					`process`
					where 
					process_id = ?) s
					where 
					p.process_group_id = s.process_group_id and
					p.process_id > s.process_id",
					$process_id);
		}
	}
?>