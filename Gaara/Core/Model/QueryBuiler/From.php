<?php

declare(strict_types = 1);
namespace Gaara\Core\Model\QueryBuiler;

use Gaara\Core\Model\QueryBuiler;

trait From {
    
    private $noFrom = false;

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
    
    /**
     * 设置不需要from片段
     * 仅对 select 生效
     * @return QueryBuiler
     */
    public function noFrom(): QueryBuiler{
        $this->noFrom = true;
        return $this;
    }
}
