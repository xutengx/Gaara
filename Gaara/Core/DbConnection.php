<?php

declare(strict_types = 1);
namespace Gaara\Core;

use Exception;
use PDOException;
use PDOStatement;
use Log;
use PDO;

/**
 * 数据库连接类，依赖 PDO_MYSQL 扩展
 */
class DbConnection {

	// 当前进程标识
	private $identification	 = null;
	// 是否主从数据库
	private $Master_slave	 = true;
	// 数据库链接名称, 当抛出异常时帮助定位数据库链接
	private $connection		 = null;
	// 数据库 读 连接集合
	private $dbRead			 = [];
	// 数据库 读 权重
	private $dbReadWeight	 = [];
	// 数据库 写 连接集合
	private $dbWrite		 = [];
	// 数据库 写 权重
	private $dbWriteWeight	 = [];
	// 当前操作类型 select update delate insert
	private $type			 = 'select';
	// 是否事务过程中 不进行数据库更换
	private $transaction	 = false;
	// ---------------------------- 单进程 ----------------------------- //
	// 单进程不进行数据库更换
	private $single			 = true;
	// 当前数据库 读 连接
	private $dbReadSingle;
	// 当前数据库 写 连接
	private $dbWriteSingle;

	// ------------------------------------------------------------------//

	/**
	 * 读取&格式化 配置信息
	 * @param array $DBconf
	 * 如下结构:
	  'db'=>[
	  'write'=>[
	  [
	  'weight'=>10,
	  'type'=>'mysql',
	  'host'=>'10.4.17.200',
	  'port'=>3306,
	  'user'=>'root',
	  'pwd'=>'Huawei$123#_',
	  'db'=>'hk'
	  ]
	  ],
	  'read'=>[
	  [
	  'weight'=>10,
	  'type'=>'mysql',
	  'host'=>'10.4.17.218',
	  'port'=>3306,
	  'user'=>'root',
	  'pwd'=>'Huawei$123#_',
	  'db'=>'hk'
	  ],
	  [
	  'weight'=>10,
	  'type'=>'mysql',
	  'host'=>'10.4.17.219',
	  'port'=>3306,
	  'user'=>'root',
	  'pwd'=>'Huawei$123#_',
	  'db'=>'hk'
	  ]
	  ]
	  ],
	 * @param int    $weight        // 权重
	 * @param string $type          // 数据库类型
	 * @param string $host          // 连接地址
	 * @param int    $port          // 端口
	 * @param string $user          // 用户名
	 * @param string $pwd           // 密码
	 * @param string $db            // 数据库
	 */

	/**
	 *
	 * @param array $DBconf	 配置数组
	 * @param string $connection 数据库链接名
	 * @param bool $single 单进程模式
	 */
	public function __construct(array $DBconf, string $connection, bool $single = true) {
		$this->identification	 = uniqid((string) getmypid());
		$this->connection		 = $connection;
		$this->single			 = $single;
		$this->confFormat($DBconf['write'], $this->dbWriteWeight, $this->dbWrite);
		if (isset($DBconf['read']) && !empty($DBconf['read'])) {
			$this->confFormat($DBconf['read'], $this->dbReadWeight, $this->dbRead);
		} else
			$this->Master_slave = false;
	}

	/**
	 * 格式化配置文件, 引用赋值
	 * @param array $theConf 待格式化的配置数组
	 * @param array &$theDbWeight 权重数组
	 * @param array &$theDb 配置数组
	 */
	private function confFormat(array $theConf, array &$theDbWeight, array &$theDb): void {
		foreach ($theConf as $k => $v) {
			$theDb[md5(serialize($v))]	 = $v;
			$t							 = end($theDbWeight);
			if (empty($t))
				$theDbWeight[$v['weight']]	 = md5(serialize($v));
			else {
				$weight										 = array_keys($theDbWeight);
				$theDbWeight[$v['weight'] + end($weight)]	 = md5(serialize($v));
			}
		}
	}

	/**
	 * 由操作类型(读/写), 返回已存在的PDO实现
	 * @return PDO
	 */
	private function PDO(): PDO {
		// http请求都属于此
		if ($this->single) {
			// 查询操作且不属于事务,使用读连接
			if ($this->type === 'select' && !$this->transaction && $this->Master_slave) {
				if (is_object($this->dbReadSingle) || ($this->dbReadSingle = $this->connect()))
					return $this->dbReadSingle;
			}
			// 写连接
			elseif (is_object($this->dbWriteSingle) || ($this->dbWriteSingle = $this->connect()))
				return $this->dbWriteSingle;
		} else
			return $this->connect();
	}

	/**
	 * 由操作类型(读/写)和权重(weight), 创建并返回PDO数据库连接
	 * @return PDO
	 */
	private function connect(): PDO {
		// 查询操作且不属于事务,使用读连接
		if ($this->type === 'select' && !$this->transaction && $this->Master_slave) {
			return $this->weightSelection($this->dbReadWeight, $this->dbRead);
		} else {
			return $this->weightSelection($this->dbWriteWeight, $this->dbWrite);
		}
	}

	/**
	 * 根据权重, 实例化pdo
	 * @param array $theDbWeight 权重数组
	 * @param array &$theDb 配置数组->pdo数组
	 * @return PDO
	 */
	private function weightSelection(array $theDbWeight, array &$theDb): PDO {
		$tmp	 = array_keys($theDbWeight);
		$weight	 = rand(1, end($tmp));
		foreach ($theDbWeight as $k => $v) {
			if ($k - $weight >= 0) {
				$key = $v;
				break;
			}
		}
		if (!is_object($theDb[$key])) {
			$settings	 = $theDb[$key];
			$dsn		 = 'mysql:dbname=' . $settings['db'] . ';host=' . $settings['host'] . ';port=' . $settings['port'];
			$theDb[$key] = new PDO($dsn, $settings['user'], $settings['pwd'], $this->initArray($settings['char']
				?? 'utf8'));
		}
		return $theDb[$key];
	}

	/**
	 * pdo初始化属性
	 * 参考文档 https://www.cnblogs.com/Zender/p/8270833.html https://www.cnblogs.com/hf8051/p/4673030.html
	 * @param string $char 字符编码
	 * @return array
	 */
	private function initArray(string $char): array {
		return [
			PDO::MYSQL_ATTR_INIT_COMMAND		 => 'SET NAMES ' . $char, // 设置字符集
			PDO::MYSQL_ATTR_INIT_COMMAND		 => "set session sql_mode='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION'", // 设置本次会话属性:`严格group(与oracle一致)`,`严格模式，进行数据的严格校验，错误数据不能插入，报error错误`,`如果被零除(或MOD(X，0))，则产生错误(否则为警告)`,`防止GRANT自动创建新用户，除非还指定了密码`,`如果需要的存储引擎被禁用或未编译，那么抛出错误`
			PDO::ATTR_ERRMODE					 => PDO::ERRMODE_EXCEPTION, // 错误以异常的形式抛出
			PDO::ATTR_EMULATE_PREPARES			 => false, // 不使用模拟prepare, 使用真正意义的prepare
			PDO::MYSQL_ATTR_USE_BUFFERED_QUERY	 => false, // 无缓冲模式,MySQL查询执行查询,同时数据等待从MySQL服务器进行获取,在PHP取回所有结果前,在当前数据库连接下不能发送其他的查询请求.
			PDO::ATTR_CASE						 => PDO::CASE_LOWER, // 强制列名小写
			PDO::ATTR_ORACLE_NULLS				 => PDO::NULL_TO_STRING, // 将 NULL 转换成空字符串
			PDO::ATTR_STRINGIFY_FETCHES			 => false, // 提取的时候不将数值转换为字符串
			PDO::ATTR_AUTOCOMMIT				 => true, // 自动提交每个单独的语句
		];
	}

	/**
	 * 内部执行, 返回原始数据对象, 触发异常处理
	 * @param string $sql
	 * @param array $pars 参数绑定数组
	 * @param bool $auto 自动执行绑定
	 * @param      $PDO 用作 `insertGetId`的return
	 * @return PDOStatement
	 * @throws PDOException
	 */
	private function prepare_execute(string $sql, array $pars = [], bool $auto = true, &$PDO = null): PDOStatement {
		try {
			// 链接数据库
			$PDO			 = $this->PDO();
			// 备要执行的SQL语句并返回一个 PDOStatement 对象
			$PDOStatement	 = $PDO->prepare($sql);
			if ($auto)
			// 执行一条预处理语句
				$PDOStatement->execute($pars);
			// 普通 sql 记录
			$this->logInfo($sql, $pars, !$auto);
			return $PDOStatement;
		} catch (PDOException $pdoException) {
			// 错误 sql 记录
			$this->logError($pdoException->getMessage(), $sql, $pars, !$auto);
			// 异常抛出
			throw $pdoException;
		}
	}

	/**
	 * 执行
	 * @param PDOStatement $PDOStatement
	 * @param array $bindings
	 * @return void
	 * @throws PDOException
	 */
	public function execute(PDOStatement $PDOStatement, array $bindings): void {
		$sql = $PDOStatement->queryString;
		try {
			// 执行一条预处理语句
			$PDOStatement->execute($bindings);
			// 普通 sql 记录
			$this->logInfo($sql, $bindings, true);
		} catch (PDOException $pdoException) {
			// 错误 sql 记录
			$this->logError($pdoException->getMessage(), $sql, $bindings, true);
			// 异常抛出
			throw $pdoException;
		}
	}

	/**
	 * 返回PDOStatement, 可做分块解析
	 * @param string $sql
	 * @param array $pars
	 * @return PDOStatement
	 */
	public function getChunk(string $sql, array $pars = []): PDOStatement {
		$this->type = 'select';
		return $this->prepare_execute($sql, $pars);
	}

	/**
	 * 查询一行
	 * @param type $sql
	 * @param array $pars 参数绑定数组
	 * @return array 一维数组
	 */
	public function getRow(string $sql, array $pars = []): array {
		$this->type	 = 'select';
		$re			 = $this->prepare_execute($sql, $pars)->fetch(\PDO::FETCH_ASSOC);
		return $re ? $re : [];
	}

	/**
	 * 查询多行
	 * @param type $sql
	 * @param array $pars 参数绑定数组
	 * @return array 二维数组
	 */
	public function getAll($sql, array $pars = []): array {
		$this->type = 'select';
		return $this->prepare_execute($sql, $pars)->fetchall(\PDO::FETCH_ASSOC);
	}

	/**
	 * 更新数据, 返回受影响的行数
	 * @param string $sql
	 * @param array $pars 参数绑定数组
	 * @return int 受影响的行数
	 */
	public function update(string $sql, array $pars = []): int {
		$this->type = 'update';
		return $this->prepare_execute($sql, $pars)->rowCount();
	}

	/**
	 * 插入数据, 返回插入的主键
	 * @param string $sql
	 * @param array $pars 参数绑定数组
	 * @return int 插入的主键
	 */
	public function insertGetId(string $sql, array $pars = []): int {
		$this->type	 = 'insert';
		$pdo		 = null;
		$res		 = $this->prepare_execute($sql, $pars, true, $pdo)->rowCount();
		if ($res)
			return $pdo->lastInsertId();
		else
			return 0;
	}

	/**
	 * 插入数据
	 * @param string $sql
	 * @param array $pars 参数绑定数组
	 * @return int 受影响的行数
	 */
	public function insert(string $sql, array $pars = []): int {
		$this->type = 'insert';
		return $this->prepare_execute($sql, $pars)->rowCount();
	}

	/**
	 * 使用PDO->prepare(), 返回的对象可用$res->execute($pars)重复调用
	 * @param string $sql
	 * @param string $type
	 * @return PDOStatement
	 * @throws Exception
	 */
	public function prepare(string $sql, string $type = 'update'): PDOStatement {
		if (!in_array($type, ['select', 'update', 'delete', 'insert', 'replace']))
			throw new Exception('$type mast in_array(select update delete insert replace). but ' . $type . ' given');
		$this->type = $type;
		return $this->prepare_execute($sql, [], false);
	}

	/**
	 * 开启事务
	 * @return bool
	 * @throws Exception
	 */
	public function begin(): bool {
		if ($this->single !== true)
			throw new Exception('非常不建议在单进程,多数据库切换模式下开启事务!');
		$this->transaction	 = true;
		$PDO				 = $this->PDO();
		return $PDO->beginTransaction();
	}

	/**
	 * 提交事务
	 * @return bool
	 */
	public function commit(): bool {
		$this->transaction	 = false;
		$PDO				 = $this->PDO();
		return $PDO->commit();
	}

	/**
	 * 是否在事务中
	 * @return bool
	 */
	public function inTransaction(): bool {
		return $this->transaction;
	}

	/**
	 * 回滚事务
	 * @return bool
	 */
	public function rollBack(): bool {
		$this->transaction	 = false;
		$PDO				 = $this->PDO();
		return $PDO->rollBack();
	}

	/**
	 * 关闭连接
	 */
	public function close() {
		$this->pdo = null;
	}

	/**
	 * 普通 sql 记录
	 * @param string $sql
	 * @param array $bindings
	 */
	private function logInfo(string $sql, array $bindings = [], bool $manual = false): bool {
		// 普通 sql 记录
		return Log::dbinfo('', [
			'sql'			 => $sql,
			'bindings'		 => $bindings,
			'manual'		 => $manual,
			'connection'	 => $this->connection,
			'master_slave'	 => $this->Master_slave,
			'type'			 => $this->type,
			'transaction'	 => $this->transaction,
			'single'		 => $this->single,
			'identification' => $this->identification
		]);
	}

	/**
	 * 错误 sql 记录
	 * @param string $msg
	 * @param string $sql
	 * @param array $bindings
	 */
	private function logError(string $msg, string $sql, array $bindings = [], bool $manual = false): bool {
		return Log::dberror($msg, [
			'sql'			 => $sql,
			'bindings'		 => $bindings,
			'manual'		 => $manual,
			'connection'	 => $this->connection,
			'master_slave'	 => $this->Master_slave,
			'type'			 => $this->type,
			'transaction'	 => $this->transaction,
			'single'		 => $this->single,
			'identification' => $this->identification
		]);
	}

	public function __call(string $method, array $parameters = []) {
		$this->type = 'update';
		return $this->PDO()->$method(...$parameters);
	}

}
