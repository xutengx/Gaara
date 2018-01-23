<?php

declare(strict_types = 1);
namespace Gaara\Expand;

use Gaara\Core\Conf;
use PHPMailer;

/**
 * 邮件发送类
 */
class Mail {

	private $mail = null;

	public function __construct(Conf $conf, PHPMailer $mail) {
		$this->mail = $mail;
		foreach ($conf->mail as $k => $v) {
			$mail->$k = $v;
		}
	}

	/**
	 * 用于调试, 返回当前所有配置
	 */
	public function getAllInfo() {
		$ref	 = new \ReflectionClass($this->mail);
		$vars	 = $ref->getProperties();
		foreach ($vars as $v) {
			echo $v->getName() . ' = ';
			$v->setAccessible(true);
			var_dump($v->getValue($this->mail));
			echo '<hr>';
		}
		exit();
	}

	/**
	 * addReplyTo('1771033392@qq.com', '回复人xt');  // 设置邮件回复人地址和名称, 可多次调用
	 * AddAddress('896622242@qq.com', '896622242@qq的名字'); // 设置谁能收到邮件, 可多次调用
	 * send() 开始发送~
	 * @param type $method
	 * @param type $args
	 * @return type
	 */
	final public function __call(string $method, array $args) {
		return $this->mail->$method(...$args);
	}

	final public function __set(string $param, $value) {
		return $this->mail->$param = $value;
	}

	final public function __get(string $name) {
		return $this->mail->$name;
	}

}
