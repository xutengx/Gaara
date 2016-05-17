<?php
namespace Main\Core;
defined('IN_SYS')||exit('ACC Denied');
class Model{
    // 引入sql类
    public $db           = null;
    // 表名,不包含表前缀
    protected $tablename    = '';
    // 主键的字段
    protected $key          = 'id';
    // 表名
    protected $table        = '';
    // 表字段
    protected $attribute = array();

    final public function __construct(){
        $this->db   = obj('Mysql');
        $this->get_thisTable();
        $this->construct();
    }
    protected function construct(){}
    final protected function get_thisTable(){
        $classname = get_class($this);
        $classname = substr($classname,strrpos($classname,'\\')+1);
        if($this->tablename == '') $this->tablename=strtr($classname, array('Module'=>''));
        $this->table = $this->db->tablepre.$this->tablename;
    }
    public function tbname(){
        return $this->table;
    }
    final protected function __clone(){exit;}
    // 以数组形式 insert 一条数据
    // return 新数据的主键
    public function insertData($cols, $addslashes=true) {
        $fileds = '';
        $values = '';
        foreach ($cols as $f => $v) {
            $fileds .= $fileds ? ",{$f}" : $f;
            if ($addslashes) $v = addslashes($v);
            $values .= $values ? ",'{$v}'" : "'{$v}'";
        }
        $sql = 'INSERT INTO '.$this->table." ({$fileds}) VALUES ({$values})";
        return $this->db->lastInsertId($sql);
    }
    //以数组形式 update 一条数据,条件为 表主键
    public function updateData($cols, $addslashes=true) {
        $fileds = '';
        foreach ($cols as $f => $v) {
            if ($addslashes) $v = addslashes($v);
            $fileds .= $fileds ? ",{$f}='{$v}'" : "{$f}='{$v}'";
        }
        $sql = 'UPDATE '.$this->table." SET $fileds WHERE ".$this->key.'='.$cols[$this->key];
        return $this->db->execute($sql);
    }
    // 以主键是否存在,对一条数据进行 insert or update
    public function modifyData($data){
        if(isset($data[$this->key])) {
            $sql = 'select '.$this->key.' from '.$this->table.' where '.$this->key.'="'.$data[$this->key].'"';
            if($this->db->getRow($sql)) return $this->updateData($data);
        }return $this->insertData($data);
    }

    public function selAll($where = false){
        $where = $where ? ' where '.$where : '';
        $sql = 'select * from '.$this->table.' '.$where;
        return $this->getAll($sql);
    }

    public function selRow($where = false){
        $where = is_numeric($where) ? ' where `'.$this->key.'`="'.$where.'" ': ($where ? ' where '.$where : '') ;
        $sql = 'select * from '.$this->table.' '.$where;
        return $this->getRow($sql);
    }
    // 核对此openid是否已经记录
    // param 包含openid的一维数组 or openid
    // return string or false
    final public function main_checkUser($wechatinfo){
        if(is_array($wechatinfo)) $openid = $wechatinfo['openid'];
        else $openid = $wechatinfo;
        $sql    = 'select openid from '.$this->table.' where openid="'.$openid.'"';
        $re     = $this->db->getRow($sql);
        return $re['openid'] ? $re['openid'] : false;
    }
    // 建立新的openid记录
    // param 包含openid的一维数组 or openid
    // return string or false
    final public function main_newUser($wechatinfo){
        if(!$wechatinfo) return false;
        if(is_array($wechatinfo)) {
            $openid = $wechatinfo['openid'];
            $sql = 'insert into '.$this->table.' (`name`,`img`,`sex`,`openid`) values ("'.$wechatinfo['nickname'].'","'.$wechatinfo['headimgurl'].'","'.$wechatinfo['sex'].'","'.$openid.'")';
        }
        else $sql = 'insert into '.$this->table.' (`openid`) values ("'.$wechatinfo.'")';
        $openid = $openid ? $openid : $wechatinfo;
        if($this->db->execute($sql)) return $openid;
        return false;
    }
    final public function __call($fun, $par=null){
        if(method_exists($this->db, $fun)) {
            if($par !== null){
                $parstr ='' ;
                $par = array_values($par);
                for($i = 0 ; $i < count($par) ; $i++){
                    $parstr .= ',$par['.$i.']';
                }
                $parstr = ltrim($parstr, ',');
                $code = 'return $this->db->{$fun}('.$parstr.');';
                return eval($code);
            }
        }
        else throw new \Main\Core\Exception('方法没定义!');
    }
}