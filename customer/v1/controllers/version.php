<?php
	class VersionController extends CommonController{
		function get(){
			return json_encode(array(
				"version" 	  => "1.0",
				"url"	  	  => "http://192.168.10.105/test.apk",
				"description" => "1.版本更稳定.\n2.更多新鲜资讯."
			));
		}
	}

?>