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
    static $dbRead            = array();
    // 数据库 读 权重
    static $dbReadWeight      = array();
    // 数据库 写 连接集合
    static $dbWrite           = array();
    // 数据库 写 权重
    static $dbWriteWeight     = array();
    // 当前操作类型 SELECT UPDATE DELETE INSERT
    private $type = 'SELECT';
    // 是否事务过程中 不进行数据库更换
//    private $transaction = false;
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
    public function __construct(array $DBconf, $single = true){
        $this->single = $single;
        foreach($DBconf['write'] as $k=>$v){
            self::$dbWrite[serialize($v)] = $v;
            $t = end(self::$dbWriteWeight);
            if(empty($t))
                self::$dbWriteWeight[$v['weight']] = serialize($v);
            else {
                $weight = array_keys(self::$dbWriteWeight);
                self::$dbWriteWeight[$v['weight']+end($weight)] = serialize($v);
            }
        }
        if(isset($DBconf['read'])){
            foreach($DBconf['read'] as $k=>$v){
                self::$dbRead[serialize($v)] = $v;
                $t = end(self::$dbReadWeight);
                if(empty($t))
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
     * 自动建表
     */
    private function creatDB(){
        $arr = explode(';', trim(obj('conf')->getCreateDb()));
        if($arr[count($arr) - 1] == '') unset($arr[count($arr) - 1]);
        $PDO = $this->PDO();
        foreach ($arr as $k=>$v) {
            $PDO->query($v);
        }
        return true;
    }
    /**
     * 内部执行, 返回原始数据对象
     * @param string $sql
     * @param array  $pars
     *
     * @return mixed
     */
    private function query_prepare_execute($sql='',array $pars=array()){
        $PDO = $this->PDO();
        $i = 0;
        try{
            loop :
            if(empty($pars))
                $res = $PDO->query($sql);
            else{
                $res = $PDO->prepare($sql);
                $res->execute($pars);
            }
        }catch(\PDOException $e){
            if($e->errorInfo[0] === '42S02' && $e->errorInfo[1] === 1146){
                if($i ++ === 1) exit('自动化建表语句有误,请核对');
                $this->creatDB();
                goto loop;
            }else {
                $er = 'query error 已经记录 :</br>'.$sql."</br>".$e->errorInfo[2]."</br>";
                obj('\Main\Core\Log')->write($sql."\r\n".$e->errorInfo[2]);
                if(DEBUG)
                    echo $er;
            }
            exit;
        }
        if($this->type === 'INSERT')
            return $PDO;
        return $res;
    }
    public function getAll($sql='', array $pars=array()){
        $this->type = 'SELECT';
        return $this->query_prepare_execute($sql, $pars)->fetchall(\PDO::FETCH_ASSOC);
    }
    public function getRow($sql='', array $pars=array()){
        $this->type = 'SELECT';
        $re = $this->query_prepare_execute($sql, $pars)->fetch(\PDO::FETCH_ASSOC);
        return $re ? $re : array();
    }

    /**
     * @param string $sql
     * @param array  $pars
     *
     * @return 1|0
     */
    public function update($sql='', array $pars=array()) {
        $this->type = 'UPDATE';
        $res = $this->query_prepare_execute($sql, $pars);
        if($res)
            return $res->rowCount();
    }
    public function execute($sql='', array $pars=array()){
        return $this->update($sql, $pars);
    }

    public function insert($sql='', array $pars=array()){
        $this->type = 'INSERT';
        $res = $this->query_prepare_execute($sql, $pars);
        if($res)
            return $res->lastInsertId();
    }
    public function count($sql=''){
        $this->type = 'SELECT';
        return $this->PDO()->query($sql)->fetchColumn();
    }

    public function prepare($sql='', $type='UPTATE'){
        $this->type = $type;
        return $this->PDO()->prepare($sql);
    }
    public function begin(){
        if($this->single !== true)
            throw new \Exception('非常不建议在单进程,多数据库切换模式下开启事务!');
        $PDO = $this->PDO();
        $PDO->beginTransaction();
    }
    public function commit(){
        $PDO = $this->PDO();
        $PDO->commit();
    }
    public function rollBack(){
        $PDO = $this->PDO();
        $PDO->rollBack();
    }

    /**
     * 关闭连接
     */
//    public function closeConnection(){
//        $this->pdo = NULL;
//    }

}
