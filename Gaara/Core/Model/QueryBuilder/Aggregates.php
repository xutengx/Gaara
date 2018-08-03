<?php

declare(strict_types = 1);
namespace Gaara\Core\Model\QueryBuilder;

/**
 * 聚合函数
 */
trait Aggregates {

	/**
	 * count 条数统计,兼容 group
	 * @param string $field 统计字段
	 * @return int
	 */
	public function count(string $field = null): int {
		$this->sqlType = 'select';
		if (!is_null($this->group)) {
			$obj = $this->getSelf();
			$sql = $this->select($field)->getAllToSql($this->bindings);
			$obj->fromRaw($this->bracketFormat($sql) . 'as gaara' . md5((string)time()));
			$obj->selectString('count(' . $field . ')');
			$res = $obj->getRow();
		}
		else {
			if (is_null($field) || $field === '*')
				$this->selectRaw('count(*)');
			else
				$this->selectString('count(' . $field . ')');
			$res = $this->getRow();
		}
		return (int)reset($res);
	}

	/**
	 * max 最大值
	 * @param string $field 字段
	 * @return int
	 */
	public function max(string $field): int {
		$this->sqlType = 'select';
		$this->selectString('max(' . $field . ')');
		$res = $this->getRow();
		return (int)reset($res);
	}

	/**
	 * max 最小值
	 * @param string $field 字段
	 * @return int
	 */
	public function min(string $field): int {
		$this->sqlType = 'select';
		$this->selectString('min(' . $field . ')');
		$res = $this->getRow();
		return (int)reset($res);
	}

	/**
	 * avg 平均值
	 * @param string $field 字段
	 * @return int
	 */
	public function avg(string $field): int {
		$this->sqlType = 'select';
		$this->selectString('avg(' . $field . ')');
		$res = $this->getRow();
		return (int)reset($res);
	}

	/**
	 * sum 取和
	 * @param string $field 字段
	 * @return int
	 */
	public function sum(string $field): int {
		$this->sqlType = 'select';
		$this->selectString('sum(' . $field . ')');
		$res = $this->getRow();
		return (int)reset($res);
	}

}
