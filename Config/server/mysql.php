<?php

return [
	'pdo_attr'	 => [
		PDO::MYSQL_ATTR_INIT_COMMAND		 => "SET SESSION SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION'", // 设置本次会话属性:`严格group(与oracle一致)`,`严格模式，进行数据的严格校验，错误数据不能插入，报error错误`,`如果被零除(或MOD(X，0))，则产生错误(否则为警告)`,`防止GRANT自动创建新用户，除非还指定了密码`,`如果需要的存储引擎被禁用或未编译，那么抛出错误`
		PDO::ATTR_ERRMODE					 => PDO::ERRMODE_EXCEPTION, // 错误以异常的形式抛出
		PDO::ATTR_EMULATE_PREPARES			 => false, // 不使用模拟prepare, 使用真正意义的prepare
		PDO::MYSQL_ATTR_USE_BUFFERED_QUERY	 => false, // 无缓冲模式,MySQL查询执行查询,同时数据等待从MySQL服务器进行获取,在PHP取回所有结果前,在当前数据库连接下不能发送其他的查询请求.
		PDO::ATTR_CASE						 => PDO::CASE_LOWER, // 强制列名小写
		PDO::ATTR_ORACLE_NULLS				 => PDO::NULL_TO_STRING, // 将 NULL 转换成空字符串
		PDO::ATTR_STRINGIFY_FETCHES			 => false, // 提取的时候不将数值转换为字符串
		PDO::ATTR_AUTOCOMMIT				 => true, // 自动提交每个单独的语句
	],
	'ini_sql'	 => [
		'SET SESSION TRANSACTION ISOLATION LEVEL REPEATABLE READ',
		'SET NAMES UTF8',
	]
];
