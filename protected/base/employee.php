<?php
	require 'model.php';
	class Employee extends Model{
		public function find(){
			return $this->db->query('select * from employee');
		}
	}
?>