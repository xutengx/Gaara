<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/12/21 0021
 * Time: 17:13
 */
namespace Main\Core;
defined('IN_SYS')||exit('ACC Denied');
class Controller extends Base{
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
        $this->phpcache = obj('\Main\Core\Cache',true,0);
        //设置session
        $this->session = obj('\Main\Core\Session');

        $strarr = array(APP.'\\'=>'','Contr'=>'');
        $this->classname = strtr(get_class($this), $strarr);
        $this->construct();
    }
    protected function construct(){
    }
    public function indexDo(){
        $this->display();
    }
    // 设置session
    protected function main_session_start(){
        $this->session->start_session();
        return true;
    }
    // 事务开启 begin;
    protected function sqlBegin(){
        $this->db = obj('Mysql');
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
    /**
     * echo json数据
     * 原ajaxbackWithMsg()
     * @param $re 状态标记
     * @param $msg 状态描述
     * @return bool
     */
    protected function returnMsg($re=1, $msg='fail!'){
        exit(json_encode( array('state'=>$re, 'msg'=>$msg)));
    }
    // 用于ajax返回
    // 原ajaxback()
    protected function returnData($re){
        if ($re !== false && $re !== null && $re !== 0 && $re !== -1) {
            exit(json_encode(array('state' => 1, 'data' => $re)));
        } else $this->returnMsg(0);
    }
    // 以组件方式引入html
    final protected function template($file=false){
        $classname =  str_replace('Contr','',get_class($this));
        $app = explode('\\', $classname);
        $file = $file ? $file : $app[1];
        $this->assignPhp('T_VIEW','Application/'.$app[0].'/View/');
        $DATA = $this->phparray;
        include ROOT.'Application/'.$app[0].'/View/template/'.$file.'.html';
        echo '<script>'.$this->cache.'</script>';
    }
    // 写入script路由方法,js赋值,并引入html文件
    final protected function display($file=false){
        $classname = $this->classname;
        $file = $file ? $file : $classname;
        $debug = debug_backtrace();
        // 方法名,为调用此方法的最近级别函数
        $method = $debug[1]['function'];

        $DATA = $this->phparray;
        $this->assign('path',PATH);
        $this->assign('in_sys',IN_SYS);
        $this->assign('view', VIEW);
        $this->assign('contr', $classname );
        $this->assign('method', $method);
        // 公用view
        obj('Template')::includeFiles();
        // 一键form提交
        obj('Template')::submitData();
        // 防scrf的ajax(基于jquery), 接受post提交数据前.先验证http头中的 csrftoken
        $ajax = obj('Secure')->newAjax($classname);
        // js string模板解析
        $template = 'String.prototype.temp = function(obj){return this.replace(/\$\w+\$/gi,function(matchs){var returns = obj[matchs.replace(/\$/g, "")];return (returns + "") == "undefined"? "": returns;});};';
        // js 路由方法
        $str = 'function __url__(Application, Controller, method){if(arguments.length==1){method=Application;Controller="'.$classname.'";Application="'.APP.'";}else if(arguments.length==2) {method=Controller;Controller=Application;Application="'.APP.'";} var url=window.location.protocol+"//"+window.location.host+window.location.pathname+"?'.PATH.'="+Application+"/"+Controller+"/"+method+"/"; return url;}';
        echo '<script>'.$str,$this->cache,$ajax,$template.'</script>';
        $this->cache = ';';

        include ROOT.'Application/'.APP.'/View/'.$file.'.html';
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
        obj('F')::set_cookie($var , $value);
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
        if(!is_dir($dir)) $this->base_mkdir($dir);
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
            $code = obj('F')::get('code');
            //获取授权
            $auth = $this->wechatTest();
            if($code === null){
                $redirect_uri = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
                $redirect_uri = str_replace(IN_SYS, 'getInfoOnWechat.php', $redirect_uri);
//                echo $redirect_uri;exit;
                $method = $is ? 'get_authorize_url2':'get_authorize_url';
                $url    = $auth->{$method}($redirect_uri,'STATE');
                header("Location:".$url); //跳转get_authorize_url2()设好的url，跳转后会跳转回上面指定的url中并且带上code变量，用get方法获取即可
            }
            else{
                $res = $auth -> get_access_token($code);// get_access_token()方法能够获取openid，access_token等信息
                $explicit = obj('F')::cookie('explicit');
                if($explicit) $this->wechatinfo = $auth->get_user_info($res['access_token'], $res['openid']);
                else $this->wechatinfo = $res['openid'];
                $this->main_getInfo();
            }
        }
    }

    /**
     * 存在$_SESSION['tid'], 查询对应appid
     * 在以http://wx.****** 访问时,启用回调为wx的appid
     * 在以http://poster.****** 访问时,启用回调为poster的appid
     * @return object
     */
    private function wechatTest(){
        try{
            if($tid = obj('\Main\Core\F')::session('themeid')) {
                $re = obj('themeModule')->selRow($tid);
                if($re['isdefault'] == 1) {
                    loop : if($_SERVER['HTTP_HOST'] == 'wx.issmart.com.cn') return obj('\Expand\Wechat',false, APPID_TEST, APPSECRET_TEST);
                    return obj('\Expand\Wechat',false, APPID, APPSECRET);
                }else {
                    $res = obj('platformModule','admin')->selRow($re['platformid']);
                    return obj('\Expand\Wechat',false, $res['appid'], $res['appsecret']);
                }
            }else goto loop;
        }catch(Exception $e){
            goto loop;
        }
    }
    // 微信授权前的 Session 校验,之后将自动授权,以及记录数据 和 Session
    final protected function main_checkSessionUser($is = false){
        $openid = obj('F')::session('openid');
        $obj    = obj('userModule');
        if($obj->main_checkUser($openid)) return $openid;
        else {
            $this->set_cookie('Location', $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], time()+60); // 记录url,完成授权后跳转
            $this->set_cookie('explicit', $is, time()+60); // 记录 是否显式授权
            $this->getInfoOnWechatProfessional($is);
        }
    }
    // 微信授权后的具体数据存储,设置 Session, 并重定向到授权前url
    final protected function main_getInfo(){
        $obj = obj('userModule');
        $openid = ( $openid = $obj->main_checkUser($this->wechatinfo) ) ? $openid : $obj->main_newUser($this->wechatinfo);
        if($openid) {
            $location = obj('F')::cookie('Location');
            $_SESSION['openid'] = $openid;
            header('Location:'.$location);
        }
        else exit('微信授权失误!请关闭网页后重试!');
    }

    final public function __call($fun, $par=array()){

        if(in_array(strtolower($fun), array('post','put','delete'), true)){
            if(!obj('Secure')::checkCsrftoken( $this->classname ))  $this->returnMsg(0, 'csrf防护中!') ;
            loop : if(!empty($par)){
                $match = isset($par[1]) ? ',$par[1]' : false ;
                $code = 'return obj(\'F\')::{$fun}("'.$par[0].'"'.$match.');';
                $bool = eval($code);

                if($bool === false ) {
                    $msg = isset($par[2]) ? $par[2] : $par[0].' 不合法!';
                    $this->returnMsg(0, $msg);
                }else if($bool === null ) throw new Exception('尝试获取'.$fun.'中的"'.$par[0].'"没有成功!');
                else return $bool;
            }else {
                /**
                 * 按数组接受POST参数 再封装$this->post($par);
                 * 例:$_POST['name']='zhangsang',$_POST['age']=18  则 return array('name'=>'zhangsang','age'=>18)
                 * 若存在'name'预定义验证规则(F::getFilterArr()中),则验证;要使用自定义验证,请用$this->post单独验证
                 * @return array
                 * @throws Exception
                 */
                $arrayKey = array();
                $code = 'return obj(\'F\')::${$fun};';
                $array = eval($code);
                if($array === null)  throw new Exception('尝试获取'.$fun.'中的数据没有成功!');
                foreach($array as $k=>$v){
                    if(array_key_exists($k,F::getFilterArr()) && !is_array($k)){
                        $arrayKey[$k] = $this->{$fun}($k, $k);
                    }else $arrayKey[$k] = $this->{$fun}($k);
                }
                return $arrayKey;
            }
        }else if(in_array(strtolower($fun), array('get','session','cookie'), true)){
            goto loop;
        }
    }
}