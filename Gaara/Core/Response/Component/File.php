<?php

declare(strict_types = 1);
namespace Gaara\Core\Response\Component;

use Closure;
use Iterator;
use Generator;
use Gaara\Core\Response;

class File {

	/**
	 * 下载文件
	 * @param string $filename
	 */
	public function downloadFile(string $path, string $name, string $showname) {
		$filename	 = $path . $name;
		$file		 = $filename;
		if (FALSE !== ($handler	 = fopen($file, 'r'))) {
			flock($handler, LOCK_EX);
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
	 * 发送 Generator 内容到缓冲区
	 * @param Generator $Generator
	 * @return Response
	 */
	public function downloadGenerator(Generator $Generator): Response {
		$this->obRestore(function() use ($Generator) {
			foreach ($Generator as $v) {
				obj(Response::class)->body()->setContent($v)->send();
			}
		}, 2, false);
		return obj(Response::class);
	}

	/**
	 * 输出并还原缓冲区
	 * @param Closure $Closure 输出 (echo)
	 * @param int $leastLevel 输出时剩余的缓冲层
	 * @param bool $restore 是否还原其他输出
	 * @return void
	 */
	private function obRestore(Closure $Closure, int $leastLevel = 0, bool $restore = true): void {
		$output		 = [];
		$MaxLevel	 = ob_get_level();
		for ($i = $leastLevel; $i < $MaxLevel; $i++) {
			$output[] = $restore ? ob_get_contents() : '';
			ob_end_clean();
		}
		$Closure();
		for ($i = $leastLevel; $i < $MaxLevel; $i++) {
			ob_start();
			echo array_pop($output);
		}
	}

}
