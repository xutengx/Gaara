<?php

namespace Main\Core\Exception;

use \Main\Core\Conf;
use \App\Kernel;
use \ReflectionClass;

class Pdo extends \Exception{
    // pdo 错误码
    private $pdocode = null;
    // 错误码
    protected $code = null;
    // 错误信息
    protected $message = null;
    // 数据库链接对象
    private $db = null;
    // 本次数据库的链接类型, 返回时还原
    private $pdo_type = null;

    // 错误码 与 "解决办法"
    private $code_map = array(
        1146 => 'table_not_exist'
    );
    public function __construct(\Throwable $previous = null, \Main\Core\DbConnection $db){
        $errorInfo = $previous->errorInfo;
        $this->pdocode = $errorInfo[0];
        $this->code = $errorInfo[1];
        $this->message = $errorInfo[2];
        $this->db = $db;
        $this->pdo_type = $this->getProp($db, 'type');
        parent::__construct($this->message, $this->code, $previous);

        $this->dealException();
    }
    /**
     * 尝试处理, 若没有对应的"解决办法", 则将原异常抛出
     * 1.尝试 userException 中是否存在 pdo 数组, 且其中包含对应错误码;
     * 2.若1不存在(1只要存在, 不论成功失败, 均不执行此条), 则 尝试 $this->code_map 是否包含对应错误码
     * @return type
     * @throws \Exception
     */
    private function dealException(){
        $Kernel = obj(Kernel::class);
        if(isset($Kernel->pdo[$this->code])){
            obj($Kernel->pdo[$this->code])->handle($this->message, $this->db);
            // 还原数据库链接的 链接类型
            $this->setProp($this->db, 'type', $this->pdo_type);
        }
        elseif(isset($this->code_map[$this->code])){
            $this->{$this->code_map[$this->code]}();
            // 还原数据库链接的 链接类型
            $this->setProp($this->db, 'type', $this->pdo_type);
        }else{
            throw new \Exception($this);
        }
    }
    
    // 动态调用
    private function table_not_exist() {
        $datatables = obj(Conf::class)->datatables;
        $arr = explode(';', trim($datatables));
        if ($arr[count($arr) - 1] == '')
            unset($arr[count($arr) - 1]);
        foreach ($arr as  $v) {
            $this->db->insert($v);
        }
        return true;
    }
    
    /**
     * 在外更改私有属性
     * @param object $cls       对象
     * @param string $prop      属性
     * @param string $value     值
     * @return boolean
     */
    private function setProp($cls, string $prop, string $value) {
        $reflectCls = new ReflectionClass($cls);
        $pro = $reflectCls->getProperty($prop);
        if ($pro && ($pro->isPrivate() || $pro->isProtected())) {
            $pro->setAccessible(true);
            $pro->setValue($cls, $value);
        } else {
            $cls->$prop = $value;
        }
        return true;
    }
    
    /**
     * 在外获取私有属性
     * @param object $cls       对象
     * @param string $prop      属性
     * @param string $value     值
     * @return mix
     */
    private function getProp($cls, string $prop) {
        $value = null;
        $reflectCls = new ReflectionClass($cls);
        $pro = $reflectCls->getProperty($prop);
        if ($pro && ($pro->isPrivate() || $pro->isProtected())) {
            $pro->setAccessible(true);
            $value = $pro->getValue($cls);
        } else {
            $value = $cls->$prop;
        }
        return $value;
    }
}