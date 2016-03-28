<?php

/**
 * 微信授权相关接口
 *
 */
namespace Expand;
defined('IN_SYS') || exit('ACC Denied');
// 测试
// appid = wx8f0ca1bc115c1fae
// appsecret = d4624c36b6795d1d99dcf0547af5443d
class Wechat{

    private $app_id;
    private $app_secret;
    private $api = '';

    public function __construct($app_id = 'wx8f0ca1bc115c1fae', $app_secret = 'd4624c36b6795d1d99dcf0547af5443d'){
        $this->app_id = $app_id;
        $this->app_secret = $app_secret;
    }
    /**
     * 获取微信授权链接(静默授权)
     * @param string $redirect_uri 跳转地址
     * @param mixed $state 参数
     */
    public function get_authorize_url($redirect_uri = '', $state = '1'){
        $redirect_uri = urlencode($redirect_uri);
        return "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->app_id}&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_base&state={$state}#wechat_redirect";
    }
    /**
     * 获取微信授权链接（用户点击授权）
     * @param string $redirect_uri 跳转地址
     * @param mixed $state 参数
     */
    public function get_authorize_url2($redirect_uri = '', $state = '1'){
        $redirect_uri = urlencode($redirect_uri);
        return "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->app_id}&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_userinfo&state={$state}#wechat_redirect";
    }
    /**
     * 获取授权token
     * @param string $code 通过get_authorize_url获取到的code
     */
    public function get_access_token($code = ''){
        $token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->app_id}&secret={$this->app_secret}&code={$code}&grant_type=authorization_code";
        $token_data = $this->http($token_url);
        if ($token_data[0] == 200) {
            return json_decode($token_data[1], TRUE);
        }
        return FALSE;
    }
    /**
     * 获取授权后的微信用户信息
     * @param string $access_token
     * @param string $open_id
     */
    public function get_user_info($access_token = '', $open_id = ''){
        if ($access_token && $open_id) {
            $info_url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$open_id}&lang=zh_CN";
            $info_data = $this->http($info_url);
            if ($info_data[0] == 200) {
                return json_decode($info_data[1], TRUE);
            }
        }
        return FALSE;
    }

    /**
     * 下载微信服务器的图片到我的服务器
     */
    public function downloadImg($where, $media_id = ''){
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->app_id.'&secret='.$this->app_secret;
        $re = $this->http($url);

        if ($re[0] == 200) {
            $res = json_decode($re[1], TRUE);
            $access_token = $res['access_token'];
        }
        if ($access_token && $media_id) {
            $info_url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=' . $access_token . '&media_id=' . $media_id;
            $img = $this->downloadFile($info_url);

            $this->saveFile($where, $img['body']);
            return true;
        }
        return FALSE;
    }

    public function http($url, $method = 'GET', $postfields = null, $headers = array(), $debug = false){
        $ci = curl_init();
        /* Curl settings */
        curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ci, CURLOPT_TIMEOUT, 30);
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);

        switch ($method) {
            case 'POST':
                curl_setopt($ci, CURLOPT_POST, true);
                if (!empty($postfields)) {
                    curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
                    $this->postdata = $postfields;
                }
                break;
        }
        curl_setopt($ci, CURLOPT_URL, $url);
        curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ci, CURLINFO_HEADER_OUT, true);

        $response = curl_exec($ci);
        $http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);

        if ($debug) {
            echo "=====post data======\r\n";
            var_dump($postfields);

            echo '=====info=====' . "\r\n";
            print_r(curl_getinfo($ci));

            echo '=====response=====' . "\r\n";
            print_r($response);
        }
        curl_close($ci);
        return array($http_code, $response);
    }
    // 下载文件
    private function downloadFile($url){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOBODY, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $packahe = curl_exec($ch);
        $httpinfo = curl_getinfo($ch);
        curl_close($ch);
        $imageAll = array_merge(array('header'=>$httpinfo), array('body'=>$packahe));
        return $imageAll;
    }
    // 写入下载文件
    private function saveFile($where, $what){
        if($f = fopen($where, 'w')){
            if(fwrite($f, $what)){
                fclose($f);
            }
        }
    }
}
