<?php

declare(strict_types = 1);
namespace Gaara\Core\Request\Traits;

use Closure;

/**
 * 客户端相关信息获取
 */
trait Filter {

	public $filterArr = [
		'email'		 => '/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/',
		'url'		 => '/\b(([\w-]+:\/\/?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|\/)))/',
		// 可以正负数字
		'int'		 => '/^-?\d+$/',
		// 密码(允许5-32字节，允许字母数字下划线)
		'passwd'	 => '/^[\w]{5,32}$/',
		// 帐号(字母开头，允许5-16字节，允许字母数字下划线)
		'account'	 => '/^[a-zA-Z][a-zA-Z0-9_]{5,16}$/',
		// 身份证：中国的身份证为15位或18位
		'idcard'	 => '/^\d{15}|\d{18}$/',
		// 中国邮政编码
		'mail'		 => '/^[1-9]\d{5}(?!\d)$/',
		// 腾讯QQ号 腾讯QQ号从10000开始
		'qq'		 => '/^[1-9][0-9]{4,}$/',
		// 国内电话号码 匹配形式如 0511-4405222 或 021-87888822
		'telephone'	 => '/^\d{3}-\d{8}|\d{4}-\d{7}$/',
		// 中国手机号码：(86)*0*13\d{9}
		'tel'		 => '/^1[3|4|5|7|8][0-9]\d{8}$/',
		// 大小写字母,数字,下划线
		'string'	 => '/^\w+$/',
		// 大小写字母,数字,下划线,减号'-'
		'token'		 => '/^[\w-]+$/',
		// 大小写字母,数字,下划线 32位
		'sign'		 => '/^[\w]{32}$/',
		// 2-8位
		'name'		 => '/^[_\w\d\x{4e00}-\x{9fa5}]{2,8}$/iu'
	];

	public function get(string $key, string $filter = null) {
		return $this->filterFunc('get', $key, $filter);
	}

	public function post(string $key, string $filter = null) {
		return $this->filterFunc('post', $key, $filter);
	}

	public function put(string $key, string $filter = null) {
		return $this->filterFunc('put', $key, $filter);
	}

	public function delete(string $key, string $filter = null) {
		return $this->filterFunc('delete', $key, $filter);
	}

	public function head(string $key, string $filter = null) {
		return $this->filterFunc('head', $key, $filter);
	}

	public function options(string $key, string $filter = null) {
		return $this->filterFunc('options', $key, $filter);
	}

	public function patch(string $key, string $filter = null) {
		return $this->filterFunc('patch', $key, $filter);
	}

	public function cookie(string $key, string $filter = null) {
		return $this->filterFunc('cookie', $key, $filter);
	}

	/**
	 * 获取当前请求类型的参数
	 * @param string $key
	 * @param string $filter 预定规则 or 正则表达式
	 * @return type
	 */
	public function input(string $key, string $filter = null) {
		return $this->filterFunc($this->method, $key, $filter);
	}

	/**
	 * 过滤请求参数, 参数不存在返回null, 验证不通过返回false
	 * @param string $method
	 * @param string $key
	 * @param string|false $filter
	 * @return mixed|false|null
	 */
	private function filterFunc(string $method, string $key, string $filter = null) {
		if (isset($this->$method[$key]))
			return (is_null($filter) || $this->filterMatch($this->$method[$key], $filter))
				? $this->$method[$key] : false;
		else
			return null;
	}

	/**
	 * 正则匹配
	 * @param string $str 检验对象
	 * @param string $filter 匹配规则
	 * @return bool
	 */
	private function filterMatch(string $str, string $filter): bool {
		$rule = $this->filterArr[$filter] ?? $filter;
		if ($rule instanceof Closure) {
			return $rule($str) ? true : false;
		}
		return preg_match($rule, $str) ? true : false;
	}

}
