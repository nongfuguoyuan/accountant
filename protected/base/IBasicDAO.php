<?php
interface IBasicDAO {
	
	public function findByWhere($where);
	
	public function findWhereOrderBy($where,$order,$start=null,$limit=null);
	
	public function save($arr);
	
	public function delete($obj);
	
	public function update($obj);
}

?>