<?php
class Base {
	private static $_app=array();
	public static $controlls=array();
	public static $models=array();
	
	public function start($ca,$obj=null){
		
		if(!isset($this::$controlls[$ca[0]])){
			$controllFile=C_PATH.DIRECTORY_SEPARATOR.$ca[0].'.php';
			
			if(is_file($controllFile)){
				include $controllFile;
				$this::$controlls[$ca[0]]=new guest();
			}else{
				return;
			}
		}
		$this::$controlls[$ca[0]]->$ca[1]($obj);
		var_dump($ca);
	}

}

?>