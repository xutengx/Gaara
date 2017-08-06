<?php
namespace Main\Core\Model;
/**
 * 链式操作
 */
class QueryBuiler{
    // 数据收集
    private $options = [];

    public function __construct(&$options) {
        $this->options = &$options;
    }
    /**
     * sql条件
     * @param String|array 数组 $p
     */
    public function where($p, $symbol = '=', $value = null){
    }
    /**
     * where语句中同时出现条件的“与”或者“或的时候”，要将多个OR用小括号括起来再和AND进行“与”，或者将多个AND用小括号括起来再与OR进行“或”。
     * @param \Closure $callback
     */
    public function orWhere(\Closure $callback){
    }
    
    /**
     * 查询字段筛选
     * @param  String|array 一维数组 $p
     */
    public function select($p){
    }
    /**
     * 更新字段筛选
     * @param  String|array 一维数组 $p
     */
    public function data($p){
    }
    /**
     * 设置数据表
     * @param string $table 表名
     * @return true
     */
    public function from($table=''){
    }
     /**
     * 连接
    * @param  String $p
     */
    public function join($str=''){
    }
     /**
     * @param $group
     */
    public function group($group){
    }
     /**
     * @param $having
     */
    public function having($having){
    }
     /**
     * 排序
     * @param string $order
     */
    public function order($order){
    }
     /**
     * @param int $start
     * @param int $max
     */
    public function limit($start=0, $max=1){
    }
}