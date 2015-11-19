<?php
	require_once('/../base/function.php');
	class ZjhController{
		public function __construct(){
			$post = &$_POST;
			$get = &$_GET;
			if(!empty($post)){
				foreach($post as $key => $value){
					$post[$key] = htmlspecialchars_decode(trim($value));
				}
			}
			if(!empty($get)){
				foreach($get as $key => $value){
					$get[$key] = htmlspecialchars_decode(trim($value));
				}
			}

			$this->post = $post;
			$this->get = $get;
			$this->json();
		}
		public function json(){
			header('Content-type:application/json');
		}
		public function load($model){
			require_once('/../base/'.$model.".php");
			$class = ucfirst($model);
			return new $class();
		}

	}
?>