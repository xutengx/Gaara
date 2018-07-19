<?php

/**
 * 微信授权相关接口

 */
namespace Gaara\Expand;

defined('IN_SYS') || exit('ACC Denied');

// 测试
// appid = wx8f0ca1bc115c1fae
// appsecret = d4624c36b6795d1d99dcf0547af5443d
class Wechat {

	protected $app_id;
	protected $app_secret;

	public function __construct($app_id = 'wx8f0ca1bc115c1fae', $app_secret = 'd4624c36b6795d1d99dcf0547af5443d') {
		$this->app_id		 = $app_id;
		$this->app_secret	 = $app_secret;
	}

	/**
	 * 有效期2小时,每日获取次数有限,建议外部缓存
	 * @return mixed
	 */
	public function wx_get_token($app_id = false, $app_secret = false) {
		$app_id		 = $app_id ? $app_id : $this->app_id;
		$app_secret	 = $app_secret ? $app_secret : $this->app_secret;
		$res		 = file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $app_id . '&secret=' . $app_secret);
		$res		 = json_decode($res, true);
		$token		 = $res['access_token'];
		return $token;
	}

	/**
	 * 注意：这里需要将获取到的ticket缓存起来（或写到数据库中）
	 * ticket和token一样，不能频繁的访问接口来获取，在每次获取后，我们把它保存起来。
	 * @return array|mixed|string|void
	 */
	public function wx_get_jsapi_ticket() {
		$token	 = obj('cache')->call($this, 'wx_get_token', 7000, $this->app_id, $this->app_secret);
		$url2	 = sprintf("https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=%s&type=jsapi", $token);
		$res	 = file_get_contents($url2);
		$res	 = json_decode($res, true);
		$ticket	 = $res['ticket'];
		return $ticket;
	}

	/**
	 * 签名
	 * @param string $nonceStr
	 *
	 * @return string
	 */
	public function get_signature($nonceStr) {
		$timestamp	 = $_SERVER['REQUEST_TIME'];
		$wxnonceStr	 = $nonceStr;
		$wxticket	 = obj('cache')->call($this, 'wx_get_jsapi_ticket', 7000);
		$wxOri		 = sprintf(
		"jsapi_ticket=%s&noncestr=%s&timestamp=%s&url=%s", $wxticket, $wxnonceStr, $timestamp, 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']
		);
		return sha1($wxOri);
	}

	/**
	 * 签名
	 * @param string $nonceStr
	 *
	 * @return string
	 */
	public function get_addrSign($nonceStr) {
		$timestamp	 = $_SERVER['REQUEST_TIME'];
		$wxnonceStr	 = $nonceStr;
		$accesstoken = obj('cache')->call($this, 'wx_get_token', 7000);
		$wxOri		 = sprintf(
		"&accesstoken=%s&appid=%snoncestr=%s&timestamp=%s&url=%s", $accesstoken, $this->app_id, $wxnonceStr, $timestamp, 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']
		);
		return sha1($wxOri);
	}

	/**
	 * 获取微信授权链接(静默授权)
	 *
	 * @param string $redirect_uri 跳转地址
	 * @param mixed  $state        参数
	 */
	public function get_authorize_url($redirect_uri = '', $state = '1') {
		$redirect_uri = urlencode($redirect_uri);
		return "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->app_id}&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_base&state={$state}#wechat_redirect";
	}

	/**
	 * 获取微信授权链接（用户点击授权）
	 *
	 * @param string $redirect_uri 跳转地址
	 * @param mixed  $state        参数
	 */
	public function get_authorize_url2($redirect_uri = '', $state = '1') {
		$redirect_uri = urlencode($redirect_uri);
		return "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->app_id}&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_userinfo&state={$state}#wechat_redirect";
	}

	/**
	 * 获取授权token
	 *
	 * @param string $code 通过get_authorize_url获取到的code
	 */
	public function get_access_token($code = '') {
		$token_url	 = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->app_id}&secret={$this->app_secret}&code={$code}&grant_type=authorization_code";
		$token_data	 = $this->http($token_url);
		if ($token_data[0] == 200) {
			return json_decode($token_data[1], true);
		}
		return false;
	}

	/**
	 * 获取授权后的微信用户信息
	 *
	 * @param string $access_token
	 * @param string $open_id
	 */
	public function get_user_info($access_token = '', $open_id = '') {
		if ($access_token && $open_id) {
			$info_url	 = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$open_id}&lang=zh_CN";
			$info_data	 = $this->http($info_url);
			if ($info_data[0] == 200) {
				return json_decode($info_data[1], true);
			}
		}
		return false;
	}

	/**
	 * 下载微信服务器的图片到我的服务器
	 */
	public function downloadImg($where, $media_id = '') {
		$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $this->app_id . '&secret=' . $this->app_secret;
		$re	 = $this->http($url);

		if ($re[0] == 200) {
			$res			 = json_decode($re[1], true);
			$access_token	 = $res['access_token'];
		}
		if ($access_token && $media_id) {
			$info_url	 = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=' . $access_token . '&media_id=' . $media_id;
			$img		 = $this->downloadFile($info_url);

			$this->saveFile($where, $img['body']);
			return true;
		}
		return false;
	}

	public function http($url, $method = 'GET', $postfields = NULL, $headers = array(), $debug = false) {
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

		$response	 = curl_exec($ci);
		$http_code	 = curl_getinfo($ci, CURLINFO_HTTP_CODE);

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
	protected function downloadFile($url) {
		$ch			 = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_NOBODY, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$packahe	 = curl_exec($ch);
		$httpinfo	 = curl_getinfo($ch);
		curl_close($ch);
		$imageAll	 = array_merge(array('header' => $httpinfo), array('body' => $packahe));
		return $imageAll;
	}

	// 写入下载文件
	protected function saveFile($where, $what) {
		if ($f = fopen($where, 'w')) {
			if (fwrite($f, $what)) {
				fclose($f);
			}
		}
	}

}
