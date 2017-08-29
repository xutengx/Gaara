<?php

namespace Main\Core\Tool\Traits;
defined('IN_SYS') || exit('ACC Denied');

/**
 * 发送请求
 */
trait RequestTrait {

    /**
     * 异步执行
     * @param string    $where  指定路由,如:index/index/indexDo
     * @param array     $pars   参数数组
     * @param string    $scheme http/https
     * @param string    $host   异步执行的服务器ip
     * @return true
     */
    function asynExe(string $where = '', array $pars = array(), $scheme = 'http', $host = '127.0.0.1') {
        $where = str_replace('\\','/',$where);
        $host =  $scheme . '://' . $host . str_replace(IN_SYS, '', $_SERVER['SCRIPT_NAME']);
        $url = $host.ltrim($where, '/');
        if(!empty($pars)){
            $url .= '?'.http_build_query($pars);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);      // 解决centos无法执行1000ms以下的超时问题
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 1);    // 网络条件不佳的情况下, 应该增大此值, 以提高可靠性
        curl_exec($ch);
        curl_close($ch);
        return true;
    }

    /**
     * 并行请求
     * @param   array   $urls   需要请求的url组成数组,如 ['www.baidu.com','test.xuteng.com?testpar=123']
     * @return  array           每个请求的响应体
     */
    function parallelExe(array $urls) {
        if (!is_array($urls) or count($urls) === 0) {
            return false;
        }

        $curl = $text = array();
        $handle = curl_multi_init();
        foreach ($urls as $k => $v) {
            $curl[$k] = curl_init($v);
            curl_setopt($curl[$k], CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl[$k], CURLOPT_HEADER, 0);
            curl_multi_add_handle($handle, $curl[$k]);
        }

        $active = null;
        do {
            $mrc = curl_multi_exec($handle, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);

        while ($active && $mrc == CURLM_OK) {
            if (curl_multi_select($handle) != -1) {
                do {
                    $mrc = curl_multi_exec($handle, $active);
                } while ($mrc == CURLM_CALL_MULTI_PERFORM);
            }
        }

        foreach ($curl as $k => $v) {
            if (curl_error($curl[$k]) == "") {
                $text[$k] = (string) curl_multi_getcontent($curl[$k]);
            }
            curl_multi_remove_handle($handle, $curl[$k]);
            curl_close($curl[$k]);
        }
        curl_multi_close($handle);
        return $text;
    }

    // curl发送post请求
    public function sendPost($url, array $data = array()) {
        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出 1 要头 0 不要
        curl_setopt($curl, CURLOPT_HEADER, 0);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        //设置post数据
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        //执行命令
        $re = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //显示获得的数据
        return $re;
    }

    // curl发送get请求
    public function sendGet($url, array $data = array()) {
        if (!empty($data)) {
            $query = http_build_query($data);
            $url .= strpos($url, '?') ? '&' . $query : '?' / $query;
        }
        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出  1 要头 0 不要
        curl_setopt($curl, CURLOPT_HEADER, 0);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //执行命令
        $re = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //显示获得的数据
        return $re;
    }
}
