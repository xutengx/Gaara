<?php

namespace App\Dev\view;
defined('IN_SYS') || exit('ACC Denied');

use App\yh\s\Sign;
use Main\Core\Request;

class index extends \Main\Core\Controller\HttpController {

    protected $view = 'App/Dev/view//view/html/';
    protected $language = 1;

    public function index() {


        $this->assignPhp('test', url('teet/tete/e', ['name' => 'as', 'age' => 12]));
        $this->assign('test', 'this is test string !');

        $this->display('demo');
    }

    public function getAjax(Request $request) {
       $a = \Tool::asynExe('\test\fun');
        var_dump($a);
        exit;
        
        foreach($request->file as $k => $v){
            var_dump($k);
            var_dump($v);
        }
        exit;
        var_dump($request->get);
        var_dump($_PUT = $this->parse_http_input_raw());
        exit;
        ;
        var_dump(FormDataParser::parser());

        var_dump($_FILES);
        var_dump($request);
        exit;
        $param = $request->input;
        $token = $request->input('token');
        $timestamp = $request->input('timestamp');
        $sign = $request->input('sign');
        $re = Sign::checkSign($param, $token, $timestamp, $sign);
        return $this->returnData($re);
    }

    public function __destruct() {
        \statistic();
    }
    
    function parse_http_input_raw() {
        $a_data = array();

        // read incoming data
        $input = file_get_contents('php://input');

        // grab multipart boundary from content type header
        preg_match('/boundary=(.*)$/', $_SERVER['CONTENT_TYPE'], $matches);

        // content type is probably regular form-encoded
        if (!count($matches)) {
            // we expect regular puts to containt a query string containing data
            parse_str(urldecode($input), $a_data);
            return $a_data;
        }

        $boundary = $matches[1];

        // split content by boundary and get rid of last -- element
        $a_blocks = preg_split("/-+$boundary/", $input);
        array_pop($a_blocks);


        // loop data blocks
        foreach ($a_blocks as $id => $block) {
            if (empty($block))
                continue;

            // you'll have to var_dump $block to understand this and maybe replace \n or \r with a visibile char
            // parse uploaded files
            if (strpos($block, 'filename=') !== FALSE) {
                // match "name", then everything after "stream" (optional) except for prepending newlines
                preg_match("/name=\"([^\"]*)\".*filename=\"([^\"].*?)\".*Content-Type:\s+(.*?)[\n|\r|\r\n]+([^\n\r].*)?$/s", $block, $matches);
                $a_data['files'][$matches[1]] = array(
                    'name' => $matches[1],
                    'filename' => $matches[2],
                    'type' => $matches[3],
                    'blob' => $matches[4]
                );
            }
            // parse all other fields
            else {
                // match "name" and optional value in between newline sequences
                preg_match('/name=\"([^\"]*)\"[\n|\r]+([^\n\r].*)?\r$/s', $block, $matches);
                $a_data[$matches[1]] = $matches[2];
            }
        }
        return $a_data;
    }
}

class FormDataParser {

    private static $partSize = 4096;    //每次最大获取字节

    /**
     * 负责解析FormData
     */

    public static function parser($options = []) {
        //$options['saveFile'] = true; 测试能否正常保存临时文件

        $formData = fopen("php://input", "r");

        $retData = [];

        $boundary = rtrim(fgets($formData), "\r\n");     //第一行是boundary

        $info = []; //info段的信息
        $data = ''; //拼接的数据
        $infoPart = true; //是否是info段

        while ($line = fgets($formData, self::$partSize)) {
            if ($boundary . "\r\n" == $line || $boundary . "--\r\n" == $line) {
                //如果是分割
                $infoPart = true;

                if ($info['type'] == 'json') {
                    $data = json_decode($data, true);
                    $retData[$info['name']] = $data;
                } else if ($info['type'] == 'file') {

                    if (isset($info['tmp_file'])) {
                        fclose($info['file_handle']);
                        $retData[$info['name']] = [
                            'org_name' => $info['org_name'],
                            'tmp_file' => $info['tmp_file']
                        ];
                    } else {
                        $retData[$info['name']] = $data;
                    }
                }

                $data = '';
            } else if ("\r\n" == $line) {
                if ($infoPart) {
                    //解析info
                    $info = self::parserInfo($data, $options);
                    if (isset($info['tmp_file'])) {
                        $info['file_handle'] = fopen($info['tmp_file'], 'w');
                    }
                    $data = '';
                    $infoPart = false;
                }
            } else {
                if ($infoPart == false && isset($info['tmp_file'])) {
                    fwrite($info['file_handle'], $line);
                } else {
                    $data .= $line;
                }
            }
        }
        fclose($formData);

        print_r($retData);
    }

    private static function parserInfo($data, $options) {
        //获取参数名称, type
        $infoPattern = '/name="(.+?)"(; )?(filename="(.+?)")?/'; //todo: 待优化
        preg_match($infoPattern, $data, $matches);

        $info['name'] = $matches[1];
        $info['type'] = 'json';

        //如果是文件
        if (count($matches) > 4) {
            $info['type'] = 'file';
            $info['org_name'] = $matches[4];
            //如果设置保存文件, 保存到临时文件
            if (isset($options['saveFile']) && $options['saveFile']) {
                $tmpFile = tempnam(sys_get_temp_dir(), 'FD');
                $info['tmp_file'] = $tmpFile;
            }
        }

        return $info;
    }
}
