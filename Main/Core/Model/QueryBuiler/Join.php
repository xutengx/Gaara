<?php

declare(strict_types = 1);
namespace Main\Core\Model\QueryBuiler;

use Main\Core\Model\QueryBuiler;

trait Join {

    /**
     * 加入不做处理的join段
     * @param string $sql
     * @return QueryBuiler
     */
    public function joinRaw(string $sql): QueryBuiler {
        return $this->joinPush($sql);
    }
    
    /**
     * 表连接
     * @param string $table
     * @param string $fieldOne
     * @param string $symbol
     * @param string $fieldTwo
     * @param string $joinType
     * @return QueryBuiler
     */
    public function joinString(string $table, string $fieldOne, string $symbol, string $fieldTwo, string $joinType = 'inner join'): QueryBuiler {
        $sql = $joinType . ' ' . $this->fieldFormat($table) . ' on ' . $this->fieldFormat($fieldOne) . $symbol . $this->fieldFormat($fieldTwo);
        return $this->joinPush($sql);
    }

    /**
     * 将Join片段加入Join, 返回当前对象
     * @param string $part
     * @return QueryBuiler
     */
    private function joinPush(string $part): QueryBuiler {
        if (empty($this->join)) {
            $this->join = $part;
        } else
            $this->join .= ' ' . $part;
        return $this;
    }
}
