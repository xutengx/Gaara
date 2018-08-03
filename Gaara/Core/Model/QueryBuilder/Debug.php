<?php

declare(strict_types = 1);
namespace Gaara\Core\Model\QueryBuilder;

use ErrorException as Exception;

trait Debug {

	/**
	 * 返回此刻的参数绑定数组
	 * @return array
	 */
	public function getBindings(): array {
		return $this->bindings;
	}

	/**
	 * 查询多行 的sql 保留参数绑定的 key
	 * @return string
	 */
	public function getAllToSqlWithBindingsKey(): string{
		$pars = $this->bindings;
		$this->sqlType	 = 'select';
		return $this->toSql($pars);
	}

	/**
	 * 查询一行 的sql
	 * @param array $pars
	 * @return string
	 */
	public function getRowToSql(): string {
		$this->sqlType	 = 'select';
		$this->limitTake(1);
		$this->toSql($this->bindings);
		return $this->lastSql;
	}

	/**
	 * 查询多行 的sql
	 * @param array $pars
	 * @return string
	 */
	public function getAllToSql(): string {
		$this->sqlType	 = 'select';
		$this->toSql($this->bindings);
		return $this->lastSql;
	}

	/**
	 * 更新数据, 返回受影响的行数 的sql
	 * @param array $pars
	 * @return string
	 * @throws Exception
	 */
	public function updateToSql(): string {
		$this->sqlType	 = 'update';
		if (empty($this->data))
			throw new Exception('要执行UPDATE操作, 需要使用data方法设置更新的值');
		$this->toSql($this->bindings);
		return $this->lastSql;
	}

	/**
	 * 插入数据, 返回插入的主键 的sql
	 * @param array $pars
	 * @return string
	 * @throws Exception
	 */
	public function insertGetIdToSql(): string {
		$this->sqlType	 = 'insert';
		$this->toSql($this->bindings);
		return $this->lastSql;
	}

	/**
	 * 插入数据 的sql
	 * @param array $pars
	 * @return string
	 * @throws Exception
	 */
	public function insertToSql(): string {
		$this->sqlType	 = 'insert';
		$this->toSql($this->bindings);
		return $this->lastSql;
	}

	/**
	 * 删除数据, 返回受影响的行数 的sql
	 * @param array $pars
	 * @return string
	 * @throws Exception
	 */
	public function deleteToSql(): string {
		$this->sqlType	 = 'delete';
		if (empty($this->data))
			throw new Exception('执行 DELETE 操作并没有相应的 where 约束, 请确保操作正确, 使用where(1)将强制执行.');
		$this->toSql($this->bindings);
		return $this->lastSql;
	}

	/**
	 * 插入or更新数据, 返回受影响的行数 的sql
	 * @param array $pars
	 * @return string
	 * @throws Exception
	 */
	public function replaceToSql(): string {
		$this->sqlType	 = 'replace';
		$this->toSql($this->bindings);
		return $this->lastSql;
	}

	/**
	 * 自动识别语句类型 的sql
	 * @param array $pars
	 * @return string
	 * @throws Exception
	 */
	public function sql(): string {
		if (!is_null($this->data))
			$this->sqlType	 = 'update';
		else
			$this->sqlType	 = 'select';
		$this->toSql($this->bindings);
		return $this->lastSql;
	}

}
