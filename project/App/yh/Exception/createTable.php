<?php

namespace App\yh\Exception;

use Main\Core\DbConnection;
use Main\Core\Conf;
defined('IN_SYS') || exit('ACC Denied');

class createTable {

    /**
     * 由$msg分析出 不存在的库表, 再进行相应的操作
     * @param type $msg
     * @param DbConnection $db
     */
    public function handle($msg, DbConnection $db) {
        $this->table_not_exist($db);
    }

    private function table_not_exist($db) {
        $datatables = obj(Conf::class)->datatables;
        $arr = explode(';', trim($datatables));
        if ($arr[count($arr) - 1] == '')
            unset($arr[count($arr) - 1]);
        foreach ($arr as $v) {
            $db->insert($v);
        }
        return true;
    }
}
