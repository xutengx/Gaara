<?php

declare(strict_types = 1);
namespace Gaara\Core\Model;

use Gaara\Core\Model\QueryBuiler;
use Gaara\Core\Model;
use Gaara\Core\DbConnection;
use InvalidArgumentException;
use Exception;
use Closure;

/**
 * 查询构造器
 */
class QueryBuiler {

	use QueryBuiler\Support;
	use QueryBuiler\Where;
	use QueryBuiler\Select;
	use QueryBuiler\Data;
	use QueryBuiler\From;
	use QueryBuiler\Join;
	use QueryBuiler\Group;
	use QueryBuiler\Order;
	use QueryBuiler\Limit;
	use QueryBuiler\Lock;
	use QueryBuiler\Having;
	use QueryBuiler\Index;
	use QueryBuiler\Union;
	use QueryBuiler\Prepare;

	use QueryBuiler\Execute;
	use QueryBuiler\Debug;
	use QueryBuiler\Aggregates;
	use QueryBuiler\Special;

	// 绑定的表名
	private $table;
	// 主键
	private $primaryKey;
	// 当前语句类别
	private $sqlType;
	// 数据库链接
	private $db;
	// 所属模型
	private $model;
	// 最近次执行的sql
	private $lastSql = null;
	private $select = null;
	private $data = null;
	private $from = null;
	private $where = null;
	private $join = null;
	private $group = null;
	private $having = null;
	private $order = null;
	private $limit = null;
	private $lock = null;
	private $union					 = [];
	// 自动绑定计数器
	private static $bindingCounter = 0;
	// 自动绑定数组
	private $bindings = [];
	// 预期的查询2维数组的索引
	private $index					 = null;
	// Model 中为 QueryBuiler 注册de自定义链式方法
	private $registerMethodFromModel = [];

	public function __construct(string $table, string $primaryKey, DbConnection $db, Model $model) {
		$this->table		 = $table;
		$this->primaryKey	 = $primaryKey;
		$this->db			 = $db;
		$this->model		 = $model;

		$this->registerMethod();
	}

	/**
	 * 在 Model 中为 QueryBuiler 注册自定义链式方法
	 * @throws InvalidArgumentException
	 */
	private function registerMethod() {
		foreach ($this->model->registerMethodForQueryBuiler() as $methodName => $func) {
			if (isset($this->$methodName) || isset($this->registerMethodFromModel[$methodName]) || method_exists($this, $methodName))
				throw new InvalidArgumentException('The method name [ ' . $methodName . ' ] is already used .');
			elseif ($func instanceof Closure) {
				$this->registerMethodFromModel[$methodName] = function(...$params)use($func) {
					return $func($this, ...$params);
				};
			} else
				throw new InvalidArgumentException('The method [ ' . $methodName . ' ] mast instanceof Closure .');
		}
	}

	/**
	 * 查询条件
	 * @param mixed ...$params
	 * @return QueryBuiler
	 */
	public function where(...$params): QueryBuiler {
		switch (func_num_args()) {
			case 1:
				switch (gettype($params[0])) {
					case 'object':
						return $this->andWhere(...$params);
					case 'array':
						return $this->whereArray(...$params);
					default :
						return $this->whereRaw((string) $params[0]);
				}
			case 2:
				return $this->whereValue((string) $params[0], '=', (string) $params[1]);
			case 3:
				return $this->whereValue((string) $params[0], (string) $params[1], (string) $params[2]);
		}
		return $this;
	}

	/**
	 * 字段值在范围内
	 * @param mixed ...$params
	 * @return QueryBuiler
	 */
	public function whereIn(...$params): QueryBuiler {
		switch (func_num_args()) {
			case 2:
				switch (gettype($params[1])) {
					case 'array':
						return $this->whereInArray(...$params);
					default :
						return $this->whereInString(...$params);
				}
		}
	}

	/**
	 * 字段值不在范围内
	 * @param mixed ...$params
	 * @return QueryBuiler
	 */
	public function whereNotIn(...$params): QueryBuiler {
		switch (func_num_args()) {
			case 2:
				switch (gettype($params[1])) {
					case 'array':
						return $this->whereNotInArray(...$params);
					default :
						return $this->whereNotInString((string) $params[0], (string) $params[1], (string) $params[2]);
				}
		}
	}

	/**
	 * 字段值在2值之间
	 * @param mixed ...$params
	 * @return QueryBuiler
	 */
	public function whereBetween(...$params): QueryBuiler {
		switch (func_num_args()) {
			case 2:
				return $this->whereBetweenArray(...$params);
			case 3:
				return $this->whereBetweenString((string) $params[0], (string) $params[1], (string) $params[2]);
		}
	}

	/**
	 * 字段值不在2值之间
	 * @param mixed ...$params
	 * @return QueryBuiler
	 */
	public function whereNotBetween(...$params): QueryBuiler {
		switch (func_num_args()) {
			case 2:
				return $this->whereNotBetweenArray(...$params);
			case 3:
				return $this->whereNotBetweenString((string) $params[0], (string) $params[1], (string) $params[2]);
		}
	}

	/**
	 * where exists
	 * @param mixed ...$params
	 * @return QueryBuiler
	 */
	public function whereExists(...$params): QueryBuiler {
		switch (func_num_args()) {
			case 1:
				switch (gettype($obj = reset($params))) {
					case 'object':
						if ($obj instanceof \Closure) {
							return $this->whereExistsClosure($obj);
						}
					case 'string':
						return $this->whereExistsRaw($obj);
				}
		}
	}

	/**
	 * where not exists
	 * @param mixed ...$params
	 * @return QueryBuiler
	 */
	public function whereNotExists(...$params): QueryBuiler {
		switch (func_num_args()) {
			case 1:
				switch (gettype($obj = reset($params))) {
					case 'object':
						if ($obj instanceof \Closure) {
							return $this->whereNotExistsClosure($obj);
						}
					case 'string':
						return $this->whereNotExistsRaw($obj);
				}
		}
	}

	/**
	 * where 子查询
	 * @param mixed ...$params
	 * @return QueryBuiler
	 */
	public function whereSubquery(...$params): QueryBuiler {
		switch (func_num_args()) {
			case 3:
				switch (gettype($obj = end($params))) {
					case 'object':
						if ($obj instanceof \Closure) {
							return $this->whereSubqueryClosure(...$params);
						} elseif ($obj instanceof QueryBuiler) {
							return $this->whereSubqueryQueryBuiler(...$params);
						}
					case 'string':
						return $this->whereSubqueryRaw(...$params);
				}
		}
	}

	/**
	 * having条件
	 * @param mixed ...$params
	 * @return QueryBuiler
	 */
	public function having(...$params): QueryBuiler {
		switch (func_num_args()) {
			case 1:
				switch (gettype($params[0])) {
					case 'object':
						return $this->andHaving(...$params);
					case 'array':
						return $this->havingArray(...$params);
					default :
						return $this->havingRaw('1');
				}
			case 2:
				return $this->havingValue((string) $params[0], '=', (string) $params[1]);
			case 3:
				return $this->havingValue((string) $params[0], (string) $params[1], (string) $params[2]);
		}
		return $this;
	}

	/**
	 * having字段值在范围内
	 * @param mixed ...$params
	 * @return QueryBuiler
	 */
	public function havingIn(...$params): QueryBuiler {
		switch (func_num_args()) {
			case 2:
				switch (gettype($params[1])) {
					case 'array':
						return $this->havingInArray(...$params);
					default :
						return $this->havingInString(...$params);
				}
		}
	}

	/**
	 * having字段值不在范围内
	 * @param mixed ...$params
	 * @return QueryBuiler
	 */
	public function havingNotIn(...$params): QueryBuiler {
		switch (func_num_args()) {
			case 2:
				switch (gettype($params[1])) {
					case 'array':
						return $this->havingNotInArray(...$params);
					default :
						return $this->havingNotInString((string) $params[0], (string) $params[1], (string) $params[2]);
				}
		}
	}

	/**
	 * having字段值在2值之间
	 * @param mixed ...$params
	 * @return QueryBuiler
	 */
	public function havingBetween(...$params): QueryBuiler {
		switch (func_num_args()) {
			case 2:
				return $this->havingBetweenArray(...$params);
			case 3:
				return $this->havingBetweenString((string) $params[0], (string) $params[1], (string) $params[2]);
		}
	}

	/**
	 * having字段值不在2值之间
	 * @param mixed ...$params
	 * @return QueryBuiler
	 */
	public function havingNotBetween(...$params): QueryBuiler {
		switch (func_num_args()) {
			case 2:
				return $this->havingNotBetweenArray(...$params);
			case 3:
				return $this->havingNotBetweenString((string) $params[0], (string) $params[1], (string) $params[2]);
		}
	}

	/**
	 * 左链接
	 * @param string $table
	 * @param string $fieldOne
	 * @param string $symbol
	 * @param string $fieldTwo
	 * @return QueryBuiler
	 */
	public function leftJoin(string $table, string $fieldOne, string $symbol, string $fieldTwo): QueryBuiler {
		return $this->joinString($table, $fieldOne, $symbol, $fieldTwo, 'left join');
	}

	/**
	 * 右链接
	 * @param string $table
	 * @param string $fieldOne
	 * @param string $symbol
	 * @param string $fieldTwo
	 * @return QueryBuiler
	 */
	public function rightJoin(string $table, string $fieldOne, string $symbol, string $fieldTwo): QueryBuiler {
		return $this->joinString($table, $fieldOne, $symbol, $fieldTwo, 'right join');
	}

	/**
	 * 内链接
	 * @param mixed ...$params
	 * @return QueryBuiler
	 */
	public function join(...$params): QueryBuiler {
		return $this->joinString(...$params);
	}

	/**
	 * 自定义二维数组的键
	 * @param mixed ...$params
	 * @return QueryBuiler
	 */
	public function index(...$params): QueryBuiler {
		switch (func_num_args()) {
			case 1:
				switch (gettype(reset($params))) {
					case 'string':
						return $this->indexString(...$params);
					case 'object':
						return $this->indexClosure(...$params);
				}
		}
	}

	/**
	 * 查询字段
	 * @param mixed ...$params
	 * @return QueryBuiler
	 */
	public function select(...$params): QueryBuiler {
		switch (func_num_args()) {
			case 1:
				switch (gettype(reset($params))) {
					case 'array':
						return $this->selectArray(...$params);
					case 'string':
						return $this->selectString(...$params);
				}
			case 2:
				return $this->selectFunction(...$params);
			case 3:
				return $this->selectFunction(...$params);
		}
	}

	/**
	 * 更新字段
	 * @param mixed ...$params
	 * @return QueryBuiler
	 */
	public function data(...$params): QueryBuiler {
		switch (func_num_args()) {
			case 1:
				return $this->dataArray(...$params);
			case 2:
				return $this->dataString(...$params);
		}
	}

	/**
	 * from数据表
	 * @param mixed ...$params
	 * @return QueryBuiler
	 */
	public function from(...$params): QueryBuiler {
		switch (func_num_args()) {
			case 1:
				return $this->fromString(...$params);
		}
	}

	/**
	 * 设置数据表
	 * @param string $table
	 * @return QueryBuiler
	 */
	public function table(string $table): QueryBuiler {
		$this->table = $table;
		return $this;
	}

	/**
	 * 分组
	 * @param mixed ...$params
	 * @return QueryBuiler
	 */
	public function group(...$params): QueryBuiler {
		switch (func_num_args()) {
			case 1:
				switch (gettype(reset($params))) {
					case 'array':
						return $this->groupArray(...$params);
					case 'string':
						return $this->groupString(...$params);
				}
		}
	}

	/**
	 * 排序
	 * @param mixed ...$params
	 * @return QueryBuiler
	 */
	public function order(...$params): QueryBuiler {
		switch (func_num_args()) {
			case 1:
				return $this->orderString(...$params);
			case 2:
				return $this->orderString(...$params);
		}
	}

	/**
	 * 限制
	 * @param mixed ...$params
	 * @return QueryBuiler
	 */
	public function limit(...$params): QueryBuiler {
		switch (func_num_args()) {
			case 1:
				return $this->limitTake(...$params);
			case 2:
				return $this->limitOffsetTake(...$params);
		}
	}

	/**
	 * union 联合查询
	 * @param mixed ...$params
	 * @return QueryBuiler
	 */
	public function union(...$params): QueryBuiler {
		switch (func_num_args()) {
			case 1:
				switch (gettype($obj = reset($params))) {
					case 'object':
						if ($obj instanceof \Closure) {
							return $this->unionClosure($obj);
						}
//						elseif ($obj instanceof QueryBuiler) {
//							return $this->unionQueryBuiler($obj);
//						}
					case 'string':
						return $this->unionRaw(...$params);
				}
		}
	}

	/**
	 * union all 联合查询
	 * @param mixed ...$params
	 * @return QueryBuiler
	 */
	public function unionAll(...$params): QueryBuiler {
		switch (func_num_args()) {
			case 1:
				switch (gettype($obj = reset($params))) {
					case 'object':
						if ($obj instanceof \Closure) {
							return $this->unionClosure($obj, 'union all');
						} elseif ($obj instanceof QueryBuiler) {
							return $this->unionQueryBuiler($obj, 'union all');
						}
					case 'string':
						return $this->unionRaw($obj, 'union all');
				}
		}
	}

	/**
	 * 排他锁
	 * @return QueryBuiler
	 */
	public function lock(): QueryBuiler{
		return $this->lockForUpdate();
	}

	public function __call(string $method, array $args = []) {
		if (isset($this->registerMethodFromModel[$method])) {
			return $this->registerMethodFromModel[$method](...$args);
		} else
			throw new Exception('Undefined method [ ' . $method . ' ].');
	}

}
