<?php
namespace index;
defined('IN_SYS')||exit('ACC Denied');
class indexContr extends \Business\businessController{
    public $phparray = 11231231;
    public function indexDo(){
        $this->cacheBegin('',3);
//        ob_start();
//        var_dump(222222222);
//        $cacheContent = ob_get_contents();
//        ob_clean();
//        ob_end_flush();
//        var_dump( $cacheContent);
//        exit;
//        var_dump($this->getThis());
        $arr = $this->cacheCall('testFuncCache');
        $arr = $this->cacheCall('test',obj('userObj'),true,'eeeee','qwe');
        $arr = $this->cacheCall('selRow',obj('userModule','admin'),false, '1');
        var_dump($arr);
//            $this->testFuncCache('par1','par2');
//       $this->dddd();
//        $this->test();
//        $this->display('test');
    }
    public function test(){
        $data = $this->post();
//        var_dump($data);
//        var_dump($_FILES);
        $this->returnData($data);
    }
    private function dddd(){
        $data = $this->get();
        $this->cacheBegin('',3);

        echo '缓存 '.date('Ymd H:i:s',time()).'</br>';
        $this->returnData($data);
    }
    public function cc(){
        echo 'i am cc';
        $this->cacheClear('index','userobj');
    }
    protected function testFuncCache(){
        $arr = func_get_args();
        return array('pars'=> $arr,'test'=>444,'arr'=>array('q'=>'q','w'=>'1222222222ww'));
    }
}