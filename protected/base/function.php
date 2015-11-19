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