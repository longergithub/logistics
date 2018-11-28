<?php
return array(
	'APP_GROUP_LIST' => 'Home',//开启应用分组
	'DEFAULT_GROUP' => 'Home',//默认分组
	'APP_GROUP_MODE' => 1,//开启独立分组
	'APP_GROUP_PATH' => 'Modules',//独立分组文件夹名称
	'LOAD_EXT_CONFIG' => '',//不能有空格
	'DB_HOST' => 'localhost',//数据库链接配置
	'DB_USER' => 'root',
	'DB_PWD' => '123456',
	'DB_NAME' => 'logistics',
	'DB_PREFIX' => 'wwt_',
	'TMPL_VAR_IDENTIFY' => 'array',//点语法默认解析
	'DEFAULT_FILTER' => 'htmlspecialchars',//默认过滤函数
	//开启页面Trace功能,true为开启
	'SHOW_PAGE_TRACE' => false,
	'URL_MODEL' => 2,
	'URL_HTML_SUFFIX' => 'html',
	'URL_ROUTER_ON' => true,//true为开启路由
	'URL_ROUTE_RULES' => array(
	),
	//配置错误页面信息，设置为ture模版中才能获取传入的错误信息
	'SHOW_ERROR_MSG' => true
);
