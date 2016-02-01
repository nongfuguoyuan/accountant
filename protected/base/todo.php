<?php
class todo extends Model {
	private $sql_str_show_self='select t.*,e.`name` as `sender`, tl.accepter from (select tl.todo_id, GROUP_CONCAT(e.`name`) as accepter from todo_list as tl,employee as e 
WHERE e.employee_id=tl.employee_id GROUP BY tl.todo_id)as tl,(select todo_id from todo_list where todo_list.employee_id=:accepter)as tl1,employee as e,
todo as t where t.todo_id=tl.todo_id and t.sender_id=e.employee_id and tl.todo_id=tl1.todo_id';
	private $sql_str = 'select t.*,e.`name` as `sender`, tl.accepter from (select tl.todo_id, GROUP_CONCAT(e.`name`) as accepter from todo_list as tl,employee as e 
WHERE e.employee_id=tl.employee_id GROUP BY tl.todo_id)as tl,employee as e,todo as t where t.todo_id=tl.todo_id and t.sender_id=e.employee_id ';
	public function find($params,$page){
		if(empty($params['task_content'])){
			$sql_str=$this->sql_str."ORDER BY t.date_start DESC,t.date_end ASC";
		}else{
			$sql_str=$this->sql_str. 'and t.task_content like "%":task_content"%" ORDER BY t.date_start DESC,t.date_end ASC';
		}
		$result = $this->db->query($sql_str,$params,$page);
		$count = $this->db->count;
		return array('total'=>$count,'data'=>$result);
	}

	public function findByAccepterIdNotice($accepter){//根据开始结束时间提醒
		$result = $this->db->query($this->sql_str_show_self." and date_start <= CURDATE() and date_end >= CURDATE() ORDER BY t.date_start DESC,t.date_end ASC",array('accepter'=>$accepter));
		return $result;
	}
	
	public function findByAccepterIdEarlyNotice($accepter){//根据开始结束时间提醒
		$result = $this->db->query($this->sql_str_show_self." and date_start <= CURDATE() and date_end <= CURDATE()",array('accepter'=>$accepter));
		return $result;
	}

	public function findById($todo_id){
		return $this->db->queryOne($this->sql_str."and t.todo_id=$todo_id");
	}
	public function add($params){
		$affected_rows=$this->db->exec("insert into todo set 
				sender_id=:sender_id,
				task_content=:task_content,
				date_start=:date_start,
				date_end=:date_end
				",$params);
		return $this->db->lastId();	

	}
	public function delete($todo_id){
		return $this->db->exec("delete from todo where todo_id=$todo_id");
	}
	
	public function update($params){
		$affected_rows = $this->db->exec("update todo set 
				sender_id=:sender_id,
				task_content=:task_content,
				date_start=:date_start,
				date_end=:date_end where
				todo_id=:todo_id
				",$params);

		return $affected_rows;
	}
}

?>