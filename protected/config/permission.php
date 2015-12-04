<?php 
	$permissions = array(
		'guest'     			=> '所有客户（所有权限）',
		'guest/_find'     		=> '查看自己名下所有客户',
		'guest/find'     		=> '查看所有客户',
		'guest/save'     		=> '添加客户',
		'guest/update'     		=> '更新客户信息',
		'guest/delete'     		=> '删除客户',
		'guest/searchByPhone' 	=> '客户/按电话搜索',
		'guest/searchByCom'     => '客户/按公司名搜索',

		'area'					=> '*查找地区',

		'accounting'			=> '代理记账（所有权限）',
		'accounting/save'		=> '添加代理记账任务',
		'accounting/update'		=> '更新代理记账信息',
		'accounting/updateStatus'=> '更新代理记账为受理状态',
		'accounting/find'		=> '查看所有代理记账用户',
		'accounting/_find'		=> '查看自己名下代理记账用户',
		'accounting/delete'		=> '删除代理记账任务',

		'progress'				=> "*进度",

		'business'				=> '工商注册（所有权限）',
		'business/_find'		=> '查看自己名下工商用户',
		'business/find'			=> '查看所有工商用户',
		'business/findOpen'		=> '查看已经开通服务的工商用户',
		'business/updateProcess'=> '更新工商用户进度',
		'business/updateStatus'	=> '更改工商用户状态为已受理',
		'business/save'			=> '添加工商用户',
		'business/update'		=> '更新工商用户信息',
		'business/delete'		=> '删除工商用户',

		'dashboard'				=> '*仪表盘(管理面板)',

		'department'			=> '部门（所有权限）',
		'department/findByEmployee'=> '显示部门用户',
		'department/findAll'	=> '显示所有部门',
		'department/findMenu'	=> '显示部门列表',

		'employee'				=> '员工(所有权限)',

		'payrecord'				=> '缴费记录(所有权限)',
		'payrecord/_find'		=> '自己名下用户最近月份缴费总额',
		'payrecord/findList'	=> '查看用户最近缴费记录',
		'payrecord/save'		=> '添加用户缴费记录',
		'payrecord/delete'		=> '删除用户缴费记录',
		'payrecord/find'		=> '所有用户最近月份缴费总额',

		'processgroup'			=> '*流程组(所有权限)',

		'process'				=> '*查看流程',

		'record'				=> '*追踪记录（所有权限）',

		'resource'				=> '*客户来源（所有权限）',

		'roles'					=> '角色（权限）设置',

		'taxcollect'			=> '缴税记录（所有权限）',
		'taxcollect/_find'		=> '自己用户名下缴税记录',
		'taxcollect/find'		=> '所有用户缴税记录',
		'taxcollect/findList'	=> '用户详细缴税记录',
		'taxcollect/add'		=> '添加缴税记录',
		'taxcollect/update'		=> '更新缴税记录',
		'taxcollect/delete'		=> '删除缴税记录',

		'taxcount'				=> '税务统计(所有权限)',
		'taxcount/save'			=> '添加税务统计',
		'taxtype'				=> '税务类型设置',
		'todo'					=> '任务（所有权限）'
	);
 ?>