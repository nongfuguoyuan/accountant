<?php
	require("sql.php");
	class Model{
		public function __construct(){
			$this->db = Sql::getInstance('192.168.10.100','accountant','user','@mecaiwu');
		}
	}
?>