<?php

namespace Main\Core;

use Main\Core\Model\Traits;

defined('IN_SYS') || exit('ACC Denied');

class Model {
    
    use Traits\DebugTrait;

    protected $db;
    // 表名,不包含表前缀
    protected $tablename = '';
    // 表前缀
    protected $tablepre = '';
    // 主键的字段, 依赖字段分析器 resolution
    protected $key = null;
    // 新增时间, 依赖字段分析器 resolution
    protected $created_time = null;
    // 更改时间, 依赖字段分析器 resolution
    protected $updated_time = null;
    // 新增时间类型, 依赖字段分析器 resolution
    protected $created_time_type = null;
    // 更改时间类型, 依赖字段分析器 resolution
    protected $updated_time_type = null;
    // 表名
    protected $table = '';
    // 表信息
    protected $field = array();
    // 链式操作集合
    protected $options = array();
    // 链式操作 sql
    protected $lastSql = null;
    // 链式操作 类型 select update delete insert
    protected $options_type = null;
    // PDOStatement
    protected $PDOStatement = null;
    // 链式操作收集器
    protected $collect = null;
    // 链式操作解释器
    protected $analysis = null;
    // 字段分析器
    protected $resolution = null;
    // 是否已设定当前sql表
    protected $from = false;
    // 是否自动维护时间
    protected $autoTIme = true;

    /**
     * @param object $DbConnection db连接对象 如 obj('Mysql',false);
     */
    final public function __construct($DbConnection = null) {
        $this->db = is_null($DbConnection) ? obj(DbConnection::class, obj(Conf::class)->db) : $DbConnection;
        $this->collect = new \Main\Core\Model\Collect($this->options);
        $this->analysis = new \Main\Core\Model\Analysis();
        $this->resolution = new \Main\Core\Model\Resolution();
        $this->get_thisTable();
        $this->construct();
    }

    final protected function get_thisTable() {
        $conf = obj(Conf::class);
        $this->tablepre = $conf->tablepre;
        $classname = get_class($this);
        $classname = substr($classname, strrpos($classname, '\\') + 1);
        if ($this->tablename === '')
            $this->tablename = strtr($classname, array('Model' => ''));
        if ($this->table === '')
            $this->table = $conf->tablepre . $this->tablename;
        $this->getTableInfo();
    }

//    public function tbname() {
//        return $this->getTable();
//    }

    public static function getTable() {
        return \obj(static::class)->table;
    }

    // 获取表信息, 自动信息填充
    protected function getTableInfo() {
        // todo
        $this->field = obj(Cache::class)->get(function() {
            return $this->db->getAll('SHOW COLUMNS FROM `' . $this->table . '`');
        }, 3600);
        list($this->key, $this->created_time, $this->created_time_type, $this->updated_time, $this->updated_time_type) = $this->resolution->getKey($this->field);
        foreach ($this->field as $v) {
            if ($v['Extra'] == 'auto_increment') {
                $this->key = $v['Field'];
                break;
            }
        }
    }
    
    // 所有手动初始化建议在此执行
    protected function construct() {
        
    }

    // 在外执行
    final public function runProtectedFunction($func = '', array $agrs = array()) {
        return call_user_func_array(array($this, $func), $agrs);
    }

    final public function __call($func, $pars = array()) {
        if (method_exists($this->collect, $func)) {
            call_user_func_array(array($this->collect, $func), $pars);
            return $this;
        }
    }

    final public static function __callStatic($func, $pars = array()) {
        $thisObj = \obj(static::class);
        if (method_exists($thisObj->collect, $func)) {
            call_user_func_array(array($thisObj->collect, $func), $pars);
            return $thisObj;
        }
    }

//---------------------------------------------------------- 链式操作 -----------------------------------------------------//
    /**
     * 准备sql, 统一执行方法
     * @param bool|true $onlyOnce 是否执行一次后销毁(一般情况下如此)
     *
     * @return $this
     */
    public function prepare($onlyOnce = false, $pars = array() ) {
        // 系统填充用户调用时未填充的先关信息
        $this->get_ready();
        
        // 调用解释器
        $sql = $this->analysis->todo_analysis($this->options, $this->options_type);
        
        // 重置当前model
        $this->reset();
        
        // 记录sql
        $this->rememberSql($sql, $pars);
        
        // return
        if ($onlyOnce)
            return $sql;
        else {
            $this->PDOStatement = $this->db->prepare($sql, $this->options_type);
            return $this;
        }
    }
    /**
     * 记录最近次的sql, 完成参数绑定的填充
     * 重载此方法可用作sql日志
     */
    protected function rememberSql($sql, $pars){
        $pars = is_array($pars) ? $pars : [];
        foreach($pars as $k => $v){
            $pars[$k] = '\''.$v.'\'';
        }
        $this->lastSql = strtr( $sql, $pars);
    }


    /**
     * 填充当前操作表, 填充查询字段, 填充更新时间, , 填充新增时间
     */
    protected function get_ready(){
        $timestamp = $_SERVER['REQUEST_TIME'];
        // 填充当前操作表
        if (!$this->from)
            $this->collect->from($this->table);
        // 填充 select
        if (!isset($this->options['select']) && $this->options_type == 'SELECT') {
            $str = '';
            foreach ($this->field as $v) {
                $str .= '`' . $this->table . '`.`' . $v['Field'] . '`,';
            }
            $this->options['select']['__string'][] = trim($str, ',') . ' ';
        }
        // 填充 update
        elseif($this->options_type == 'UPDATE'){
            // 自动维护时间
            if($this->autoTIme && !is_null($this->updated_time) && !is_null($this->updated_time_type)){
                switch ($this->updated_time_type) {
                    case 'timestamp':
                    case 'datetime':
                        $time = date('Y-m-d H;i;s', $timestamp);
                        break;
                    case 'int':
                    case 'bigint':
                        $time = $timestamp;
                        break;
                    default:
                        break;
                }
                $str = '`' . $this->table . '`.`' . $this->updated_time . '`="'.$time.'"';
                $this->options['data']['__string'][] = $str;
            }
        }
        // 填充 create
        elseif($this->options_type == 'INSERT'){
            // 自动维护时间
            if($this->autoTIme && !is_null($this->created_time) && !is_null($this->created_time_type)){
                $str = '';
                switch ($this->created_time_type) {
                    case 'timestamp':
                    case 'datetime':
                        $time = date('Y-m-d H;i;s', $timestamp);
                        break;
                    case 'int':
                    case 'bigint':
                        $time = $timestamp;
                        break;
                    default:
                        break;
                }
                $str .= '`' . $this->table . '`.`' . $this->created_time . '`="'.$time.'"';
                $this->options['data']['__string'][] = trim($str, ',') . ' ';
            }
        }
    }

    /**
     * 重置链式操作
     */
    protected function reset() {
        // 链式操作集合
        $this->options = array();
        // 链式操作 sql
//        $this->options_sql = array();
        // 链式操作 类型 select update delete insert
        $this->options_type = null;
    }

    /**
     * 参数绑定, 并执行
     * @param array $pars
     */
    public function execute($pars = array()) {
        $this->PDOStatement->execute($pars);
        return $this->PDOStatement->fetchall(\PDO::FETCH_ASSOC);
    }

    public function getRow($pars = array()) {
        $this->options_type = 'SELECT';
        $this->collect->limit(1);
        $sql = $this->prepare(true, $pars);
        return $this->db->getRow($sql, $pars);
    }

    public function getAll($pars = array()) {
        $this->options_type = 'SELECT';
        $sql = $this->prepare(true, $pars);
        return $this->db->getAll($sql, $pars);
    }

    public function update($pars = array()) {
        $this->options_type = 'UPDATE';
        if (!isset($this->options['data']))
            throw new Exception('要执行UPDATE操作, 需要使用data方法设置更新的值');
        $sql = $this->prepare(true, $pars);
        return $this->db->update($sql, $pars);
    }

    public function insert($pars = array()) {
        $this->options_type = 'INSERT';
        if (!isset($this->options['data']))
            throw new Exception('要执行INSERT操作, 需要使用data方法设置新增的值');
        $sql = $this->prepare(true, $pars);
        return $this->db->insert($sql, $pars);
    }

    public function delete($pars = array()) {
        $this->options_type = 'DELETE';
        if (!isset($this->options['where']))
            throw new Exception('执行 DELETE 操作并没有相应的 where 约束, 请确保操作正确, 使用where(1)将强制执行.');
        $sql = $this->prepare(true, $pars);
        return $this->db->update($sql, $pars);
    }

    public function replace($pars = array()) {
        $this->options_type = 'REPLACE';
        if (!isset($this->options['data']))
            throw new Exception('要执行REPLACE操作, 需要使用data方法设置新增or修改的值');
        $sql = $this->prepare(true, $pars);
        return $this->db->update($sql, $pars);
    }

    public function begin() {
        return $this->db->begin();
    }

    public function commit() {
        return $this->db->commit();
    }

    public function rollBack() {
        return $this->db->rollBack();
    }

    public function __get($attr) {
        if ($attr === 'db')
            return $this->db;
    }
}
