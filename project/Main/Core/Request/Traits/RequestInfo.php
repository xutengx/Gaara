<?php

namespace Main\Core\Request\Traits;

defined('IN_SYS') || exit('ACC Denied');

/**
 * 请求相关信息获取
 */
trait RequestInfo {

    /**
     * 获取当前完整url请就方法
     * @return string
     */
    private function getUrl() :string{
        return $this->url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    }
    
    /**
     * 获取当前完整url请就方法
     * @return string
     */
    private function getUrlWithoutQueryString() :string{
        return $this->urlWithoutQueryString = \str_replace('?' . $_SERVER['QUERY_STRING'], '', $this->url);
    }
}
