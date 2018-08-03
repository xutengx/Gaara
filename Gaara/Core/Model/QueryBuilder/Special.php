<?php

declare(strict_types = 1);
namespace Gaara\Core\Model\QueryBuilder;

use Gaara\Core\Model\QueryBuilder;

/**
 * 特殊用途
 */
trait Special {

	/**
	 * 随机抽样
	 * @param string $field 排序字段
	 * @return QueryBuilder
	 */
	public function inRandomOrder(string $field = null): QueryBuilder {
		$key  = $field ?? $this->primaryKey;
		$from = $this->fieldFormat(empty($this->from) ? $this->table : $this->from);
		$sql  = <<<EOF
select floor(rand()*((select max(`$key`) from $from)-(select min(`$key`) from $from))+(select min(`$key`) from $from))
EOF;
		return $this->whereSubQueryRaw($key, '>=', $sql);
	}

}
