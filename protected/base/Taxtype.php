<?php
	class Taxtype extends Model{
		function find(){
			return $this->db->query('select * from `tax_type`');
		}
		function findById($tax_type_id){
			return $this->db->queryOne('select * from `tax_type` where tax_type_id=?',$tax_type_id);
		}
		function findByParentid($parent_id){
			return $this->db->query("select 
				tax_type_id,
				name,
				parent_id
				 from 
				`tax_type`
				where parent_id = ?",$parent_id);
		}
		function add($params){
			$result = $this->db->exec("insert into `tax_type` set 
				parent_id=:parent_id,
				name=:name,
				create_time=now()
			",$params);
			if($result){
				return $this->db->lastId();
			}else{
				return false;
			}
		}
		function findByName($name){
			return $this->db->queryOne("select * from `tax_type` where name=?",$name);
		}
		function update($params){
			return $this->db->exec('update `tax_type` set 
				name=:name
				where tax_type_id=:tax_type_id
			',$params);
		}
		function delete($tax_type_id){
			return $this->db->exec('delete from `tax_type` where tax_type_id=?',$tax_type_id);
		}
	}
?>