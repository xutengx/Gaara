<?php

namespace Gaara\Expand;
defined('IN_SYS') || exit('ACC Denied');

class Image {

    // 1中英文验证码 2对上传图片进行缩放 3打水印
    /**
     * 返回中英文验证码图片
     * 建议用法:1,控制器的单独public方法中, obj('\Gaara\Expand\Image',true)->yzm($width, $height, $complexity, $onlyEnglish) && exit;
     * 2,将view中的验证码img的src指向以上控制器方法
     * 需要开启 session;
     * $_SESSION ['yzm']则为校验值
     * @param int $width 宽度
     * @param int $height   高度
     * @param int $complexity   复杂度(验证码上的字符数)
     * @param bool|true $onlyEnglish    仅使用字母与数字
     * @return bool
     * @throws \Exception
     */
    public function yzm($width = 150, $height = 50, $complexity = 4, $onlyEnglish = true) {
        $this->firstMustBeBig($width, $height);
        // imagecreate 创建画布
        $resData = imagecreatetruecolor($width, $height);
        // 给画布分配颜色
        $color0 = imagecolorallocate($resData, rand(200, 255), rand(200, 255), rand(200, 255));
        // 填充颜色
        imagefill($resData, 0, 0, $color0);
        // 给画布分配颜色
        $color1 = imagecolorallocate($resData, rand(0, 100), rand(0, 100), rand(0, 100));
        $color2 = imagecolorallocate($resData, rand(156, 190), rand(156, 190), rand(156, 190));
        $color3 = imagecolorallocate($resData, rand(101, 155), rand(101, 155), rand(101, 155));

        if ($onlyEnglish) {
            $arrData1 = range(0, 9);
            $arrData2 = range('a', 'z');
            $arrData3 = range('A', 'Z');
            $arrData4 = array_merge($arrData1, $arrData2, $arrData3);
        } else
            $arrData4 = array('浙', '江', '省', '杭', '州', '市', '宋', '城', '景', '区');

        $strData = "";
        $x = 5;
        $fontsize = ( $width / $complexity ) - 2;
        $fontsize = ($fontsize < $height) ? $fontsize : $height;
        $y = $height - $fontsize * 0.3;

        for ($i = 0; $i < $complexity; $i ++) {
            $strDatax = $arrData4 [rand(0, count($arrData4) - 1)];


            $strData .= $strDatax;
            // 画文字
            $font = ROOT . 'Gaara/Support/Image/0.ttf';
            if (!file_exists($font))
                throw new \Exception('字体文件' . $font . '不存在!');
            imagettftext($resData, $fontsize * 0.8, rand(- 30, 30), $x, $y, $color1, $font, $strDatax);
            $x += $fontsize;
            if ($i == 0 || $i == 1) {
                // 画线
                imageline($resData, rand(0, $width / 2), rand(0, $height / 2), rand($width / 2, $width), rand($height / 2, $height), $color2);
            }
        }
        $_SESSION ['yzm'] = strtolower($strData);
        // 画像素点
        for ($i = 0; $i < 100; $i ++)
            imagesetpixel($resData, rand(0, $width), rand(0, $height), $color3);
        header("content-type:image/png");
        // 输出画布
        imagepng($resData);
        // 销毁画布资源
        imagedestroy($resData);
        return true;
    }

    /**
     * 对图片进行缩放
     * @param $file(图片绝对路径全称)
     * @param int $size (图片目标大小)
     * @param int $new_w (图片目标宽度, 高度则按比例适应)
     * return 原文件名 or false
     */
    public function zoom($file, $size = 50, $new_w = 500) {
        // 清除缓存
        clearstatcache();
        if (ceil(filesize($file) / 1000) > $size) {
            $arrData = getimagesize($file);
            // 1 = GIF，2 = JPG，3 = PNG，4 = SWF，5 = PSD，6 = BMP，7 = TIFF(intel byte order)，8 = TIFF(motorola byte order)，9 = JPC，10 = JP2，11 = JPX，12 = JB2，13 = SWC，14 = IFF，15 = WBMP，16 = XBM
//            var_dump($arrData);
            switch ($arrData [2]) {
                case 1 :
                    $pic_creat = imagecreatefromgif($file);
                    $fun = 'gif';
                    break;
                case 2 :
                    $pic_creat = imagecreatefromjpeg($file);
                    $fun = 'jpeg';
                    break;
                case 3 :
                    $pic_creat = imagecreatefrompng($file);
                    $fun = 'png';
                    break;
                default:
                    return false;
                    break;
            }
            $src_w = $arrData [0];
            $src_h = $arrData [1];
            if ($src_w > $new_w) {
                $new_h = ( 500 / $src_w ) * $src_h;
                $dst_image = imagecreatetruecolor($new_w, $new_h);
                imagecopyresampled($dst_image, $pic_creat, 0, 0, 0, 0, $new_w, $new_h, $src_w, $src_h);
//                unlink($file);
                $f = 'image' . $fun;
                $f($dst_image, $file);
                // 清除缓存
                clearstatcache();
                if (ceil(filesize($file) / 1000) > $size)
                    return $this->zoom($file);
                else
                    return $file;
            }else {
//                unlink($file);
                if ($fun == 'jpeg' || $fun == 'png') {
                    imagejpeg($pic_creat, $file, 44);
                    imagedestroy($pic_creat);
                    // 清除缓存
                    clearstatcache();
                    if (ceil(filesize($file) / 1000) > $size)
                        return $this->zoom($file);
                    else
                        return $file;
                }else if ($fun == 'gif') {
                    return $file;
                }
            }
        } else
            return $file;
    }

//打水印
    public function waterMark($filName, $dirName, $alpha = 0, $wz = "www.23php.com版权所有") {
        $arrData = getimagesize($filName);
        switch ($arrData [2]) {
            case 1 :
                $src_image = imagecreatefromgif($filName);
                break;
            case 2 :
                $src_image = imagecreatefromjpeg($filName);
                break;
            case 3 :
                $src_image = imagecreatefrompng($filName);
                break;
            default :
                die('上传文件有误');
                break;
        }
        $font = ROOT . 'Gaara/Support/Image/0.ttf';
        $color0 = imagecolorallocatealpha($src_image, 200, 200, 200, $alpha);
        imagettftext($src_image, 20, 0, $arrData [0] - (mb_strlen($wz, "utf-8") * 30), 30, $color0, $font, $wz);
        imagepng($src_image, $dirName);
        imagedestroy($src_image);
    }

    // 生成2维码
    public function newUrl($url, $dir = false) {
        $dir = $dir ? $dir : $this->makeFilename('data/weima/', 'png');
        if ($dir && $url) {
            $errorCorrectionLevel = 'L'; //容错级别
            $matrixPointSize = 6; //生成图片大小
            //生成二维码图片
            require_once ROOT.'Gaara/Support/Image/Qrcode.php';
            \QRcode::png($url, $dir, $errorCorrectionLevel, $matrixPointSize, 2);
            return $dir;
        }
    }

    // 生成带uid的2维码,带logo, 返回2维码地址
    // 未测
    public function newUrl2() {
        $logo = 'logo.png'; //准备好的logo图片
        $QR = 'qrcode.png'; //已经生成的原始二维码图
        if ($logo !== FALSE) {
            $QR = imagecreatefromstring(file_get_contents($QR));
            $logo = imagecreatefromstring(file_get_contents($logo));
            $QR_width = imagesx($QR); //二维码图片宽度
            $QR_height = imagesy($QR); //二维码图片高度
            $logo_width = imagesx($logo); //logo图片宽度
            $logo_height = imagesy($logo); //logo图片高度
            $logo_qr_width = $QR_width / 5;
            $scale = $logo_width / $logo_qr_width;
            $logo_qr_height = $logo_height / $scale;
            $from_width = ($QR_width - $logo_qr_width) / 2;
            //重新组合图片并调整大小
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
        }
        //输出图片
        imagepng($QR, 'hello.png');
    }

    /**
     * 交换2个参数, 使得$first总是较大的
     * @param $first
     * @param $second
     */
    protected function firstMustBeBig(&$first, &$second) {
        if ($second > $first) {
            $tmp = $first;
            $first = $second;
            $second = $tmp;
        }
    }

    // 生成随机文件名
    protected function makeFilename($dir, $ext, $id = 123) {
        $dir = $dir ? trim($dir, '/') . '/' : './';
        $ext = trim($ext, '.');
        $dir .= uniqid($id);
        $dir .= '.' . $ext;
        return $dir;
    }
}
