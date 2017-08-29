<?php

declare(strict_types = 1);
namespace Main\Core\Model\Traits;
defined('IN_SYS') || exit('ACC Denied');

/**
 * ORM相关
 */
trait ObjectRelationalMappingTrait {
    /**
     * orm属性集合
     * @var array 
     */
    public $orm = [];
    
    /**
     * orm属性设置
     * @param string $key
     * @param string $value
     */
    public function __set(string $key, string $value): void {
        $this->orm[$key] = $value;
    }
    
    /**
     * orm属性保存更新
     * @param int $key  主键
     * @return int      受影响的行数
     */
    public function save(int $key):int{
        $param = [];
        $bind  = [];
        foreach($this->orm as $k => $v){
            $tempkey = ':'.$k ;
            $param[$k] = $tempkey;
            $bind[$tempkey]  = $v;
        }
        $this->data($param);
        $this->where($this->key, $key);
        return $this->update($bind);
    }

    /**
     * orm属性新增
     * @return int      新增的数据的主键
     */
    public function create():int{
        $param = [];
        $bind  = [];
        foreach($this->orm as $k => $v){
            $tempkey = ':'.$k ;
            $param[$k] = $tempkey;
            $bind[$tempkey]  = $v;
        }
        $this->data($param);
        return $this->insert($bind);
    }
}
