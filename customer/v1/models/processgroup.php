<?php
	class Processgroup extends Model{
		function findName($process_group_id){
			return $this->db->queryOne("select name from `process_group` where process_group_id=?",$process_group_id);
		}
	}
?>