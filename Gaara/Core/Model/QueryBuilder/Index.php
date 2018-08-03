<?php

declare(strict_types = 1);
namespace Gaara\Core\Model\QueryBuilder;

use Closure;
use Gaara\Core\Model\QueryBuilder;

trait Index {

	/**
	 * 预期的查询2维数组的索引,设置为一个字段
	 * @param string $field
	 * @return QueryBuilder
	 */
	public function indexString(string $field): QueryBuilder {
		$this->index = $field;
		return $this;
	}

	/**
	 * 预期的查询2维数组的索引,设置为一个闭包的返回值
	 * @param Closure $callback
	 * @return QueryBuilder
	 */
	public function indexClosure(Closure $callback): QueryBuilder {
		$this->index = $callback;
		return $this;
	}

}
