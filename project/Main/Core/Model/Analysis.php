<?php

namespace Main\Core\Model;
defined('IN_SYS') || exit('ACC Denied');

use \Main\Core;

/**
 * 解析 \Main\Core\Model 中的链式调用数据
 */
final class Analysis {

    // 数据收集
    private $options = [];
    // 数据sql
    private $options_sql = [];
    // 链式操作 类型 select update delete insert
    private $options_type = '';

    // 最终sql
//    private $sql = '';

    public function __construct() {
        
    }

    public function todo_analysis($options, $options_type) {

        $this->options = $options;
        $this->options_type = $options_type;
//        if($options_type == "UPDATE") var_dump ($options);
        foreach ($this->options as $k => $v) {
            $this->$k($v);
        }
        return $this->make_sql();
    }

    private function make_sql() {
        // todo
        switch ($this->options_type) {
            case 'SELECT':
                $sql = 'SELECT ' .
                        ( isset($this->options_sql['select']) ? $this->options_sql['select'] : '* ') .
                        'FROM ' . $this->options_sql['from'];
                break;
            case 'UPDATE':
                $sql = 'UPDATE ' .
                        $this->options_sql['from'] .
                        'SET ' . ( isset($this->options_sql['data']) ? $this->options_sql['data'] : '');
                break;
            case 'INSERT':
                $sql = 'INSERT INTO ' .
                        $this->options_sql['from'] .
                        'SET ' . ( isset($this->options_sql['data']) ? $this->options_sql['data'] : '');
                break;
            case 'REPLACE':
                $sql = 'REPLACE INTO ' .
                        $this->options_sql['from'] .
                        'SET ' . ( isset($this->options_sql['data']) ? $this->options_sql['data'] : '');
                break;
            case 'DELETE':
                $sql = 'DELETE ' .
                        'FROM ' . $this->options_sql['from'];
                break;
        }
        $sql .= ( isset($this->options_sql['join']) ? $this->options_sql['join'] : '') .
                ( isset($this->options_sql['where']) ? $this->options_sql['where'] : '') .
                ( isset($this->options_sql['group']) ? $this->options_sql['group'] : '') .
                ( isset($this->options_sql['having']) ? $this->options_sql['having'] : '') .
                ( isset($this->options_sql['order']) ? $this->options_sql['order'] : '') .
                ( isset($this->options_sql['limit']) ? $this->options_sql['limit'] : '');
        $this->reset();
        return $sql;
    }

    // 重置
    private function reset() {
        $this->options = [];
        $this->options_sql = [];
        $this->options_type = '';
    }

    /**
     * 解析 $this->options['where']
     */
    private function where(array $arr) {
        $str = '';
        $temp = '';
        foreach ($arr as $k => $v) {
            if ($k === '__string') {
                foreach ($v as $kk => $vv)
                    $str .= $vv . ' AND ';
            } else
                foreach ($v as $kk => $vv) {
                    if (is_array($vv)) {
                        if (stristr($vv[0], 'between')) {
                            $temp .= '`' . $k . '` ' . $vv[0] . ' ' . $this->filterPars($vv[1]) . ' AND ' . $this->filterPars($vv[2]) . ' AND ';
                        } elseif (stristr($vv[0], 'in')) {
                            if (is_array($vv[1])) {
                                $in = '(';
                                foreach ($vv[1] as $kkk => $vvv) {
                                    $in .= $this->filterPars($vvv) . ',';
                                }
                                $in = rtrim($in, ',') . ')';
                                $temp .= '`' . $k . '` ' . $vv[0] . ' ' . $in . ' AND ';
                            } else {
                                if (strstr($vv[1], ',')) {
                                    $arr = explode(',', $vv[1]);
                                    $in = '(';
                                    foreach ($arr as $kkk => $vvv) {
                                        $in .= $this->filterPars($vvv) . ',';
                                    }
                                    $in = rtrim($in, ',') . ')';
                                    $temp .= '`' . $k . '` ' . $vv[0] . ' ' . $in . ' AND ';
                                } else
                                    $temp .= '`' . $k . '` ' . $vv[0] . ' (' . $this->filterPars($vv[1]) . ') AND ';
                            }
                        }elseif (stristr($vv[0], 'like')) {
                            $temp .= '`' . $k . '` ' . $vv[0] . ' ' . $this->filterPars($vv[1]) . ' AND ';
                        } else
                            $temp .= '`' . $k . '`' . $vv[0] . $this->filterPars($vv[1]) . ' AND ';
                    }else {
                        $temp .= '`' . $k . '`=' . $this->filterPars($vv) . ' AND ';
                    }
                }
        }
        $this->options_sql['where'] = 'WHERE ( ' . trim($str . $temp, 'AND ') . ' ) ';
    }

    /**
     * 解析 $this->options['select']
     */
    private function select(array $arr) {
        if (isset($this->options_type) && $this->options_type !== 'SELECT')
            throw new \Exception(' 在非查询语句中,定义了查询字段 ! ');
        $this->options_type = 'SELECT';
        $str = '';
        foreach ($arr as $k => $v) {
            if ($k === '__string') {
                foreach ($v as $kk => $vv) {
                    if (strstr($vv, ',')) {
                        $array = explode(',', $vv);
                        foreach ($array as $kkk => $vvv) {
                            $str .= $this->filterColumn(trim($vvv, ' ')) . ',';
                        }
                    } else
                        $str .= $this->filterColumn($vv) . ',';
                }
            } else
                $str .= $this->filterColumn($v) . ',';
        }
        $this->options_sql['select'] = trim($str, ',') . ' ';
    }

    private function data(array $arr) {
        if ($this->options_type !== 'UPDATE' && $this->options_type !== 'INSERT' && $this->options_type !== 'REPLACE')
            throw new Core\Exception(' 在非更新语句中,定义了更新字段 ! ');
        $str = '';
        foreach ($arr as $k => $v) {
            if ($k === '__string')
                foreach ($v as $kk => $vv)
                    $str .= $vv . ',';
            else
                $str .= $this->filterColumn($k) . '=' . $this->filterPars($v) . ',';
        }
        $this->options_sql['data'] = trim($str, ',') . ' ';
    }

    /**
     * @param string $str
     * 解析 $this->options['from']
     */
    private function from($str = '') {
        $this->options_sql['from'] = $this->filterColumn($str) . ' ';
    }

    private function join(array $arr) {
        // 检测是否存在join ,不存在则补上
        $checkJoin = function($str) {
            $arr = explode(' ', $str);
            foreach ($arr as $k => $v) {
                if ($v == '')
                    unset($arr[$k]);
            }
            $arr = array_values($arr);
            if ((strtoupper($arr[0]) === 'JOIN') || (strtoupper($arr[1]) === 'JOIN'))
                return $str;
            else
                return 'INNER JOIN ' . $str;
        };
        $temp = '';
        foreach ($arr as $k => $v) {
            $temp .= $checkJoin(trim($v)) . ' ';
        }
        $this->options_sql['join'] = $temp . ' ';
    }

    private function group(array $arr) {
        $str = '';
        foreach ($arr as $k => $v) {
            if ($k === '__string') {
                foreach ($v as $kk => $vv) {
                    $tmp = str_ireplace('group by ', '', trim($vv, ' '));
                    if (stristr($tmp, ',')) {
                        $temp = explode(',', $tmp);
                        foreach ($temp as $vvv)
                            $str .= $this->filterColumn($vvv) . ',';
                    }
                }
            } else
                $str .= $this->filterColumn($v) . ',';
        }
        $this->options_sql['group'] = 'GROUP BY ' . trim($str, ',') . ' ';
    }

    private function having(array $arr) {
        $str = '';
        foreach ($arr as $k => $v) {
            if ($k === '__string') {
                foreach ($v as $kk => $vv) {
                    $tmp = str_ireplace('having ', '', trim($vv, ' '));
                    if ($and = stristr($tmp, 'and')) {
                        $array = explode(substr($and, 0, 3), $tmp);
                        foreach ($array as $vvv)
                            $str .= $this->filterColumn($vvv) . ' AND ';
                    } else
                        $str .= $vv . ' AND ';
                }
            }
        }
        $this->options_sql['group'] = 'HAVING ' . rtrim($str, ' AND ') . ' ';
    }

    private function order(array $arr) {
        $order_str = '';
        foreach ($arr as $k => $v) {
            if ($k === '__string') {
                foreach ($v as $kk => $vv) {
                    $tmp = str_ireplace('order by ', '', trim($vv));
                    $order_str .= $tmp . ( preg_match('#\s(asc|desc)$#i', $tmp) ? '' : ' ASC') . ',';
                }
            } else {
                $tmp = str_ireplace('order by ', '', trim($v));
                $order_str .= $tmp . ( preg_match('#\s(asc|desc)$#i', $tmp) ? '' : ' ASC') . ',';
            }
        }
        $this->options_sql['order'] = 'ORDER BY ' . trim($order_str, ',') . ' ';
    }

    private function limit(array $arr) {
        if ($this->options_type === 'SELECT')
            $this->options_sql['limit'] = 'LIMIT ' . $arr['start'] . ',' . $arr['max'];
        else
            $this->options_sql['limit'] = 'LIMIT ' . $arr['max'];
    }

    /**
     * 将可能与sql关键字混淆的 列名 加上 反引号
     * 如 hk_user.account as like 处理成 `hk_user`.`account` as `like`
     * @param string $str 需要处理的string单元
     * @return string
     */
    private function filterColumn($str = '') {
        $addBackQuote = function($str = '') {
            $str = trim($str, ' ');
            if ($str === '*')
                return $str;
            return '`' . trim($str, '`') . '`';
        };
        $temp = '';
        $str = trim($str, ' ');
        if ($as = stristr($str, ' as ')) {
            $array = explode(substr($as, 0, 4), $str);
            $array[0] = trim($array[0], ' ');
            $array[1] = trim($array[1], ' ');
            // 将 count(hk_user.account) 过滤为 count(`hk_user`.`account`)
            if (($a = strstr($array[0], '('))) {
                $action = str_replace($a, '', $array[0]);
                $a = ltrim($a, '(');
                $a = rtrim($a, ')');
                if (strstr($a, '.')) {
                    $arr = explode('.', $a);
                    $temp .= $addBackQuote($arr[0]) . '.' . $addBackQuote($arr[1]);
                } else
                    $temp .= $addBackQuote($a);
                $temp = $action . '(' . $temp . ')';
            }elseif (strstr($array[0], '.')) {
                $arr = explode('.', $array[0]);
                $temp .= $addBackQuote($arr[0]) . '.' . $addBackQuote($arr[1]);
            } else
                $temp .= $addBackQuote($array[0]);
            $temp .= ' AS ' . $addBackQuote($array[1]);
        }else {
            // 将 count(hk_user.account) 过滤为 count(`hk_user`.`account`)
            if (($a = strstr($str, '('))) {
                $action = str_replace($a, '', $str);
                $a = ltrim($a, '(');
                $a = rtrim($a, ')');
                if (strstr($a, '.')) {
                    $arr = explode('.', $a);
                    $temp .= $addBackQuote($arr[0]) . '.' . $addBackQuote($arr[1]);
                } else
                    $temp .= $addBackQuote($a);
                $temp = $action . '(' . $temp . ')';
            }elseif (strstr($str, '.')) {
                $arr = explode('.', $str);
                $temp .= $addBackQuote($arr[0]) . '.' . $addBackQuote($arr[1]);
            } else
                $temp .= $addBackQuote($str);
        }
        return $temp;
    }

    /**
     * 将 非占位符 加上 ""
     * @param string $str
     * @return string
     */
    private function filterPars($str = '') {
        $str = trim($str, ' ');
        if (strstr($str, ':') && !strtotime($str))
            return $str;
        else
            return '"' . $str . '"';
    }
}
