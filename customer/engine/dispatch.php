<?php
	class Dispatch{
		
		private $class = "";
		private $method = "";
		private $version = "";
		private $param = "";

		private function defineConst(){
			$v = $this->version;
			define("CUSTOMER_C",CUSTOMER.$v."/controllers/");
			define("CUSTOMER_M",CUSTOMER.$v."/models/");
			define("CUSTOMER_S",CUSTOMER.$v."/static/");
			define("CUSTOMER_V",CUSTOMER.$v."/view/");
			define('CUSTOMER_HOST',_HOST."customer/".$v."/");
		}

		public function __construct(){
			$uri = explode("/",$_SERVER['REQUEST_URI']);
			foreach($uri as $key => $value){
				if($value == "customer"){
					$this->version = strtolower($uri[$key+1]);
					$this->class = strtolower($uri[$key+2]);
					$this->method = strtolower($uri[$key+3]);
					$this->param = isset($uri[$key+4]) ? $uri[$key+4] : "";
				}
			}
			$this->defineConst();
			require("commoncontroller.php");
		}

		public function start(){
			if(empty($this->class) || empty($this->method)){
				trigger_error("format you request is invalid!");
			}else{
				$file = CUSTOMER_C.$this->class.".php";
				if(is_file($file)){
					require($file);
					$class = ucfirst($this->class)."Controller";
					$controller = new $class;
					if(is_callable(array($controller,$this->method))){
						return call_user_func(array($controller,$this->method),$this->param);
					}else{
						trigger_error("API you request is not exist!");
					}
				}else{
					trigger_error("file you request is not exist!");
				}
			}
		}

	}
?>