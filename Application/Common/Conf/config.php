<?php
return array(
	//'配置项'=>'配置值'
	'mytemplate_dir'  => 'http://116.62.171.54:8080/manbike0.2/mytemplate/gentelella',
	'DB_TYPE' => 'mysql',
	'DB_HOST' => 'localhost',
	'DB_PORT' => '3306',
	'DB_NAME' => 'dwz',
	'DB_USER' => 'root',
	'DB_PWD' => '',
	'DB_PREFIX' => 'dwz_',
	'agent_version' => 1,
	'AUTH_CONFIG' => array(
		'AUTH_ON' => true, //认证开关
		'AUTH_TYPE' => 1, // 认证方式，1为时时认证；2为登录认证。
		'AUTH_GROUP' => 'think_auth_group', //用户组数据表名
		'AUTH_GROUP_ACCESS' => 'think_auth_group_access', //用户组明细表
		'AUTH_RULE' => 'think_auth_rule', //权限规则表
		'AUTH_USER' => 'think_auth_user' //用户信息表
	)
);