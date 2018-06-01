<?php

declare(strict_types = 1);
namespace App\yh\m;

class MerchantSecret extends \Gaara\Core\Model {

    /**
     * 查询商户的密钥信息
     * @param int $id
     * @return array
     */
    public function getByMerchantId(int $id): array {
        return $this->where(['id' => $id])->getRow();
    }

    /**
     * 新增商户的密钥信息
     * @param int $id
     * @param string $yh_key
     * @param string $public_key
     * @param string $private_key
     * @return bool
     */
    public function createInfo(int $id, string $yh_key, string $public_key, string $private_key): int {
        return $this->data([
            'id' => $id,
            'yh_key' => $yh_key,
            'public_key' => $public_key,
            'private_key' => $private_key,
        ])->insert();
    }

    public function delById(int $id){
        return $this->where('id', $id)->delete();
    }
}
