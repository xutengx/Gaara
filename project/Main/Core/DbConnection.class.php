<?php
namespace Main\Core;
defined('IN_SYS')||exit('ACC Denied');
/**
 * 数据库连接类，依赖 PDO_MYSQL 扩展
 */
class DbConnection{
    // 是否主从分离数据库
    static $Master_slave = true;
    // 数据库 读 连接集合
    static $dbRead            = [];
    // 数据库 读 权重
    static $dbReadWeight      = [];
    // 数据库 写 连接集合
    static $dbWrite           = [];
    // 数据库 写 权重
    static $dbWriteWeight     = [];
    // 当前操作类型 SELECT UPDATE DELETE INSERT
    private $type = 'SELECT';
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
                'tablepre'=>'hk_',
                'keytable'=>'user',
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
                'tablepre'=>'hk_',
                'keytable'=>'user',
                'db'=>'hk'
            ],
            [
                'weight'=>10,
                'type'=>'mysql',
                'host'=>'10.4.17.219',
                'port'=>3306,
                'user'=>'root',
                'pwd'=>'Huawei$123#_',
                'tablepre'=>'hk_',
                'keytable'=>'user',
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
     * @param string $tablepre      // 表前缀
     * @param string $keytable      // 核对表(用于数据库自动创建)
     * @param string $db            // 数据库
     */
    public function __construct(array $DBconf, $single = true){
        $this->single = $single;
        foreach($DBconf['write'] as $k=>$v){
            self::$dbWrite[serialize($v)] = $v;
            if(empty(end(self::$dbWriteWeight)))
                self::$dbWriteWeight[$v['weight']] = serialize($v);
            else {
                $weight = array_keys(self::$dbWriteWeight);
                self::$dbWriteWeight[$v['weight']+end($weight)] = serialize($v);
            }
        }
        if(isset($DBconf['read'])){
            foreach($DBconf['read'] as $k=>$v){
                self::$dbRead[serialize($v)] = $v;
                if(empty(end(self::$dbReadWeight)))
                    self::$dbReadWeight[$v['weight']] = serialize($v);
                else {
                    $weight = array_keys(self::$dbReadWeight);
                    self::$dbReadWeight[$v['weight']+end($weight)] = serialize($v);
                }
            }
        }else self::$Master_slave = false;
    }

    /**
     * 单进程单链接实现
     * @return object PDO
     */
    private function &PDO(){
        if($this->single){
            if($this->type === 'SELECT'){
                if(is_object(self::$dbReadSingle) || (self::$dbReadSingle = &$this->connect()))
                    return self::$dbReadSingle;
            }
            elseif(is_object(self::$dbWriteSingle) || (self::$dbWriteSingle = &$this->connect()))
                return self::$dbWriteSingle;
        }else return $this->connect();
    }

    /**
     * 由操作类型 和 权重 选择数据库连接
     * 创建 PDO 实例
     * @return object PDO
     */
    private function &connect(){
        if($this->type === 'SELECT' && self::$Master_slave){
            $tmp = array_keys(self::$dbReadWeight);
            $weight = rand(1,end($tmp));
            foreach(self::$dbReadWeight as $k=>$v){
                if($k-$weight >= 0){
                    $key = $v;
                    break;
                }
            }
            if( !is_object(self::$dbRead[$key]) ){
                $settings = self::$dbRead[$key];
                $dsn = 'mysql:dbname='.$settings['db'].';host='.$settings['host'].';port='.$settings['port'];
                self::$dbRead[$key] = new \PDO($dsn, $settings['user'], $settings['pwd'], array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES '.(!empty($settings['char']) ? $settings['char'] : 'utf8')));
                self::$dbRead[$key]->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                self::$dbRead[$key]->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
            }
            return self::$dbRead[$key];
        }else{
            $tmp = array_keys(self::$dbWriteWeight);
            $weight = rand(1,end($tmp));
            foreach(self::$dbWriteWeight as $k=>$v){
                if($k-$weight >= 0){
                    $key = $v;
                    break;
                }
            }
            if( !is_object(self::$dbWrite[$key]) ){
                $settings = self::$dbWrite[$key];
                $dsn = 'mysql:dbname='.$settings['db'].';host='.$settings['host'].';port='.$settings['port'];
                self::$dbWrite[$key] = new \PDO($dsn, $settings['user'], $settings['pwd'], array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES '.(!empty($settings['char']) ? $settings['char'] : 'utf8')));
                self::$dbWrite[$key]->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                self::$dbWrite[$key]->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
            }
            return self::$dbWrite[$key];
        }
    }

    /**
     * 内部执行, 返回原始数据对象
     * @param string $sql
     * @param array  $pars
     *
     * @return mixed
     */
    private function query_prepare_execute($sql='',array $pars=[]){
        $PDO = $this->PDO();
        try{
            if(empty($pars))
                $res = $PDO->query($sql);
            else{
                $res = $PDO->prepare($sql);
                $res->execute($pars);
            }
        }catch(\PDOException $e){
            echo "有错误！有错误！";
//            print_r($res->errorInfo());
            $error = 'wwwww';
//            obj('\Main\Core\Log')->write($sql."\r\n".$error);
            if(DEBUG) echo ('query error 已经记录 :</br>'.$sql."</br>".$error."</br>");
            exit;
        }
        return $res;
    }
    public function getAll($sql='', array $pars=[]){
        $this->type = 'SELECT';
        return $this->query_prepare_execute($sql, $pars)->fetchall(\PDO::FETCH_ASSOC);
    }
    public function getRow($sql='', array $pars=[]){
        $this->type = 'SELECT';
        $re = $this->query_prepare_execute($sql, $pars)->fetch(\PDO::FETCH_ASSOC);
        return $re ? $re : [];
    }

    /**
     * @param string $sql
     * @param array  $pars
     *
     * @return 1|0
     */
    public function update($sql='', array $pars=[]) {
        $this->type = 'UPDATE';
        $PDO = $this->PDO();
        if(empty($pars))
            $res = $PDO->exec($sql);
        else{
            $res = $PDO->prepare($sql);
            $res->execute($pars);
            $res = $res->rowCount();
        }
        return $res;
    }
    public function insert($sql='', array $pars=[]){
        $this->type = 'INSERT';
        $PDO = $this->PDO();
        if(empty($pars))
            $PDO->exec($sql);
        else
            $PDO->prepare($sql)->execute($pars);
        $res = $PDO->lastinsertid();
        return $res;
    }
    public function count($sql=''){
        $this->type = 'SELECT';
        return $this->PDO()->query($sql)->fetchColumn();
    }

    public function prepare($sql='', $type='UPTATE'){
        $this->type = $type;
        return $this->PDO()->prepare($sql);
    }

    /**
     * 关闭连接
     */
//    public function closeConnection(){
//        $this->pdo = NULL;
//    }

}
