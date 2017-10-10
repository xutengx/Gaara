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
        return new QueryBuiler($this->table, $this->primaryKey, $this->db, $this->model);
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
        if($has_as = stristr($field, ' as ')){
            $as = substr($has_as, 0, 4);
            $info = explode($as, $field);
            $alias = ' as `'.end($info).'`';
            $maybefunc = reset($info);
        } else {
            $alias = '';
            $maybefunc = $field;    // eg: sum(order.amount)
        }
        if (($a = strstr($maybefunc, '('))) {   // eg: (order.amount)
            $action = str_replace($a, '', $maybefunc);  // eg: sum
            $a = ltrim($a, '(');
            $a = rtrim($a, ')');
            if (strstr($a, '.')) {
                $arr = explode('.', $a);
                $temp = '`' . reset($arr) . '`.`' . end($arr) . '`';
            } else
                $temp = '`' . $a . '`';
            $temp = $action . '(' . $temp . ')';
        }else{
            if (strpos($maybefunc, '.') === false) {
                $temp = '`' . $maybefunc . '`';
            } else {
                $arr = explode('.', $maybefunc);
                $temp = '`' . reset($arr) . '`.`' . end($arr) . '`';
            }
        }
        return $temp.$alias;
        
    }

    /**
     * 值加上双引号
     * 注:参数绑定的形参不加双引号
     * @param string $value 字段 eg:1765595948
     * @return string   eg:"1765595948"
     */
    private function valueFormat(string $value): string {
        if ((strpos($value, ':') === 0) || ($value === '?'))
            return (string)$value;
        else
            return '"' . (string)$value . '"';
    }

    /**
     * 值加上括号
     * @param string $value 字段 eg:1765595948
     * @return string   eg:(1765595948)
     */
    private function bracketFormat(string $value): string {
        return '(' . $value . ')';
    }
    
    /**
     * 生成sql
     * @param array $pars 参数绑定, 在此处, 仅作记录sql作用
     * @return string sql
     */
    public function toSql(array $pars = []): string {
        $sql = '';
        $remember = true;
        switch ($this->sqlType) {
            case 'select':
                $sql = 'select ' . $this->dealSelect() . ' from ' . $this->dealFrom();
                break;
            case 'update':
                $sql = 'update ' . $this->dealFrom() . ' set' . $this->dealData();
                break;
            case 'insert':
                $sql = 'insert into ' . $this->dealFrom() . ' set' . $this->dealData();
                break;
            case 'replace':
                $sql = 'replace into ' . $this->dealFrom() . ' set' . $this->dealData();
                break;
            case 'delete':
                $sql = 'delete from ' . $this->dealFrom();
                break;
            default :
                $remember = false;
                break;
        }
        $sql .= $this->dealJoin() .
                $this->dealWhere() .
                $this->dealGroup() .
                $this->dealHaving() .
                $this->dealOrder() .
                $this->dealLimit();
        if(!empty($this->union)){
            $sql = $this->bracketFormat($sql);
            foreach($this->union as $type => $clauseArray){
                foreach($clauseArray as $clause)
                    $sql .= $type.$this->bracketFormat($clause);
            }
        }
        if ($remember)
            $this->rememberSql($sql, $pars);
        return $sql;
    }

    /**
     * 记录最近次的sql, 完成参数绑定的填充
     * 重载此方法可用作sql日志
     * @param string $sql 拼接完成的sql
     * @param array $pars 参数绑定数组
     * @return void
     */
    private function rememberSql(string $sql, array $pars): void {
        $pars = is_array($pars) ? $pars : [];
        foreach ($pars as $k => $v) {
            $pars[$k] = '\'' . $v . '\'';
        }
        $this->lastSql = strtr($sql, $pars);
        $this->model->setLastSql($this->lastSql);
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

    /**
     * data
     * @return string
     */
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

    /**
     * join
     * @return string
     */
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

    /**
     * group
     * @return string
     */
    private function dealGroup(): string {
        if (empty($this->group)) {
            return '';
        } else {
            return ' group by ' . $this->group;
        }
    }

    /**
     * having
     * @return string
     */
    private function dealHaving(): string {
        if (empty($this->having)) {
            return '';
        } else {
            return ' having ' . $this->having;
        }
    }

    /**
     * order
     * @return string
     */
    private function dealOrder(): string {
        if (empty($this->order)) {
            return '';
        } else {
            return ' order by ' . $this->order;
        }
    }

    /**
     * limit
     * @return string
     */
    private function dealLimit(): string {
        if (empty($this->limit)) {
            return '';
        } else {
            return ' limit ' . $this->limit;
        }
    }

}