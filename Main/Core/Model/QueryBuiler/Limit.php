<?php

declare(strict_types = 1);
namespace Main\Core\Model\QueryBuiler;

use Main\Core\Model\QueryBuiler;

trait Limit{

    /**
     * limit带偏移量
     * @param string $offset
     * @param string $take
     * @return QueryBuiler
     */
    public function limitOffsetTake(string $offset, string $take): QueryBuiler {
        $sql = $offset.','.$take;
        return $this->limitPush($sql);
    }

    /**
     * limit不带偏移量
     * @param string $take
     * @return QueryBuiler
     */
    public function limitTake(string $take): QueryBuiler {
        $sql = $take;
        return $this->limitPush($sql);
    }
     
    /**
     * 将limit片段加入limit, 返回当前对象
     * 多次调用,将覆盖之前
     * @param string $part
     * @return QueryBuiler
     */
    private function limitPush(string $part): QueryBuiler {
        $this->limit = $part;
        return $this;
    }
}