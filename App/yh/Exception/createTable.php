<?php

declare(strict_types = 1);
namespace App\yh\Exception;
defined('IN_SYS') || exit('ACC Denied');

use Main\Core\DbConnection;
use Main\Core\Conf;

class createTable {

    /**
     * 由$msg分析出 不存在的库表, 再进行相应的操作
     * @param type $msg
     * @param DbConnection $db
     */
    public function handle($msg, DbConnection $db): void {
        $this->table_not_exist($db);
    }

    /**
     * 建表语句无法回滚.
     * @param type $db
     */
    private function table_not_exist($db): void {
        $datatables = obj(Conf::class)->datatables;
        $arr = explode(';', trim($datatables));
        if ($arr[count($arr) - 1] == '')
            unset($arr[count($arr) - 1]);
        foreach ($arr as $v) {
            $db->insert($v);
        }
    }
}
