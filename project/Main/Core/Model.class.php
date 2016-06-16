<?php
namespace Main\Core;
defined('IN_SYS')||exit('ACC Denied');
class Model{
    protected $db ;
    // 表名,不包含表前缀
    protected $tablename   = '';
    // 主键的字段
    protected $key          = 'id';
    // 表名
    protected $table        = '';
    // 表字段6
    protected $attribute = [];
    // 链式操作集合
    protected $options = [];
    /**
     * @param object $DbConnection db连接对象 如 obj('Mysql',false);
     */
    final public function __construct($DbConnection = null){
//        self::$dbRead = obj('\Main\Core\DbConnection',true)
        $this->db = obj('\Main\Core\DbConnection',true, obj('conf')->db);
//        $re = $this->db->exec("UPDATE `hk`.`hk_user` SET `id`='14', `account`='d23652369', `passwd`='123123', `name`='22', `sex`='1', `img`='', `sign`='', `tel`='0', `email`='', `idcard`='', `address`='', `status`='1', `timeCreate`='2016-06-06 11:28:08', `timeLogin`='2016-06-06 11:28:09', `ipLogin`='127.0.0.1', `level`='0' WHERE (`id`=?)",[14]);
//        $re = $this->db->insert("INSERT INTO `hk`.`hk_user` (`id`, `account`, `passwd`, `name`, `sex`, `img`, `sign`, `tel`, `email`, `idcard`, `address`, `status`, `timeCreate`, `timeLogin`, `ipLogin`, `level`) VALUES ('16', 'f23652369', '123123', '', '1', '', '', '0', '', '', '', '1', '2016-06-06 11:28:08', '2016-06-06 11:28:09', '127.0.0.1', ?)",[0]);
//        var_dump($re );
//        exit;
        $this->get_thisTable();
        $this->construct();
    }
    protected function construct(){}
    final protected function get_thisTable(){
        $conf = obj('conf');
        $classname = get_class($this);
        $classname = substr($classname,strrpos($classname,'\\')+1);
        if($this->tablename == '') $this->tablename=strtr($classname, array('Model'=>''));
        $this->table = $conf->tablepre.$this->tablename;
    }
    public function tbname(){
        return $this->table;
    }
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
    public function modifyData($cols){
        $fileds = '';
        $values = '';
        foreach ($cols as $f => $v) {
            $fileds .= $fileds ? ",{$f}" : $f;
            $values .= $values ? ",'{$v}'" : "'{$v}'";
        }
        $sql = 'REPLACE INTO '.$this->table." ({$fileds}) VALUES ({$values})";
        return $this->db->execute($sql);
    }

    public function selAll($where = false){
        $where = $where ? ' where '.$where : '';
        $sql = 'select * from '.$this->table.' '.$where;
        return $this->db->getAll($sql);
    }

    public function selRow($where = false){
        $where = is_numeric($where) ? ' where `'.$this->key.'`="'.$where.'" ': ($where ? ' where '.$where : '') ;
        $sql = 'select * from '.$this->table.' '.$where;
        return $this->db->getRow($sql);
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
//---------------------------------------------------------- 链式操作 -----------------------------------------------------//

    public function where($where){
        if(is_string($where) && $where !== ''){
            $this->options['where']['__string'][] = $where ;
        }elseif(is_array($where) && !empty($where)){
            foreach($where as $k=>$v){
//                if (is_array($v) && !empty($v)) {
//                    $this->options['where'][$k] = array_merge(isset($this->options['where'][$k]) ? $this->options['where'][$k] :[], $v);
//                }elseif(is_string($v)) {
                    $this->options['where'][$k][] = $v;
//                }
            }
        }
        return $this;
    }

    final public function __call($fun, $par=null){
        if(method_exists($this->db, $fun) && ($par !== null))
            return call_user_func_array([$this->db, $fun], $par);
        else throw new \Main\Core\Exception('方法没定义!');
    }

}