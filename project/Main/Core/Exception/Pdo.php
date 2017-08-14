<?php

namespace Main\Core\Exception;

class Pdo extends \Exception{
    // pdo 错误码
    protected $pdocode = null;
    // 错误码
    protected $code = null;
    // 错误信息
    protected $message = null;

    // 错误码 与 "解决办法"
    protected $code_map = array(
        1146 => 'table_not_exist'
    );
    public function __construct(\Throwable $previous = null){
        $errorInfo = $previous->errorInfo;
        $this->pdocode = $errorInfo[0];
        $this->code = $errorInfo[1];
        $this->message = $errorInfo[2];
        parent::__construct($this->message, $this->code, $previous);
        $this->dealException();
    }
    /**
     * 尝试处理, 若没有对应的"解决办法", 则将原异常抛出
     * @return type
     * @throws \Exception
     */
    protected function dealException(){
        if(isset($this->code_map[$this->code])){
            return $this->{$this->code_map[$this->code]}();
        }
        throw new \Exception($this);
    }
    
    protected function table_not_exist(){
        var_dump($this->message);
    }
}