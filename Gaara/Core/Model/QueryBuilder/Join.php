<?php

declare(strict_types = 1);
namespace Gaara\Core\Model\QueryBuilder;

use Gaara\Core\Model\QueryBuilder;

trait Join {

	/**
	 * 加入不做处理的join段
	 * @param string $sql
	 * @return QueryBuilder
	 */
	public function joinRaw(string $sql): QueryBuilder {
		return $this->joinPush($sql);
	}

	/**
	 * 表连接
	 * @param string $table
	 * @param string $fieldOne
	 * @param string $symbol
	 * @param string $fieldTwo
	 * @param string $joinType
	 * @return QueryBuilder
	 */
	public function joinString(string $table, string $fieldOne, string $symbol, string $fieldTwo, string $joinType = 'inner join'): QueryBuilder {
		$sql = $joinType . ' ' . $this->fieldFormat($table) . ' on ' . $this->fieldFormat($fieldOne) . $symbol . $this->fieldFormat($fieldTwo);
		return $this->joinPush($sql);
	}

	/**
	 * 将Join片段加入Join, 返回当前对象
	 * @param string $part
	 * @return QueryBuilder
	 */
	protected function joinPush(string $part): QueryBuilder {
		if (is_null($this->join)) {
			$this->join = $part;
		} else
			$this->join .= ' ' . $part;
		return $this;
	}

}
