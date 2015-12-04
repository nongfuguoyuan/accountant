<?php 
	class Area extends model{
		function find(){
			return $this->db->query('select * from `area`');
		}
	}
 ?>