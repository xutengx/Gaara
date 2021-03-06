<?php

declare(strict_types = 1);
namespace Gaara\Core;

use Gaara\Contracts\ServiceProvider\Single;
use Gaara\Core\Model\QueryBuilder;
use Gaara\Core\Model\Traits\{DebugTrait, ObjectRelationalMappingTrait, Transaction};
use PDOStatement;

abstract class Model implements Single {

	use DebugTrait, ObjectRelationalMappingTrait, Transaction;

	// 所有数据库连接类
	protected static $dbs;
	// 当前model的数据库连接类
	protected $db;
	// 主键的字段
	protected $primaryKey = 'id';
	// 表名
	protected $table = '';
	// 表信息
	protected $field = [];
	// 链式操作集合
	protected $options = [];
	// 链式操作 sql
	protected $lastSql = '';
	// PDOStatement
	protected $PDOStatement = null;
	// 主动指定配置文件
	protected $connection = null;

	final public function __construct() {
		$this->db = $this->getDB();
		$this->get_thisTable();
		$this->getTableInfo();
		$this->construct();
	}

	/**
	 * 获取数据库链接对象 $this->db
	 * @return DbConnection
	 * @throws \Gaara\Exception\BindingResolutionException
	 * @throws \ReflectionException
	 */
	protected function getDB(): DbConnection {
		$conf             = obj(Conf::class)->db;
		$this->connection = $this->connection ?? $conf['connection'];
		return self::$dbs[$this->connection] ??
		       self::$dbs[$this->connection] = dobj(DbConnection::class, [$this->connection]);
	}

	/**
	 * 得到当前模型对应的数据表名
	 * @return void
	 */
	final protected function get_thisTable(): void {
		// 驼峰转下划线
		$uncamelize = function($camelCaps, $separator = '_') {
			return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
		};
		if ($this->table === '') {
			$classname   = get_class($this);
			$classname   = substr($classname, strrpos($classname, '\\') + 1);
			$this->table = $uncamelize(strtr($classname, ['Model' => '']));
		}
	}

	/**
	 * 获取表字段信息, 填充主键
	 */
	protected function getTableInfo(): void {
		$this->field = obj(Cache::class)->remember(function() {
			return $this->db->getAll('SHOW COLUMNS FROM `' . $this->table . '`');
		}, 3600);
		foreach ($this->field as $v) {
			if ($v['extra'] === 'auto_increment') {
				$this->primaryKey = $v['field'];
				break;
			}
		}
	}

	/**
	 * 所有手动初始化建议在此执行
	 */
	protected function construct() { }

	/**
	 * 返回当前表名
	 * @return string
	 * @throws \Gaara\Exception\BindingResolutionException
	 * @throws \ReflectionException
	 */
	public static function getTable(): string {
		return obj(static::class)->table;
	}

	/**
	 * 静态链式操作
	 * @param string $method
	 * @param array $parameters
	 * @return mixed
	 * @throws \Gaara\Exception\BindingResolutionException
	 * @throws \ReflectionException
	 */
	final public static function __callStatic(string $method, array $parameters = []) {
		return obj(static::class)->newQuery()->$method(...$parameters);
	}

	/**
	 * 对象链式操作
	 * @param string $method
	 * @param array $parameters
	 * @return mixed
	 */
	public function __call(string $method, array $parameters = []) {
		return $this->newQuery()->$method(...$parameters);
	}

	/**
	 * 返回一个查询构造器
	 * @param string $table 绑定表名
	 * @param string $primaryKey 主键名
	 * @return QueryBuilder
	 */
	public function newQuery(string $table = null, string $primaryKey = null): QueryBuilder {
		$queryTable      = $table ?? $this->table;
		$queryPrimaryKey = $primaryKey ?? $this->primaryKey;
		return new QueryBuilder($queryTable, $queryPrimaryKey, $this->db, $this);
	}

	/**
	 * 在 Model 中为 QueryBuilder 注册自定义链式方法
	 * 重载此方法
	 * @return array
	 */
	public function registerMethodForQueryBuilder(): array {
		return [];
	}

	/**
	 * 原生sql支持, 普通执行
	 * @param string $sql
	 * @param string $type 使用的数据库链接类型
	 * @return PDOStatement
	 * @throws \Exception
	 */
	public function query(string $sql, string $type = 'update'): PDOStatement {
		$PDOStatement = $this->db->prepare($sql, $type);
		$PDOStatement->execute();
		return $PDOStatement;
	}

	/**
	 * 原生sql支持, 返回`PDOStatement`对象可用PDOStatement::execute($pars)重复调用
	 * @param string $sql
	 * @param string $type 使用的数据库链接类型
	 * @return PDOStatement
	 * @throws \Exception
	 */
	public function prepare(string $sql, string $type = 'update'): PDOStatement {
		return $this->db->prepare($sql, $type);
	}

}
