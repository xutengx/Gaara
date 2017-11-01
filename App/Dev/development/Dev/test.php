<?php
namespace App\development\Dev;
defined('IN_SYS') || exit('ACC Denied');

class test extends \Gaara\Core\Container {
    protected static $instance = asyncDev::class;
}
