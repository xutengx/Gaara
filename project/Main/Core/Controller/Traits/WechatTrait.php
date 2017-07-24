<?php

namespace Main\Core\Controller\Traits;

defined('IN_SYS') || exit('ACC Denied');

/**
 * 微信授权
 */
trait WechatTrait {
    // 缓存微信授权返回值
    protected $wechatinfo = array();
    
    // 微信授权,$is = 0 为静默授权
    // 配合 getInfoOnWechat.php 入口文件使用, 防止路由参数导致的手机不兼容
    // return 用户信息 $this->wechatinfo
    public function getInfoOnWechatProfessional($is = false){
        $appid = obj('conf')->wechat['appid'];
        $appsecret = obj('conf')->wechat['appsecret'];
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger') === false){
            header("Content-type: text/html; charset=utf-8");
            echo '<p>请在微信中打开</p>';
            if(!DEBUG) exit();
        }else{
            $code = obj('F')->get('code');
            //获取授权
            $auth = obj('\Main\Expand\Wechat',true, $appid, $appsecret);
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
}
