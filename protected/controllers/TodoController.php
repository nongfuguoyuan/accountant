<?php
class TodoController extends ZjhController {
	
	public function find(){
		$post=$this->post;
		$page = isset($post['page'])? $post['page']:1;
		$pagenum = isset($post['pageNum'])?$post['pageNum']:1;
		$task_content = @$post['task_content'];
		$params = array(
				'task_content'=>$task_content
			);
		return $this->load('todo')->find($params,array($page,$pagenum));
		
		// return json_encode($result);
	}
	public function findById($todo_id){
		return $this->load('todo')->findById($todo_id);
	}
	public function save(){
		$post=$this->post;
		$sender_id=$this->session['user']['employee_id'];
		$accepter=isset($post['accepters'])?$post['accepters']:null;
		$task_content=isset($post['task_content'])?$post['task_content']:null;
		$date_start=isset($post['date_start'])?$post['date_start']:null;
		$date_end=isset($post['date_end'])?$post['date_end']:null;
		
		$result = $this->load('todo')->add(array(
			'sender_id'=>$sender_id,
			'task_content'=>$task_content,
			'date_start'=>$date_start,
			'date_end'=>$date_end
		));
		
		$arr=array('todo_id'=>$result,'todo_list'=>$accepter);
		$this->load('todo_list')->add($arr);
		return $this->findById($result);
	}
	
	public function delete(){
		$post=$this->post;
		$todo_id=(int)$post['todo_id'];
		return $this->load('todo')->delete($todo_id);
		
	}
	
	public function update(){
		$post=$this->post;
		$sender_id=$this->session['user']['employee_id'];
		$sender=@$post['sender_id'];
		$task_content=isset($post['task_content'])?$post['task_content']:null;
		$date_start=isset($post['date_start'])?$post['date_start']:null;
		$date_end=isset($post['date_end'])?$post['date_end']:null;
		$todo_id=isset($post['todo_id'])?$post['todo_id']:null;
		if($sender == $sender_id){
			$result = $this->load('todo')->update(array(
			'sender_id'=>$sender_id,
			'task_content'=>$task_content,
			'date_start'=>$date_start,
			'date_end'=>$date_end,
			'todo_id'=>$todo_id
			));
		}else{
			return "没有权限修改";
		}
		
		if($result){
			return $this->findById($todo_id);
		}else{
			return 0;
		}
	}
	
	public function todoNotice(){
		$post=$this->post;
		$accepter=$this->session['user']['employee_id'];
		$result=$this->load('todo')->findByAccepterIdNotice($accepter);
		$total = count($result);
		$arr=array('total'=>$total,'data'=>$result);
		return $arr;
	}

	public function todoEarly()
	{
		$post=$this->post;
		$accepter=$this->session['user']['employee_id'];
		$result=$this->load('todo')->findByAccepterIdEarlyNotice($accepter);
		$total = count($result);
		$arr=array('total'=>$total,'data'=>$result);
		return $arr;
	}
}

?>