<?php
class todo extends Model {
	
	public function find($page){
		$result = $this->db->query("select t.todo_id,t.task_content,t.accepter as accepter_id,t.date_start as date_start,t.date_end as date_end,e.`name` as `sender`,e1.`name` as accepter from todo as t,employee as e,
employee as e1 where t.`sender`=e.employee_id and t.accepter=e1.employee_id order by date_start desc,date_end",array(),$page);
		$count = $this->db->count;
	    $pagebar=$this->db->getPage();
		return array('count'=>$count,'data'=>$result,'pagebar'=>$pagebar);
	}
	public function findBy($array){
		$sql="select t.todo_id,t.task_content,t.date_start as date_start,t.date_end as date_end,e.`name` as `sender`,e1.`name` as accepter from todo as t,employee as e,
employee as e1 where t.`sender`=e.employee_id and t.accepter=e1.employee_id";
		foreach ($array as $key => $value) {
			$sql.=" and $key=:$key";
		}
		
		return $this->db->query($sql,$array,null);
	}

	public function search($array){//按任务内容模糊查询
		$sql="select t.todo_id,t.task_content,t.date_start as date_start,t.date_end as date_end,e.`name` as `sender`,e1.`name` as accepter from todo as t,employee as e,
employee as e1 where t.`sender`=e.employee_id and t.accepter=e1.employee_id";
		foreach ($array as $key => $value) {
			$sql.=" and $key like '%$value%'";
		}
		
		return $this->db->query($sql,$array,null);
	}
	
	public function findByAccepterIdNotice($accepter){//根据开始结束时间提醒
		$result = $this->db->query("select t.todo_id,t.task_content,t.date_start as date_start,t.date_end as date_end,e.`name` as `sender`,e1.`name` as accepter from todo as t,employee as e,
employee as e1 where t.`sender`=e.employee_id and t.accepter=e1.employee_id and accepter=:accepter and date_start < NOW() and date_end >NOW()",array('accepter'=>$accepter));
		return $result;
	}
	
	public function findByAccepterIdEarlyNotice($accepter){//根据开始结束时间提醒
		$result = $this->db->query("select t.todo_id,t.task_content,t.date_start as date_start,t.date_end as date_end,e.`name` as `sender`,e1.`name` as accepter from todo as t,employee as e,
employee as e1 where t.`sender`=e.employee_id and t.accepter=e1.employee_id and accepter=:accepter and date_end <NOW()",array('accepter'=>$accepter));
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