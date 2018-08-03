<?php

declare(strict_types = 1);
namespace Gaara\Core\Model\QueryBuilder;

use Gaara\Core\Model\QueryBuilder;

trait Order {

	/**
	 * 单个order
	 * @param string $field
	 * @return QueryBuilder
	 */
	public function orderString(string $field, string $order = 'asc'): QueryBuilder {
		$sql = $this->fieldFormat($field) . ' ' . $order;
		return $this->orderPush($sql);
	}

	/**
	 * 将order片段加入order, 返回当前对象
	 * @param string $part
	 * @return QueryBuilder
	 */
	protected function orderPush(string $part): QueryBuilder {
		if (is_null($this->order)) {
			$this->order = $part;
		} else
			$this->order .= ',' . $part;
		return $this;
	}

}
