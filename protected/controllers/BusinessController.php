<?php
	require_once('zjhcontroller.php');
	class BusinessController extends ZjhController{
		function findall(){
			$post = $this->post;
			$page = $post['page'];
			$pagenum = $post['pageNum'];
			if(empty($page)) $page = 1;
			if(empty($pagenum)) $pagenum = 2;
			return $this->load('business')->findall(array($page,$pagenum));
		}
	}
?>