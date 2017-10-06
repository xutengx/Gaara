<?php

declare(strict_types = 1);
namespace Main\Core\Model\QueryBuiler;

use Main\Core\Model\QueryBuiler;

trait Data {

    /**
     * 字段$field赋值$value
     * @param string $str
     * @return QueryBuiler
     */
    public function dataString(string $field, string $value): QueryBuiler {
        $sql = $this->fieldFormat($field) . '=' . $this->valueFormat($value);
        return $this->dataPush($sql);
    }

    /**
     * 批量数组赋值
     * @param array $arr
     * @return QueryBuiler
     */
    public function dataArray(array $arr): QueryBuiler {
        foreach ($arr as $field => $value) {
            $this->dataString((string)$field, (string)$value);
        }
        return $this;
    }

    /**
     * 将data片段加入data, 返回当前对象
     * @param string $part
     * @return QueryBuiler
     */
    private function dataPush(string $part): QueryBuiler {
        if (empty($this->data)) {
            $this->data = $part;
        } else
            $this->data .= ',' . $part;
        return $this;
    }
}
