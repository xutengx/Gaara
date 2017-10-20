<?php

declare(strict_types = 1);
namespace Main\Core\Model\QueryBuiler;

use Main\Core\Model\QueryBuiler;
/**
 * 特殊用途
 */
trait Special {
    
    /**
     * 随机抽样
     * @param string $field 排序字段
     * @return QueryBuiler
     */
    public function inRandomOrder(string $field = null): QueryBuiler {
        $key = $field ?? $this->primaryKey;
        $from = $this->fieldFormat(empty($this->from) ? $this->table : $this->from );
        $sql = <<<EOF
select floor(RAND()*((select max(`$key`) from $from)-(select min(`$key`) from $from))+(select min(`$key`) from $from))
EOF;
        return $this->whereSubQueryRaw($key, '>=', $sql);
    }
}
