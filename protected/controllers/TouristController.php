<?php 
	
	class TouristController extends ZjhController{

		function updateBrowser(){

			return $this->view(V_PATH."update_browser.html",array(
				'css' => CSS,
				'img' => IMG,
				'host'=> _HOST
			));
		}

		function login(){

			$post = $this->post;
			if(count($post)){
				$phone = $post['phone'];
				$pass = $post['pass'];

				if(!validate('phone',$phone)){
					return false;
				}

				if(strlen($pass) < 5){
					return false;
				}

				$result = $this->load('employee')->login($phone,secret($pass));
				
				if($result){
					$per = $result['permissions'];
					
					if(!empty($per)){
						$result['permissions'] = array_map("strtolower",unserialize($per));
					}

					$this->session['user'] = $result;
					$this->redirect('index');
				}else{
					$this->redirect("login");
				}
			}else{
				return $this->view(V_PATH."login.html",array(
					"css"=>CSS,
					'host'=>_HOST
				));
			}
		}

		function logout(){
			session_unset();
			session_destroy();
			$this->redirect("login");
		}
		
		function index(){
			if(empty($this->session['user'])){
				$this->redirect("login");
			}else{
				$permissions = $this->session['user']['permissions'];
				return $this->view(HOME_PATH,array(
					'static'=>_STATIC,
					'host'=>_HOST,
					'css'=>CSS,
					'js'=>JS,
					'img'=>IMG,
					'permission'=>$permissions
				));
			}
		}
	}

 ?>