<?php

declare(strict_types = 1);
namespace Main\Core\Model\QueryBuiler;

use Main\Core\Model\QueryBuiler;

trait Group {

    /**
     * 单个group
     * @param string $field
     * @return QueryBuiler
     */
    public function groupString(string $field): QueryBuiler {
        $sql = $this->fieldFormat($field);
        return $this->groupPush($sql);
    }

    /**
     * 批量group
     * @param array $arr
     * @return QueryBuiler
     */
    public function groupArray(array $arr): QueryBuiler {
        foreach ($arr as $field) {
            $this->groupString((string)$field);
        }
        return $this;
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
