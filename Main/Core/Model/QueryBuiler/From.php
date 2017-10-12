<?php

declare(strict_types = 1);
namespace Main\Core\Model\QueryBuiler;

use Main\Core\Model\QueryBuiler;

trait From {

    /**
     * 将一个from加入查询
     * @param string $str
     * @param string $delimiter
     * @return QueryBuiler
     */
    public function fromString(string $str): QueryBuiler {
        $this->from = '`' . $str . '`';
        return $this;
    }

    /**
     * 将一个from加入查询
     * @param string $str
     * @param string $delimiter
     * @return QueryBuiler
     */
    public function fromRaw(string $str): QueryBuiler {
        $this->from = $str;
        return $this;
    }
}
