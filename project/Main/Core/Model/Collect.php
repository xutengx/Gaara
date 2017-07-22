<?php
namespace Main\Core\Model;
/**
 * 收集 \Main\Core\Model 中的链式调用数据
 */
class Collect{
    // 数据收集
    private $options = [];

    public function __construct(&$options) {
        $this->options = &$options;
    }
    /**
     * sql条件
     * @param String|array 数组 $p
     */
    public function where($p){
        if(is_array($p) && !empty($p))
            foreach($p as $k=>$v)
                $this->options['where'][$k][] = $v;
        else $this->options['where']['__string'][] = $p ;
    }
    
    /**
     * 查询字段筛选
     * @param  String|array 一维数组 $p
     */
    public function select($p){
        if(is_array($p) && !empty($p))
            $this->options['select'] = array_merge(isset($this->options['select']) ? $this->options['select'] : array(), $p);
        else $this->options['select']['__string'][] = $p;
    }
    /**
     * 更新字段筛选
     * @param  String|array 一维数组 $p
     */
    public function data($p){
        if(is_array($p) && !empty($p))
            $this->options['data'] = array_merge(isset($this->options['data']) ? $this->options['data'] : array(), $p);
        else $this->options['data']['__string'][] = $p;
    }
    /**
     * 设置数据表
     * @param string $table 表名
     * @return true
     */
    public function from($table=''){
        $this->options['from'] = $table ;
        return true;
    }
     /**
     * 连接
    * @param  String $p
     */
    public function join($str=''){
        $this->options['join'][] = $str;
    }
     /**
     * @param $group
     */
    public function group($group){
        if(is_array($group) && !empty($group))
            $this->options['group'] = array_merge(isset($this->options['group']) ? $this->options['group'] : array(), $group);
        else $this->options['group']['__string'][] = $group;
    }
     /**
     * @param $having
     */
    public function having($having){
        if(is_array($having) && !empty($having))
            $this->options['having'] = array_merge(isset($this->options['having']) ? $this->options['having'] : array(), $having);
        else $this->options['having']['__string'][] = $having;
        return $this;
    }
     /**
     * 排序
     * @param string $order
     */
    public function order($order){
        if(is_array($order) && !empty($order))
            $this->options['order'] = array_merge(isset($this->options['order']) ? $this->options['order'] : array(), $order);
        else{
            $this->options['order']['__string'][] = $order ;
        }
    }
     /**
     * @param int $start
     * @param int $max
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
    }
}