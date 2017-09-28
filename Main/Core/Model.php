<?php

declare(strict_types = 1);
namespace Main\Core;

use Main\Core\Model\Traits;
use \Log;

class Model {

    use Traits\DebugTrait;
    use Traits\ObjectRelationalMappingTrait;
    use Traits\Transaction;
    // 所有数据库连接类
    public static $dbs;
    // 当前model的数据库连接类
    protected $db;
    // 表名,不包含表前缀
    protected $tablename = '';
    // 表前缀
    protected $tablepre = '';
    // 主键的字段
    protected $key = 'id';
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
    // 是否已设定当前sql表
    protected $from = false;
    // 主动指定配置文件
    protected $connection = null;

    /**
     * 构造方法, 连接对象
     * @param object $DbConnection db连接对象 如 obj('Mysql',false);
     */
    final public function __construct( $DbConnection = null) {
        $this->db = $this->getDB();
        $this->collect = new \Main\Core\Model\Collect($this->options);
        $this->analysis = obj(\Main\Core\Model\Analysis::class);
        $this->get_thisTable();
        $this->construct();
    }
    
    /**
     * 获取数据库链接对象 $this->db
     * @return DbConnection
     */
    protected function getDB(): DbConnection {
        $conf = obj(Conf::class)->db;
        $this->connection = $this->connection ?? $conf['default'];
        $connectionConf = $conf['connections'][$this->connection];
        return self::$dbs[$this->connection] ?? self::$dbs[$this->connection] = dobj(DbConnection::class, $connectionConf);;
    }

    /**
     * 得到当前模型对应的数据表名
     */
    final protected function get_thisTable() {
        // 驼峰转下划线
        $uncamelize = function ($camelCaps, $separator = '_') {
            return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
        };
        $conf = obj(Conf::class);
        $this->tablepre = $conf->tablepre;
        $classname = get_class($this);
        $classname = substr($classname, strrpos($classname, '\\') + 1);
        if ($this->tablename === '')
            $this->tablename = strtr($classname, array('Model' => ''));
        if ($this->table === '')
            $this->table = $conf->tablepre . $uncamelize($this->tablename);
        $this->getTableInfo();
    }

    /**
     * 返回当前表名
     * @return string
     */
    public static function getTable() :string {
        return \obj(static::class)->table;
    }

    /**
     * 获取表字段信息, 填充主键
     */
    protected function getTableInfo() {
        // todo
        $this->field = obj(Cache::class)->get(function() {
            return $this->db->getAll('SHOW COLUMNS FROM `' . $this->table . '`');
        }, 3600);
        foreach ($this->field as $v) {
            if ($v['Extra'] == 'auto_increment') {
                $this->key = $v['Field'];
                break;
            }
        }
    }

    /**
     * 所有手动初始化建议在此执行
     */
    protected function construct() {
        
    }

    /**
     * 对象链式操作
     * @param string $func
     * @param array $pars
     * @return \Main\Core\Model
     */
    final public function __call(string $func, array $pars = array()): Model {
        if (method_exists($this->collect, $func)) {
            call_user_func_array(array($this->collect, $func), $pars);
            return $this;
        }
    }

    /**
     * 静态链式操作
     * @param string $func
     * @param array $pars
     * @return \Main\Core\Model
     */
    final public static function __callStatic(string $func, array $pars = array()): Model {
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
    public function prepare($onlyOnce = false, $pars = array()) {
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
    protected function rememberSql($sql, $pars) {
        $pars = is_array($pars) ? $pars : [];
        foreach ($pars as $k => $v) {
            $pars[$k] = '\'' . $v . '\'';
        }
        $this->lastSql = strtr($sql, $pars);
    }

    /**
     * 填充当前操作表, 填充查询字段, 填充更新时间, , 填充新增时间
     */
    protected function get_ready() {
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
    }

    /**
     * 重置链式操作
     */
    protected function reset() {
        // 链式操作集合
        $this->options = array();
        // 链式操作 类型 select update delete insert
        $this->options_type = null;
    }

    /**
     * 参数绑定, 并执行
     * @param array $pars
     */
    public function execute(array $pars = array()) {
        $this->PDOStatement->execute($pars);
        return $this->PDOStatement->fetchall(\PDO::FETCH_ASSOC);
    }

    /**
     * 查询一行
     * @param array $pars 参数绑定数组
     * @return array    一维数组
     */
    public function getRow(array $pars = array()): array {
        $this->options_type = 'SELECT';
        $this->collect->limit(1);
        $sql = $this->prepare(true, $pars);
        return $this->db->getRow($sql, $pars);
    }

    /**
     * 查询多行
     * @param array $pars 参数绑定数组
     * @return array    二维数组
     */
    public function getAll(array $pars = array()): array  {
        $this->options_type = 'SELECT';
        $sql = $this->prepare(true, $pars);
        return $this->db->getAll($sql, $pars);
    }

    /**
     * 更新数据, 返回受影响的行数
     * @param array $pars 参数绑定数组
     * @return int 受影响的行数
     * @throws Exception
     */
    public function update(array $pars = array()): int {
        $this->options_type = 'UPDATE';
        if (!isset($this->options['data']))
            throw new Exception('要执行UPDATE操作, 需要使用data方法设置更新的值');
        $sql = $this->prepare(true, $pars);
        return $this->db->update($sql, $pars);
    }

    /**
     * 插入数据, 返回插入的主键
     * @param array $pars 参数绑定数组
     * @return int 插入的主键
     * @throws Exception
     */
    public function insertGetId(array $pars = array()): int {
        $this->options_type = 'INSERT';
        if (!isset($this->options['data']))
            throw new Exception('要执行INSERT操作, 需要使用data方法设置新增的值');
        $sql = $this->prepare(true, $pars);
        return $this->db->insertGetId($sql, $pars);
    }

    /**
     * 插入数据
     * @param array $pars 参数绑定数组
     * @return bool
     * @throws Exception
     */
    public function insert(array $pars = array()): bool {
        $this->options_type = 'INSERT';
        if (!isset($this->options['data']))
            throw new Exception('要执行INSERT操作, 需要使用data方法设置新增的值');
        $sql = $this->prepare(true, $pars);
        return $this->db->insert($sql, $pars);
    }

    /**
     * 删除数据, 返回受影响的行数
     * @param array $pars 参数绑定数组
     * @return int 受影响的行数
     * @throws Exception
     */
    public function delete(array $pars = array()): int {
        $this->options_type = 'DELETE';
        if (!isset($this->options['where']))
            throw new Exception('执行 DELETE 操作并没有相应的 where 约束, 请确保操作正确, 使用where(1)将强制执行.');
        $sql = $this->prepare(true, $pars);
        return $this->db->update($sql, $pars);
    }

    /**
     * 插入or更新数据, 返回受影响的行数
     * @param array $pars 参数绑定数组
     * @return int 受影响的行数
     * @throws Exception
     */
    public function replace(array $pars = array()): int {
        $this->options_type = 'REPLACE';
        if (!isset($this->options['data']))
            throw new Exception('要执行REPLACE操作, 需要使用data方法设置新增or修改的值');
        $sql = $this->prepare(true, $pars);
        return $this->db->update($sql, $pars);
    }

    public function __get($attr) {
        if ($attr === 'db')
            return $this->db;
        else throw new \Main\Core\Exception;
    }
}
