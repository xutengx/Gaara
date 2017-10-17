<?php

declare(strict_types = 1);
namespace Main\Core\Model\Traits;

use Exception;
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
     * @return void
     */
    public function __set(string $key, string $value): void {
        $this->orm[$key] = $value;
    }

    /**
     * orm属性保存更新
     * @param int $key 主键
     * @return int 受影响的行数
     */
    public function save(int $key = null): int {
        $param = [];
        $bind = [];
        foreach ($this->field as $v) {
            if (array_key_exists($v['Field'], $this->orm)) {
                $tempkey = ':' . $v['Field'];
                $param[$v['Field']] = $tempkey;
                $bind[$tempkey] = $this->orm[$v['Field']];
            }
        }
        if(is_null($key) && isset($this->orm[$this->primaryKey])){
            $key = $this->orm[$this->primaryKey];
        }elseif(is_null($key))
            throw new Exception ('model ORM save without key');
        return $this->data($param)
                ->where($this->primaryKey, $key)
                ->update($bind);
    }

    /**
     * orm属性新增
     * @return bool
     */
    public function create(): bool{
        $param = [];
        $bind = [];
        foreach ($this->field as $v) {
            if (array_key_exists($v['Field'], $this->orm)) {
                $tempkey = ':' . $v['Field'];
                $param[$v['Field']] = $tempkey;
                $bind[$tempkey] = $this->orm[$v['Field']];
            }
        }
        return $this->data($param)
                ->insert($bind);
    }
}
