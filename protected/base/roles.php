<?php 
	class Roles extends Model{

		function find(){
			return $this->db->query('select roles_id,name from `roles`');
		}

		function findAllName(){
			return $this->db->query('select roles_id,name from `roles`');
		}

		function permissionList($roles_id){

			return $this->db->queryOne('select permission from `roles` where roles_id=?',$roles_id);

		}
		function permission($roles_id){
			return $this->db->queryOne('select permission from `roles` where roles_id=?',$roles_id);
		}

		function add($params){
			$result = $this->db->exec('insert into `roles` set 
				name=:name,
				permission=:permission,
				create_time=now()
			',$params);

			if($result){
				return $this->db->lastId();
			}else{
				return false;
			}
		}

		function update($params){
			return $this->db->exec('update `roles` set 
				name=:name,
				permission=:permission
				where 
				roles_id=:roles_id'
				,$params);
		}

		function findName($roles_id){
			return $this->db->queryOne('select roles_id,name from `roles` where roles_id = ?',$roles_id);
		}

		function delete($roles_id){
			return $this->db->exec('delete from `roles` where roles_id=?',$roles_id);
		}
	}
 ?>