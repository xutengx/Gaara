<?php

declare(strict_types = 1);
namespace Gaara\Core\Model\QueryBuilder;

use Gaara\Core\Model\QueryBuilder;

trait From {

	protected $noFrom = false;

	/**
	 * 将一个from加入查询
	 * @param string $str
	 * @param string $delimiter
	 * @return QueryBuilder
	 */
	public function fromString(string $str): QueryBuilder {
		$this->from = '`' . $str . '`';
		return $this;
	}

	/**
	 * 将一个from加入查询
	 * @param string $str
	 * @param string $delimiter
	 * @return QueryBuilder
	 */
	public function fromRaw(string $str): QueryBuilder {
		$this->from = $str;
		return $this;
	}

	/**
	 * 设置不需要from片段
	 * 仅对 select 生效
	 * @return QueryBuilder
	 */
	public function noFrom(): QueryBuilder {
		$this->noFrom = true;
		return $this;
	}

}
