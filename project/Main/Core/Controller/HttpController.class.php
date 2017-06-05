<?php
namespace Main\Core\Controller;
use \Main\Core\Controller\Module;
defined('IN_SYS')||exit('ACC Denied');
/**
 * 响应页面
 * Class HttpController
 * @package Main\Core\Controller
 */
abstract class HttpController extends \Main\Core\Controller{
    // 页面过期时间  0 : 不过期
    protected $viewOverTime = 0;
    // 缓存js赋值
    protected $cache = ';';
    // 缓存php赋值
    protected $phparray = array();
    // 当前Contr所在app名
    protected $app = NULL;
    // 当前Contr名
    protected $classname = NULL;
    // 缓存微信授权返回值
    protected $wechatinfo = array();

    public function __construct(){
        $app = explode('\\', get_class($this));
        if($app[0] == 'App'){
            $this->app = $app[1];
            $this->classname = str_replace('Contr','',$app[3]);
        }
        $this->construct();
    }
    public function construct(){}
    // 微信授权,$is = 0 为静默授权
    // 配合 getInfoOnWechat.php 入口文件使用, 防止路由参数导致的手机不兼容
    // return 用户信息 $this->wechatinfo
    public function getInfoOnWechatProfessional($is = false){
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger') === false){
            header("Content-type: text/html; charset=utf-8");
            echo '<p>请在微信中打开</p>';
            if(!DEBUG) exit();
        }else{
            $code = obj('F')->get('code');
            //获取授权
            $auth = obj('\Main\Expand\Wechat',true, APPID, APPSECRET);
            if($code === null){
                $redirect_uri = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
                $redirect_uri = str_replace(IN_SYS, 'getInfoOnWechat.php', $redirect_uri);
                $method = $is ? 'get_authorize_url2':'get_authorize_url';
                $url    = $auth->{$method}($redirect_uri,'STATE');
                //                https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8f0ca1bc115c1fae&redirect_uri=http%3A%2F%2F172.19.5.55%2Fgit%2Fphp_%2Fproject%2FgetInfoOnWechat.php&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect"
                header('Location:'.$url); //跳转get_authorize_url2()设好的url，跳转后会跳转回上面指定的url中并且带上code变量，用get方法获取即可
            }
            else{
                $res = $auth -> get_access_token($code);// get_access_token()方法能够获取openid，access_token等信息
                $explicit = obj('F')->cookie('explicit');
                if($explicit) $this->wechatinfo = $auth->get_user_info($res['access_token'], $res['openid']);
                else $this->wechatinfo = array('openid'=>$res['openid']);
                $this->main_getInfo();
            }
        }
    }
    // 微信授权前的 Session 校验,之后将自动授权,以及记录数据 和 Session
    final protected function main_checkSessionUser($is = false){
        $user = obj('F')->session('user');

        $arr['openid'] = isset($user['openid']) ? $user['openid'] : null;
        $arr['time'] = isset($user['time']) ? $user['time'] : null;
        $res = obj('userModel')->where($arr)->getRow();

        if($res) return $arr['openid'];
        else {
            $this->setcookie('Location', $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], time()+60); // 记录url,完成授权后跳转
            $this->setcookie('explicit', $is, time()+60); // 记录 是否显式授权
            $this->getInfoOnWechatProfessional($is);
        }
    }
    // 微信授权后的具体数据存储,设置 Session, 并重定向到授权前url
    final protected function main_getInfo(){
        $obj = obj('userModel');
        if(!$re = $obj->where(array('openid'=>$this->wechatinfo['openid']))->getRow()){
            $arr = array(
                'openid'=>':openid',
                'name'=>':name',
                'img'=>':img',
                'sex'=>':sex',
                'time'  =>date('Y-m-d H:i:s',$_SERVER['REQUEST_TIME'])
            );
            $par = array(
                ':openid'=>$this->wechatinfo['openid'],
                ':name'=>isset($this->wechatinfo['nickname']) ? $this->wechatinfo['nickname'] : '',
                ':img'=>isset($this->wechatinfo['headimgurl']) ? $this->wechatinfo['headimgurl'] : '',
                ':sex'=>isset($this->wechatinfo['sex']) ? $this->wechatinfo['sex'] : 0
            );
            $id = $obj->data($arr)->insert($par);
            $re = $obj->where('id='.$id)->getRow();
        }
        $_SESSION['user'] = $re;
        $location = obj('F')->cookie('Location');
        header('Location:'.$location);
    }
    // cookie即时生效
    protected function setcookie($var, $value = '', $time = 0, $path = '', $domain = '', $s = false){
        $_COOKIE[$var] = $value;
        obj('F')->set_cookie($var , $value);
        if (is_array($value)) {
            foreach ($value as $k => $v) {
                setcookie($var . '[' . $k . ']', $v, $time, $path, $domain, $s);
            }
        } else setcookie($var, $value, $time, $path, $domain, $s);
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
        echo json_encode( array('state'=>$re, 'msg'=>$msg), JSON_UNESCAPED_UNICODE);
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
            echo json_encode(array('state' => 1, 'data' => $re),JSON_UNESCAPED_UNICODE);
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
        obj('cache')->call(obj('Template'), 'includeFiles', 1, MINJS);
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
            if(!obj('Secure')->checkCsrftoken( $this->classname , $this->viewOverTime))  $this->returnMsg(0, '页面已过期,请刷新!!') && exit ;
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