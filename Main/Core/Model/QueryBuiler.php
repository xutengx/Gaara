<?php

declare(strict_types = 1);
namespace Main\Core\Model;

use Main\Core\Model\QueryBuiler;
use Closure;
/**
 * 链式操作
 */
class QueryBuiler{

    use QueryBuiler\Where;
    use QueryBuiler\Select;
    
    // 绑定的表名
    private $table;
    // 主键
    private $primaryKey;


    public function __construct(string $table, string $primaryKey) {
        $this->table = $table;
        $this->primaryKey = $primaryKey;
    }
    
    /**
     * 获取一个与自己主属性相同的实例, 不同于clone
     * @return QueryBuiler
     */
    private function getSelf(): QueryBuiler {
        return new QueryBuiler($this->table, $this->primaryKey);
    }
    
    /**
     * sql条件
     * @param String|array 数组 $p
     */
    public function where(): QueryBuiler {
        $params = func_get_args();
        var_dump(func_num_args());exit;
        switch (func_num_args()) {
            case 1:
                switch (gettype($params[0])) {
                    case 'object':
                        if($params[0] instanceof Closure){
                            $this->whereClosure($params[0]);
                        }else 
                        break;
                    case 2:
                        break;
                    case 3:
                        break;

                    default:
                        break;
            
                }
                break;
            case 2:
                
                
                
                break;
            case 3:
                break;

            default:
                break;
        }
        return $this;
    }
    
    /**
     * sql条件
     * @param String|array 数组 $p
     */
    public function whereIn(){
        
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