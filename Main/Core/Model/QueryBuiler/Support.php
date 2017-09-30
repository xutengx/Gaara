<?php

declare(strict_types = 1);
namespace Main\Core\Model\QueryBuiler;

use Main\Core\Model\QueryBuiler;

trait Support {

    /**
     * 获取一个与自己主属性相同的实例, 不同于clone
     * @return QueryBuiler
     */
    private function getSelf(): QueryBuiler {
        return new QueryBuiler($this->table, $this->primaryKey);
    }

    /**
     * 给与sql片段两端空格
     * @param string $part sql片段
     * @return string
     */
    private function partFormat(string $part): string {
        return ' ' . trim($part) . ' ';
    }

    /**
     * 给字段加上反引号
     * @param string $field 字段 eg: sum(order.amount) as sum_price
     * @return string eg: sum(`order`.`amount`) as `sum_price`
     */
    private function fieldFormat(string $field): string {
        //todo
        //todo
        //todo
        if (strpos($field, '.') === false) {
            return '`' . $field . '`';
        } else {
            $arr = explode('.', $field);
            return '`' . reset($arr) . '`.`' . end($arr) . '`';
        }
    }

    /**
     * 值加上双引号
     * @param string $value 字段 eg:1765595948
     * @return string   eg:"1765595948"
     */
    private function valueFormat(string $value): string {
        return '"' . $value . '"';
    }

    /**
     * 值加上括号
     * @param string $value 字段 eg:1765595948
     * @return string   eg:(1765595948)
     */
    private function bracketFormat(string $value): string {
        return '(' . $value . ')';
    }

    public function toSql(): string {
        $sql = '';
        switch ($this->sqlType) {
            case 'select':
                $sql = 'select ' . $this->dealSelect() . ' from ' . $this->dealFrom();
                break;
            case 'update':
                $sql = 'update ' . $this->dealFrom() . ' set ' . $this->dealData();
                break;
            case 'insert':
                $sql = 'insert into ' . $this->dealFrom() . ' set ' . $this->dealData();
                break;
            case 'replace':
                $sql = 'replace into ' . $this->dealFrom() . ' set ' . $this->dealData();
                break;
            case 'delete':
                $sql = 'delete from ' . $this->dealFrom();
                break;
        }

        $sql .= $this->dealJoin() .
                $this->dealWhere() .
                $this->dealGroup() .
                $this->dealHaving() .
                $this->dealOrder() .
                $this->dealLimit();
        return $sql;
    }

    /**
     * 返回select部分
     * @return string
     */
    private function dealSelect(): string {
        if (empty($this->select)) {
            return '*';
        } else {
            return $this->select;
        }
    }

    private function dealData(): string {
        if (empty($this->data)) {
            return '';
        } else {
            return ' ' . $this->data;
        }
    }

    /**
     * 返回from部分
     * @return string
     */
    private function dealFrom(): string {
        if (empty($this->from)) {
            return '`' . $this->table . '`';
        } else {
            return $this->from;
        }
    }

    private function dealJoin(): string {
        if (empty($this->join)) {
            return '';
        } else
            return ' ' . $this->join;
    }

    /**
     * 返回where部分
     * @return string
     */
    private function dealWhere(): string {
        if (empty($this->where)) {
            return '';
        } else {
            if (is_null($this->sqlType)) {
                return $this->where;
            } else {
                return ' where ' . $this->where;
            }
        }
    }

    private function dealGroup(): string {
        if (empty($this->group)) {
            return '';
        } else {
            return ' group by ' . $this->group;
        }
    }

    private function dealHaving(): string {
        if (empty($this->having)) {
            return '';
        } else {
            return ' having ' . $this->having;
        }
    }

    private function dealOrder(): string {
        if (empty($this->order)) {
            return '';
        } else {
            return ' order by ' . $this->order;
        }
    }

    private function dealLimit(): string {
        if (empty($this->limit)) {
            return '';
        } else {
            return ' limit ' . $this->limit;
        }
    }
}
