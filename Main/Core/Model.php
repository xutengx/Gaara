<?php

declare(strict_types = 1);
namespace Main\Core;

use Main\Core\Model\QueryBuiler;
use Main\Core\Model\Traits;
use PDOStatement;

class Model {

    use Traits\DebugTrait;
    use Traits\ObjectRelationalMappingTrait;
    use Traits\Transaction;
    // 所有数据库连接类
    protected static $dbs;
    // 当前model的数据库连接类
    protected $db;
    // 主键的字段
    protected $primaryKey = 'id';
    // 表名
    protected $table = '';
    // 表信息
    protected $field = array();
    // 链式操作集合
    protected $options = array();
    // 链式操作 sql
    protected $lastSql = '';
    // PDOStatement
    protected $PDOStatement = null;
    // 主动指定配置文件
    protected $connection = null;

    /**
     * 构造方法, 连接对象
     * @param object $DbConnection db连接对象 如 obj('Mysql',false);
     */
    final public function __construct() {
        $this->db = $this->getDB();
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
    final protected function get_thisTable(): void {
        // 驼峰转下划线
        $uncamelize = function ($camelCaps, $separator = '_') {
            return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
        };
        if ($this->table === ''){
            $classname = get_class($this);
            $classname = substr($classname, strrpos($classname, '\\') + 1);
            $this->table = $uncamelize(strtr($classname, array('Model' => '')));
        }
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
    protected function getTableInfo(): void {
        $this->field = obj(Cache::class)->get(function() {
            return $this->db->getAll('SHOW COLUMNS FROM `' . $this->table . '`');
        }, 3600);
        foreach ($this->field as $v) {
            if ($v['Extra'] === 'auto_increment') {
                $this->primaryKey = $v['Field'];
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
     * 返回一个查询构造器
     * @param string $table 绑定表名
     * @param string $primaryKey 主键名
     * @return QueryBuiler
     */
    public function newQuery(string $table = null, string $primaryKey = null): QueryBuiler {
        $queryTable = $table ?? $this->table;
        $queryPrimaryKey = $primaryKey ?? $this->primaryKey;
        return new QueryBuiler($queryTable, $queryPrimaryKey, $this->db, $this);
    }

    /**
     * 对象链式操作
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call(string $method, array $parameters = []) {
        return $this->newQuery()->$method(...$parameters);
        
    }

    /**
     * 静态链式操作
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    final public static function __callStatic(string $method, array $parameters = []) {
        return obj(static::class)->newQuery()->$method(...$parameters);
    }
    
    /**
     * 原生sql支持, 普通执行
     * @param string $sql
     * @param string $type 使用的数据库链接类型
     * @return PDOStatement
     */
    public function query(string $sql, string $type = 'update'): PDOStatement{
        $PDOStatement = $this->db->prepare($sql, $type);
        $PDOStatement->execute();
        return $PDOStatement;
    }
    
    /**
     * 原生sql支持, 返回`PDOStatement`对象可用PDOStatement::execute($pars)重复调用
     * @param string $sql
     * @param string $type 使用的数据库链接类型
     * @return PDOStatement
     */
    public function prepare(string $sql, string $type = 'update'): PDOStatement{
        return $this->db->prepare($sql, $type);
    }

}
