<?php

declare(strict_types = 1);
namespace Main\Core;

use PDOException;
use PDOStatement;
use Main\Core\Exception\Pdo;
use \Log;

/**
 * 数据库连接类，依赖 PDO_MYSQL 扩展
 */
class DbConnection {

    // 是否主从数据库
    static $Master_slave = true;
    // 数据库 读 连接集合
    static $dbRead = array();
    // 数据库 读 权重
    static $dbReadWeight = array();
    // 数据库 写 连接集合
    static $dbWrite = array();
    // 数据库 写 权重
    static $dbWriteWeight = array();
    // 当前操作类型 select update delate insert
    private $type = 'select';
    // 是否事务过程中 不进行数据库更换
    private $transaction = false;
    // ---------------------------- 单进程 ----------------------------- //
    // 单进程不进行数据库更换
    private $single = true;
    // 当前数据库 读 连接
    static $dbReadSingle;
    // 当前数据库 写 连接
    static $dbWriteSingle;

    // ------------------------------------------------------------------//

    /**
     * 读取配置信息
     * @param array $DBconf
     * 如下结构:
      'db'=>[
      'write'=>[
      [
      'weight'=>10,
      'type'=>'mysql',
      'host'=>'10.4.17.200',
      'port'=>3306,
      'user'=>'root',
      'pwd'=>'Huawei$123#_',
      'db'=>'hk'
      ]
      ],
      'read'=>[
      [
      'weight'=>10,
      'type'=>'mysql',
      'host'=>'10.4.17.218',
      'port'=>3306,
      'user'=>'root',
      'pwd'=>'Huawei$123#_',
      'db'=>'hk'
      ],
      [
      'weight'=>10,
      'type'=>'mysql',
      'host'=>'10.4.17.219',
      'port'=>3306,
      'user'=>'root',
      'pwd'=>'Huawei$123#_',
      'db'=>'hk'
      ]
      ]
      ],
     * @param int    $weight        // 权重
     * @param string $type          // 数据库类型
     * @param string $host          // 连接地址
     * @param int    $port          // 端口
     * @param string $user          // 用户名
     * @param string $pwd           // 密码
     * @param string $db            // 数据库
     */
    public function __construct(array $DBconf, $single = true) {
        $this->single = $single;
        foreach ($DBconf['write'] as $k => $v) {
            self::$dbWrite[md5(serialize($v))] = $v;
            $t = end(self::$dbWriteWeight);
            if (empty($t))
                self::$dbWriteWeight[$v['weight']] = md5(serialize($v));
            else {
                $weight = array_keys(self::$dbWriteWeight);
                self::$dbWriteWeight[$v['weight'] + end($weight)] = md5(serialize($v));
            }
        }
        if (isset($DBconf['read'])) {
            foreach ($DBconf['read'] as $k => $v) {
                self::$dbRead[md5(serialize($v))] = $v;
                $t = end(self::$dbReadWeight);
                if (empty($t))
                    self::$dbReadWeight[$v['weight']] = md5(serialize($v));
                else {
                    $weight = array_keys(self::$dbReadWeight);
                    self::$dbReadWeight[$v['weight'] + end($weight)] = md5(serialize($v));
                }
            }
        } else
            self::$Master_slave = false;
    }

    /**
     * 由操作类型(读/写), 返回已存在的PDO实现
     * @return PDO
     */
    private function PDO(): \PDO {
        // http请求都属于此
        if ($this->single) {
            // 查询操作且不属于事务,使用读连接
            if ($this->type === 'select' && !$this->transaction) {
                if (is_object(self::$dbReadSingle) || (self::$dbReadSingle = $this->connect()))
                    return self::$dbReadSingle;
            }
            // 写连接
            elseif (is_object(self::$dbWriteSingle) || (self::$dbWriteSingle = $this->connect()))
                return self::$dbWriteSingle;
        } else
            return $this->connect();
    }

    /**
     * 由操作类型(读/写)和权重(weight), 创建并返回PDO数据库连接
     * @return PDO
     */
    private function connect(): \PDO {
        // 查询操作且不属于事务,使用读连接
        if ($this->type === 'select' && !$this->transaction && self::$Master_slave) {
            $tmp = array_keys(self::$dbReadWeight);
            $weight = rand(1, end($tmp));
            foreach (self::$dbReadWeight as $k => $v) {
                if ($k - $weight >= 0) {
                    $key = $v;
                    break;
                }
            }
            if (!is_object(self::$dbRead[$key])) {
                $settings = self::$dbRead[$key];
                $dsn = 'mysql:dbname=' . $settings['db'] . ';host=' . $settings['host'] . ';port=' . $settings['port'];
                self::$dbRead[$key] = new \PDO($dsn, $settings['user'], $settings['pwd'], array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . (!empty($settings['char']) ? $settings['char'] : 'utf8')));
                self::$dbRead[$key]->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                self::$dbRead[$key]->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
            }
            return self::$dbRead[$key];
        } else {
            $tmp = array_keys(self::$dbWriteWeight);
            $weight = rand(1, end($tmp));
            foreach (self::$dbWriteWeight as $k => $v) {
                if ($k - $weight >= 0) {
                    $key = $v;
                    break;
                }
            }
            if (!is_object(self::$dbWrite[$key])) {
                $settings = self::$dbWrite[$key];
                $dsn = 'mysql:dbname=' . $settings['db'] . ';host=' . $settings['host'] . ';port=' . $settings['port'];
                self::$dbWrite[$key] = new \PDO($dsn, $settings['user'], $settings['pwd'], array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . (!empty($settings['char']) ? $settings['char'] : 'utf8')));
                self::$dbWrite[$key]->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                self::$dbWrite[$key]->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
            }
            return self::$dbWrite[$key];
        }
    }

    /**
     * 内部执行, 返回原始数据对象, 触发异常处理
     * @param string $sql
     * @param array $pars 参数绑定数组
     * @return PDOStatement or PDO
     * @throws PDOException
     */
    private function query_prepare_execute(string $sql = '', array $pars = array()) {
        $PDO = $this->PDO();
        $i = 0;
        loop :
        try {
            if (empty($pars)) {
                $res = $PDO->query($sql);
            } else {
                $res = $PDO->prepare($sql);
                $res->execute($pars);
            }
        } catch (PDOException $pdo) {
            Log::error($sql, [' sql error ', $pdo->getMessage()]);
            if ($i ++ >= 1) {
                throw $pdo;  // 抛出异常
            }
            new Pdo($pdo, $this);       // 尝试解决
            goto loop;
        }
        if ($this->type === 'insert')
            return $PDO;
        return $res;
    }
    
    /**
     * 返回PDOStatement, 可做分块解析
     * @param string $sql
     * @param array $pars
     * @return PDOStatement
     */
    public function getChunk(string $sql = '', array $pars = array()): PDOStatement {
        $this->type = 'select';
        return $this->query_prepare_execute($sql, $pars);
    }
    
    /**
     * 查询一行
     * @param type $sql
     * @param array $pars 参数绑定数组
     * @return array 一维数组
     */
    public function getRow(string $sql = '', array $pars = array()): array {
        $this->type = 'select';
        $re = $this->query_prepare_execute($sql, $pars)->fetch(\PDO::FETCH_ASSOC);
        return $re ? $re : [];
    }

    /**
     * 查询多行
     * @param type $sql
     * @param array $pars 参数绑定数组
     * @return array 二维数组
     */
    public function getAll($sql = '', array $pars = array()): array {
        $this->type = 'select';
        return $this->query_prepare_execute($sql, $pars)->fetchall(\PDO::FETCH_ASSOC);
    }

    /**
     * 更新数据, 返回受影响的行数
     * @param string $sql
     * @param array $pars 参数绑定数组
     * @return int 受影响的行数
     */
    public function update(string $sql = '', array $pars = array()): int {
        $this->type = 'update';
        $res = $this->query_prepare_execute($sql, $pars);
        if ($res)
            return $res->rowCount();
    }

    /**
     * 插入数据, 返回插入的主键
     * @param string $sql
     * @param array $pars 参数绑定数组
     * @return int 插入的主键
     */
    public function insertGetId(string $sql = '', array $pars = array()): int {
        $this->type = 'insert';
        $res = $this->query_prepare_execute($sql, $pars);
        if ($res)
            return $res->lastInsertId();
    }

    /**
     * 插入数据
     * @param string $sql
     * @param array $pars 参数绑定数组
     * @return bool
     */
    public function insert(string $sql = '', array $pars = array()): bool {
        $this->type = 'insert';
        $res = $this->query_prepare_execute($sql, $pars);
        return $res ? true : false;
    }

    /**
     * 使用PDO->prepare(), 返回的对象可用$res->execute($pars)重复调用
     * @param string $sql
     * @param string $type
     * @return type
     * @throws Exception
     */
    public function prepare(string $sql = '', string $type = 'update'): PDOStatement {
        if (!in_array($type, ['select', 'update', 'delete', 'insert', 'replace']))
            throw new Exception('$type mast in_array(select update delete insert replace). but '.$type.' given');
        $this->type = $type;
        return $this->PDO()->prepare($sql);
    }

    /**
     * 开启事务
     * @return bool
     * @throws \Exception
     */
    public function begin(): bool {
        if ($this->single !== true)
            throw new \Exception('非常不建议在单进程,多数据库切换模式下开启事务!');
        $this->transaction = true;
        $PDO = $this->PDO();
        return $PDO->beginTransaction();
    }

    /**
     * 提交事务
     * @return bool
     */
    public function commit(): bool {
        $this->transaction = false;
        $PDO = $this->PDO();
        return $PDO->commit();
    }

    /**
     * 是否在事务中
     * @return bool
     */
    public function inTransaction(): bool {
        return $this->transaction;
    }

    /**
     * 回滚事务
     * @return bool
     */
    public function rollBack(): bool {
        $this->transaction = false;
        $PDO = $this->PDO();
        return $PDO->rollBack();
    }

    /**
     * 关闭连接
     */
    public function close() {
        $this->pdo = NULL;
    }
    
    public function __call(string $method, array $parameters = []) {
        $this->type = 'update';
        return $this->PDO()->$method(...$parameters);
    }
}