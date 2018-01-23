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
	public function getRow(array $pars = []): array {
		$this->sqlType	 = 'select';
		$this->limitTake(1);
		$sql			 = $this->toSql($pars);
		return $this->db->getRow($sql, $pars);
	}

	/**
	 * 查询多行
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
	public function getChunk(array $pars = []): QueryChunk {
		$this->sqlType	 = 'select';
		$sql			 = $this->toSql($pars);
		$PDOStatement	 = $this->db->getChunk($sql, $pars);
		return new QueryChunk($PDOStatement, $this->index);
	}

	/**
	 * 更新数据, 返回受影响的行数
	 * @param array $pars
	 * @return int
	 * @throws Exception
	 */
	public function update(array $pars = []): int {
		$this->sqlType	 = 'update';
		if (empty($this->data))
			throw new Exception('要执行UPDATE操作, 需要使用data方法设置更新的值');
		$sql			 = $this->toSql($pars);
		return $this->db->update($sql, $pars);
	}

	/**
	 * 插入数据, 返回插入的主键
	 * @param array $pars
	 * @return int
	 * @throws Exception
	 */
	public function insertGetId(array $pars = []): int {
		$this->sqlType	 = 'insert';
		if (empty($this->data))
			throw new Exception('要执行INSERT操作, 需要使用data方法设置新增的值');
		$sql			 = $this->toSql($pars);
		return $this->db->insertGetId($sql, $pars);
	}

	/**
	 * 插入数据
	 * @param array $pars
	 * @return bool
	 * @throws Exception
	 */
	public function insert(array $pars = []): bool {
		$this->sqlType	 = 'insert';
		if (empty($this->data))
			throw new Exception('要执行INSERT操作, 需要使用data方法设置新增的值');
		$sql			 = $this->toSql($pars);
		return $this->db->insert($sql, $pars);
	}

	/**
	 * 删除数据, 返回受影响的行数
	 * @param array $pars
	 * @return int
	 * @throws Exception
	 */
	public function delete(array $pars = []): int {
		$this->sqlType	 = 'delete';
		if (empty($this->where))
			throw new Exception('执行 DELETE 操作并没有相应的 where 约束, 请确保操作正确, 使用where(1)将强制执行.');
		$sql			 = $this->toSql($pars);
		return $this->db->update($sql, $pars);
	}

	/**
	 * 插入or更新数据, 返回受影响的行数
	 * @param array $pars
	 * @return int
	 * @throws Exception
	 */
	public function replace(array $pars = []): int {
		$this->sqlType	 = 'replace';
		if (empty($this->data))
			throw new Exception('要执行REPLACE操作, 需要使用data方法设置新增or修改的值');
		$sql			 = $this->toSql($pars);
		return $this->db->update($sql, $pars);
	}

}
