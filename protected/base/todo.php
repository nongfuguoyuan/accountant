<?php
require_once 'model.php';
class todo extends Model {
	
	public function find($page){
		$result = $this->db->query('select t.todo_id,t.`task`,t.date_start,t.date_end,e.`name` as `master`,e1.`name` as recipient from todo as t,employee as e,
employee as e1 where t.`sender`=e.employee_id and t.accepter=e1.employee_id',array(),$page);
		$count = $this->db->count;
		return array('count'=>$count,'data'=>$result);
	}
}

?>