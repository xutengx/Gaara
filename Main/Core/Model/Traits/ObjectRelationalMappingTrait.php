<?php

declare(strict_types = 1);
namespace Main\Core\Model\Traits;
defined('IN_SYS') || exit('ACC Denied');

use Main\Core\Exception;
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
        if(is_null($key) && isset($this->orm[$this->key])){
            $key = $this->orm[$this->key];
        }else
            throw new Exception ('model ORM save without key');
        $this->data($param);
        $this->where($this->key, $key);
        return $this->update($bind);
    }

    /**
     * orm属性新增
     * @return int      int 0 表示失败, string 0 是主键没自增属性时的成功返回, 其他int表示主键
     */
    public function create() {
        $param = [];
        $bind = [];
        foreach ($this->field as $v) {
            if (array_key_exists($v['Field'], $this->orm)) {
                $tempkey = ':' . $v['Field'];
                $param[$v['Field']] = $tempkey;
                $bind[$tempkey] = $this->orm[$v['Field']];
            }
        }
        $this->data($param);
        return $this->insert($bind);
    }
}
