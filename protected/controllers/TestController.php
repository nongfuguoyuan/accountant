<?php
	class TestController extends ZjhController{
		public function haha(){
			return $this->load('employee')->find();
		}
	}
?>