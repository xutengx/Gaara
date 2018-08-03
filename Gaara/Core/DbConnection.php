<?php

declare(strict_types = 1);
namespace Gaara\Core;

use Exception;
use Gaara\Contracts\ServiceProvider\Single;
use Log;
use PDO;
use PDOException;
use PDOStatement;

/**
 * 数据库连接类，依赖 PDO_MYSQL 扩展
 */
class DbConnection implements Single {

	// 当前进程标识
	protected $identification = null;
	// 是否主从数据库
	protected $Master_slave = true;
	// 数据库链接名称, 当抛出异常时帮助定位数据库链接
	protected $connection = null;
	// 数据库 读 连接集合
	protected $dbRead = [];
	// 数据库 读 权重
	protected $dbReadWeight = [];
	// 数据库 写 连接集合
	protected $dbWrite = [];
	// 数据库 写 权重
	protected $dbWriteWeight = [];
	// 当前操作类型 select update delate insert
	protected $type = 'select';
	// 是否事务过程中 不进行数据库更换
	protected $transaction = false;
	// ---------------------------- 单进程 ----------------------------- //
	// 单进程不进行数据库更换
	protected $single = true;
	// 当前数据库 读 连接
	protected $dbReadSingle;
	// 当前数据库 写 连接
	protected $dbWriteSingle;

	// ------------------------------------------------------------------//

	/**
	 * 读取&格式化 配置信息
	 * @param array $DBconf
	 * 如下结构:
	 * 'db'=>[
	 * 'write'=>[
	 * [
	 * 'weight'=>10,
	 * 'type'=>'mysql',
	 * 'host'=>'10.4.17.200',
	 * 'port'=>3306,
	 * 'user'=>'root',
	 * 'pwd'=>'Huawei$123#_',
	 * 'db'=>'hk'
	 * ]
	 * ],
	 * 'read'=>[
	 * [
	 * 'weight'=>10,
	 * 'type'=>'mysql',
	 * 'host'=>'10.4.17.218',
	 * 'port'=>3306,
	 * 'user'=>'root',
	 * 'pwd'=>'Huawei$123#_',
	 * 'db'=>'hk'
	 * ],
	 * [
	 * 'weight'=>10,
	 * 'type'=>'mysql',
	 * 'host'=>'10.4.17.219',
	 * 'port'=>3306,
	 * 'user'=>'root',
	 * 'pwd'=>'Huawei$123#_',
	 * 'db'=>'hk'
	 * ]
	 * ]
	 * ],
	 * @param int $weight // 权重
	 * @param string $type // 数据库类型
	 * @param string $host // 连接地址
	 * @param int $port // 端口
	 * @param string $user // 用户名
	 * @param string $pwd // 密码
	 * @param string $db // 数据库
	 */

	/**
	 * @param string $connection 数据库链接名
	 * @param bool $single 单进程模式
	 */
	public function __construct(string $connection, bool $single = true) {
		$DBconf               = obj(Conf::class)->getDriverConnection('db', $connection);
		$this->identification = uniqid((string)getmypid());
		$this->connection     = $connection;
		$this->single         = $single;
		$this->confFormat($DBconf['write'], $this->dbWriteWeight, $this->dbWrite);
		if (isset($DBconf['read']) && !empty($DBconf['read'])) {
			$this->confFormat($DBconf['read'], $this->dbReadWeight, $this->dbRead);
		}
		else
			$this->Master_slave = false;
	}

	/**
	 * 格式化配置文件, 引用赋值
	 * @param array $theConf 待格式化的配置数组
	 * @param array &$theDbWeight 权重数组
	 * @param array &$theDb 配置数组
	 */
	protected function confFormat(array $theConf, array &$theDbWeight, array &$theDb): void {
		foreach ($theConf as $k => $v) {
			$theDb[md5(serialize($v))] = $v;
			$t                         = end($theDbWeight);
			if (empty($t))
				$theDbWeight[$v['weight']] = md5(serialize($v));
			else {
				$weight                                   = array_keys($theDbWeight);
				$theDbWeight[$v['weight'] + end($weight)] = md5(serialize($v));
			}
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
	 * 普通 sql 记录
	 * @param string $sql
	 * @param array $bindings
	 * @param bool $manual
	 * @return bool
	 */
	protected function logInfo(string $sql, array $bindings = [], bool $manual = false): bool {
		// 普通 sql 记录
		return Log::dbinfo('', [
			'sql'            => $sql,
			'bindings'       => $bindings,
			'manual'         => $manual,
			'connection'     => $this->connection,
			'master_slave'   => $this->Master_slave,
			'type'           => $this->type,
			'transaction'    => $this->transaction,
			'single'         => $this->single,
			'identification' => $this->identification
		]);
	}

	/**
	 * 错误 sql 记录
	 * @param string $msg
	 * @param string $sql
	 * @param array $bindings
	 * @param bool $manual
	 * @return bool
	 */
	protected function logError(string $msg, string $sql, array $bindings = [], bool $manual = false): bool {
		return Log::dberror($msg, [
			'sql'            => $sql,
			'bindings'       => $bindings,
			'manual'         => $manual,
			'connection'     => $this->connection,
			'master_slave'   => $this->Master_slave,
			'type'           => $this->type,
			'transaction'    => $this->transaction,
			'single'         => $this->single,
			'identification' => $this->identification
		]);
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
	 * 内部执行, 返回原始数据对象, 触发异常处理
	 * @param string $sql
	 * @param array $pars 参数绑定数组
	 * @param bool $auto 自动执行绑定
	 * @param      $PDO 用作 `insertGetId`的return
	 * @return PDOStatement
	 * @throws PDOException
	 */
	protected function prepare_execute(string $sql, array $pars = [], bool $auto = true, &$PDO = null): PDOStatement {
		try {
			// 链接数据库
			$PDO = $this->PDO();
			// 备要执行的SQL语句并返回一个 PDOStatement 对象
			$PDOStatement = $PDO->prepare($sql);
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
	 * 由操作类型(读/写), 返回已存在的PDO实现
	 * @return PDO
	 */
	protected function PDO(): PDO {
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
		}
		else
			return $this->connect();
	}

	/**
	 * 由操作类型(读/写)和权重(weight), 创建并返回PDO数据库连接
	 * @return PDO
	 */
	protected function connect(): PDO {
		// 查询操作且不属于事务,使用读连接
		if ($this->type === 'select' && !$this->transaction && $this->Master_slave) {
			return $this->weightSelection($this->dbReadWeight, $this->dbRead);
		}
		else {
			return $this->weightSelection($this->dbWriteWeight, $this->dbWrite);
		}
	}

	/**
	 * 根据权重, 实例化pdo
	 * @param array $theDbWeight 权重数组
	 * @param array &$theDb 配置数组->pdo数组
	 * @return PDO
	 */
	protected function weightSelection(array $theDbWeight, array &$theDb): PDO {
		$tmp    = array_keys($theDbWeight);
		$weight = rand(1, end($tmp));
		foreach ($theDbWeight as $k => $v) {
			if ($k - $weight >= 0) {
				$key = $v;
				break;
			}
		}
		if (!is_object($theDb[$key])) {
			$settings    = $theDb[$key];
			$theDb[$key] = $this->newPdo($settings['type'], $settings['db'], $settings['host'],
				(string)$settings['port'], $settings['user'], $settings['pwd']);
		}
		return $theDb[$key];
	}

	/**
	 * pdo初始化属性
	 * 参考文档 https://www.cnblogs.com/Zender/p/8270833.html https://www.cnblogs.com/hf8051/p/4673030.html
	 * @param string $type
	 * @param string $db
	 * @param string $host
	 * @param string $port
	 * @param string $user
	 * @param string $pwd
	 * @return PDO
	 */
	protected function newPdo(string $type, string $db, string $host, string $port, string $user, string $pwd): PDO {
		$serverIni = obj(Conf::class)->getServerConf($type);
		$dsn       = $type . ':dbname=' . $db . ';host=' . $host . ';port=' . $port;
		$pdo       = new PDO($dsn, $user, $pwd, $serverIni['pdo_attr']);
		foreach ($serverIni['ini_sql'] as $ini_sql) {
			$pdo->prepare($ini_sql)->execute();
		}
		return $pdo;
	}

	/**
	 * 查询一行
	 * @param string $sql
	 * @param array $pars 参数绑定数组
	 * @return array 一维数组
	 */
	public function getRow(string $sql, array $pars = []): array {
		$this->type = 'select';
		$re         = $this->prepare_execute($sql, $pars)->fetch(PDO::FETCH_ASSOC);
		return $re ? $re : [];
	}

	/**
	 * 查询多行
	 * @param string $sql
	 * @param array $pars 参数绑定数组
	 * @return array 二维数组
	 */
	public function getAll(string $sql, array $pars = []): array {
		$this->type = 'select';
		return $this->prepare_execute($sql, $pars)->fetchall(PDO::FETCH_ASSOC);
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
		$this->type = 'insert';
		$pdo        = null;
		$res        = $this->prepare_execute($sql, $pars, true, $pdo)->rowCount();
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
		$this->transaction = true;
		$PDO               = $this->PDO();
		return $PDO->beginTransaction();
	}

	/**
	 * 提交事务
	 * @return bool
	 */
	public function commit(): bool {
		$this->transaction = false;
		$PDO               = $this->PDO();
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
		$this->transaction = false;
		$PDO               = $this->PDO();
		return $PDO->rollBack();
	}

	/**
	 * 关闭连接
	 */
	public function close() {
		$this->pdo = null;
	}

	public function __call(string $method, array $parameters = []) {
		$this->type = 'update';
		return $this->PDO()->$method(...$parameters);
	}

}
