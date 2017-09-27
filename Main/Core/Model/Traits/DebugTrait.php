<?php

declare(strict_types = 1);
namespace Main\Core\Model\Traits;

/**
 * 调试相关
 */
trait DebugTrait {

    /**
     * 返回完整sql, 已执行sql
     * @param type $pars
     * @return string
     */
    public static function getLastSql(): string {
        return \obj(static::class)->lastSql;
    }

    /**
     * 返回完整sql, 不执行sql
     * @param array $pars
     * @return string
     */
    public function getRowToSql(array $pars = array()): string {
        $this->options_type = 'SELECT';
        $this->collect->limit(1);
        $sql = $this->prepare(true, $pars);
        return $this->lastSql;
    }

    /**
     * 返回完整sql, 不执行sql
     * @param array $pars
     * @return string
     */
    public function getAllToSql(array $pars = array()): string {
        $this->options_type = 'SELECT';
        $sql = $this->prepare(true, $pars);
        return $this->lastSql;
    }

    /**
     * 返回完整sql, 不执行sql
     * @param array $pars
     * @return string
     */
    public function updateToSql(array $pars = array()): string {
        $this->options_type = 'UPDATE';
        if (!isset($this->options['data']))
            throw new Exception('要执行UPDATE操作, 需要使用data方法设置更新的值');
        $sql = $this->prepare(true, $pars);
        return $this->lastSql;
    }

    /**
     * 返回完整sql, 不执行sql
     * @param array $pars
     * @return string
     */
    public function insertToSql(array $pars = array()): string {
        $this->options_type = 'INSERT';
        if (!isset($this->options['data']))
            throw new Exception('要执行INSERT操作, 需要使用data方法设置新增的值');
        $sql = $this->prepare(true, $pars);
        return $this->lastSql;
    }

    /**
     * 返回完整sql, 不执行sql
     * @param array $pars
     * @return string
     */
    public function deleteToSql(array $pars = array()): string {
        $this->options_type = 'DELETE';
        if (!isset($this->options['where']))
            throw new Exception('执行 DELETE 操作并没有相应的 where 约束, 请确保操作正确, 使用where(1)将强制执行.');
        $sql = $this->prepare(true, $pars);
        return $this->lastSql;
    }

    /**
     * 返回完整sql, 不执行sql
     * @param array $pars
     * @return string
     */
    public function replaceToSql(array $pars = array()): string {
        $this->options_type = 'REPLACE';
        if (!isset($this->options['data']))
            throw new Exception('要执行REPLACE操作, 需要使用data方法设置新增or修改的值');
        $sql = $this->prepare(true, $pars);
        return $this->lastSql;
    }

}
