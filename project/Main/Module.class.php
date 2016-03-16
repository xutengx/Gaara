<?php
namespace Main;
defined('IN_SYS')||exit('ACC Denied');
class Module extends Base{
    protected static $ins   = null;
    // 引入sql类
    protected $db           = null;
    // 表名,不包含表前缀
    protected $tablename    = '';
    // 主键的字段
    protected $key          = 'id';
    // 表名
    protected $table        = '';
    // 链式操作
    protected $action       = array();

    final protected function __construct(){
        $this->db   = sql::getins();
        $this->get_thisTable();
    }
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
    public static function getins(){
        if(static::$ins instanceof static || (static::$ins = new static)) return static::$ins;
    }
    // 以数组形式 insert 一条数据
    // return 新数据的主键
    public function insertByArray($cols, $addslashes=true) {
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
    public function updateByArray($cols, $addslashes=true) {
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
            if($this->db->getRow($sql)) return $this->updateByArray($data);
        }return $this->insertByArray($data);
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
        if(is_array($wechatinfo)) {
            $openid = $wechatinfo['openid'];
            $sql = 'insert into '.$this->table.' (`name`,img,sex,openid) values ("'.$wechatinfo['nickname'].'","'.$wechatinfo['headimgurl'].'","'.$wechatinfo['sex'].'","'.$openid.'")';
        }
        else $sql = 'insert into '.$this->table.' (openid) values ("'.$wechatinfo.'")';
        $openid = $openid ? $openid : $wechatinfo;
        if($this->db->execute($sql)) return $openid;
        return false;
    }
    final public function __call($fun, $par=null){
        if(!method_exists($this->db, $fun)) throw new \Main\Exception('方法没定义!');
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
}