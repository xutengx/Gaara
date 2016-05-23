<?php
namespace Main\Core\Controller\Module;
defined('IN_SYS')||exit('ACC Denied');
trait ViewModule{
    // 缓存js赋值
    protected $cache = ';';
    // 缓存php赋值
    protected $phparray = array();
    // 当前Contr所在app名
    protected $app = NULL;
    // 当前Contr名
    protected $classname = NULL;

    public function ViewModuleConstruct(){
        $app = explode('\\', get_class($this));
        if($app[0] == 'App'){
            $this->app = $app[1];
            $this->classname = str_replace('Contr','',$app[3]);
        }
    }
    public function indexDo(){
        $this->display();
    }
    /**
     * @param int    $re   状态标记
     * @param string $msg  状态描述
     *
     * @return bool
     */
    protected function returnMsg($re=1, $msg='fail!'){
        echo json_encode( array('state'=>$re, 'msg'=>$msg));
        return true;
    }
    /**
     * echo json
     * @param string $re
     *
     * @return bool
     */
    protected function returnData($re=''){
        if ($re !== false && $re !== null && $re !== 0 && $re !== -1) {
            echo json_encode(array('state' => 1, 'data' => $re));
        } else $this->returnMsg(0);
        return true;
    }
    // 以组件方式引入html
    final protected function template($file=false){
        $file = $file ? $file : $this->classname;
        $this->assignPhp('T_VIEW','App/'.$this->app.'/View/');
        $DATA = $this->phparray;
        include ROOT.'App/'.$this->app.'/View/template/'.$file.'.html';
        echo '<script>'.$this->cache.'</script>';
    }
    // 写入script路由方法,js赋值,并引入html文件
    final protected function display($file=false){
        $file = $file ? $file : $this->classname;
        $debug = debug_backtrace();
        // 方法名,为调用此方法的最近级别函数
        $method = $debug[1]['function'];

        $DATA = $this->phparray;
        $this->assign('path',PATH);
        $this->assign('in_sys',IN_SYS);
        $this->assign('view', VIEW);
        $this->assign('contr', $this->classname );
        $this->assign('method', $method);
        // 防scrf的ajax(基于jquery), 接受post提交数据前.先验证http头中的 csrftoken
        $ajax = obj('Secure')->csrfAjax($this->classname);
        // js 路由方法
        $str = 'function __url__(Application, Controller, method){if(arguments.length==1){method=Application;Controller="'.$this->classname.'";Application="'.$this->app.'";}else if(arguments.length==2) {method=Controller;Controller=Application;Application="'.$this->app.'";} var url=window.location.protocol+"//"+window.location.host+window.location.pathname+"?'.PATH.'="+Application+"/"+Controller+"/"+method+"/"; return url;}';
        echo <<<EEE
<!DOCTYPE html>
<html lang="zh-CN" xml:lang='zh-CN' xmlns='http://www.w3.org/1999/xhtml'>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
EEE;
        // 公用view
        obj('cache')->cacheCall(obj('Template'), 'includeFiles', 3600, MINJS);
        // 页面各种赋值
        echo '<script>'.$ajax,$this->cache,$str.'</script>';
        // 重置
        $this->cache = ';';
        if(file_exists(ROOT.'App/'.$this->app.'/View/'.$file.'.html'))
            include ROOT.'App/'.$this->app.'/View/'.$file.'.html';
        else throw new \Main\Core\Exception(ROOT.'App/'.$this->app.'/View/'.$file.'.html'.'不存在!');
        echo <<<EEE
</html>
EEE;
        return true;
    }
    /**
     * 将数据赋值到页面js 支持 int string array bool
     * @param string $name js对应键
     * @param string $val  js对应值
     *
     * @throws \Main\Core\Exception
     */
    protected function assign($name='', $val=''){
        $type = gettype($val);
        switch($type){
            case 'boolean':
                if($val === true)
                    $this->cache .= 'var '.$name.'=true;';
                else if($val === false)
                    $this->cache .= 'var '.$name.'=false;';
                break;
            case 'integer':
                $this->cache .= 'var '.$name.'='.$val.';';
                break;
            case 'string':
                $this->cache .= "var ".$name."='".$val."';";
                break;
            case 'array':
                $this->cache .= "var ".$name."=".json_encode($val).";";
                break;
            default:
                throw new \Main\Core\Exception('暂不支持的数据类型!');
                break;
        }
    }
    /**
     * 将数据赋值到页面php 以$DATA[$key]调用
     * @param string $key
     * @param string $val
     */
    protected function assignPhp($key='', $val=''){
        $this->phparray[$key] = $val;
    }
    final public function __call($fun, $par=array()){
        if(in_array(strtolower($fun), array('post','put','delete'))){
            if(!obj('Secure')->checkCsrftoken( $this->classname ))  $this->returnMsg(0, '页面已过期,请刷新!!') && exit ;
            loop : if(!empty($par)){
                $bool = call_user_func_array(array(obj('f'),$fun), $par);
                if($bool === false ) {
                    $msg = isset($par[2]) ? $par[2] : $par[0].' 不合法!';
                    $this->returnMsg(0, $msg) && exit;
                }else if($bool === null ) throw new \Main\Core\Exception('尝试获取'.$fun.'中的"'.$par[0].'"没有成功!');
                else return $bool;
            }else {
                /**
                 * 按数组接受POST参数 再封装$this->post($par);
                 * 例:$_POST['name']='zhangsang',$_POST['age']=18  则 return array('name'=>'zhangsang','age'=>18)
                 * 若存在'name'预定义验证规则(F->getFilterArr()中),则验证;要使用自定义验证,请用$this->post单独验证
                 * @return array
                 * @throws \Main\Core\Exception
                 */
                $arrayKey = array();
                $array = obj('f')->$fun;
                if($array === null)  throw new \Main\Core\Exception('尝试获取'.$fun.'中的数据没有成功!');
                foreach( $array as $k => $v ){
                    if(array_key_exists($k, obj('F')->getFilterArr()) && !is_array($k)){
                        $arrayKey[ $k ] = $this->{$fun}($k, $k);
                    }else $arrayKey[ $k ] = obj('F')->{$fun}($k);
                }
                return $arrayKey;
            }
        }else if(in_array(strtolower($fun), array('get','session','cookie'))){
            goto loop;
        }elseif(DEBUG) throw new \Main\Core\Exception('未定义的方法:'.$fun.'!');
    }
}