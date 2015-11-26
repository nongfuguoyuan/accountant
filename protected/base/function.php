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
		case 'email':
			return preg_match('/^[\da-zA-Z_]{5,}@[a-z\d]+\.(com|cn|net)$/',$str);
			break;
		case 'pass':
			return preg_match('/^[a-zA-Z0-9_#%$@.]{8,20}$/', $str);
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
			return false;
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
	$port = $row['port'] ? $row['port']:80;
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