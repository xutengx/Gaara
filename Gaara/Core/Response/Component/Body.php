<?php

declare(strict_types = 1);
namespace Gaara\Core\Response\Component;

use Gaara\Core\Tool;

class Body {

	protected $contentType;
	protected $content;
	protected $char;

	/**
	 * 设置Http响应的文档类型
	 * @param string $contentType
	 * @return Body
	 */
	public function setContentType(string $contentType): Body {
		$this->contentType = $contentType;
		return $this;
	}

	/**
	 * 设置响应字符集
	 * @param string $char
	 * @return \Gaara\Core\Response\Component\Body
	 */
	public function setChar(string $char): Body {
		$this->char = $char;
		return $this;
	}

	/**
	 * 发送响应
	 * @return Body
	 */
	public function send(): Body {
		echo $this->encode($this->content);
		return $this->setContent(null);
	}

	/**
	 * 编码响应内容
	 * @param mixed $data
	 * @return string
	 */
	protected function encode($data): string {
		$encode = '';
		switch ($this->contentType) {
			case 'json':
				$encode = json_encode($data, JSON_UNESCAPED_UNICODE);
				break;
			case 'xml':
				$encode = obj(Tool::class)->xml_encode($data, $this->char);
				break;
			case 'php':
				$encode = serialize($data);
				break;
			case 'html':
				$encode = is_array($data) ? json_encode($data, JSON_UNESCAPED_UNICODE) : $data;
				break;
		}
		return (string)$encode;
	}

	/**
	 * 设置响应内容
	 * @param mixed $content
	 * @return \Gaara\Core\Response\Component\Body
	 */
	public function setContent($content): Body {
		$this->content = $content;
		return $this;
	}

}
