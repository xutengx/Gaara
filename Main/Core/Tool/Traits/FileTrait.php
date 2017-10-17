<?php

declare(strict_types = 1);
namespace Main\Core\Tool\Traits;

use Exception;

/**
 * 文件操作
 */
trait FileTrait {

    /**
     * 路径 转 绝对路径
     * @param string &$dir
     */
    public function absoluteDir(string &$dir = '') {
        $system = php_uname('s');
        $dir = str_replace('\\', '/', trim($dir));
        if (substr($system, 0, 5) === 'Linux') {
            $pos = strpos($dir, '/');
            if ($pos === false || $pos !== 0)
                $dir = ROOT . ltrim($dir, './');
        }else if (substr($system, 0, 7) === 'Windows') {
            $pos = strpos($dir, ':');
            if ($pos === false || $pos !== 1)
                $dir = ROOT . ltrim($dir, './');
        } else
            exit('未兼容的操作系统!');
    }

    // 分割下载 // test
    public function download2(string $path, string $name, string $showname) {
//        $this->absoluteDir($path);
        $filename = $path . $name;
        $file = $filename;
        if (FALSE !== ($handler = fopen($file, 'r'))) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . $showname . '.zip');
            header('Content-Transfer-Encoding: chunked');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            while (false !== ($chunk = fread($handler, 4096))) {
                echo $chunk;
            }
        }
        exit;
    }

    // 推送下载文件
    // param  路径  文件名
    public function download(string $path, string $name) {
//        $this->absoluteDir($path);
        $filename = $path . $name;
        $file = fopen($filename, "r");
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        header("Accept-Length:" . filesize($filename));
        header("Content-Disposition: attachment; filename=" . $name);
        echo fread($file, filesize($filename));
        fclose($file);
        exit();
    }

    /**
     * 递归删除 目录(绝对路径)下的所有文件,不包括自身
     * @param string $dirName 目录
     *
     * @return void
     */
    public function delDirAndFile(string $dirName = '') {
//        $this->absoluteDir($dirName);
        if (is_dir($dirName) && $dir_arr = scandir($dirName)) {
            foreach ($dir_arr as $k => $v) {
                if ($v == '.' || $v == '..') {
                    
                } else {
                    if (is_dir($dirName . '/' . $v)) {
                        $this->delDirAndFile($dirName . '/' . $v);
                        rmdir($dirName . '/' . $v);
                    } else
                        unlink($dirName . '/' . $v);
                }
            }
        }
    }

    /**
     * 将任意内容写进文件
     * @param string $fileName  文件名
     * @param string $text  内容
     * @return bool
     */
    public function printInFile(string $fileName = '', string $text = ''): bool {
        //if( ! $fileName || ! $text ) return false;
//        $this->absoluteDir($fileName);
//        var_dump($fileName);
        if (strripos($fileName, '/') === (strlen($fileName) - 1))
            return false;      // filename 为路径,而不是文件名
        if (!file_exists($fileName)) {
            if (is_dir(dirname($fileName)) || $this->__mkdir(dirname($fileName)))
                touch($fileName);
        }
        if ($fp = fopen($fileName, "wb")) {
            flock($fp, LOCK_EX | LOCK_NB);
            if (fwrite($fp, $text)) {
                fclose($fp);
                return true;
            } else {
                fclose($fp);
                return false;
            }
        }
        return false;
    }

    /**
     * 返回文件夹下的所有文件 组成的一维数组
     * @param string $dirName 文件夹路径
     * @return array 一维数组
     * @throws Exception
     */
    public function getFiles(string $dirName = ''): array {
        $dirName = rtrim($dirName, '/');
        $arr = array();
        if (is_dir($dirName) && $dir_arr = scandir($dirName)) {
            foreach ($dir_arr as $k => $v) {
                if ($v == '.' || $v == '..') {
                    
                } else {
                    if (is_dir($dirName . '/' . $v)) {
                        $arr = array_merge($arr, $this->getFiles($dirName . '/' . $v));
                    } else {
                        $arr[] = $dirName . '/' . $v;
                    }
                }
            }
            return $arr;
        } else
            throw new Exception($dirName . ' 并非可读路径!');
    }

    /**
     * 生成随机文件名
     * @param string $dir 文件所在的目录(相对,绝对)
     * @param string $ext 文件后缀
     * @param string $uni 唯一标识
     * @return string
     */
    final public function makeFilename(string $dir = '', string $ext = '', string $uni = 'def'): string {
        $ext = trim($ext, '.');
        $dir = $dir ? rtrim($dir, '/') . '/' : './';
        $dir .= uniqid($uni);
        $dir .= '.' . $ext;
        return $dir;
    }

    /**
     * 递归创建目录
     * @param string $dir 目录名(相对or绝对路径)
     * @param int    $mode 目录权限
     * @return bool
     */
    final public function __mkdir(string $dir = '', $mode = 0777): bool {
//        $this->absoluteDir($dir);
        if (is_dir(dirname($dir)) || $this->__mkdir(dirname($dir)))
            return mkdir($dir, $mode);
    }

}
