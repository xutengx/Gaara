<?php
namespace Main\Core;
defined('IN_SYS')||exit('ACC Denied');
class Code{
    private $tool = null;
    public function __construct(){
        $this->tool = obj(Tool::class);
    }
    public function makeModule($where, $classname){
        $namespace = $this->makeNamespace($classname);
        $module = <<<EOF
<?php
namespace {$namespace};
defined('IN_SYS')||exit('ACC Denied');
class {$classname} extends \Main\Core\Model{

}
EOF;
        return $this->tool->printInFile($where,$module);
    }
    public function makeObject($where, $classname){
        $namespace = $this->makeNamespace($classname);
        $module = <<<EOF
<?php
namespace {$namespace};
defined('IN_SYS')||exit('ACC Denied');
class {$classname} extends \Main\Core\Object{

}
EOF;
        return $this->tool->printInFile($where,$module);
    }
    
    public function makeCopy($className, $realClassName) {
        $where = $className;
        $namespace = $this->makeNamespace($className);
        $code = <<<EOF
<?php
namespace {$namespace};
defined('IN_SYS')||exit('ACC Denied');
class {$className} extends \Main\Core\Container {
    protected static \$instance = \\{$realClassName}::class; 
}
EOF;
        return $this->tool->printInFile($where.'.php', $code);
    }
    
    /**
     * 将完整类名,分割成 命名空间 和 类名
     * @param $classname
     * @return string &$classname
     */
    private function makeNamespace(&$classname){
        $is = strrpos($classname, '\\');
        $namespace = substr($classname, 0 ,(int)$is);
        $classname = substr($classname, (int)$is+1);
        return $namespace;
    }
}