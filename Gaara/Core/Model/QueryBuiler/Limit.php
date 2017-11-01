<?php

declare(strict_types = 1);
namespace Gaara\Core\Model\QueryBuiler;

use Gaara\Core\Model\QueryBuiler;

trait Limit{

    /**
     * limit带偏移量
     * @param int $offset
     * @param int $take
     * @return QueryBuiler
     */
    public function limitOffsetTake(int $offset, int $take): QueryBuiler {
        $sql = $offset.','.$take;
        return $this->limitPush($sql);
    }

    /**
     * limit不带偏移量
     * @param int $take
     * @return QueryBuiler
     */
    public function limitTake(int $take): QueryBuiler {
        $sql = $take;
        return $this->limitPush($sql);
    }
     
    /**
     * 将limit片段加入limit, 返回当前对象
     * 多次调用,将覆盖之前
     * @param int $part
     * @return QueryBuiler
     */
    private function limitPush(int $part): QueryBuiler {
        $this->limit = $part;
        return $this;
    }
}