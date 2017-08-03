<?php

namespace Main\Core\Controller\Traits;

defined('IN_SYS') || exit('ACC Denied');

/**
 * 请求过滤
 */
trait RequestTrait {
    // 令牌校验
    protected function checkCsrfToken() {
        if (!obj('Secure')->checkCsrftoken($this->classname, $this->viewOverTime))
            $this->returnMsg(0, '页面已过期,请刷新!!') && exit;
    }
    
    // 请求参数获取
    protected function requestFun($key = null, $rule = null, $msg = null, $fun = 'get'){
        $par = func_get_args();
        if (!is_null($key)) {
            $bool = call_user_func_array(array(obj('Request'), $fun), $par);
            if ($bool === false) {
                $msg = isset($par[2]) ? $par[2] : $par[0] . ' 不合法!';
                $this->returnMsg(0, $msg) && exit;
            } else if ($bool === null)
                throw new \Main\Core\Exception('尝试获取' . $fun . '中的"' . $par[0] . '"没有成功!');
            else
                return $bool;
        }else{
            $arrayKey = array();
            $array = obj('Request')->$fun;
            if ($array === null)
                throw new \Main\Core\Exception('尝试获取' . $fun . '中的数据没有成功!');
            foreach ($array as $k => $v) {
                if (array_key_exists($k, obj('Request')->getFilterArr()) && !is_array($k)) {
                    $arrayKey[$k] = $this->{$fun}($k, $k);
                } else
                    $arrayKey[$k] = obj('Request')->{$fun}($k);
            }
            return $arrayKey;
        }
    }
    protected function get($key = null, $rule = null, $msg = null) {
        return $this->requestFun($key, $rule, $msg, 'get');
    }
    protected function cookie($key = null, $rule = null, $msg = null) {
        return $this->requestFun($key, $rule, $msg, 'cookie');
    }
    protected function session($key = null, $rule = null, $msg = null) {
        return $this->requestFun($key, $rule, $msg, 'session');
    }
    protected function put($key = null, $rule = null, $msg = null) {
        $this->checkCsrfToken();
        return $this->requestFun($key, $rule, $msg, 'post');
    }    
    protected function post($key = null, $rule = null, $msg = null) {
        $this->checkCsrfToken();
        return $this->requestFun($key, $rule, $msg, 'post');
    }    
    protected function delete($key = null, $rule = null, $msg = null) {
        $this->checkCsrfToken();
        return $this->requestFun($key, $rule, $msg, 'post');
    }
}
