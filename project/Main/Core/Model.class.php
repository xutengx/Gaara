<?php
namespace Main\Core;
defined('IN_SYS')||exit('ACC Denied');
class Model{
    protected $db ;
    // 表名,不包含表前缀
    protected $tablename   = '';
    // 表名,不包含表前缀
    protected $tablepre   = '';
    // 主键的字段
    protected $key          = 'id';
    // 表名
    protected $table        = '';
    // 表字段6
    protected $attribute = [];
    // 链式操作集合
    protected $options = [];
    // 链式操作 sql
    protected $options_sql = [];
    // 链式操作集合
    protected $options_analysis = [];
    // 链式操作 类型 select update delete insert
    protected $options_type = null;
    // PDOStatement
    protected $PDOStatement = null;
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
        $this->tablepre = $conf->tablepre;
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
    /**
     * sql条件
     * @param $where
     *
     * @return $this
     */
    public function where($where){
        if(is_string($where) && $where !== ''){
            $this->options['where']['__string'][] = $where ;
        }elseif(is_array($where) && !empty($where)){
            foreach($where as $k=>$v){
                $this->options['where'][$k][] = $v;
            }
        }
        return $this;
    }

    /**
     * 查询字段筛选
     * @param  String|一维数组 $what
     *
     * @return $this
     */
    public function select($what){
        if(is_string($what) && $what !== ''){
            $this->options['select']['__string'][] = $what ;
        }elseif(is_array($what) && !empty($what)){
            $this->options['select'] = array_merge(isset($this->options['select']) ? $this->options['select'] : [] , $what);
        }
        return $this;
    }

    /**
     * 设置数据表
     * @param string     $table 表名
     * @param bool|false $withTablepre 是否已包含表前缀
     *
     * @return $this
     */
    public function from($table='', $withTablepre=false){
        if(is_string($table) && $table !== ''){
            $table = trim($table,' ');
            $this->options['from'] = ( $withTablepre ? '': $this->tablepre ) .$table ;
        }
        return $this;
    }

    /**
     * 连接
     * @param string $str
     * @return $this
     */
    public function join($str=''){
        if(is_string($str) && $str !== ''){
            $str = trim($str,' ');
            if(!stristr($str,'join'))
                $str = 'INNER JOIN '.$str;
            $this->options['join'][] = $str;
        }
        return $this;
    }

    /**
     * 准备sql
     * @param bool|true $onlyOnce 是否执行一次后销毁(一般情况下如此)
     *
     * @return $this
     */

    public function prepare($onlyOnce = false){
        // 填充
        if(!isset($this->options['from']))
            $this->from($this->table, true);

        // 解析$this->options 生成sql子句
        foreach($this->options as $k => $v){
            $funcName = 'analysis_'.$k;
            $this->$funcName($v);
        }
        switch($this->options_type){
            case 'SELECT':
                $sql = 'SELECT '.( isset($this->options_sql['select']) ? $this->options_sql['select'] : '*').' '
                    . ( isset($this->options_sql['from']) ? $this->options_sql['from'] : $this->table).
                    ( isset($this->options_sql['where']) ? ' WHERE '. $this->options_sql['where'] : '');

                break;
        }
        $this->reset();
        if($onlyOnce)
            return $sql;
        else {
            $this->PDOStatement = $this->db->prepare($sql, $this->options_type);
            return $this;
        }
    }

    /**
     * 重置链式操作
     */
    protected function reset(){
        // 链式操作集合
        $this->options = [];
        // 链式操作 sql
        $this->options_sql = [];
        // 链式操作集合
        $this->options_analysis = [];
        // 链式操作 类型 select update delete insert
        $this->options_type = null;
    }

    /**
     * 参数绑定, 并执行
     * @param array $pars
     */
    public function execute($pars = []){
        $this->PDOStatement->execute($pars);
        return $this->PDOStatement->fetchall(\PDO::FETCH_ASSOC);
    }

    public function getRow($pars = []){
        $this->options_type = 'SELECT';
        $sql = $this->prepare(true);
        if($pars === false) return $sql;
        elseif($pars === true) exit($sql);
        return $this->db->getRow($sql, $pars);
    }


    public function getAll($pars = []){
        $this->options_type = 'SELECT';
        $sql = $this->prepare(true);
        if($pars === false) return $sql;
        elseif($pars === true) exit($sql);
        return $this->db->getAll($sql, $pars);
    }

    /**
     * 解析 $this->options['where']
     */
    protected function analysis_where(array $arr){
        $str = '';
        $temp = '';
        foreach($arr as $k=>$v){
            if($k === '__string'){
                foreach($v as $kk=>$vv)
                    $str .= $vv.' AND ';
            }else foreach( $v as $kk => $vv ){
                if(is_array($vv)){
                    if(stristr($vv[0],'between')){
                        $temp .= '`'.$k.'` '.$vv[0].' '.$this->filterPars($vv[1]).' AND '.$this->filterPars($vv[2]).' AND ';
                    }elseif(stristr($vv[0],'in')){
                        if(is_array($vv[1])){
                            $in = '(';
                            foreach ($vv[1] as $kkk => $vvv){
                                $in .= $this->filterPars($vvv).',';
                            }
                            $in = rtrim($in,','). ')';
                            $temp .= '`'.$k.'` '.$vv[0].' '.$in.' AND ';
                        }else{
                            if(strstr($vv[1],',')){
                                $arr = explode(',', $vv[1]);
                                $in = '(';
                                foreach($arr as $kkk =>$vvv){
                                    $in .= $this->filterPars($vvv).',';
                                }
                                $in = rtrim($in,','). ')';
                                $temp .= '`'.$k.'` '.$vv[0].' '.$in.' AND ';
                            }else $temp .= '`'.$k.'` '.$vv[0].' ('.$this->filterPars($vv[1]).') AND ';
                        }
                    }elseif(stristr($vv[0],'like')){
                        $temp .= '`'.$k.'` '.$vv[0].' '.$this->filterPars($vv[1]).' AND ';
                    }
                    else $temp .= '`'.$k.'`'.$vv[0].$this->filterPars($vv[1]).' AND ';
                }else {
                    $temp .= '`'.$k.'`='.$this->filterPars($vv).' AND ';
                }
            }
        }
        $this->options_sql['where'] = trim($str.$temp, 'AND ').' ';
    }

    /**
     * 解析 $this->options['select']
     */
    protected function analysis_select(array $arr){
        if(isset($this->options_type) && $this->options_type !== 'SELECT')
            throw new Exception(' 在非查询语句中,定义了查询字段 ! ');
        $this->options_type = 'SELECT';
        $str = '';
        foreach($arr as $k=>$v){
            if($k === '__string'){
                foreach($v as $kk=>$vv){
                    if(strstr($vv,',')){
                        $array = explode(',', $vv);
                        foreach($array as $kkk => $vvv){
                            $str .= $this->filterColumn(trim($vvv,' ')).',';
                        }
                    }else $str .= $this->filterColumn($vv).',';
                }
            }else $str .= $this->filterColumn($v).',';
        }
        $this->options_sql['select'] = trim($str, ',').' ';
    }

    /**
     * @param string $str
     * 解析 $this->options['from']
     */
    protected function analysis_from($str=''){
        $this->options_sql['from'] = 'FROM '.$this->filterColumn($str).' ';
    }

    /**
     * 将可能与sql关键字混淆的 列名 加上 反引号
     * 如 hk_user.account as like 处理成 `hk_user`.`account` as `like`
     * @param string $str 需要处理的string单元
     * @return string
     */
    protected function filterColumn($str=''){
        $addBackQuote = function($str=''){
            return '`'.trim($str,'`').'`';
        };
        $temp = '';
        $str = trim($str,' ');
        if($as = stristr($str,'as')){
            $array = explode(substr($as,0,2), $str);
            $array[0] = trim($array[0],' ');
            $array[1] = trim($array[1],' ');
            // 将 count(hk_user.account) 过滤为 count(`hk_user`.`account`)
            if(($a = strstr($array[0],'(')) ){
                $action = str_replace($a, '', $array[0]);
                $a = ltrim($a,'(');
                $a = rtrim($a,')');
                if(strstr($a,'.')){
                    $arr = explode('.', $a);
                    $temp .= $addBackQuote($arr[0]).'.'.$addBackQuote($arr[1]);
                }else $temp .= $addBackQuote($a);
                $temp = $action.'('.$temp.')';
            }elseif(strstr($array[0],'.')){
                $arr = explode('.', $array[0]);
                $temp .= $addBackQuote($arr[0]).'.'.$addBackQuote($arr[1]);
            }else $temp .= $addBackQuote($array[0]);
            $temp .= ' AS '.$addBackQuote($array[1]);
        }else $temp .= $addBackQuote($str) ;
        return $temp;
    }

    /**
     * 将 非占位符 加上 ""
     * @param string $str
     *
     * @return string
     */
    protected function filterPars($str=''){
        $str = trim($str, ' ');
        if(strstr($str,':'))
            return $str;
        else return '"'.$str.'"';
    }

    final public function __call($fun, $par=null){
        if(method_exists($this->db, $fun) && ($par !== null))
            return call_user_func_array([$this->db, $fun], $par);
        else throw new \Main\Core\Exception('方法没定义!');
    }

}