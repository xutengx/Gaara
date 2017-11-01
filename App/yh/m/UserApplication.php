<?php

declare(strict_types = 1);
namespace App\yh\m;
defined('IN_SYS') || exit('ACC Denied');

class UserApplication extends \Gaara\Core\Model {

    /**
     * 获取应用信息
     * @param int $merchant_id
     * @return array
     */
    public function getAllByMerchantId(int $merchant_id): array {
        return $this->where('merchant_id', $merchant_id)->getAll();
    }

    /**
     * 删除应用信息
     * @param int $id
     * @return type
     */
    public function delById(int $id) {
        return $this->where('id', $id)->delete();
    }

    public function getInfoByIdWithMerchant(int $id, int $merchant_id): array {
        return $this->where('id', ':id')->where('merchant_id', $merchant_id)->getRow([':id' => $id]);
    }
}
