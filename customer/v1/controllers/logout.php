<?php
	class LogoutController extends CommonController{
		function get(){
			session_destroy();
		}
	}