<?php
	//by zjh
	class Processgroup extends Model{

		/*查找一项服务的完整流程*/
		function findWhole($process_group_id){
			return $this->db->query('select name from `process` where process_group_id = ?',$process_group_id);
		}

		function findNameById($process_group_id){
			return $this->db->queryOne("select name from `process_group` where process_group_id = ?",$process_group_id);
		}

		function add($name){
			$result = $this->db->exec('insert into `process_group` set name=?,create_time=now()',$name);
			if($result){
				return $this->findByid($this->db->lastId());
			}else{
				return false;
			}
		}
		function update($params){
			return $this->db->exec('update `process_group` set name=:name where process_group_id=:process_group_id',$params);
		}
		function delete($process_group_id){
			return $this->db->exec('delete from `process_group` where process_group_id=?',$process_group_id);
		}
		function find(){
			return $this->db->query('select * from `process_group`');
		}
		function findByid($process_group_id){
			return $this->db->queryOne('select * from `process_group` where process_group_id=?',$process_group_id);
		}
		function findByName($name){
			return $this->db->queryOne('select * from `process_group` where name=?',$name);
		}
	}
?>