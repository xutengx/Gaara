<?php

declare(strict_types = 1);
namespace Gaara\Core\Response\Component;

use Closure;
use Iterator;
use Generator;
use Gaara\Core\{
	Tool, Response
};
use Gaara\Core\Model\QueryChunk;

class File {

	/**
	 * 下载文件
	 * @param string $filename
	 */
	public function downloadFile2(string $path, string $name, string $showname) {
		$filename	 = $path . $name;
		$file		 = $filename;
		if (FALSE !== ($handler	 = fopen($file, 'r'))) {
			flock($handler, LOCK_SH);
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
			flock($handler, LOCK_UN);
		}
	}

	/**
	 * 直接下载某个文件
	 * @param string $downloadFile
	 * @param string $downloadFileName
	 */
	public function download(string $downloadFile, string $downloadFileName = null) {
		$file		 = obj(Tool::class)->absoluteDir($downloadFile);
		$fileName = $downloadFileName ?? basename($file);
		$fileHandle	 = fopen($file, "r");
		flock($fileHandle, LOCK_SH);
		obj(Response::class)->header()->set('Content-type', 'application/octet-stream');
		obj(Response::class)->header()->set('Accept-Ranges', 'bytes');
		obj(Response::class)->header()->set('Accept-Length', filesize($file));
		obj(Response::class)->header()->set('Content-Disposition', "attachment; filename=" . $fileName);
		obj(Response::class)->setContent(fread($fileHandle, filesize($file)))->sendReal();
		flock($fileHandle, LOCK_UN);
		fclose($fileHandle);
		return obj(Response::class);
	}

	/**
	 * 将数据库分块 (getChunk()) 或者 数据库结果 (getAll()), 导出为csv格式
	 * 为性能提升, 将直接发送响应头
	 * @param array|QueryChunk $QueryChunkOrArray
	 * @param string $downloadFileName
	 * @return Response
	 */
	public function exportCsv($QueryChunkOrArray, string $downloadFileName = null): Response {
		$filename = $downloadFileName ? rtrim($downloadFileName, '.csv') . '.csv' : time() . '.csv';

		obj(Response::class)->header()->set('Content-Type', 'mime/type')
		->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

		// 手动 sendReal 避免频繁开关缓冲区
		obj(Response::class)->obRestore(function() use ($QueryChunkOrArray) {
			$is_QueryChunk = ($QueryChunkOrArray instanceof QueryChunk) ? true : false;
			foreach ($QueryChunkOrArray as $v) {
				obj(Response::class)->setContent($this->arrayKeyValueToCsvRows($v, $is_QueryChunk))->send();
				break;
			}
			foreach ($QueryChunkOrArray as $v) {
				obj(Response::class)->body()->setContent($this->arrayValueToCsvRow($v))->send();
			}
		}, 0, false);
		return obj(Response::class);
	}

	/**
	 * 一位数组的键和值分别转化为csv的一行
	 * 生成器需要返回2行, 而普通数组只需要1行
	 * @param array $arr
	 * @bool $is_QueryChunk
	 * @return string
	 */
	private function arrayKeyValueToCsvRows(array $arr, bool $is_QueryChunk = true): string {
		$keyArr	 = array_keys($arr);
		$str	 = '"' . implode('","', $keyArr) . '"' . "\n";
		$str	 .= $is_QueryChunk ? $this->arrayValueToCsvRow($arr) : '';
		return $str;
	}

	/**
	 * 一位数组的值转化为csv的一行
	 * 在数字左侧加上等号
	 * @param array $arr
	 * @return string
	 */
	private function arrayValueToCsvRow(array $arr): string {
		$str = '';
		foreach ($arr as $v)
			$str .= (is_numeric($v) ? '=' : '') . '"' . $v . '",';
		return rtrim($str, ',') . "\n";
	}

}
