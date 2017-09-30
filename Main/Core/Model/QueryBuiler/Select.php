<?php

declare(strict_types = 1);
namespace Main\Core\Model\QueryBuiler;

use Main\Core\Model\QueryBuiler;

trait Select {

    /**
     * 加入不做处理的字段
     * @param string $sql
     * @return QueryBuiler
     */
    public function selectRaw(string $sql): QueryBuiler {
        return $this->selectPush($sql);
    }

    /**
     * 将一个数组加入查询
     * @param array $arr
     * @return QueryBuiler
     */
    public function selectArray(array $arr): QueryBuiler {
        $str = '';
        foreach ($arr as $field) {
            $str .= $this->fieldFormat($field) . ',';
        }
        $sql = rtrim($str, ',');
        return $this->selectPush($sql);
    }

    /**
     * 将一个string加入查询
     * @param string $str
     * @param string $delimiter
     * @return QueryBuiler
     */
    public function selectString(string $str, string $delimiter = ','): QueryBuiler {
        return $this->selectArray(explode($delimiter, $str));
    }

    /**
     * 将select片段加入select, 返回当前对象
     * @param string $part
     * @return QueryBuiler
     */
    private function selectPush(string $part): QueryBuiler {
        if (empty($this->select)) {
            $this->select = $part;
        } else
            $this->select .= ',' . $part;
        return $this;
    }
}
