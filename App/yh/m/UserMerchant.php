<?php

declare(strict_types = 1);
namespace App\yh\m;
defined('IN_SYS') || exit('ACC Denied');

class UserMerchant extends \Main\Core\Model {

    /**
     * 获取商户信息
     * @param int $id
     * @return array
     */
    public function getInfo(int $id): array {
        return $this->where('id', $id)->getRow();
    }
}
