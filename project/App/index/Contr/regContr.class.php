<?php
namespace App\index\Contr;
use \Main\Core\Controller;
defined('IN_SYS')||exit('ACC Denied');
class regContr extends Controller\HttpController{
    protected $viewOverTime = 1800;
    // 短信api
    const messageUrl = 'http://112.74.102.122:1280/manage/resSend/send.shtml';

    public function reg(){
        $data = $this->post();
        if(
            $data['tel'] == $_SESSION['reg_sign']['tel']
            && $data['yzm'] == $_SESSION['reg_sign']['yzm']
            && $_SERVER['REQUEST_TIME'] - $_SESSION['reg_sign']['yzm'] > 300
        ){
            $re = obj('userModel')->regByTel($data['tel'], $data['passwd']);
            $this->returnData($re);
        }
        $this->returnMsg(0,'验证码错误!');
    }
    /**
     * 调用第三方短信接口
     */
    public function sendYZM(){
        $tel = $this->post('tel','tel');
        $re = obj('userModel')->where('tel='.$tel)->getRow();
        if($re != array()) $this->returnMsg(0,'此手机号码已经注册') && exit;

        $yzm = rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9);
        $post = array(
            'content' => '尊敬的用户，您的验证码是'.$yzm.'，5分钟内有效',
            'mobile' => $tel,
            'telephone' => 18211555413,
            'password' => 'hao111'
        );
        $re = obj('tool')->sendPost(self::messageUrl, $post);
        $re = json_decode($re, true);
        switch($re['status']){
            case 'success':
                $_SESSION['reg_sign'] = array(
                    'tel'   => $tel,
                    'yzm'   => $yzm,
                    'time'  => $_SERVER['REQUEST_TIME']
                );
                $this->returnMsg(1, '') && exit;
                break;
            case '107':
                $this->returnMsg(0, '您输入的手机号码有误 !') && exit;
                break;
            case '103':
            case '104':
                $this->returnMsg(0, '短信系统繁忙,请稍后再尝试 !') && exit;
                break;
            default:
                $this->returnMsg(0, '错误码 :'.$re['status'].' ,请与我们的工作人员联系 !') && exit;
                break;
        }
    }
}