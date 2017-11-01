<?php

declare(strict_types = 1);
namespace Gaara\Core\Model\QueryBuiler;

use Gaara\Core\Model\QueryBuiler;

trait Group {

    /**
     * 单个group
     * @param string $field
     * @return QueryBuiler
     */
    public function groupString(string $field, string $delimiter = ','): QueryBuiler {
        return $this->groupArray(explode($delimiter, $field));

    }

    /**
     * 批量group
     * @param array $arr
     * @return QueryBuiler
     */
    public function groupArray(array $arr): QueryBuiler {
        $str = '';
        foreach ($arr as $field) {
            $str .= $this->fieldFormat($field) . ',';
        }
        $sql = rtrim($str, ',');
        return $this->groupPush($sql);
    }

    /**
     * 将Group片段加入Group, 返回当前对象
     * @param string $part
     * @return QueryBuiler
     */
    private function groupPush(string $part): QueryBuiler {
        if (empty($this->group)) {
            $this->group = $part;
        } else
            $this->group .= ',' . $part;
        return $this;
    }
}
