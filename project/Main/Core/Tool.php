<?php

namespace Main\Core;

use \Main\Core\Tool\Traits;
defined('IN_SYS') || exit('ACC Denied');
/**
 * 工具类
 */
class Tool {
    
    // 请求相关
    use Traits\RequestTrait;
    
    // 文件操作
    use Traits\FileTrait;

    // 字符处理
    use Traits\CharacterTrait;
    
    
}
