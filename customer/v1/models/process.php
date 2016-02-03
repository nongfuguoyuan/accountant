<?php
	class Process extends Model{
		function findName($process_group_id){
			return $this->db->query("
				select
				name
				from `process`
				where process_group_id=?
			",$process_group_id);
		}
	}
?>