<?php
	//by zjh
	class Process extends Model{
		
		function findByName($name){
			return $this->db->queryOne('select name from `process` where name = ?',$name);
		}
		function add($params){
			return $this->db->exec('insert into `process` set process_group_id=:process_group_id,name=:name,create_time=now()',$params);
		}
		
		function delete($process_id){
			return $this->db->exec("delete from `process` where process_id=?",$process_id);
		}
		
		function update($params){
			return $this->db->exec('update `process` set name=:name where process_id=:process_id',$params);
		}
		
		function find(){
			// return 5;
			return $this->db->query('select p.name,p.process_id,g.name g_name,g.process_group_id from `process` p,`process_group` g where p.process_group_id = g.process_group_id');
		}

		function findByGroupid($process_group_id){
			return $this->db->query('select * from `process` where process_group_id = ?',$process_group_id);
		}
	}
?>