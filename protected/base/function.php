<?php
/*
 * example:modelName->model_name
 */
function orm($name){
	$reg = "/([A-Z]{1})/";
	$tableName = preg_replace($reg, "_\$1", $name);
	$tableName=strtolower($tableName);
	
	return $tableName;
}
// 返回随机的，未经加密的密码
function randomPass($num = 6){
	if($num <= 0){
		throw new Exception("random need a positive number as parameter", 1);
	}
	$source = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$str = "";
	for($i = 0;$i < $num;$i++){
		$index = rand(0,51);
		$str = $str . substr($source,$index,1);
	}
	return $str;
}
/*
 * example:EmployeeController->EmployeeModel
 */
function cm($controller){
	return substr($controller, 0, strlen($controller)-10)."Model";
}
/*
*author:zjh
*date:2015-11-19
*验证函数
*/
function validate($case,$str){
	switch ($case) {
		case 'name':
			return preg_match('/^[\x{4e00}-\x{9fa5}]{2,4}$/u',$str);
			break;
		case 'year':
			return preg_match('/^20\d{2}$/',$str);
			break;
		case 'date'://e:2015-12-12 no hours ...
			return preg_match('/^20\d{2}[-\/\.]{1}(1[012]?|[1-9]|0[1-9])[-\/\.](0[1-9]|[12][0-9]|[1-9]|3[01])$/',$str);
			break;
		case 'month':
			return preg_match('/(^0[1-9]$)|(^1[012]$)|(^[1-9]$)/',$str);
			break;
		case 'email':
			return preg_match('/^[\da-zA-Z_]{5,}@[a-z\d]+\.(com|cn|net)$/',$str);
			break;
		case 'pass':
			return preg_match('/^[a-zA-Z0-9_#%$@.]{6,20}$/', $str);
			break;
		case 'phone':
			return preg_match('/^1[34578]\d{9}$/',$str);
			break;
		case 'tel':
			return preg_match('/^0\d{2,3}[-]?\d{7,8}/',$str);
			break;
		case 'code':
			return preg_match('/^[\da-z]{4}$/', $str);
			break;
		case 'sms':
			return preg_match('/^\d{6}$/',$str);
			break;
		default:
			throw new Exception('type you validate is not be supported');
			break;
	}
}
/*
*author:zjh
*date:2015-11-19
*返回当前时间，以及格式化
*/
function timenow(){
	return date('Y-m-d H:i:s');
}
function format($time,$place="/"){
	return date('Y'.$place.'m'.$place.'d',strtotime($time));
}
/*
*author:zjh
*date:2015-11-19
*通过程序而不是表单提交post请求
*/
function phppost($url,$data=''){

	$post='';
	$row = parse_url($url);
	$host = $row['host'];
	$port = isset($row['port']) ? $row['port']:80;
	$file = $row['path'];
	while (list($k,$v) = each($data)) 
	{
		$post .= rawurlencode($k)."=".rawurlencode($v)."&";	//转URL标准码
	}
	$post = substr( $post , 0 , -1 );
	$len = strlen($post);
	$fp = @fsockopen( $host ,$port, $errno, $errstr, 10);
	if (!$fp) {
		return "$errstr ($errno)\n";
	} else {
		$receive = '';
		$out = "POST $file HTTP/1.0\r\n";
		$out .= "Host: $host\r\n";
		$out .= "Content-type: application/x-www-form-urlencoded\r\n";
		$out .= "Connection: Close\r\n";
		$out .= "Content-Length: $len\r\n\r\n";
		$out .= $post;	
		fwrite($fp, $out);
		while (!feof($fp)) {
			$receive .= fgets($fp, 128);
		}
		fclose($fp);
		$receive = explode("\r\n\r\n",$receive);
		unset($receive[0]);
		return implode("",$receive);
	}
}
function secret($pass){
	return md5(crypt($pass,substr($pass,0,2)));
}