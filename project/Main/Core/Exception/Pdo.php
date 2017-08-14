<?php

namespace Main\Core\Exception;
use \Main\Core\Conf;

class Pdo extends \Exception{
    // pdo 错误码
    protected $pdocode = null;
    // 错误码
    protected $code = null;
    // 错误信息
    protected $message = null;
    // 数据库链接对象
    protected $db = null;

    // 错误码 与 "解决办法"
    protected $code_map = array(
        1146 => 'table_not_exist'
    );
    public function __construct(\Throwable $previous = null, \Main\Core\DbConnection $db){
        $errorInfo = $previous->errorInfo;
        $this->pdocode = $errorInfo[0];
        $this->code = $errorInfo[1];
        $this->message = $errorInfo[2];
        $this->db = $db;
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
    
    protected function table_not_exist() {
        $datatables = obj(Conf::class)->datatables;
        $arr = explode(';', trim($datatables));
        if ($arr[count($arr) - 1] == '')
            unset($arr[count($arr) - 1]);
        $PDO = run($this->db, 'PDO');
        foreach ($arr as  $v) {
            $PDO->query($v);
        }
        return true;
    }
}