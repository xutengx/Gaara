<?php

declare(strict_types = 1);
namespace Gaara\Core\Model;

use Gaara\Core\DbConnection;
use PDOStatement;

/**
 * 参数绑定, 重复调用
 */
class QueryPrepare {

	protected $PDOStatement;
	protected $db;
	protected $bindings = [];

	/**
	 * QueryPrepare constructor.
	 * @param PDOStatement $PDOStatement
	 * @param array $bindings 自动绑定参数数组
	 * @param DbConnection $db
	 */
	public function __construct(PDOStatement $PDOStatement, array $bindings = [], DbConnection $db) {
		$this->PDOStatement = $PDOStatement;
		$this->bindings     = $bindings;
		$this->db           = $db;
	}

	/**
	 * 查询一行
	 * @param array $bindings 手动绑定参数数组
	 * @return array
	 */
	public function getRow(array $bindings = []): array {
		$this->execute($bindings);
		$re = $this->PDOStatement->fetch(\PDO::FETCH_ASSOC) ?? [];
		return $re ? $re : [];
	}

	/**
	 * 执行 PDOStatement
	 * @param array $bindings 手动绑定参数数组
	 * @return void
	 */
	protected function execute(array $bindings = []): void {
		// 本次执行的`绑定参数数组`
		$realBindings = [];
		// 合并`手动绑定参数数组`与`自动绑定参数数组`
		// 还原`自动绑定参数数组`中的`手动键`
		foreach ($this->bindings as $k => $v) {
			$realBindings[$k] = $bindings[$v] ?? $v;
		}
		// 在 DbConnection 中执行, 以便统一管理
		$this->db->execute($this->PDOStatement, $realBindings);
	}

	/**
	 * 查询多行
	 * @param array $bindings 手动绑定参数数组
	 * @return array
	 */
	public function getAll(array $bindings = []): array {
		$this->execute($bindings);
		return $this->PDOStatement->fetchall(\PDO::FETCH_ASSOC) ?? [];
	}

	/**
	 * 插入
	 * @param array $bindings 手动绑定参数数组
	 * @return int 影响的行数
	 */
	public function insert(array $bindings = []): int {
		$this->execute($bindings);
		return $this->PDOStatement->rowCount();
	}

	/**
	 * 删除
	 * @param array $bindings 手动绑定参数数组
	 * @return int 影响的行数
	 */
	public function delete(array $bindings = []): int {
		return $this->update($bindings);
	}

	/**
	 * 更新
	 * @param array $bindings 手动绑定参数数组
	 * @return int 影响的行数
	 */
	public function update(array $bindings = []): int {
		$this->execute($bindings);
		return $this->PDOStatement->rowCount();
	}

	/**
	 * 插入or更新
	 * @param array $bindings 手动绑定参数数组
	 * @return int 影响的行数
	 */
	public function replace(array $bindings = []): int {
		return $this->update($bindings);
	}

}
