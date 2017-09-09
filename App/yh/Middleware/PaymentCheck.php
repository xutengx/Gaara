<?php

declare(strict_types = 1);
namespace App\yh\Middleware;
defined('IN_SYS') || exit('ACC Denied');

use Main\Core\Middleware;
use \Main\Core\Request;
use App\yh\s\Token;
use App\yh\s\Sign;
use Response;

/**
 * 规则校验api调用权限
 */
class PaymentCheck extends Middleware {

    private $token = null;
    private $sign = null;
    
    public function handle(Request $request) {
        if ($this->getToken($request)) {
            if ($this->checkToken($this->token)) {
                // 赋值 $request 
                $request->userinfo = $userInfo = $this->analysisToken($this->token);
                if ($this->getSign($request)) {
                    if ($this->checkSign($request, $userInfo['secret'])) {
                        if ($this->checkIdentity($request)) {
                            if (isset($userInfo['payment']) && $userInfo['payment'] === 1) {
                                return true;
                            } else
                                return $this->error('没有调用支付api的权限');
                        } else
                            return $this->error('调用接口不匹配');
                    } else
                        return $this->error('sign不合法');
                } else
                    return $this->error('未携带sign');
            } else
                return $this->error('token已失效');
        } else
            return $this->error('未携带token');
    } 
    
    /**
     * 确定当前身份,是否为商户
     * @param Request $request
     * @return bool
     */
    private function checkIdentity(Request $request): bool{
        return isset($request->userinfo['email']);
    }


    /**
     * 获取token
     * @param Request $request
     * @return bool
     */
    private function getToken(Request $request) : bool{
        $paramArr = $request->input;
        if(isset($paramArr['token'])){
            $request->token = $this->token = $paramArr['token'];
            return true;
        }else
            return false;
    }

    /**
     * 获取sign
     * @param Request $request
     * @return bool
     */
    private function getSign(Request $request) : bool{
        $paramArr = $request->input;
        if(isset($paramArr['sign'])){
            $request->sign = $this->sign = $paramArr['sign'];
            return true;
        }else
            return false;
    }
    /**
     * 检测token
     * @param string $token
     * @return bool
     */
    private function checkToken(string $token) : bool{
        return Token::checkToken($token);
    }
    
    /**
     * 检测sign
     * @param Request $request  当前请求
     * @param string $secret    商户的api对接盐
     * @return bool
     */
    private function checkSign(Request $request, string $secret) : bool{
        $param = $request->input;
        $token = $this->token;
        $timestamp = $request->input('timestamp');
        $sign = $this->sign;
        
        return Sign::checkApiSign($param, $token, (int)$timestamp, $sign, $secret);
    }
    
    /**
     * 解析token
     * @param string $token
     * @return array
     */
    private function analysisToken(string $token):array{
        return Token::decryptToken($token);
    }
    
    /**
     * 返回错误信息以及响应
     * @param string $msg
     * @param int $code
     */
    private function error(string $msg, int $code = 0){
        $data = ['code'=> $code, 'msg' => $msg];
        exit(Response::returnData($data, 'json', 403));
    }
}
