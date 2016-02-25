<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/16 0016
 * Time: 15:17
 */
namespace Expand;
defined('IN_SYS') || exit('ACC Denied');
class Image {
    // 1中英文验证码 2对上传图片进行缩放 3打水印
    /**
     * 返回中英文验证码图片
     * 建议用法:1,控制器的单独public方法中, obj('\Expand\Image',true)->yzm($width, $height, $complexity, $onlyEnglish) && exit;
     * 2,将view中的验证码img的src指向以上控制器方法
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
        $resData = imagecreatetruecolor ( $width, $height );
        // 给画布分配颜色
        $color0 = imagecolorallocate ( $resData, rand ( 200, 255 ), rand ( 200, 255 ), rand ( 200, 255 ) );
        // 填充颜色
        imagefill ( $resData, 0, 0, $color0 );
        // 给画布分配颜色
        $color1 = imagecolorallocate ( $resData, rand ( 0, 100 ), rand ( 0, 100 ), rand ( 0, 100 ) );
        $color2 = imagecolorallocate ( $resData, rand ( 156, 190 ), rand ( 156, 190 ), rand ( 156, 190 ) );
        $color3 = imagecolorallocate ( $resData, rand ( 101, 155 ), rand ( 101, 155 ), rand ( 101, 155 ) );

        if ($onlyEnglish) {
            $arrData1 = range ( 0, 9 );
            $arrData2 = range ( 'a', 'z' );
            $arrData3 = range ( 'A', 'Z' );
            $arrData4 = array_merge ( $arrData1, $arrData2, $arrData3 );
        } else $arrData4 = array ('浙', '江', '省', '杭', '州', '市', '宋', '城', '景', '区');

        $strData = "";
        $x = 5;
        $fontsize = ( $width / $complexity ) -2;
        $fontsize = ($fontsize < $height) ? $fontsize : $height;
        $y = $height-$fontsize*0.3;

        for($i = 0; $i < $complexity; $i ++) {
            $strDatax = $arrData4 [rand ( 0, count ( $arrData4 ) - 1 )];


            $strData .= $strDatax;
            // 画文字
            $font = ROOT.'Main/Support/Image/0.ttf';
            if(!file_exists($font)) throw new \Exception('字体文件'.$font.'不存在!');
            imagettftext ( $resData, $fontsize*0.8, rand ( - 30, 30 ), $x, $y, $color1, $font , $strDatax );
            $x += $fontsize;
            if ($i == 0 || $i == 1) {
                // 画线
                imageline ( $resData, rand ( 0, $width/2 ), rand ( 0, $height/2 ), rand ( $width/2, $width ), rand ( $height/2, $height ), $color2 );
            }
        }
        $_SESSION ['yzm'] = strtolower($strData);
        // 画像素点
        for($i = 0; $i < 100; $i ++) imagesetpixel ( $resData, rand ( 0, $width ), rand ( 0, $height ), $color3 );
        header ( "content-type:image/png" );
        // 输出画布
        imagepng ( $resData );
        // 销毁画布资源
        imagedestroy ( $resData );
        return true;
    }
//对上传图片进行缩放
    public function zoom($percent1 = 1, $percent2 = 2, $percent3 = 3, $dir = "./upload/") {
        if (! empty ( $_POST )) {
            $imgArrData = array (
                ".png",
                ".jpeg",
                ".jpg",
                ".gif"
            );
            $suffix = strrchr ( $_FILES ['wj'] ['name'], "." );
            if (! in_array ( $suffix, $imgArrData )) {
                exit ( '只能上传图片' );
            }

            $arrData = getimagesize ( $_FILES ['wj'] ['tmp_name'] );
            switch ($arrData [2]) {
                case 1 :
                    $src_image = imagecreatefromgif ( $_FILES ['wj'] ['tmp_name'] );
                    break;
                case 2 :
                    $src_image = imagecreatefromjpeg ( $_FILES ['wj'] ['tmp_name'] );
                    break;
                case 3 :
                    $src_image = imagecreatefrompng ( $_FILES ['wj'] ['tmp_name'] );
                    break;
                default :
                    die ( '上传文件有误' );
                    break;
            }

            if (! file_exists ( $dir )) {
                mkdir ( $dir ) or die ( '创建目录失败' );
            }

            $src_w = $arrData [0];
            $src_h = $arrData [1];

            $dst_w = $src_w / $percent1;
            $dst_h = $src_h / $percent1;

            $dst_image = imagecreatetruecolor ( $dst_w, $dst_h );
            imagecopyresampled ( $dst_image, $src_image, 0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h );
            $filName = $dir . uniqid ( "big_" ) . ".png";
            imagepng ( $dst_image, $filName );
            $this->waterMark ( $filName, $filName );

            $dst_w = $src_w / $percent2;
            $dst_h = $src_h / $percent2;
            $dst_image = imagecreatetruecolor ( $dst_w, $dst_h );
            imagecopyresampled ( $dst_image, $src_image, 0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h );
            imagepng ( $dst_image, $dir . uniqid ( "small_" ) . ".png" );

            $dst_w = $src_w / $percent3;
            $dst_h = $src_h / $percent3;
            $dst_image = imagecreatetruecolor ( $dst_w, $dst_h );
            imagecopyresampled ( $dst_image, $src_image, 0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h );
            imagepng ( $dst_image, $dir . uniqid ( "min_" ) . ".png" );

            imagedestroy ( $src_image );
            imagedestroy ( $dst_image );

        }
    }
//打水印
    public function waterMark($filName, $dirName, $alpha = 0, $wz = "www.23php.com版权所有") {
        $arrData = getimagesize ( $filName );
        switch ($arrData [2]) {
            case 1 :
                $src_image = imagecreatefromgif ( $filName );
                break;
            case 2 :
                $src_image = imagecreatefromjpeg ( $filName );
                break;
            case 3 :
                $src_image = imagecreatefrompng ( $filName );
                break;
            default :
                die ( '上传文件有误' );
                break;
        }
        $font = ROOT.'Main/Support/Image/0.ttf';
        $color0 = imagecolorallocatealpha ( $src_image, 200, 200, 200, $alpha );
        imagettftext ( $src_image, 20, 0, $arrData [0] - (mb_strlen ( $wz, "utf-8" ) * 30), 30, $color0, $font, $wz );
        imagepng ( $src_image, $dirName );
        imagedestroy ( $src_image );
    }
    // 生成2维码
    public function newUrl($url, $dir=false){
        $dir = $dir?$dir:$this->makeFilename('data','png');
        if($dir && $url ){
            $errorCorrectionLevel = 'L';//容错级别
            $matrixPointSize = 6;//生成图片大小
            //生成二维码图片
            \QRcode::png($url, $dir, $errorCorrectionLevel, $matrixPointSize, 2);
            return $dir;
        }
    }

    // 生成带uid的2维码,带logo, 返回2维码地址
    // 未测
    public function newUrl2(){
        $logo = 'logo.png';//准备好的logo图片
        $QR = 'qrcode.png';//已经生成的原始二维码图
        if ($logo !== FALSE) {
            $QR = imagecreatefromstring(file_get_contents($QR));
            $logo = imagecreatefromstring(file_get_contents($logo));
            $QR_width = imagesx($QR);//二维码图片宽度
            $QR_height = imagesy($QR);//二维码图片高度
            $logo_width = imagesx($logo);//logo图片宽度
            $logo_height = imagesy($logo);//logo图片高度
            $logo_qr_width = $QR_width / 5;
            $scale = $logo_width/$logo_qr_width;
            $logo_qr_height = $logo_height/$scale;
            $from_width = ($QR_width - $logo_qr_width) / 2;
            //重新组合图片并调整大小
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,
                $logo_qr_height, $logo_width, $logo_height);
        }
        //输出图片
        imagepng($QR, 'hello.png');
    }

    // 压缩图片
    // test
    public function base_imgSlimming(){
        // 源文件
        $filename = '1.jpg';
        // 设置最大宽高
        $width = 640;
        $height = 1000;
        // Content type
        header('Content-Type: image/jpeg');

        // 获取新尺寸
        list($width_orig, $height_orig) = getimagesize($filename);
        $ratio_orig = $width_orig/$height_orig;
        if ($width/$height > $ratio_orig) {
            $width = $height*$ratio_orig;
        } else {
            $height = $width/$ratio_orig;
        }

        // 重新取样
        $image_p = imagecreatetruecolor($width, $height);
        $image = imagecreatefromjpeg($filename);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

        // 输出
        imagejpeg($image_p, null, 100);
    }
    // 压缩图片
    // test
    public function base_imgSlimming2(){
        // 源文件
        $filename = '1.jpg';

        header('Content-Type: image/jpeg');
        $image=imagecreatefromjpeg($filename);
        imagejpeg($image,'123.jpg',30);//注意后面那个数字0，这里即压缩等级，参数范围：0-100*/
        imagedestroy($image);
    }
    /**
     * 交换2个参数, 使得$first总是较大的
     * @param $first
     * @param $second
     */
    private function firstMustBeBig(&$first, &$second){
        if($second > $first){
            $tmp=$first; $first=$second; $second=$tmp;
        }
    }
    // 生成随机文件名
    private function makeFilename($dir, $ext, $id=123){
        $dir = $dir?trim($dir,'/').'/':'./';
        $ext = trim($ext,'.');
        $dir .= uniqid($id);
        $dir .='.'.$ext;
        return $dir;
    }
}