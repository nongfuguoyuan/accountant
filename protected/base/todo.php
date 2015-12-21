<?php
class todo extends Model {
	private $sql_str = 'select t.*,e.`name` as `sender`,GROUP_CONCAT(e1.`name`) as accepter from todo as t,todo_list as tl,employee as e,
employee as e1 where t.todo_id=tl.todo_id and tl.employee_id=e1.employee_id and t.`sender_id`=e.employee_id ';
	public function find($page){
		$result = $this->db->query($this->sql_str,array(),$page);
		$count = $this->db->count;
		return array('total'=>$count,'data'=>$result);
	}
	public function findBy($array){
		$sql=$this->sql_str;
		foreach ($array as $key => $value) {
			$sql.="and $key=:$key";
		}
		
		return $this->db->query($sql,$array,null);
	}

	public function search($array){//按任务内容模糊查询
		$sql=$this->sql_str;
		foreach ($array as $key => $value) {
			$sql.="and $key like '%$value%'";
		}
		
		return $this->db->query($sql,$array,null);
	}
	
	public function findByAccepterIdNotice($accepter){//根据开始结束时间提醒
		$result = $this->db->query($this->sql_str."and tl.employee_id=:accepter and date_start < NOW() and date_end >NOW()",array('accepter'=>$accepter));
		return $result;
	}
	
	public function findByAccepterIdEarlyNotice($accepter){//根据开始结束时间提醒
		$result = $this->db->query($this->sql_str."and tl.employee_id=:accepter and date_end <NOW()",array('accepter'=>$accepter));
		return $result;
	}

	public function add($params){
		$affected_rows=$this->db->exec("insert into todo set 
				sender=:sender,
				accepter=:accepter,
				task_content=:task_content,
				date_start=:date_start,
				date_end=:date_end
				",$params);
		$last_id=$this->db->lastId();
		$data=$this->findBy(array('todo_id'=>$last_id));
		return array('affected_rows'=>$affected_rows,'data'=>$data);

	}
	
	public function delete($todo_id){
		return $this->db->exec("delete from todo where todo_id=$todo_id");
	}
	
	public function update($params){
		$affected_rows = $this->db->exec("update todo set 
				sender=:sender,
				accepter=:accepter,
				task_content=:task_content,
				date_start=:date_start,
				date_end=:date_end where
				todo_id=:todo_id
				",$params);

		$data=$this->findBy(array('todo_id'=>$params['todo_id']));
		return array('affected_rows'=>$affected_rows,'data'=>$data);
	}
}

?>