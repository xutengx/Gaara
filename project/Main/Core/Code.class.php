<?php
namespace Main\Core;
defined('IN_SYS')||exit('ACC Denied');
class Code{
    private $tool = null;
    public function __construct(){
        $this->tool = obj('\Main\Core\Tool');
    }
    public function makeModule($where,$namespace,$classname){
        $module = <<<EOF
<?php
namespace {$namespace};
defined('IN_SYS')||exit('ACC Denied');
class {$classname} extends \Business\businessModule{

}
EOF;
        return $this->tool->printInFile($where,$module);
    }
    public function makeObject($where,$namespace,$classname){
        $module = <<<EOF
<?php
namespace {$namespace};
defined('IN_SYS')||exit('ACC Denied');
class {$classname} extends \Business\businessObject{

}
EOF;
        return $this->tool->printInFile($where,$module);
    }
    public function makeBusiness($where, $state, $classname){
        $contr = <<<EOF
<?php
namespace business;
use \Main\Core;
defined('IN_SYS')||exit('ACC Denied');
class {$classname} extends Core\Controller{

}
EOF;
        $controller = <<<EOF
<?php
namespace business;
use \Main\Core;
defined('IN_SYS')||exit('ACC Denied');
class {$classname} extends Core\Controller{

}
EOF;
        $module = <<<EOF
<?php
namespace business;
use \Main\Core;
defined('IN_SYS')||exit('ACC Denied');
class {$classname} extends Core\Module{

}
EOF;
        $object = <<<EOF
<?php
namespace business;
use \Main\Core;
defined('IN_SYS')||exit('ACC Denied');
class {$classname} extends Core\Object{

}
EOF;
        $base = <<<EOF
<?php
namespace business;
use \Main\Core;
defined('IN_SYS')||exit('ACC Denied');
class {$classname} extends Core\Base{

}
EOF;
        return $this->tool->printInFile($where,$$state);
    }
}