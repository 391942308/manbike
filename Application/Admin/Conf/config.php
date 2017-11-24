<?php
return array(
	//'配置项'=>'配置值'
	'DB_TYPE' => 'mysql',
	'DB_HOST' => 'localhost',
	'DB_PORT' => '3306',
	'DB_NAME' => 'dwz',
	'DB_USER' => 'root',
	'DB_PWD' => 'root',
	'DB_PREFIX' => 'dwz_',
	'agent_version' => 1,
	'AUTH_CONFIG' => array(
		'AUTH_ON' => true, //认证开关
		'AUTH_TYPE' => 1, // 认证方式，1为时时认证；2为登录认证。
		'AUTH_GROUP' => 'dwz_auth_group', //用户组数据表名
		'AUTH_GROUP_ACCESS' => 'dwz_auth_group_access', //用户组明细表
		'AUTH_RULE' => 'dwz_auth_rule', //权限规则表
		'AUTH_USER' => 'dwz_auth_user' //用户信息表
	),
		'TMPL_TEMPLATE_SUFFIX'=>'.php',
//		//默认错误跳转对应的模板文件
//		'TMPL_ACTION_ERROR' => 'Public:error',
//		//默认成功跳转对应的模板文件
//		'TMPL_ACTION_SUCCESS' => 'Public:success',
);