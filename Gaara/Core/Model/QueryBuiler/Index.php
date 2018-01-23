<?php

declare(strict_types = 1);
namespace Gaara\Core\Model\QueryBuiler;

use Gaara\Core\Model\QueryBuiler;
use Closure;

trait Index {

	/**
	 * 预期的查询2维数组的索引,设置为一个字段
	 * @param string $field
	 * @return QueryBuiler
	 */
	public function indexString(string $field): QueryBuiler {
		$this->index = $field;
		return $this;
	}

	/**
	 * 预期的查询2维数组的索引,设置为一个闭包的返回值
	 * @param Closure $callback
	 * @return QueryBuiler
	 */
	public function indexClosure(Closure $callback): QueryBuiler {
		$this->index = $callback;
		return $this;
	}

}
