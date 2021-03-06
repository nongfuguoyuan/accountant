<?php

class RolesController extends ZjhController {

	function find(){
		return $this->load('roles')->find();
	}

	function findAllName(){
		return $this->load('roles')->findAllName();
	}

	function permissionList(){
		$roles_id = (int)$this->post['roles_id'];
		
		$result = $this->load('roles')->permissionList($roles_id);

		if($result){
			return unserialize($result['permission']);
		}else{
			return false;
		}
	}

	function permission(){
		$result = $roles_id = $this->load('permission')->permission($roles_id);
		if($result){
			return unserialize($result['permission']);
		}else{
			return false;
		}
	}

	function allPermission(){
		
		global $permissions;

		return $permissions;
	}
	
	function save(){
		$name = $this->post['name'];
		$permission = $this->post['permission'];

		$lastid = $this->load('roles')->add(array(
			'name' => $name,
			'permission' => serialize($permission)
		));

		if($lastid){
			return $this->load('roles')->findName($lastid);
		}else{
			return false;
		}

	}

	function update(){
		$roles_id = (int)$this->post['roles_id'];
		$name = $this->post['name'];
		$permission = $this->post['permission'];

		if($roles_id == 0){
			return '请选定要操作的记录';
		}
		if(strlen($name) < 1){
			return '名字不符合要求';
		}

		$result =  $this->load('roles')->update(array(
			'roles_id'   => $roles_id,
			'name'       => $name,
			'permission' => serialize($permission)
		));

		if($result){
			return $this->load('roles')->findName($roles_id);
		}else{
			return 0;//update fail
		}
	}

	function delete(){
		return $this->load('roles')->delete((int)$this->post['roles_id']);
	}
}

?>
