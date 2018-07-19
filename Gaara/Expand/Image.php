<?php

declare(strict_types = 1);
namespace Gaara\Expand;

use Exception;
use Response;
use Gaara\Contracts\ServiceProvider\Single;

/**
 * 图像相关
 */
class Image implements Single {

	protected $font = 'Gaara/Expand/Image/Font/1.ttf';

	/**
	 * 验证码
	 * 建议用法:1.控制器的单独public方法中, exit(obj(\Gaara\Expand\Image::class)->yzm()); 2.将view中的验证码img的src指向以上控制器方法;
	 * 依赖 $_SESSION, $_SESSION['yzm']既为验证值
	 * @param int $width 宽度
	 * @param int $height 高度
	 * @param int $complexity 复杂度(验证码上的字符数)
	 * @return string 验证码的base64
	 * @throws Exception
	 */
	public function yzm(int $width = 150, int $height = 50, int $complexity = 4): string {
		if (!$this->hasSession()) {
			throw new Exception('Image::yzm is dependent on Session');
		}
		$font = ROOT . $this->font;
		if (!is_file($font)) {
			throw new Exception('The font file "' . $font . '" does not exist');
		}
		$this->firstMustBeBig($width, $height);
		// imagecreate 创建画布
		$resData = imagecreatetruecolor($width, $height);
		// 给画布分配颜色
		$color0	 = imagecolorallocate($resData, rand(200, 255), rand(200, 255), rand(200, 255));
		// 填充颜色
		imagefill($resData, 0, 0, $color0);
		// 给画布分配颜色
		$color1	 = imagecolorallocate($resData, rand(0, 100), rand(0, 100), rand(0, 100));
		$color2	 = imagecolorallocate($resData, rand(156, 190), rand(156, 190), rand(156, 190));
		$color3	 = imagecolorallocate($resData, rand(101, 155), rand(101, 155), rand(101, 155));


		$arrData1	 = range(0, 9);
		$arrData2	 = range('a', 'z');
		$arrData3	 = range('A', 'Z');
		$arrData4	 = array_merge($arrData1, $arrData2, $arrData3);


		$strData	 = "";
		$x			 = 5;
		$fontsize	 = ( $width / $complexity ) - 2;
		$fontsize	 = ($fontsize < $height) ? $fontsize : $height;
		$y			 = $height - $fontsize * 0.3;

		for ($i = 0; $i < $complexity; $i ++) {
			$strDatax	 = $arrData4 [rand(0, count($arrData4) - 1)];
			$strData	 .= $strDatax;
			imagettftext($resData, $fontsize * 0.8, rand(- 30, 30), (int) $x, (int) $y, $color1, $font, (string) $strDatax);
			$x			 += $fontsize;
			if ($i === 0 || $i === 1) {
				// 画线
				imageline($resData, rand(0, $width / 2), rand(0, $height / 2), rand($width / 2, $width), rand($height / 2, $height), $color2);
			}
		}
		$_SESSION ['yzm']	 = strtolower($strData);
		// 画像素点
		for ($i = 0; $i < 100; $i ++)
			imagesetpixel($resData, rand(0, $width), rand(0, $height), $color3);
		ob_start();
		// 输出画布
		imagepng($resData);
		// 销毁画布资源
		imagedestroy($resData);
		$imageData			 = ob_get_contents();
		ob_end_clean();
		$imageData			 = 'data:image/png;base64,' . base64_encode($imageData);
		return $imageData;
	}

	/**
	 * 检测session是否可用
	 * return bool
	 */
	protected function hasSession(): bool {
		return isset($_SESSION);
	}

	/**
	 * 交换2个参数, 使得$first总是较大的
	 * @param int $first
	 * @param int $second
	 * @return void
	 */
	protected function firstMustBeBig(int &$first, int &$second): void {
		if ($second > $first) {
			$tmp	 = $first;
			$first	 = $second;
			$second	 = $tmp;
		}
	}

}
