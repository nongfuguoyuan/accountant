// <?php
// $config_db=include C_PATH.'data.php';
// //  Model模型类
// // 实现了ORM和ActiveRecords模式
// class Model{
// 	protected static $mysqli;
// 	protected $result;

// 	/*
// 	 * 架构函数 取得DB类的实例对象 字段检查
// 	 * @access public
// 	 * @param string $name 模型名称
// 	 * @param string $tablePrefix 表前缀
// 	 * @param mixed $connection 数据连接信息
// 	 */
// 	public function __construct($config_db){
// 		$this->db($config_db);
// 	}
// 	private function db($config_db){
// 		self::$mysqli=new mysqli($config_db['host']='localhost', $config_db['user']='root', $config_db['password']='', $config_db['database']='accountant', $config_db['port']='3306');
// 		if(self::$mysqli->errno){
// 			exit("数据库连接错误！");
// 		}
// 	}
// 	/*
// 	 * @param array $tableName表名 
// 	 * @param array $condition查询条件
// 	 */
// 	public function find($tableName,$condition){
		
// 		$sql = "select * from $tableName where $condition";
// 		$result=self::$mysqli->query($sql);
// 	}
// }

// //直接查找不用封装成对象//来自BasicController
// function find($arr){
// 	$tag=true;
// 	if($arr['order_by']!=''){
// 		$order=$arr[$key];
// 		$tag=false;
// 	}elseif ($arr['start']&&$arr['limit']){
// 		$start=$arr['start'];
// 		$limit=$arr['limit'];
// 	}

// 	if($tag){
// 		foreach ($arr as $key=>$value){
// 			$where.="$key=$value and";
// 		}
// 		$where=substr($where, 0,strlen($where)-3);
// 		$this->dao->findByWhere($where);
// 	}else{
// 		foreach ($arr as $key=>$value){
// 			if($key!='order_by'&&$key!='start'&&$key!='limit'){
// 				$where.="$key=$value and";
// 			}
// 		}
// 		$where=substr($where, 0,strlen($where)-3);
// 		$this->dao->findWhereOrderBy($where,$order,$start,$limit);
// 	}

// 	$arr = $this->dao->findByWhere($where);
// }
// ?>