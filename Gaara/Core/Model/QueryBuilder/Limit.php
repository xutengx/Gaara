<?php

declare(strict_types = 1);
namespace Gaara\Core\Model\QueryBuilder;

use Gaara\Core\Model\QueryBuilder;

trait Limit {

	/**
	 * limit带偏移量
	 * @param int $offset
	 * @param int $take
	 * @return QueryBuilder
	 */
	public function limitOffsetTake(int $offset, int $take): QueryBuilder {
		$sql = (string)$offset . ',' . (string)$take;
		return $this->limitPush($sql);
	}

	/**
	 * 将limit片段加入limit, 返回当前对象
	 * 多次调用,将覆盖之前
	 * @param string $part
	 * @return QueryBuilder
	 */
	protected function limitPush(string $part): QueryBuilder {
		$this->limit = $part;
		return $this;
	}

	/**
	 * limit不带偏移量
	 * @param int $take
	 * @return QueryBuilder
	 */
	public function limitTake(int $take): QueryBuilder {
		$sql = (string)$take;
		return $this->limitPush($sql);
	}

}
