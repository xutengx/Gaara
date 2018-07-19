<?php

declare(strict_types = 1);
namespace Gaara\Core;

use Gaara\Expand\JavaScriptPacker;
use Gaara\Contracts\ServiceProvider\Single;

class Template implements Single {

	// 跳转中间页面
	protected $jumpTo = 'jumpTo';

	// jquery 引入
	const jqueryDir		 = 'Gaara/Views/include/jquery/';
	// js 引入
	const jsDir			 = 'Gaara/Views/include/js/';
	// js_plugins 引入
	const pluginsDir		 = 'Gaara/Views/include/plugins/';
	// js_plugins 所需的css 引入
	const pluginsCssDir	 = 'Gaara/Views/include/css/';
	// 自动压缩后js 存放的目录, public 下
	const dataDir			 = 'open/minStatic/';

	/**
	 * 插入页面内容
	 * @param string $file
	 * @return bool
	 */
	public function show(string $file): bool {
		include ROOT . 'App/' . APP . '/View/template/' . $file . '.html';
		return true;
	}

	/**
	 * 返回页面内容
	 * @param string $file
	 * @return string
	 */
	public function view(string $file): string {
		return file_get_contents(ROOT . 'Gaara/Views/tpl/' . $file . '.html');
	}

	/**
	 * 跳转中间页
	 * @param string $message
	 * @param string $jumpUrl
	 */
	public function jumpTo(string $url, string $message, int $waitSecond = 3) {
		include ROOT . 'Gaara/Views/tpl/' . $this->jumpTo . '.html';
		exit;
	}

	/**
	 * 自动加载静态文件 , 目前仅在控制器父类 Controller->display() 中调用并缓存
	 * @return string JS引入语句 (直接echo即可使用)
	 */
	public function includeFiles(): string {
		$str = '';
		$str .= $this->createMin(ROOT . self::jqueryDir);
		$str .= '<script>jQuery.extend({inpath:"' . self::dataDir . '"});</script>';
		$str .= $this->createMin(ROOT . self::jsDir);
		$this->createMin(ROOT . self::pluginsDir);
		$this->createMin(ROOT . self::pluginsCssDir);
		return $str;
	}

	/**
	 * 生成压缩文件
	 * @param string $originaDir 需要压缩的js所在目录
	 * @param string $newDir 压缩后的js存放目录
	 * @return string JS引入语句 (直接echo即可使用)
	 */
	protected function createMin(string $originaDir, $newDir = null): string {
		$newDir	 = is_null($newDir) ? self::dataDir : $newDir;
		$files	 = obj(Tool::class)->getFiles($originaDir);
		$str	 = '';
		foreach ($files as $v) {
			$ext = strrchr($v, '.');
			if ($ext === '.js') {
				$jsname = $newDir . str_replace($originaDir, '', $v);
				if (!is_file($jsname) || filemtime($v) > filectime($jsname)) {
					$content = $this->AutomaticPacking(file_get_contents($v));
					obj(Tool::class)->printInFile($jsname, $content);
				}
				$str .= '<script src="' . obj(Request::class)->hostStaticInfo . $jsname . '"></script>';
			} elseif ($ext === '.css') {
				$jsname = $newDir . str_replace($originaDir, '', $v);
				if (!is_file($jsname) || filemtime($v) > filectime($jsname)) {
					$content = $this->compressCss(file_get_contents($v));
					obj(Tool::class)->printInFile($jsname, $content);
				}
				$str .= '<link rel="stylesheet" type="text/css" href="' . obj(Request::class)->hostStaticInfo . $jsname . '" />';
			}
		}
		return $str;
	}

	/**
	 * 压缩js,比较2种模式
	 * @param string $content 压缩前js内容
	 * @return string 压缩后js
	 */
	protected function AutomaticPacking(string $content): string {
		$packerNormal	 = (new JavaScriptPacker($content, 'Normal', false, false))->pack();
		$packerNone		 = (new JavaScriptPacker($content, 'None', false, false))->pack();
		return strlen($packerNormal) > strlen($packerNone) ? $packerNone : $packerNormal;
	}

	/**
	 * 压缩css
	 * @param string $content 压缩前css内容
	 * @return string 压缩后css内容
	 */
	protected function compressCss(string $content): string {
		/* remove comments */
		$content = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $content);
		/* remove tabs, spaces, newlines, etc. */
		$content = str_replace(array("
", "\r", "\n", "\t", '  ', '    ', '    '), '', $content);
		return $content;
	}

}
