<?php

declare(strict_types = 1);
namespace Main\Core\Pipeline\Traits;
defined('IN_SYS') || exit('ACC Denied');

/**
 * 设置管道流程
 */
trait SetPipes {

    /**
     * 重新设置当前执行流程
     * @param array $arr    
     * @return bool
     */
    public function setPipes(array $arr): bool {
        $this->pipes = $arr;
        return true;
    }

    /**
     * 返回当前流程
     * @return array
     */
    public function getPipes(): array {
        return $this->pipes;
    }
    
    /**
     * 加入一个流程到尾部
     * @param string $step
     * @return int
     */
    public function pipesPush(string $step): int {
        return array_push($this->pipes, $step);
    }
    
    /**
     * 加入一个流程到头部
     * @param string $step
     * @return int
     */
    public function pipesUnshift(string $step): int {
        return array_unshift($this->pipes, $step);
    }
    
    /**
     * 设置管道方法
     * @param string $func  方法名
     * @return bool
     */
    public function setFunc(string $func) : bool{
        $this->func = $func;
        return true;
    }
    
    /**
     * 返回管道方法
     * @param string $func  方法名
     * @return string
     */
    public function getFunc() : string{
        return $this->func;
    }
}
