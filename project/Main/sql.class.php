<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/12/21 0021
 * Time: 17:13
 */
namespace Main;
defined('IN_SYS')||exit('ACC Denied');
class sql{
    private static $ins = null;
    private $conn       = null;
    public  $conf       = array();
    public  $tablepre   = null;

    final private function __construct(){
        $this->conf = conf::getins();
        $this->connect($this->conf->host, $this->conf->user, $this->conf->pwd);
        $this->setchar($this->conf->char);
        $this->select_db($this->conf->db);
        $this->tablepre = $this->conf->tablepre;
    }
    private function connect ($h,$u,$p){
        $this->conn = mysqli_connect($h,$u,$p);
        if(!$this->conn) throw new Exception('连接db失败');
    }
    final private function __clone(){
        exit();
    }
    public static function getins(){
        if((self::$ins instanceof self) || (self::$ins = new self())) return self::$ins;
    }
    private function creatDB(){
        $arr = explode(";", trim($this->conf->sql));
        if($arr[count($arr) - 1] == '') unset($arr[count($arr) - 1]);
        $this->query('use '.$this->conf->db);
        foreach ($arr as $k=>$v) {
            $this->query($v);
        }
        return true;
    }
    private function select_db($db){
        $sql = 'use  '.$db.';';
        $this->query($sql);
        $this->check_db();
    }
    private function check_db(){
        $tables = $this->conf->keytable;
        $sql = 'show tables like "'.$tables.'"';
        $this->query($sql);
        if(!mysqli_affected_rows($this->conn)) $this->creatDB();
    }
    private function setchar($char){
        $sql = 'set names'.' '.$char;
        return $this->query($sql);
    }
    public function query($sql){
        $rs = mysqli_query($this->conn, $sql);
        try{
            if(!$rs) throw new Exception();
        }
        catch(Exception $e){
            $error = mysqli_error($this->conn);
            log::write($sql."\r\n".$error);
            if(ini_get('display_errors')) echo ('query error 已经记录 :</br>'.$sql."</br>".$error."</br>");
        }
        return $rs;
    }
    // 执行无返回sql.如update.return 受影响的行数
    public function execute($sql) {
        $this->query($sql);
        return mysqli_affected_rows($this->conn);
    }
    // return int 上次sql影响的一个主键
    public function lastInsertId($sql){
        if($this->execute($sql)){
            $sql = 'select LAST_INSERT_ID() ';
            $re  = $this->getRow($sql);
            return $re['LAST_INSERT_ID()'];
        }return false;
    }
    public function getAll($sql){
        $rs = $this->query($sql);
        $list = array();
        while($row = mysqli_fetch_assoc($rs)){
            $list[]=$row;
        }
        return $list;
    }
    public function getRow($sql) {
        $res = $this->query($sql . " LIMIT 1");
        $rows = mysqli_fetch_array($res, MYSQLI_ASSOC);
        return $rows === false ? false : $rows;
    }
    // 拆分大的 DELETE 或 INSERT 语句
    public function bigQuery($sql){
        while(1){
            mysqli_query($this->conn, $sql.' LIMIT 1000');
            if (mysqli_affected_rows() == 0) {
                break;
            }
            usleep(50000);
        }
    }
}