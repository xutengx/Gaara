<?php

namespace App\admin\Model;

class categoryModel extends \Main\Core\Model {

    // 获取 pid 的子类
    public function get_children_by_pid($pid) {
        return $this->where([
                    'pid' => ':pid'
                ])->getAll([
                    ':pid' => $pid
        ]);
    }

}