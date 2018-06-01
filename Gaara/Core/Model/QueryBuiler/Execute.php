<?php

declare(strict_types = 1);
namespace Gaara\Core\Model\QueryBuiler;

use ErrorException as Exception;
use Gaara\Core\Model\QueryChunk;

trait Execute {

	/**
	 * 查询一行
	 * @param array $pars
	 * @return array
	 */
	public function getRow(): array {
		$this->sqlType	 = 'select';
		$this->limitTake(1);
		$sql			 = $this->toSql($this->bindings);
		return $this->db->getRow($sql, $this->bindings);
	}

	/**
	 * 查询多行
	 * 以下写法无法使用自定义索引
	 * $this->sqlType = 'select';
	 * $sql = $this->toSql($pars);
	 * return $this->db->getAll($sql, $pars);
	 * 以上写法无法使用自定义索引
	 * @param array $pars
	 * @return array
	 */
	public function getAll(array $pars = []): array {
		$QueryChunk	 = $this->getChunk($pars);
		$data		 = [];
		foreach ($QueryChunk as $k => $v) {
			$data[$k] = $v;
		}
		return $data;
	}

	/**
	 * 块状获取
	 * @param array $pars
	 * @return QueryChunk
	 */
	public function getChunk(): QueryChunk {
		$this->sqlType	 = 'select';
		$sql			 = $this->toSql($this->bindings);
		$PDOStatement	 = $this->db->getChunk($sql, $this->bindings);
		return new QueryChunk($PDOStatement, $this->index);
	}

	/**
	 * 更新数据, 返回受影响的行数
	 * @param array $pars
	 * @return int
	 * @throws Exception
	 */
	public function update(): int {
		$this->sqlType	 = 'update';
		if (empty($this->data))
			throw new Exception('要执行UPDATE操作, 需要使用data方法设置更新的值');
		$sql			 = $this->toSql($this->bindings);
		return $this->db->update($sql, $this->bindings);
	}

	/**
	 * 插入数据, 返回插入的主键
	 * @param array $pars
	 * @return int
	 * @throws Exception
	 */
	public function insertGetId(): int {
		$this->sqlType	 = 'insert';
		if (is_null($this->data))
			throw new Exception('要执行INSERT操作, 需要使用data方法设置新增的值');
		$sql			 = $this->toSql($this->bindings);
		return $this->db->insertGetId($sql, $this->bindings);
	}

	/**
	 * 插入数据
	 * @param array $pars
	 * @return bool
	 * @throws Exception
	 */
	public function insert(): int {
		$this->sqlType	 = 'insert';
		if (is_null($this->data))
			throw new Exception('要执行INSERT操作, 需要使用data方法设置新增的值');
		$sql			 = $this->toSql($this->bindings);
		return $this->db->insert($sql, $this->bindings);
	}

	/**
	 * 删除数据, 返回受影响的行数
	 * @param array $pars
	 * @return int
	 * @throws Exception
	 */
	public function delete(): int {
		$this->sqlType	 = 'delete';
		if (empty($this->where))
			throw new Exception('执行 DELETE 操作并没有相应的 where 约束, 请确保操作正确, 使用where(1)将强制执行.');
		$sql			 = $this->toSql($this->bindings);
		return $this->db->update($sql, $this->bindings);
	}

	/**
	 * 插入or更新数据, 返回受影响的行数
	 * @param array $pars
	 * @return int
	 * @throws Exception
	 */
	public function replace(): int {
		$this->sqlType	 = 'replace';
		if (is_null($this->data))
			throw new Exception('要执行REPLACE操作, 需要使用data方法设置新增or修改的值');
		$sql			 = $this->toSql($this->bindings);
		return $this->db->update($sql, $this->bindings);
	}

}
