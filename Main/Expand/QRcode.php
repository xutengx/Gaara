<?php

declare(strict_types = 1);
namespace Main\Expand;

use Main\Core\Conf;
use chillerlan\QRCode\QRCode as C_QRCode;
use chillerlan\QRCode\Output\QRImage;
use chillerlan\QRCode\Output\QRImageOptions;

/**
 * 二维码相关
 * https://packagist.org/packages/chillerlan/php-qrcode#1.1.1
 */
class QRcode {

    /**
     * 原作者将配置单独成类以兼容多个模式, 在此仅仅使用QRImage, 遂将配置移回, 以简化调用
     */
    public function __construct(Conf $conf) {
        foreach($conf->qrcode as $k => $v){
            $this->$k = $v;
        }
    }
    
    /**
     * 将$data,转化为base64的二维码
     * @param string $data
     * @return string
     */
    public function base64(string $data): string{
        return (new C_QRCode($data, $this->getQRImage()))->output();
    }
    
    /**
     * 获取QRImage对象
     * @return QRImage
     */
    private function getQRImage(): QRImage{
        return new QRImage($this->getQRImageOptions());
    }
    
    /**
     * 获取QRImageOptions对象,并赋值配置
     * @return QRImageOptions
     */
    private function getQRImageOptions(): QRImageOptions{
        $QRImageOptions = new QRImageOptions();
        $options = (get_object_vars($this));
        foreach($options as $k => $v){
            $QRImageOptions->$k = $v;
        }
        return $QRImageOptions;
    }
}
