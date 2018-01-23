<?php

declare(strict_types = 1);
namespace Gaara\Expand;

use Gaara\Core\Conf;
use chillerlan\QRCode\QRCode as C_QRCode;
use chillerlan\QRCode\QROptions;

/**
 * 二维码相关
 * https://packagist.org/packages/chillerlan/php-qrcode#2.0.1
 */
class QRcode {

	/**
	 * 原作者将配置单独成类以兼容多个模式, 在此仅仅使用QRImage, 遂将配置移回, 以简化调用
	 */
	public function __construct(Conf $conf) {
		foreach ($conf->qrcode as $k => $v) {
			$this->$k = $v;
		}
	}

	/**
	 * 将$data,转化为base64的二维码
	 * @param string $data
	 * @return string
	 */
	public function base64(string $data): string {
		return (new C_QRCode($this->getQROptions()))->render($data);
	}

	/**
	 * 获取QRImageOptions对象,并赋值配置
	 * @return QROptions
	 */
	private function getQROptions(): QROptions {
		$options = (get_object_vars($this));

		return new QROptions($options);
	}

}
