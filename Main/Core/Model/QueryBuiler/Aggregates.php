<?php

declare(strict_types = 1);
namespace Main\Core\Model\QueryBuiler;

/**
 * 聚合函数
 */
trait Aggregates {
  
    /**
     * count 条数统计
     * @param string $field 统计字段
     * @param array $pars
     * @return int
     */
    public function count(string $field = null, array $pars = []): int {
        $this->sqlType = 'select';
        if(is_null($field) || $field === '*')
            $this->selectRaw('count(*)');
        else
            $this->selectString('count('.$field.')');
        $res = $this->getRow($pars);
        return (int)reset($res);
    }
    
    /**
     * max 最大值
     * @param string $field 字段
     * @param array $pars
     * @return int
     */
    public function max(string $field, array $pars = []): int {
        $this->sqlType = 'select';
        $this->selectString('max('.$field.')');
        $res = $this->getRow($pars);
        return (int)reset($res);
    }
    
    /**
     * max 最小值
     * @param string $field 字段
     * @param array $pars
     * @return int
     */
    public function min(string $field, array $pars = []): int {
        $this->sqlType = 'select';
        $this->selectString('min('.$field.')');
        $res = $this->getRow($pars);
        return (int)reset($res);
    }
    
    /**
     * avg 平均值
     * @param string $field 字段
     * @param array $pars
     * @return int
     */
    public function avg(string $field, array $pars = []): int {
        $this->sqlType = 'select';
        $this->selectString('avg('.$field.')');
        $res = $this->getRow($pars);
        return (int)reset($res);
    }
    
    /**
     * sum 取和
     * @param string $field 字段
     * @param array $pars
     * @return int
     */
    public function sum(string $field, array $pars = []): int {
        $this->sqlType = 'select';
        $this->selectString('sum('.$field.')');
        $res = $this->getRow($pars);
        return (int)reset($res);
    }
}
