<?php

declare(strict_types = 1);
namespace Gaara\Core\Exception;

use Exception;

/**
 * 此异常类抛出的异常, 将会被`ExceptionHandler`中间件捕获, 并进行友好的响应
 */
class MessageException extends Exception {
    
}