<?php
	//by zjh
	class Resource extends Model{
		function find(){
			return $this->db->query('select * from `resource`');
		}
		function fingByDes($description){
			return $this->db->queryOne('select resource_id from `resource` where description=?',$description);
		}
		function findById($resource_id){
			return $this->db->queryOne('select * from 	`resource` where resource_id=?',$resource_id);
		}
		function add($params){
			$result = $this->db->exec('insert into `resource` set description=:description,create_time=:create_time',$params);
			if($result){
				return $this->db->queryOne("select * from `resource` where resource_id=?",$this->db->lastId());
			}
		}
		function delete($resource_id){
			return $this->db->exec('delete from `resource` where resource_id=?',$resource_id);
		}
		function update($params){
			return $this->db->exec('update `resource` set description=:description where resource_id=:resource_id',$params);
		}
	}
?>