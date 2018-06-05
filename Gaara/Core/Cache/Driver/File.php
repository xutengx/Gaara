<?php

declare(strict_types = 1);
namespace Gaara\Core\Cache\Driver;

use Gaara\Core\Cache\DriverInterface;
use Closure;

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
		$filename	 = $this->makeFilename($key);
		if (is_file($filename) && $content	 = file_get_contents($filename)) {
			$expire		 = (int) substr($content, 8, 12);
			$filemtime	 = (int) substr($content, 20, 12);
			$time		 = $this->getExpire($filemtime, $expire);
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
		$filename	 = $this->makeFilename($key);
		$data		 = "<?php\n//" . sprintf('%012d', $expire) . sprintf('%012d', time()) . $value . "\n?>";
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
		$filename	 = $this->makeFilename($key);
		if (is_file($filename) && $content	 = file_get_contents($filename)) {
			$expire		 = (int) substr($content, 8, 12);
			$filemtime	 = (int) substr($content, 20, 12);
			$time		 = $this->getExpire($filemtime, $expire);
			if ($time === 0) {
				unlink($filename); //缓存过期删除缓存文件
				return -2;
			}
			return $time;
		} else
			return -2;
	}

	/**
	 * 自减 (原子性)
	 * @param string $key
	 * @param int $step
	 * @return int 自减后的值
	 */
	public function decrement(string $key, int $step = 1): int {
		return $this->increment($key, $step * -1);
	}

	/**
	 * 自增 (原子性)
	 * @param string $key
	 * @param int $step
	 * @return int 自增后的值
	 */
	public function increment(string $key, int $step = 1): int {
		return $this->lock($key, function($handle)use ($step) {
			$value		 = $this->getWithLock($handle);
			$new_value	 = (int) $value + $step; // (int)false === 0
			$expire		 = $this->ttlWithLock($handle);
			$new_expire	 = ( $expire === -2 ) ? -1 : $expire;
			if ($this->setWithLock($handle, (string) $new_value, $new_expire)) {
				return $new_value;
			} else
				throw new Exception('Cache Increment Error!');
		});
	}

	/**
	 * 以独占锁开启一个文件, 并执行闭包
	 * @param string $key
	 * @param Closure $callback
	 * @return mixed
	 */
	private function lock(string $key, Closure $callback) {
		$filename	 = $this->makeFilename($key);
		$type		 = is_file($filename) ? 'rb+' : 'wb+';
		if ($handle		 = fopen($filename, $type)) {
			if (flock($handle, LOCK_EX)) {
				$res = $callback($handle);
				flock($handle, LOCK_UN);
			}
			fclose($handle);
			return $res;
		}
	}

	/**
	 * 读取缓存
	 * @param steam $handle 文件句柄
	 * @return string|false
	 */
	private function getWithLock($handle) {
		$content = '';
		while (!feof($handle)) {//循环读取，直至读取完整个文件
			$content .= fread($handle, 1024);
		}
		$expire		 = (int) substr($content, 8, 12);
		$filemtime	 = (int) substr($content, 20, 12);
		$time		 = $this->getExpire($filemtime, $expire);
		if ($time === 0) {
			return false;
		}
		return substr($content, 32, -3);
	}

	/**
	 * 得到一个key的剩余有效时间
	 * @param steam $handle 文件句柄
	 * @return int 0表示过期, -1表示无过期时间, -2表示未找到key
	 */
	private function ttlWithLock($handle): int {
		$content = '';
		while (!feof($handle)) {//循环读取，直至读取完整个文件
			$content .= fread($handle, 1024);
		}
		$expire		 = (int) substr($content, 8, 12);
		$filemtime	 = (int) substr($content, 20, 12);
		$time		 = $this->getExpire($filemtime, $expire);
		if ($time === 0) {
			return -2;
		}
		return $time;
	}

	/**
	 * 设置缓存
	 * @param steam $handle 文件句柄
	 * @param string $value 内容
	 * @param int $expire 过期时间
	 * @return int 写入字符数
	 */
	private function setWithLock($handle, string $value, int $expire): int {
		$data = "<?php\n//" . sprintf('%012d', $expire) . sprintf('%012d', time()) . $value . "\n?>";
		rewind($handle);  // 重置指针
		ftruncate($handle, 0); // 清空文件
		return fwrite($handle, $data);
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
