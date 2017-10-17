<?php

declare(strict_types = 1);
namespace Main\Core\Request\Traits;

use Exception;

/**
 * 客户端相关信息获取
 */
trait ClientInfo {

    /**
     * 获取客户端ip
     * @return string
     * @throws Exception
     */
    private function getIp(): string {
        if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER["HTTP_CLIENT_IP"])) {
            $cip = $_SERVER["HTTP_CLIENT_IP"];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } elseif (isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER["REMOTE_ADDR"])) {
            $cip = $_SERVER["REMOTE_ADDR"];
        } else {
            throw new Exception('无法获取客户端ip');
        }
        return $this->ip = $cip;
    }
}
