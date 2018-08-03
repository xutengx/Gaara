<?php

declare(strict_types = 1);
namespace Gaara\Core\Tool\Traits;

use Exception;

/**
 * 文件操作
 */
trait FileTrait {

	/**
	 * 路径 转 绝对路径
	 * @param string $dirOld
	 * @return string
	 * @throws Exception
	 */
	public static function absoluteDir(string $dirOld): string {
		$system = php_uname('s');
		$dir    = str_replace('\\', '/', trim($dirOld));
		if (substr($system, 0, 5) === 'Linux') {
			$pos = strpos($dir, '/');
			if ($pos === false || $pos !== 0)
				$dir = ROOT . ltrim($dir, './');
		}
		elseif (substr($system, 0, 7) === 'Windows') {
			$pos = strpos($dir, ':');
			if ($pos === false || $pos !== 1)
				$dir = ROOT . ltrim($dir, './');
		}
		else
			throw new Exception('Incompatible operating system');
		return $dir;
	}

	/**
	 * 递归删除 目录(绝对路径)下的所有文件,不包括自身
	 * @param string $dirName 目录(绝对)
	 * @return void
	 */
	public static function delDirAndFile(string $dirName = ''): void {
		if (is_dir($dirName) && $dir_arr = scandir($dirName)) {
			foreach ($dir_arr as $k => $v) {
				if ($v === '.' || $v === '..') {

				}
				else {
					if (is_dir($dirName . '/' . $v)) {
						static::delDirAndFile($dirName . '/' . $v);
						rmdir($dirName . '/' . $v);
					}
					else
						unlink($dirName . '/' . $v);
				}
			}
		}
	}

	/**
	 * 将任意内容写进文件
	 * @param string $filename 文件名(绝对)
	 * @param string $text 内容
	 * @return bool
	 */
	public static function printInFile(string $filename, string $text): bool {
		if (!is_file($filename)) {
			if (is_dir(dirname($filename)) || static::__mkdir(dirname($filename)))
				touch($filename);
		}
		return file_put_contents($filename, $text, LOCK_EX) === false ? false : true;
	}

	/**
	 * 递归创建目录
	 * @param string $dir 目录名(绝对路径)
	 * @param int $mode 目录权限
	 * @return bool
	 */
	public static function __mkdir(string $dir, int $mode = 0777): bool {
		return (is_dir(dirname($dir)) || static::__mkdir(dirname($dir))) ? mkdir($dir, $mode) : true;
	}

	/**
	 * 返回文件夹下的所有文件 组成的一维数组
	 * @param string $dirName 文件夹路径(绝对)
	 * @return array 一维数组
	 * @throws Exception
	 */
	public static function getFiles(string $dirName = ''): array {
		$dirName = rtrim($dirName, '/');
		$arr     = [];
		if (is_dir($dirName) && $dir_arr = scandir($dirName)) {
			foreach ($dir_arr as $k => $v) {
				if ($v === '.' || $v === '..') {

				}
				else {
					if (is_dir($dirName . '/' . $v)) {
						$arr = array_merge($arr, static::getFiles($dirName . '/' . $v));
					}
					else {
						$arr[] = $dirName . '/' . $v;
					}
				}
			}
			return $arr;
		}
		else
			throw new Exception($dirName . 'is not readable path!');
	}

	/**
	 * 生成随机文件名
	 * @param string $dir 文件所在的目录(绝对)
	 * @param string $ext 文件后缀
	 * @param string $uni 唯一标识
	 * @return string
	 */
	public static function makeFilename(string $dir, string $ext, string $uni = 'def'): string {
		$ext = trim($ext, '.');
		$dir = rtrim($dir, '/') . '/';
		$dir .= uniqid($uni);
		$dir .= '.' . $ext;
		return $dir;
	}

}
