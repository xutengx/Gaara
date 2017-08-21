<?php

declare(strict_types = 1);
namespace App\yh\Middleware;
defined('IN_SYS') || exit('ACC Denied');

use Main\Core\Middleware;
use \Main\Core\Request;
use App\yh\s\Token;

/**
 * token规则校验
 */
class TokenCheck extends Middleware {

    private $token = null;
    
    public function handle(Request $request) {
        // 获取token
        $this->token = getToken($request);
        // 检测token
        $this->checkToken($this->token);
        
    }
    /**
     * 获取token
     * @param Request $request
     * @return string
     */
    private function getToken(Request $request){
        $paramArr = $request->{$request->method};
        if(isset($paramArr['token'])){
            return $paramArr['token'];
        }else
            return $this->error ('未携带token');
    }
    /**
     * 解析token
     * @param string $token
     */
    private function checkToken(string $token){
        if(Token::checkToken($token)){
            return true;
        }else{
            return $this->error ('token已失效');
        }
        
    }
    
    private function onCache(){
        
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
