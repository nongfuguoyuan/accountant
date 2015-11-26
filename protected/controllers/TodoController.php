<?php
require 'zjhcontroller.php';
class TodoController extends ZjhController {
	
	public function findAll(){
		$post=$this->post;
		$page = isset($post['page'])? $post['page']:1;
		$pagenum = isset($post['pageNum'])?$post['pageNum']:1;
		$result = $this->load('todo')->find(array($page,$pagenum));
		if(!$result){
			$result = array();
		}
		return $result;
		// return json_encode($result);
	}
	public function findById(){
		$post=$this->post;
		$result = $this->load('todo')->findBy($post);
		if(!$result){
			$result = array();
		}
		return $result;
	}
	public function save(){
		$post=$this->post;
		$sender=isset($post['sender'])?$post['sender']:null;
		$accepter=isset($post['accepter'])?$post['accepter']:null;
		$task_content=isset($post['task_content'])?$post['task_content']:null;
		$date_start=isset($post['date_start'])?$post['date_start']:null;
		$date_end=isset($post['date_end'])?$post['date_end']:null;
		
		$result = $this->load('todo')->add(array(
			'sender'=>$sender,
			'accepter'=>$accepter,
			'task_content'=>$task_content,
			'date_start'=>$date_start,
			'date_end'=>$date_end
		));
		$affected_rows=$result;

		$arr=array();
		return $result;
	}
	
	public function delete(){
		$post=$this->post;
		$todo_id=(int)$post['todo_id'];
		return $this->load('todo')->delete($todo_id);
		
	}
	
	public function update(){
		$post=$this->post;
		$sender=isset($post['sender'])?$post['sender']:null;
		$accepter=isset($post['accepter'])?$post['accepter']:null;
		$task_content=isset($post['task_content'])?$post['task_content']:null;
		$date_start=isset($post['date_start'])?$post['date_start']:null;
		$date_end=isset($post['date_end'])?$post['date_end']:null;
		$todo_id=isset($post['todo_id'])?$post['todo_id']:null;
		
		$result = $this->load('todo')->update(array(
			'sender'=>$sender,
			'accepter'=>$accepter,
			'task_content'=>$task_content,
			'date_start'=>$date_start,
			'date_end'=>$date_end,
			'todo_id'=>$todo_id
		));
		return $result;
	}
	
	public function todoNotice(){
		$post=$this->post;
		$accepter=isset($post['accepter'])?$post['accepter']:null;
		$result=$this->load('todo')->findByAccepterIdNotice($accepter);
		$total = count($result);
		$arr=array('total'=>$total,'data'=>$result);
		return $arr;
	}

	public function todoEarly()
	{
		$post=$this->post;
		$accepter=isset($post['accepter'])?$post['accepter']:null;
		$result=$this->load('todo')->findByAccepterIdEarlyNotice($accepter);
		$total = count($result);
		$arr=array('total'=>$total,'data'=>$result);
		return $arr;
	}

	public function search(){
		$post = $this->post;

		$result = $this->load('todo')->search($post);
		if(!$result){
			$result = array();
		}
		return $result;
	}
}

?>