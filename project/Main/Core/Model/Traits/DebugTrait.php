<?php

namespace Main\Core\Model\Traits;

defined('IN_SYS') || exit('ACC Denied');

/**
 * 调试相关
 */
trait DebugTrait {
    
    
    /**
     * 返回完整sql, 已执行sql
     * @param type $pars
     */
    public static function getLastSql(){
        return \obj(static::class)->lastSql;
    }
    /**
     * 返回完整sql, 不执行sql
     * @param type $pars
     */
    public function getRowToSql($pars = array()) {
        $this->options_type = 'SELECT';
        $this->collect->limit(1);
        $sql = $this->prepare(true, $pars);
        return $this->lastSql;
    }

    public function getAllToSql($pars = array()) {
        $this->options_type = 'SELECT';
        $sql = $this->prepare(true, $pars);
        return $this->lastSql;
    }

    public function updateToSql($pars = array()) {
        $this->options_type = 'UPDATE';
        if (!isset($this->options['data']))
            throw new Exception('要执行UPDATE操作, 需要使用data方法设置更新的值');
        $sql = $this->prepare(true, $pars);
        return $this->lastSql;
    }

    public function insertToSql($pars = array()) {
        $this->options_type = 'INSERT';
        if (!isset($this->options['data']))
            throw new Exception('要执行INSERT操作, 需要使用data方法设置新增的值');
        $sql = $this->prepare(true, $pars);
        return $this->lastSql;
    }

    public function deleteToSql($pars = array()) {
        $this->options_type = 'DELETE';
        if (!isset($this->options['where']))
            throw new Exception('执行 DELETE 操作并没有相应的 where 约束, 请确保操作正确, 使用where(1)将强制执行.');
        $sql = $this->prepare(true, $pars);
        return $this->lastSql;
    }

    public function replaceToSql($pars = array()) {
        $this->options_type = 'REPLACE';
        if (!isset($this->options['data']))
            throw new Exception('要执行REPLACE操作, 需要使用data方法设置新增or修改的值');
        $sql = $this->prepare(true, $pars);
        return $this->lastSql;
    }
}
