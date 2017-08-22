<?php

declare(strict_types = 1);
namespace App\yh\Middleware;
defined('IN_SYS') || exit('ACC Denied');

use Main\Core\Middleware;
use \Main\Core\Request;
use App\yh\s\Token;
use App\yh\s\Sign;

/**
 * 规则校验
 */
class SignCheck extends Middleware {

    private $token = null;
    private $sign = null;
    
    public function handle(Request $request) {
        if ($this->getToken($request)) {
            if ($this->checkToken($this->token)) {
                // 赋值 $request 
                $request->userinfo = $this->analysisToken($this->token);
                if ($this->getSign()) {
                    if ($this->checkSign($request)) {
                        return true;
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
     * @return bool
     */
    private function checkSign(Request $request) : bool{
        $param = $request->input;
        $token = $this->token;
        $timestamp = $request->input('timestamp');
        $sign = $this->sign;
        return Sign::checkSign($param, $token, $timestamp, $sign);
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
    private function error(string $msg, int $code = 403){
        \Response::returnData($msg, 'json', $code);
    }
}
