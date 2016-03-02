<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/12/21 0021
 * Time: 17:13
 */
namespace Main;
defined('IN_SYS')||exit('ACC Denied');
class Controller extends Base{
    protected static $ins        = null;
    // 引入sql类
    protected $db         = null;
    // 引入cache类
    protected $phpcache  = null;
    // 引入session类
    protected $session   = null;
    // 缓存js赋值
    private $cache        = ';';
    // 缓存php赋值
    private $phparray        = array();
    // 缓存微信授权返回值
    protected $wechatinfo  = array();
    // 当前Contr名
    protected $classname = null;

    final public function __construct(){
        $this->phpcache = Cache::getins(0);
        //设置session
        $this->session = session::getins();

        $strarr = array(APP.'\\'=>'','Contr'=>'');
        $this->classname = strtr(get_class($this), $strarr);
        $this->construct();
    }
    protected function construct(){
    }
    public static function getins(){
        if(static::$ins instanceof static || (static::$ins = new static)) return static::$ins;
    }
    public function indexDo(){
        echo 'hello world !';
    }
    // 设置session
    protected function main_session_start(){
        $this->session->start_session();
        return true;
    }
    // 事务开启 begin;
    protected function sqlBegin(){
        $this->db = sql::getins();
        $sql = 'begin';
        $this->db->query($sql);
    }
    // 事务提交 commit;
    protected function sqlCommit(){
        $sql = 'commit';
        $this->db->query($sql);
    }
    // 事务回滚 rollback
    protected function sqlRollback(){
        $sql = 'rollback';
        $this->db->query($sql);
    }
    // 开启页面缓存区域 参数应为用户唯一id
    protected function cacheHtmlStart($key='default',$cacheTime=false){
        $classname = str_replace('Contr', '',get_class($this));
        $this->phpcache->htmlCacheCheck($classname, $key, $cacheTime);

    }
    // 结束页面缓存区域
    protected function cacheHtmlEnd(){
        $this->phpcache->htmlCacheOut();
    }
    // 开启数据缓存区域
    // param array $arr 标记参数数组
    // param int $cacheTime 独立缓存时间
    // return void
    protected function cacheDataStart(array $arr=array(), $cacheTime = false){
        $classname = str_replace('Contr', '',get_class($this));
        $debug = debug_backtrace();
        // 方法名,为调用此方法的最近级别函数
        $functionName = $debug[1]['function'];
        $key    = '';
        if( !isset($arr) || empty($arr) )  $key = 'default';
        else {
            foreach($arr as $k=>$v){
                $key .= $key ? '_'.$v : $v;
            }
        }
        $this->phpcache->dataCacheCheck($classname, $functionName, $key, $cacheTime);
    }
    // 结束数据缓存区域
    protected function cacheDataEnd(){
        $this->phpcache->dataCacheOut();
    }
    // 清除指定缓存
    // param 不指定则清除所有缓存
    protected function cacheClear($App = '', $Contr = '', $Func = ''){
        $this->phpcache->clearCache($App, $Contr, $Func);
    }
    //用于ajax返回
    protected function ajaxback($re){
        if($re !== false && $re !== null && $re !== 0) echo json_encode( array('state'=>'1', 'data'=>$re));
        else echo json_encode( array('state'=>'0'));
        return true;
    }
    /**
     * echo json数据
     * @param $re 状态标记
     * @param $msg 状态描述
     * @return bool
     */
    protected function ajaxbackWithMsg($re=1, $msg=''){
        echo json_encode( array('state'=>$re, 'msg'=>$msg));
        return true;
    }
    protected function get($key, $match=false){
        $bool = F::get($key, $match);
        if($bool === false ) $this->ajaxbackWithMsg(0, $key.' 不合法!') && exit;
        if($bool === null ) throw new Exception('尝试获取get中的"'.$key.'"没有成功!');
        return $bool;
    }
    // 仅接受自定义ajax的post请求
    protected function post($key, $match=false, $msg=false){
        if(!Secure::checkCsrftoken( $this->classname )) $this->ajaxbackWithMsg(0, 'csrf防护中!') && exit;
        $bool = F::post($key, $match);
        if($bool === false ) {
            $msg = $msg ? $msg : $key.' 不合法!';
            $this->ajaxbackWithMsg(0, $msg) && exit;
        }
        if($bool === null ) throw new Exception('尝试获取post中的"'.$key.'"没有成功!');
        return $bool;
    }
    protected function cookie($key, $match=false){
        $bool = F::cookie($key, $match);
        if($bool === false ) $this->ajaxbackWithMsg(0, $key.' 不合法!') && exit;
        if($bool === null ) throw new Exception('尝试获取cookie中的"'.$key.'"没有成功!');
        return $bool;
    }
    protected function session($key, $match=false){
        $bool = F::session($key, $match);
        if($bool === false ) $this->ajaxbackWithMsg(0, $key.' 不合法!') && exit;
        if($bool === null ) throw new Exception('尝试获取session中的"'.$key.'"没有成功!');
        return $bool;
    }
    // 写入script路由方法,js赋值,并引入html文件
    final protected function display($file=false){
        $classname = $this->classname;
        $file = $file ? $file : $classname;
        $debug = debug_backtrace();
        // 方法名,为调用此方法的最近级别函数
        $method = $debug[1]['function'];

        $DATA = $this->phparray;
        include ROOT.'Application/'.APP.'/View/'.$file.'.html';
        $this->assign('path',PATH);
        $this->assign('in_sys',IN_SYS);
        $this->assign('view', VIEW);
        $this->assign('contr', $classname );
        $this->assign('method', $method);

        // 防scrf的ajax(基于jquery), 接受post提交数据前.先验证http头中的 csrftoken
        $ajax = Secure::newAjax($classname);
        // js string模板解析
        $template = 'String.prototype.temp = function(obj){return this.replace(/\$\w+\$/gi,function(matchs){var returns = obj[matchs.replace(/\$/g, "")];return (returns + "") == "undefined"? "": returns;});};';
        // js 路由方法
        $str = 'function __url__(Application, Controller, method){if(arguments.length==1){Controller=Application;Application="'.APP.'";method="indexDo";}else if(arguments.length==2) {method=Controller;Controller=Application;Application="'.APP.'";} var url=window.location.protocol+"//"+window.location.host+window.location.pathname+"?'.PATH.'="+Application+"/"+Controller+"/"+method+"/"; return url;}';
        echo '<script>'.$str,$this->cache,$ajax,$template.'</script>';
        $this->cache = ';';
        return true;
    }
    // 缓存js赋值 string
    protected function assign($name, $val){
        $this->cache .= "var ".$name."='".$val."';";
    }
    // 缓存js赋值 json对象
    protected function assignJson($name, $val){
        $this->cache .= "var ".$name."=".json_encode($val).";";
    }
    // 缓存php赋值,以$DATA[$key]调用
    protected function assignPhp($key, $val){
        $this->phparray[$key] = $val;
    }
    // cookie即时生效
    protected function set_cookie($var, $value = '', $time = 0, $path = '', $domain = '', $s = false){
        $_COOKIE[$var] = $value;
        F::set_cookie($var , $value);
        if (is_array($value)) {
            foreach ($value as $k => $v) {
                setcookie($var . '[' . $k . ']', $v, $time, $path, $domain, $s);
            }
        } else setcookie($var, $value, $time, $path, $domain, $s);
    }
    // 人性化相对时间
    protected function friendlyDate($sTime, $format='Y-m-d H:i'){
        $dTime      =   time() - $sTime;
        $state      =   $dTime>0?'前':'后';
        $dTime      =   abs($dTime);
        if($dTime < 60 ){
            return $dTime . ' 秒'.$state;
        } else if($dTime < 3600 ){
            return intval($dTime/60) . ' 分钟'.$state;
        } else if($dTime < 3600*24 ){
            return intval($dTime/3600) . ' 小时'.$state;
        } else if($dTime < 3600*24*7 ){
            return intval($dTime/(3600*24)) . ' 天'.$state;
        } else return date($format, $sTime);
    }
    // 生成随机文件名
    protected function makeFilename($dir, $ext, $id=123){
        $dir = $dir?trim($dir,'/').'/':'./';
        $ext = trim($ext,'.');
        $dir .= uniqid($id);
        $dir .='.'.$ext;
        return $dir;
    }
    // 微信授权,$is = 0 为静默授权
    // 配合 getInfoOnWechat.php 入口文件使用, 防止路由参数导致的手机不兼容
    // return 用户信息 $this->wechatinfo
    public function getInfoOnWechatProfessional($is = false){
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger') === false){
            header("Content-type: text/html; charset=utf-8");
            echo '<p>请在微信中打开</p>';
            //exit();
        }else{
            $code = F::get('code');
            //获取授权
            $auth = $this->wechatTest();
            if($code === null){
                $redirect_uri = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
                $redirect_uri = str_replace(IN_SYS, 'getInfoOnWechat.php', $redirect_uri);
                $state  = "STATE";
                $method = $is ? 'get_authorize_url2':'get_authorize_url';
                $url    = $auth->{$method}($redirect_uri, $state);
                header("Location:".$url); //跳转get_authorize_url2()设好的url，跳转后会跳转回上面指定的url中并且带上code变量，用get方法获取即可
            }
            else{
                $res = $auth -> get_access_token($code);// get_access_token()方法能够获取openid，access_token等信息
                $userinfo = $auth->get_user_info($res['access_token'], $res['openid']);
                $this->wechatinfo = $userinfo;
                $this->main_getInfo();
            }
        }
    }

    /**
     * 在以http://wx.****** 访问时,启用回调为wx的appid
     * 在以http://poster.****** 访问时,启用回调为poster的appid
     * @return object
     */
    private function wechatTest(){
        if($_SERVER['HTTP_HOST'] == 'wx.issmart.com.cn') return obj('\Expand\Wechat',false, APPID_TEST, APPSECRET_TEST);
        return obj('\Expand\Wechat',false, APPID, APPSECRET);
    }
    // php分页
    public function page(){

    }
    // 微信授权前的 Session 校验,之后将自动授权,以及记录数据 和 Session
    final protected function main_checkSessionUser($is = false){
        $openid = F::session('openid');
        $obj    = obj('userModule');
        if($obj->main_checkUser($openid)) return $openid;
        else {
            $this->set_cookie('Location', $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], time()+60); // 记录url,完成授权后跳转
            $this->getInfoOnWechatProfessional($is);
        }
    }
    // 微信授权后的具体数据存储,设置 Session, 并重定向到授权前url
    final protected function main_getInfo(){
        $obj = obj('userModule');
        $openid = ( $openid = $obj->main_checkUser($this->wechatinfo) ) ? $openid : $obj->main_newUser($this->wechatinfo);
        if($openid) {
            $location = F::cookie('Location');
            $_SESSION['openid'] = $openid;
            header('Location:'.$location);
        }
        else exit('微信授权失误!');
    }
    // 模块间重定向
    final protected function main_headerTo($app='index', $contr='index', $method='indexDo', array $pars=array()){
        $str = '';
        if(!empty($pars)){
            foreach($pars as $k=>$v){
                $str .= '/'.$k.'/'.$v;
            }
        }
        $where = IN_SYS.'?'.PATH.'='.$app.'/'.$contr.'/'.$method.$str;
        header('Location:'.$where);
        exit;
    }
}