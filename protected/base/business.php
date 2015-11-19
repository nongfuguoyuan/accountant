<?php
	require('model.php');
	class Business extends Model{
		function findall($page){
			return $this->db->query('select * from area',array(),$page);
		}
	}
?>