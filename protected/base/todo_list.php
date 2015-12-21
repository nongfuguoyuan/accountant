<?php
class todo_list extends Model{
	/*
		author zgj
		批量添加指派人
		$params里面包含了任务id和一个员工id数组
		返回影响行数
	*/
	public function add($params){
		$todo_id = $params['todo_id'];
		$todo_list = $params['todo_list'];
		$count = count($todo_list);
		$sql_str = 'insert into todo_list(todo_id, employee_id) values ';
		for ($i=0; $i < $count; $i++) { 
			$params["todo_list{$i}"]=$todo_list[$i];
			$sql_str .= "(:todo_id,:todo_list{$i}) ";
		}
		return $this->db->exec($sql_str,$params);
	}
}