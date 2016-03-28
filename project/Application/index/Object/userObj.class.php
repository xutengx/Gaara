<?php
namespace admin;
defined('IN_SYS')||exit('ACC Denied');
class userObj extends \Main\Object {
    // 用户账号信息
    public $account = null;
    // 用户 所有公众平台和微话题 3维数组
    public $info = null;
    /**
     * 验证用户登入信息, 通过则将信息则加入$_SESSION['account'];
     */
    public function login($account, $passwd){
        $re = obj('adminModule')->login($account, $passwd);
        if(!$re) return false;
        else {
            $_SESSION['account'] = $re;
            $_SESSION['account']['adminstate'] = obj('adminModule')->createState($re['id']);
            return true;
        }
    }
    /**
     *  核对admin是否已经登入
     *  并初始化对象属性
     *  return  $account
     */
    public function checkLogin(){
        $account = \Main\F::session('account');
        if(isset($account['account']) && !empty($account['account']) && obj('adminModule')->checklogin($account))  {
            $this->account = $account;
            if($account['isroot'] == 1)
                $this->info     = $this->makeRootList($account['id']);
            else $this->info     = $this->makeList($account['id']);
            return $account;
        }
        else $this->base_headerTo('admin','login','indexDo');
    }
    /**
     *  核对root是否已经登入
     *  并初始化对象属性
     *  return  $account
     */
    public function checkRootLogin(){
        $account = \Main\F::session('account');
        if(isset($account['account']) && !empty($account['account']) && obj('adminModule')->checklogin($account))  {
            if($account['isroot'] != 1) exit('无权访问!');
            $this->account = $account;
            $this->info     = $this->makeRootList($account['id']);
            return $account;
        }
        else $this->base_headerTo('admin','login','indexDo');
    }
    /**
     *  核对themeid是否允许用户访问
     *  return tid
     */
    public function checkThemeid($tid){
        $account = \Main\F::session('account');
        if($account['isroot'] == 1) return $tid;
        foreach($this->info[0]['platform'] as $value)
            foreach($value['themes'] as $v)
                if($v['id'] == $tid) return $tid;
        exit('非法访问!');
    }

    /**
     * return 用户可以 更改的conf
     */
    public function getConfs(){
        $account = \Main\F::session('account');
        if($account['isroot'] == 1) $list = obj('confModule')->selAll();
        else $list = obj('confModule')->selAll('adminid="'.$account['id'].'"');
        foreach($list as $k=>$v){
            $list[$k]['conf'] = unserialize($v['conf']);
        }
        return $list;
    }
    // 查询当前 admin 下的所有公众平台和微话题
    private function makeList($id){
        $list = obj('platformModule')->getFormsByAdminid($id);
        foreach($list as $k=>$v){
            $list[$k]['themes'] = obj('themeModule')->getThemesByFormid($v['id']);
        }
        $this->info[] = $this->account;
        $this->info[0]['platform'] = $list;
        return $this->info;
    }
    // 查询 所有管理员的 所有公众平台 和微话题
    private function makeRootList(){
        $adminArr = obj('adminModule')->getAdmins();
        foreach($adminArr as $k=>$v){
            $adminArr[$k]['platform'] = $this->BAK_makeList($v['id']);
        }
        $this->info = $adminArr;
        return $adminArr;
    }
    // 查询 所有管理员的 所有公众平台 和微话题
//    private function BAK_makeRootList(){
//        $adminArr = obj('adminModule')->getAdmins();
//        foreach($adminArr as $k=>$v){
//            $adminArr[$k]['platform'] = $this->makeList($v['id']);
//        }
//        $this->info = $adminArr;
//        return $adminArr;
//    }
    // 查询当前 admin 下的所有公众平台和微话题
    private function BAK_makeList($id){
        $list = obj('platformModule')->getFormsByAdminid($id);
        foreach($list as $k=>$v){
            $list[$k]['themes'] = obj('themeModule')->getThemesByFormid($v['id']);
        }
        return $list;
    }
}