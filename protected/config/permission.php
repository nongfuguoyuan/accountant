<?php 
	/*
	以下方法存在，但不再使用，因为流程组不再允许变动

	'processgroup'			=> '流程组(所有权限)',
	'processgroup/save'		=> '添加流程组',
	'processgroup/update'	=> '流程组(所有权限)',
	'processgroup/delete'	=> '流程组(所有权限)',
	*/

	$permissions = array(
		'guest'     			=> '所有客户(所有权限)',
		'guest/find'     		=> '查看客户',
		'guest/save'     		=> '添加客户',
		'guest/update'     		=> '更新客户',
		'guest/delete'     		=> '删除客户',

		'accounting'			=> '代理记账（所有权限）',
		'accounting/findOpen'	=> '开通代理记账用户',
		'accounting/save'		=> '添加代理记账',
		'accounting/update'		=> '更新代理记账',
		'accounting/updateStatus'=> '更新代理记账状态',
		'accounting/delete'		=> '删除代理记账任务',

		'progress'				=> "工商业务进度（所有权限）",
		'progress/save'			=> "添加进度",
		'progress/update'		=> "更新进度",
		'progress/delete'		=> "删除进度",

		'business'				=> '工商（所有权限）',
		'business/updateProcess'=> '更新工商用户进度',
		'business/updateFee'	=> '更新工商交费信息',
		'business/updateStatus'	=> '更新工商用户状态',
		'business/findOpen'		=> '详细信息面板',
		'business/save'			=> '添加工商用户',
		'business/update'		=> '更新工商用户',
		'business/delete'		=> '删除工商用户',

		'department'			=> '部门（所有权限）',
		'department/find'		=> '显示所有部门',
		'department/findMenu'	=> '菜单（只有部门）',
		'department/findWholeMenu'=> '菜单（包括员工）',
		'department/save'		=> '添加部门',
		'department/update'		=> '更新部门',
		'department/delete'		=> '删除部门',
		'employee'				=> '员工(所有权限)',
		'employee/findAccountings'=>'查询所有会计',
		"employee/updateStatus" => '更新员工状态（离职）',
		"employee/save"			=>	'添加员工',
		"employee/update"		=>	'更新员工信息',
		"employee/delete"		=>	'删除员工',

		'payrecord'				=> '交费记录(所有权限)',
		'payrecord/update'		=> '更新交费记录',
		'payrecord/save'		=> '添加交费记录',
		'payrecord/delete'		=> '删除交费记录',

		'process'				=> '流程(所有权限)',
		'process/find'			=> '查询流程',
		'process/save'			=> '添加流程',
		'process/update'		=> '更新流程',
		'process/delete'		=> '删除流程',

		'record'				=> '追踪记录（所有权限）',
		'record/find'			=> '查询追踪记录',
		'record/save'			=> '添加追踪记录',
		'record/update'			=> '更新追踪记录',
		'record/delete'			=> '删除追踪记录',

		'resource'				=> '客户来源（所有权限）',
		'resource/find'			=> '查询客户来源',
		'resource/save'			=> '添加客户来源',
		'resource/update'		=> '更新客户来源',
		'resource/delete'		=> '删除客户来源',


		'roles'					=> '权限设置（所有权限）',

		'taxcollect'			=> '缴税记录（所有权限）',
		'taxcollect/find'		=> '查询缴税记录',
		'taxcollect/save'		=> '添加缴税记录',
		'taxcollect/update'		=> '更新缴税记录',
		'taxcollect/delete'		=> '删除缴税记录',

		'taxcount'				=> '税务统计(所有权限)',
		'taxcount/find'			=> '查询税务统计',
		'taxcount/save'			=> '添加税务统计',
		'taxcount/update'		=> '更新税务统计',
		'taxcount/delete'		=> '删除税务统计',

		'taxtype'				=> '税务类型设置',
		'todo'					=> '任务（所有权限）'
	);

	//所有人都该具备的权限

	$publicPermission = array(
		'processgroup/find'				=> '查询业务类型',
		'process/getAll'				=> '所有流程（下拉列表）',
		"process/getList"				=> '某一个业务的进度',
		'employee/findAccountings'		=> '查找所有会计（下拉列表）',
		'taxcollect/find'				=> '',
		'taxcollect/findList'			=> '用户详细缴税记录',
		'dashboard'						=> '仪表盘(管理面板)',
		'area'							=> '查找地区',
		'tourist'						=> '登录/登入首页/注销/等',
		'process/find'					=> '查询所有流程',
		'department/find'				=> '显示所有部门',
		'department/findMenu'			=> '菜单（只有部门）',
		'department/findWholeMenu'		=> '菜单（包括员工）',
		"employee/session" 				=>	'员工查看是否登陆',
		'employee/updatePass'			=> '更新员工密码',
		"employee/permission"			=>	'员工查看权限',
		"employee/find"					=>	'员工查看其他员工信息',
		"employee/findByDepartmentid"	=>	'按部门查看员工',
		'progress/find'					=> '查询进度',
		'accounting/find'				=> '查看代理记账',
		'business/find'					=> '查看工商用户',
		'payrecord/find'				=> '所有缴费总览',
		'payrecord/findList'			=> '查看详细缴费记录',
		'taxcollect/find'				=> '查看税务详细统计',
		'taxtype/findByParentid'		=> '查找税务类型',
		'resource/find'					=> '查找信息来源',
		'taxcount/find'					=> '查看税务统计'
	);
	//错误码说明
	$error_code = array(
		'1' => '没有权限',
		'2' =>	'登录超时，请重新登录',
		'3'	=> '请求的文件不存在',
		'4'	=> '请求的API不存在',
		'5'	=> '添加或者更新失败'
	);
 ?>