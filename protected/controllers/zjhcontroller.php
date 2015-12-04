<?php
	class ZjhController{

		public function __construct(){
			$post = &$_POST;
			$get = &$_GET;
			
			if(!empty($post)){
				foreach($post as $key => $value){
					// if(is_string($value)){
					// 	$value = htmlspecialchars_decode(trim($value))
					// }
					$post[$key] = $value;
				}
			}
			if(!empty($get)){
				foreach($get as $key => $value){
					$get[$key] = htmlspecialchars_decode(trim($value));
				}
			}

			$this->post = $post;
			$this->get = $get;
			$this->session = &$_SESSION;
			$this->json();
		}

		public function redirect($uri){
			header("Location:"._HOST.$uri);
			exit;
		}

		public function html(){
			header("Content-type:text/html;charset=utf-8");
		}
		public function view($file,$data=array()){
			$this->html();
			extract($data);
			ob_start();
			if(file_exists($file)){
				require($file);
			}else{
				trigger_error('can not find static file!');
			}
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		}
		
		public function page(){
			$page = (int)$this->post['page'];
			$pagenum = (int)$this->post['pageNum'];

			if(empty($page)) $page = 1;

			return $page;
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