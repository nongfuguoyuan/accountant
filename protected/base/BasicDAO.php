<?php

class BasicDAO implements \IBasicDAO {
	
	protected $modelName = 'BasicModel';
	private $tableName = null;
	public $map = array();
	
	private $host = "192.168.10.100";
	private $user = "root";
	private $password = "123456";
	private $database = "accountant";
	
	//初始化
	public function __construct($tableName){
		$tableName = preg_replace("/Controller$/", "", $tableName);
		$tableName = preg_replace('/([A-Z]{1})/', '_\$1', lcfirst($tableName));
		$this->tableName=strtolower($tableName);
		
		
	}
	
	//获取连接
	public function getConnection(){
		$conn = mysqli_connect($this->host, $this->user, $this->password, $this->database);
		mysqli_query($conn, "SET NAMES 'UTF8'");
		return $conn;
	}	
	//查询总行数
	public function count(){
		$conn=$this->getConnection();
		$sql="select COUNT(*) from employee ".$this->tableName;
		return mysqli_query($conn, $sql);
	}
	//默认获取自己的表结构
	public function getColumns(){
		
		$this->getColumn($this->tableName);
	}
	
	//获得一个表的列名
	public function getColumn($tableName){
		
		$sql = "show columns from ".$tableName;
		$conn = $this->getConnection();
		$columns = array();
		
		if($conn!=null){
			
			$rtn = mysqli_query($conn, $sql);
			
			while($rtn!==false&&($row=mysqli_fetch_array($rtn))!=null){
				
				$columns[] = $row[0];
			}
			
			return $columns;
		}

	}
	
	//通过ID查询
	public function findById($arr){
		foreach ($arr as $key=>$value){
			$where=$key."=".$value;
			break;
		}
		$arr=$this->findByWhere($where);
		return $arr;
	}
	
	//条件查询
	public function findByWhere($where) {
		
		//获得数据表的列名
		$columns = $this->getColumn($this->tableName);
		
		//拼接sql语句

		$sql = "select * from ".$this->tableName." where ".$where;
		
		$conn = $this->getConnection();
		
		$arr = array();
		
		if($conn!=null){
			
			$rtn = mysqli_query($conn, $sql);
			
			while($rtn!==false&&($row=mysqli_fetch_assoc($rtn))){	
				
				$arr[] = $row;
				
			}
			
			mysqli_close($conn);
		}
		$page=isset($_POST['page'])?isset($_POST['page']):1;
		$pageNum=isset($_POST['pageNum'])?isset($_POST['pageNum']):18;
		$arr=$this->page($arr,$page,$pageNum);
		return $arr;
	}
	
	//分页查询；支持排序
	public function findWhereOrderBy($where, $order, $start = null, $limit = null) {
	
		//获得数据表的列名
		$columns = $this->getColumn($this->tableName);
		
		//拼接sql语句
		$sql = "select * from ".$this->tableName." where ".$where." order by ".$order;
		if($limit!=null){
			
			$sql.=" limit ".$start.",".$limit;
		}
		
		$conn = $this->getConnection();
		
		$arr = array();
		
		if($conn!=null){
			
			$rtn = mysqli_query($conn, $sql);
			
			while ($rtn!==false&&($row=mysqli_fetch_assoc($rtn))!=null){

				$arr[] = $row;
			}
			
			mysqli_close($conn);
		}
		
		return $arr;
		
	}
	//模糊查询
	public function search($where){
		$table = $this->tableName;
		$sql='select '.$table.'_id,`name` from '.$table.' where '.$where;
		$conn=$this->getConnection();
	
		$arr=array();
		if($conn!=null){
			$rtn=mysqli_query($conn, $sql);
			while ($rtn!==false&&($row=mysqli_fetch_assoc($rtn))!=null){
				$arr[]=$row;
			}	
		}
		
		$arr=$this->page($arr,1,5);
		$arr=$arr['data'];
		return $arr;
	}
	//联表查询
	public function leftJoin($select,$tables,$ids=array(),$where='',$groupby='',$order=''){
		
		$conn = $this->getConnection();
		$sql="$select from $tables[0] ";
		$count = count($tables);
		if(empty($ids)){
			for ($i=1;$i<$count;$i++){
				$sql.="left join $tables[$i] on $tables[0].$tables[$i]_id = $tables[$i].$tables[$i]_id ";
			}
		}else{
			for ($i=1,$j=0;$i<$count;$i++,$j++){
				$sql.="left join $tables[$i] on $tables[0].$ids[$j]_id = $tables[$i].$ids[$j]_id ";
			}
		}
		if($where!=''){
			$sql.=$where;
		}
		$sql.=$groupby.$order;
		
		$rtn = mysqli_query($conn,$sql);
		$arr=array();
		while ($rtn!=false&&($row=mysqli_fetch_assoc($rtn))){		
			$arr[]=$row;
		}
		
		$page=isset($_POST['page'])?$_POST['page']:1;
		$pageNum=isset($_POST['pageNum'])?$_POST['pageNum']:18;
		$arr=$this->page($arr,$page,$pageNum);
		return $arr;
	}
	/*
	 *  分页函数 
	 */
	public function page($arr,$page=1,$pageNum=18){
		$count=count($arr);
		
		$start=($page-1)*$pageNum;
		$start = $start>0?$start:0;
		$limit = ($start+$pageNum)<$count?$pageNum:($count-$start);
		if($start<$count&&$limit>0){
			$arr1['total']=floor($count/$pageNum)+1;//总页数
			$arr=array_slice($arr, $start, $limit);
			$arr1['data']=$arr;
		
			return $arr1;
		}
	}
	//保存操作
	public function save($arr) {
	
		$columns = $this->getColumn($this->tableName);
		
		$conn = $this->getConnection();
		$tag = false;
		if($conn!=null){
			
			$sql = "insert into ".$this->tableName."(";
			
			foreach ($columns as $column){
				$sql .= "".$column.",";
			}
			
			$sql = substr($sql, 0, strlen($sql)-1).") values(";
			
			foreach ($columns as $column){
				if(isset($arr["$column"])){
					$value = $arr["$column"];//$obj->{"get".ucfirst($column)}();
					
					//判断$value的类型
					if($value==null){
						
						$sql .= "null,";
					}else if(preg_match("/^[0-9]*$/", $value)){
						
						//是数字
						$sql .= $value.",";
					}else{
						$sql .= "'".$value."',";
					}
				}else{
					if(preg_match('/time$/i', $column)){
						$sql.="now(),";
					}else{
						$sql .= "null,";
					}	
				}
			}
			
			$sql = substr($sql, 0, strlen($sql)-1);

			$sql .= ")";
			
			//执行sql语句
			
			
			$tag = mysqli_query($conn, $sql);
			
			mysqli_close($conn);		
		}
		
		return $tag;
	}
	
	//更新操作
	public function update($arr) {
		
		$conn = $this->getConnection();
		$columns = $this->getColumn($this->tableName);
		$count = count($columns);
		$tag = false;
		$arr1 = array();
		if($conn!=null){
			
			$sql = "update ".$this->tableName." set ";
			
			for($i=1;$i<$count;++$i){
				
				$column = $columns[$i];
				
				$value = isset($arr[$columns[$i]])?$arr[$columns[$i]]:null;//$obj->{"get".ucfirst($columns[$i])}();

				if(preg_match("/^[0-9]$/", $value)){
					
					$sql.=$column."=".$value.",";
				}else{
					
					$sql.=$column."='".$value."',";
				}
			}
			
			$sql = substr($sql, 0, strlen($sql)-1);
			
			$sql.=" where ";
			$tempColumn = $columns[0];
			
			$tempValue = $arr[$tempColumn];//$obj->{"get".ucfirst($column[0])}();
			
			if(preg_match("/^[0-9]$/", $tempValue)){
				
				$sql.=$tempColumn."=".$tempValue;
			}else{
				
				$sql.=$tempColumn."='".$tempValue."'";
			}
			
			//执行操作
			
			$tag=mysqli_query($conn, $sql);
			
			$arr1['tag']=$tag;
			//获取插入的最新数据
			
			$where="$tempColumn=$tempValue";
			$info=$this->findByWhere($where);
			$info=$info['data'];
			$arr1['info']=$info;
			mysqli_close($conn);
		}
		
		return $arr1;
	}

	//删除操作
	public function delete($id) {
	
		$conn = $this->getConnection();
		$tag = false;
	
		if($conn!=null){
				
			$sql = "delete from ".$this->tableName." where ";
				
			$columns = $this->getColumn($this->tableName);
				
			$value = $id;//$obj->{"get".ucfirst($columns[0])}();
				
			if($value!=null){
	
				//是数字
				if(preg_match("/^[0-9]*$/", $value)){
						
					$sql.=$columns[0]."=".$value;
				}else{
						
					$sql.=$columns[0]."='".$value."'";
				}
	
				//执行
				$tag=mysqli_query($conn, $sql);
	
			}
				
			mysqli_close($conn);
		}
	
		return $tag;
	}
}

?>