<?php

declare(strict_types = 1);
namespace Gaara\Core\Model\QueryBuiler;

use Gaara\Core\Model\QueryBuiler;

trait Order {

	/**
	 * 单个order
	 * @param string $field
	 * @return QueryBuiler
	 */
	public function orderString(string $field, string $order = 'asc'): QueryBuiler {
		$sql = $this->fieldFormat($field) . ' ' . $order;
		return $this->orderPush($sql);
	}

	/**
	 * 将order片段加入order, 返回当前对象
	 * @param string $part
	 * @return QueryBuiler
	 */
	protected function orderPush(string $part): QueryBuiler {
		if (is_null($this->order)) {
			$this->order = $part;
		} else
			$this->order .= ',' . $part;
		return $this;
	}

}
