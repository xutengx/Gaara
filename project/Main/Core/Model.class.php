<?php
namespace Main\Core;
defined('IN_SYS')||exit('ACC Denied');
class Model{
    protected $db ;
    // 表名,不包含表前缀
    protected $tablename   = '';
    // 表前缀
    protected $tablepre   = '';
    // 主键的字段
    protected $key          = null;
    // 表名
    protected $table        = '';
    // 表信息
    protected $field = array();
    // 链式操作集合
    protected $options = array();
    // 链式操作 sql
    protected $options_sql = array();
    // 链式操作集合
//    protected $options_analysis = [];
    // 链式操作 类型 select update delete insert
    protected $options_type = null;
    // PDOStatement
    protected $PDOStatement = null;
    /**
     * @param object $DbConnection db连接对象 如 obj('Mysql',false);
     */
    final public function __construct($DbConnection = null){
        $this->db = obj('\Main\Core\DbConnection',true, obj('conf')->db);
        $this->get_thisTable();
        $this->construct();
    }
    protected function construct(){}
    final protected function get_thisTable(){
        $conf = obj('conf');
        $this->tablepre = $conf->tablepre;
        $classname = get_class($this);
        $classname = substr($classname,strrpos($classname,'\\')+1);
        if($this->tablename === '') $this->tablename=strtr($classname, array('Model'=>''));
        if($this->table === '') $this->table = $conf->tablepre.$this->tablename;
        $this->getTableInfo();
    }
    public function tbname(){
        return $this->table;
    }
    // 获取表信息
    protected function getTableInfo(){
        $this->field = obj('cache')->get(true, function(){
            return $this->db->getAll('SHOW COLUMNS FROM `'.$this->table.'`');
        },3600);
        foreach($this->field as $v){
            if($v['Extra'] == 'auto_increment'){
                $this->key = $v['Field'];
                break;
            }
        }
    }
    // 在外执行
    final public function runProtectedFunction( $func='', array $agrs = array() ){
        return call_user_func_array( array( $this, $func ), $agrs );
    }
//---------------------------------------------------------- 链式操作 -----------------------------------------------------//
    /**
     * sql条件
     * @param $where
     *
     * @return $this
     */
    public function where($where){
        if(is_array($where) && !empty($where))
            foreach($where as $k=>$v)
                $this->options['where'][$k][] = $v;
        else $this->options['where']['__string'][] = $where ;
        return $this;
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
        $this->options_sql['where'] = 'WHERE '.trim($str.$temp, 'AND ').' ';
    }

    /**
     * 查询字段筛选
     * @param  String|array 一维数组 $what
     *
     * @return $this
     */
    public function select($what){
        if(is_array($what) && !empty($what))
            $this->options['select'] = array_merge(isset($this->options['select']) ? $this->options['select'] : array(), $what);
        else $this->options['select']['__string'][] = $what;
        return $this;
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

    public function data($set){
        if(is_array($set) && !empty($set))
            $this->options['data'] = array_merge(isset($this->options['data']) ? $this->options['data'] : array(), $set);
        else $this->options['data']['__string'][] = $set;
        return $this;
    }
    protected function analysis_data(array $arr){
        if($this->options_type !== 'UPDATE' && $this->options_type !== 'INSERT'&& $this->options_type !== 'REPLACE')
            throw new Exception(' 在非更新语句中,定义了更新字段 ! ');
        $str = '';
        foreach($arr as $k=>$v){
            if($k === '__string')
                $str .= $v.',';
            else $str .= $this->filterColumn($k).'='.$this->filterPars($v).',';
        }
        $this->options_sql['data'] = trim($str, ',').' ';
    }

    /**
     * 设置数据表
     * @param string     $table 表名
     * @param bool|false $withTablepre 是否已包含表前缀
     *
     * @return $this
     */
    public function from($table='', $withTablepre=false){
        $table = trim($table,' ');
        $this->options['from'] = ( $withTablepre ? '': $this->tablepre ) .$table ;
        return $this;
    }

    /**
     * @param string $str
     * 解析 $this->options['from']
     */
    protected function analysis_from($str=''){
        $this->options_sql['from'] = $this->filterColumn($str).' ';
    }

    /**
     * 连接
     * @param string $str
     * @return $this
     */
    public function join($str=''){
        $str = trim($str,' ');
        if(!stristr($str,'join'))
            $str = 'INNER JOIN '.$str;
        $this->options['join'][] = $str;
        return $this;
    }
    protected function analysis_join(array $arr){
        $temp = '';
        foreach($arr as $k=>$v){
            $temp .= $v;
        }
        $this->options_sql['join'] = $temp.' ';
    }

    /**
     * @param $group
     *
     * @return $this
     */
    public function group($group){
        if(is_array($group) && !empty($group))
            $this->options['group'] = array_merge(isset($this->options['group']) ? $this->options['group'] : array(), $group);
        else $this->options['group']['__string'][] = $group;
        return $this;
    }

    protected function analysis_group(array $arr){
        $str = '';
        foreach($arr as $k=>$v){
            if($k === '__string'){
                foreach($v as $kk=>$vv){
                    $tmp = str_ireplace('group by ', '', trim($vv,' '));
                    if(stristr($tmp,',')){
                        $temp = explode(',', $tmp);
                        foreach($temp as $vvv)
                            $str .= $this->filterColumn($vvv).',';
                    }
                }
            }else $str .= $this->filterColumn($v).',';
        }
        $this->options_sql['group'] = 'GROUP BY '.trim($str, ',').' ';
    }

    /**
     * @param $having
     *
     * @return $this
     */
    public function having($having){
        if(is_array($having) && !empty($having))
            throw new Exception('having暂不支持数组参数');
        else $this->options['having']['__string'][] = $having;
        return $this;
    }
    protected function analysis_having(array $arr){
        $str = '';
        foreach($arr as $k=>$v){
            if($k === '__string'){
                foreach($v as $kk=>$vv){
                    $tmp = str_ireplace('having ', '', trim($vv,' '));
                    if($and = stristr($tmp,'and')){
                        $array = explode(substr($and,0,3), $tmp);
                        foreach($array as $vvv)
                            $str .= $this->filterColumn($vvv).' AND ';
                    }else $str .= $vv.' AND ';
                }
            }
        }
        $this->options_sql['group'] = 'HAVING '.rtrim($str, ' AND ').' ';
    }

    /**
     * 排序
     * @param string $order
     * @return $this
     */
    public function order($order){
        if(is_array($order) && !empty($order))
            throw new Exception('order方法目前仅支持string参数');
        else{
            $order = trim($order,' ');
            $this->options['order']['__string'][] = $order ;
        }
        return $this;
    }
    protected function analysis_order(array $arr){
        $str = '';
        foreach($arr as $k=>$v){
            if($k === '__string'){
                foreach($v as $kk=>$vv){
                    $tmp = str_ireplace('order by ', '', trim($vv,' '));
                    $str .= $tmp.( preg_match('#\s(asc|desc)$#i', $tmp) ? '' : ' ASC').',';
                }
            }
            $str = 'ORDER BY '.rtrim($str, ',');
        }
        $this->options_sql['order'] = trim($str, ',').' ';
    }

    /**
     * @param int $start
     * @param int $max
     *
     * @return $this
     * @throws Exception
     */
    public function limit($start=0, $max=1){
        if(func_num_args() === 1)
            $this->options['limit'] = array(
                'start' => 0,
                'max'   => $start
            );
        else
            $this->options['limit'] = array(
                'start' => $start,
                'max'   => $max
            );
        return $this;
    }
    protected function analysis_limit(array $arr){
        $this->options_sql['limit'] = 'LIMIT '.$arr['start'].','.$arr['max'];
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
        if(!isset($this->options_sql['select'])){
            $str = '';
            foreach($this->field as $v){
                $str .= '`'.$this->table.'`.`'.$v['Field'].'`,';
            }
            $this->options_sql['select'] = trim($str, ',').' ';
        }
        $sql = '';
        switch($this->options_type){
            case 'SELECT':
                $sql = 'SELECT '.
                    ( isset($this->options_sql['select']) ? $this->options_sql['select'] : '* ').
                    'FROM '.( isset($this->options_sql['from']) ? $this->options_sql['from'] : $this->table).
                    ( isset($this->options_sql['join']) ? $this->options_sql['join'] : '');
                break;
            case 'UPDATE':
                $sql = 'UPDATE '.
                    ( isset($this->options_sql['from']) ? $this->options_sql['from'] : $this->table).
                    'SET '.( isset($this->options_sql['data']) ? $this->options_sql['data'] : '');
                break;
            case 'INSERT':
                $sql = 'INSERT INTO '.
                    ( isset($this->options_sql['from']) ? $this->options_sql['from'] : $this->table).
                    'SET '.( isset($this->options_sql['data']) ? $this->options_sql['data'] : '');
                break;
            case 'REPLACE':
                $sql = 'REPLACE INTO '.
                    ( isset($this->options_sql['from']) ? $this->options_sql['from'] : $this->table).
                    'SET '.( isset($this->options_sql['data']) ? $this->options_sql['data'] : '');
                break;
            case 'DELETE':
                $sql = 'DELETE '.
                    'FROM '.( isset($this->options_sql['from']) ? $this->options_sql['from'] : $this->table);
                break;
        }
        $sql .= ( isset($this->options_sql['join']) ? $this->options_sql['join'] : '').
            ( isset($this->options_sql['where']) ? $this->options_sql['where'] : '').
            ( isset($this->options_sql['group']) ? $this->options_sql['group'] : '').
            ( isset($this->options_sql['having']) ? $this->options_sql['having'] : '').
            ( isset($this->options_sql['order']) ? $this->options_sql['order'] : '').
            ( isset($this->options_sql['limit']) ? $this->options_sql['limit'] : '');
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
        $this->options = array();
        // 链式操作 sql
        $this->options_sql = array();
        // 链式操作集合
//        $this->options_analysis = array();
        // 链式操作 类型 select update delete insert
        $this->options_type = null;
    }

    /**
     * 参数绑定, 并执行
     * @param array $pars
     */
    public function execute($pars = array()){
        $this->PDOStatement->execute($pars);
        return $this->PDOStatement->fetchall(\PDO::FETCH_ASSOC);
    }

    public function getRow($pars = array()){
        $this->options_type = 'SELECT';
        if(!isset($this->options['limit']))
            $this->limit(1);
        $sql = $this->prepare(true);
        if($pars === false) return $sql;
        elseif($pars === true) exit($sql);
        return $this->db->getRow($sql, $pars);
    }

    public function getAll($pars = array()){
        $this->options_type = 'SELECT';
        $sql = $this->prepare(true);
        if($pars === false) return $sql;
        elseif($pars === true) exit($sql);
        return $this->db->getAll($sql, $pars);
    }

    public function update($pars = array()){
        $this->options_type = 'UPDATE';
        if(!isset($this->options['data']))
            throw new Exception('要执行UPDATE操作, 需要使用data方法设置更新的值');
        $sql = $this->prepare(true);
        if($pars === false) return $sql;
        elseif($pars === true) exit($sql);
        return $this->db->update($sql, $pars);

    }

    public function insert($pars = array()){
        $this->options_type = 'INSERT';
        if(!isset($this->options['data']))
            throw new Exception('要执行INSERT操作, 需要使用data方法设置新增的值');
        $sql = $this->prepare(true);
        if($pars === false) return $sql;
        elseif($pars === true) exit($sql);
        return $this->db->insert($sql, $pars);
    }

    public function delete($pars = array()){
        $this->options_type = 'DELETE';
        if(!isset($this->options['where']))
            throw new Exception('执行 DELETE 操作并没有相应的 where 约束, 请确保操作正确, 使用where(1)将强制执行.');
        $sql = $this->prepare(true);
        if($pars === false) return $sql;
        elseif($pars === true) exit($sql);
        return $this->db->update($sql, $pars);
    }

    public function replace($pars = array()){
        $this->options_type = 'REPLACE';
        if(!isset($this->options['data']))
            throw new Exception('要执行REPLACE操作, 需要使用data方法设置新增or修改的值');
        $sql = $this->prepare(true);
        if($pars === false) return $sql;
        elseif($pars === true) exit($sql);
        return $this->db->update($sql, $pars);
    }
    public function begin(){
        return $this->db->begin();
    }
    public function commit(){
        return $this->db->commit();
    }
    public function rollBack(){
        return $this->db->rollBack();
    }

    /**
     * 将可能与sql关键字混淆的 列名 加上 反引号
     * 如 hk_user.account as like 处理成 `hk_user`.`account` as `like`
     * @param string $str 需要处理的string单元
     * @return string
     */
    protected function filterColumn($str=''){
        $addBackQuote = function($str=''){
            $str = trim($str , ' ');
            if($str === '*') return $str;
            return '`'.trim($str,'`').'`';
        };
        $temp = '';
        $str = trim($str,' ');
        if($as = stristr($str,' as ')){
            $array = explode(substr($as,0,4), $str);
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
            }
            else $temp .= $addBackQuote($array[0]);
            $temp .= ' AS '.$addBackQuote($array[1]);
        }else {
            // 将 count(hk_user.account) 过滤为 count(`hk_user`.`account`)
            if(($a = strstr($str,'(')) ){
                $action = str_replace($a, '', $str);
                $a = ltrim($a,'(');
                $a = rtrim($a,')');
                if(strstr($a,'.')){
                    $arr = explode('.', $a);
                    $temp .= $addBackQuote($arr[0]).'.'.$addBackQuote($arr[1]);
                }else $temp .= $addBackQuote($a);
                $temp = $action.'('.$temp.')';
            }elseif(strstr($str,'.')){
                $arr = explode('.', $str);
                $temp .= $addBackQuote($arr[0]).'.'.$addBackQuote($arr[1]);
            }
            else $temp .= $addBackQuote($str);
        }
        return $temp;
    }

    /**
     * 将 非占位符 加上 ""
     * @param string $str
     * @return string
     */
    protected function filterPars($str=''){
        $str = trim($str, ' ');
        if(strstr($str,':') && !strtotime($str))
            return $str;
        else return '"'.$str.'"';
    }

    public function __get($attr){
        if($attr === 'db')
            return $this->db;
    }
}