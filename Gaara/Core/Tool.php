<?php

declare(strict_types = 1);
namespace Gaara\Core;

use \Gaara\Core\Tool\Traits\{
	RequestTrait, FileTrait, CharacterTrait
};
use Gaara\Contracts\ServiceProvider\Single;

/**
 * 工具类
 */
class Tool implements Single {

// 请求相关
	use RequestTrait;

// 文件操作
	use FileTrait;

// 字符处理
	use CharacterTrait;
}
