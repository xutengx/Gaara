<?php

declare(strict_types = 1);
namespace Gaara\Core;

use PhpConsole;
use Gaara\Core\Conf;
use Gaara\Core\Response\Traits\{
	SetTrait, GetTrait, Format, RequestInfo
};
use Gaara\Core\Response\Component\{
	Header, Body, File
};

/**
 * 处理系统全部响应( 输出 )
 */
class Response {

	use SetTrait,
	 GetTrait,
	 Format,
	 RequestInfo;

	// 响应状态码
	public $status;
	private $header;
	private $body;
	private $file;

	public function __construct(Header $Header, Body $Body, File $File) {
		$this->header	 = $Header;
		$this->file		 = $File;
		$this->body		 = $Body;
		$this->setContentType($this->getAcceptType());
	}

	/**
	 *
	 * @return Header
	 */
	public function header(): Header {
		return $this->header;
	}

	/**
	 *
	 * @return Body
	 */
	public function body(): Body {
		return $this->body;
	}

	/**
	 *
	 * @return File
	 */
	public function file(): File {
		return $this->file;
	}

	/**
	 *
	 */
	public function send() {
		$this->header()->send();
		$this->body()->send();
	}

	/**
	 * 终止进程并响应内容, 可通过set方法设置状态码等 / exitData
	 * @param mixed $data
	 * @return void
	 */
	public function sendExit(): void {
		$level = ob_get_level();
		for ($i = 0; $i < $level; $i++) {
			ob_end_clean();
		}
		exit($this->send());
	}

	/**
	 * 返回页面
	 * @param string $file
	 * @return string
	 */
	public function view(string $file): string {
		$data = obj(Template::class)->view($file);
		return $this->setContentType('html')->response($data);
	}

}
