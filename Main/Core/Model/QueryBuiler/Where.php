<?php

declare(strict_types = 1);
namespace Main\Core\Model\QueryBuiler;

use Closure;
use Main\Core\Model\QueryBuiler;

trait Where {

    public function whereExists() {
        
    }

    /**
     * 加入一个不做处理的条件
     * @param string $sql
     * @return QueryBuiler
     */
    public function whereRaw(string $sql): QueryBuiler {
        return $this->wherePush($sql);
    }

    /**
     * 且
     * @param Closure $callback
     * @return QueryBuiler
     */
    public function andWhere(Closure $callback): QueryBuiler {
        $sql = $this->whereClosure($callback);
        return $this->wherePush($sql);
    }

    /**
     * 或
     * @param Closure $callback
     * @return QueryBuiler
     */
    public function orWhere(Closure $callback): QueryBuiler {
        $sql = $this->whereClosure($callback);
        return $this->wherePush($sql, 'or');
    }

    /**
     * 比较字段与字段
     * @param string $fieldOne
     * @param string $symbol 
     * @param string $fieldTwo
     * @return QueryBuiler
     */
    public function whereColumn(string $fieldOne, string $symbol, string $fieldTwo): QueryBuiler {
        $sql = $this->fieldFormat($fieldOne) . $symbol . $this->fieldFormat($fieldTwo);
        return $this->wherePush($sql);
    }

    /**
     * 比较字段与值
     * @param string $field
     * @param string $symbol 
     * @param string $value
     * @return QueryBuiler
     */
    public function whereValue(string $field, string $symbol, string $value): QueryBuiler {
        $sql = $this->fieldFormat($field) . $symbol . $this->valueFormat($value);
        return $this->wherePush($sql);
    }
    
    /**
     * 批量相等条件
     * @param array $arr
     * @return QueryBuiler
     */
    public function whereArray(array $arr): QueryBuiler {
        foreach($arr as $field => $value){
            $this->whereValue($field, '=', $value);
        }
        return $this;
    }

    /**
     * 字段值在2值之间
     * @param string $field
     * @param string $min
     * @param string $max
     * @return QueryBuiler
     */
    public function whereBetweenString(string $field, string $min, string $max): QueryBuiler {
        $sql = $this->fieldFormat($field) . 'between' . $this->valueFormat($min) . 'and' . $this->valueFormat($max);
        return $this->wherePush($sql);
    }

    /**
     * 字段值在2值之间
     * @param string $field
     * @param array $range
     * @return QueryBuiler
     */
    public function whereBetweenArray(string $field, array $range): QueryBuiler {
        $sql = $this->fieldFormat($field) . 'between' . $this->valueFormat(reset($range)) . 'and' . $this->valueFormat(end($range));
        return $this->wherePush($sql);
    }

    /**
     * 字段值不在2值之间
     * @param string $field
     * @param string $min
     * @param string $max
     * @return QueryBuiler
     */
    public function whereNotBetweenString(string $field, string $min, string $max): QueryBuiler {
        $sql = $this->fieldFormat($field) . 'not between' . $this->valueFormat($min) . 'and' . $this->valueFormat($max);
        return $this->wherePush($sql);
    }

    /**
     * 字段值不在2值之间
     * @param string $field
     * @param array $range
     * @return QueryBuiler
     */
    public function whereNotBetweenArray(string $field, array $range): QueryBuiler {
        $sql = $this->fieldFormat($field) . 'not between' . $this->valueFormat(reset($range)) . 'and' . $this->valueFormat(end($range));
        return $this->wherePush($sql);
    }

    /**
     * 字段值在范围内
     * @param string $field
     * @param array $values
     * @return QueryBuiler
     */
    public function whereInArray(string $field, array $values): QueryBuiler {
        $sql = $this->fieldFormat($field) . 'in' . $this->bracketFormat($this->valueFormat(implode('","', $values)));
        return $this->wherePush($sql);
    }

    /**
     * 字段值在范围内
     * @param string $field
     * @param string $value
     * @param array $delimiter
     * @return QueryBuiler
     */
    public function whereInString(string $field, string $value, string $delimiter = ','): QueryBuiler {
        return $this->whereInArray($field, explode($delimiter, $value));
    }

    /**
     * 字段值不在范围内
     * @param string $field
     * @param array $values
     * @return QueryBuiler
     */
    public function whereNotInArray(string $field, array $values): QueryBuiler {
        $sql = $this->fieldFormat($field) . 'not in' . $this->bracketFormat($this->valueFormat(implode('","', $values)));
        return $this->wherePush($sql);
    }

    /**
     * 字段值不在范围内
     * @param string $field
     * @param string $value
     * @param array $delimiter
     * @return QueryBuiler
     */
    public function whereNotInString(string $field, string $value, string $delimiter = ','): QueryBuiler {
        return $this->whereNotInArray($field, explode($delimiter, $value));
    }

    /**
     * 字段值为null
     * @param string $field
     * @return QueryBuiler
     */
    public function whereNull(string $field): QueryBuiler {
        $sql = $this->fieldFormat($field) . 'is null';
        return $this->wherePush($sql);
    }

    /**
     * 字段值不为null
     * @param string $field
     * @return QueryBuiler
     */
    public function whereNotNull(string $field): QueryBuiler {
        $sql = $this->fieldFormat($field) . 'is not null';
        return $this->wherePush($sql);
    }

    /**
     * 闭包
     * @param Closure $callback
     * @return string
     */
    private function whereClosure(Closure $callback): string {
        $str = '';
        $res = $callback($queryBuiler = $this->getSelf());
        // 调用方未调用return
        if (is_null($res)) {
            $str = $queryBuiler->toSql();
        }
        // 调用方未调用toSql
        elseif ($res instanceof QueryBuiler) {
            $str = $res->toSql();
        }
        // 正常
        else
            $str = $res;
        $sql = $this->bracketFormat($str);
        return $sql;
    }

    /**
     * 将where片段加入where, 返回当前对象
     * @param string $part
     * @param string $relationship
     * @return QueryBuiler
     */
    private function wherePush(string $part, string $relationship = 'and'): QueryBuiler {
        if (empty($this->where)) {
            $this->where = $part;
        } else
            $this->where .= ' ' . $relationship . ' ' . $part;
        return $this;
    }
}
