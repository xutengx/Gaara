<?php

declare(strict_types = 1);
namespace Gaara\Core\Cache\Driver;

use Gaara\Core\Cache\DriverInterface;

class File implements DriverInterface {

    // 缓存目录
    private $cacheRoot;
    // 缓存扩展名
    private $cacheFileExt = '.php';

    final public function __construct($options = array()) {
        $this->cacheRoot = isset($options['dir']) ? ROOT . $options['dir'] : ROOT . 'data/Cache/';
    }

    /**
     * 读取缓存
     * @param string $key 键
     * @return string|false
     */
    public function get(string $key) {
        $filename = $this->makeFilename($key);
        if (is_file($filename) && $content = file_get_contents($filename)) {
            $expire = (int) substr($content, 8, 12);
            $filemtime = (int) substr($content, 20, 12);
            $time = $this->getExpire($filemtime, $expire);
            if ($time === 0) {
                //缓存过期删除缓存文件
                unlink($filename);
                return false;
            }
            return substr($content, 32, -3);
        } else
            return false;
    }

    /**
     * 设置缓存
     * @param string $key 键
     * @param string $value 值
     * @param int $expire 缓存有效时间 , -1表示无过期时间
     * @return bool
     */
    public function set(string $key, string $value, int $expire): bool {
        $filename = $this->makeFilename($key);
        $data = "<?php\n//" . sprintf('%012d', $expire) . sprintf('%012d', time()) . $value . "\n?>";
        return $this->saveFile($filename, $data);
    }

    /**
     * 删除单一缓存
     * @param string $key 键
     * @return bool
     */
    public function rm(string $key): bool {
        $filename = $this->makeFilename($key);
        return is_file($filename) && unlink($filename);
    }

    /**
     * 批量清除缓存
     * @param string $key
     * @return bool
     */
    public function clear(string $key): bool {
        $cachedir = $this->cacheRoot . $key;
        $this->del_DirAndFile($cachedir);
        return rmdir($cachedir);
    }

    /**
     * 得到一个key的剩余有效时间
     * @param string $key
     * @return int 0表示过期, -1表示无过期时间, -2表示未找到key
     */
    public function ttl(string $key): int {
        $filename = $this->makeFilename($key);
        if (is_file($filename) && $content = file_get_contents($filename)) {
            $expire = (int) substr($content, 8, 12);
            $filemtime = (int) substr($content, 20, 12);
            $time = $this->getExpire($filemtime, $expire);
            if ($time === 0) {
                //缓存过期删除缓存文件
                unlink($filename);
                return -2;
            }
            return $time;
        } else
            return -2;
    }

    /**
     * 返回过期剩余时间, -1表示无过期时间
     * @param int $filemtime
     * @param int $expir
     * @return int
     */
    private function getExpire(int $filemtime, int $expir): int {
        if ($expir === -1)
            return -1;
        $time = $filemtime + $expir - time();
        return ( $time > 0 ) ? $time : 0;
    }

    /**
     * 将key转化为目录
     * @param string $key
     * @return string
     */
    private function makeFilename(string $key): string {
        return $this->cacheRoot . $key . $this->cacheFileExt;
    }

    /**
     * 递归删除 目录(绝对路径)下的所有文件,bu包括自身
     * @param string $dirName
     * @return void
     */
    private function del_DirAndFile(string $dirName): void {
        if (is_dir($dirName) && $dir_arr = scandir($dirName)) {
            foreach ($dir_arr as $k => $v) {
                if ($v === '.' || $v === '..') {
                    
                } else {
                    if (is_dir($dirName . '/' . $v)) {
                        $this->del_DirAndFile($dirName . '/' . $v);
                        rmdir($dirName . '/' . $v);
                    } else
                        unlink($dirName . '/' . $v);
                }
            }
        }
    }

    /**
     * 写入文件
     * @param string $filename 文件名(绝对路径)
     * @param string $text
     * @return bool
     */
    private function saveFile(string $filename, string $text): bool {
        if (!is_file($filename)) {
            if (is_dir(dirname($filename)) || $this->_mkdir(dirname($filename)))
                touch($filename);
        }
        return file_put_contents($filename, $text, LOCK_EX) === false ? false : true;
    }

    /**
     * 递归生成目录
     * @param type $dir
     * @param type $mode
     * @return type
     */
    private function _mkdir(string $dir, int $mode = 0777): bool {
        if (is_dir(dirname($dir)) || $this->_mkdir(dirname($dir)))
            return mkdir($dir, $mode);
    }

}
