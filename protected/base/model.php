<?php
	require 'zjhsql.php';

	class Model{
		public function __construct(){
			$this->db = ZjhSql::getInstance('192.168.10.100','accountant','user','@mecaiwu');
		}
	}
?>