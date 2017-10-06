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
        return obj(static::class)->lastSql;
    }

    /**
     * 记录最近次执行的sql
     * @param string $sql
     * @return string
     */
    public function setLastSql(string $sql): string {
        return $this->lastSql = $sql;
    }
}
