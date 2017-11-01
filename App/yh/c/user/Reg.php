<?php

declare(strict_types = 1);
namespace App\yh\c\user;
defined('IN_SYS') || exit('ACC Denied');

use App\yh\m\MainUser;
use Gaara\Core\Secure;
use Gaara\Expand\Mail;
use Gaara\Core\Controller\HttpController;

class Reg extends HttpController {
    // 生成邮箱注册, 所需的盐
    const key = 'yhreg'; 
    // 激活邮件的有效时间 (s)
    const overtime = 3600; 

    /**
     * 用户邮箱注册注册
     * @param Secure    $secure    加密算法对象
     * @param Mail      $mail      邮件发送对象
     * @param mainUser  $user      数据库操作对象
     */
    public function index(Secure $secure, Mail $mail, MainUser $user) {
        $email = $this->post('email', 'email');
        $url = $this->post('url','url');
        
        // 检测邮箱 
        if(!$this->checkEmail($email, $user)){
            return $this->returnMsg(0, '邮箱已被注册!!');
        }
        
        // 生成激活链接
        $urlLink = $this->makeToken($email, $url, $secure);
        
        // 发送邮件
        return $this->returnData($this->sendMail($email, $urlLink, $mail));
    }
    
    /**
     * 新增用户, 设置密码
     * @param Secure $secure
     * @param GaaraUser $user
     */
    public function setPasswd(Secure $secure, MainUser $user) {
        $email = $this->post('email', 'email');
        $token = $this->post('token');
        $passwd = $this->post('passwd', 'passwd');
        // 验证链接
        $this->checkToken($token, $email, $secure);
        // 新增用户
        return $this->returnData(function() use ($user, $email, $passwd){
            return $user->createUser($email, $passwd);
        });
//        return $this->returnData($user->createUser($email, $passwd)); 
    }

    /**
     * 检测邮箱是否可用
     * @param mainUser  $user      数据库操作对象
     * @return json
     */
    public function email(MainUser $user){
        $email = $this->get('email','email');
        return $this->returnData($this->checkEmail($email, $user));
    }
    /**
     * 效验 token
     * @param string $token
     * @param string $email
     * @param Secure $secure
     * @return boolean
     */
    private function checkToken(string $token, string $email, Secure $secure){
        $strInfo = $secure->decrypt($token, self::key);
        $strArr  = explode('|', $strInfo);
        if(count($strArr) === 2){
            if($strArr[0] === $email){
                if($strArr[1] > \time() - self::overtime){
                    return true;
                }
                else exit($this->returnMsg(0, '链接已经过期.'));
            }
            else exit($this->returnMsg(0, '链接与邮箱不匹配.'));
        }
        else exit($this->returnMsg(0, '无效的链接.'));
        
    }
    
    /**
     * 检测邮箱是否已经被注册
     */
    private function checkEmail(string $email, MainUser $user): bool {
        $has = $user->getEmail($email);
        return $has ? false : true;
    }

    /**
     * 生成邮箱注册takon, 并记录数据库
     * @param string $email     将要注册的邮箱
     * @param string $address   激活链接(无takon)
     * @param Secure $secure    存在加密算法的对象
     * @return string           可以校验的takon
     */
    private function makeToken(string $email, string $address, Secure $secure) : string{
        $str = $email.'|'.time();
        $token = $secure->encrypt($str, self::key);
        return $address.$token;
    }
    
    /**
     * 发送邮箱激活邮件
     * @param string $email     目标邮箱
     * @param string $url       激活链接
     * @param Mail $mail        邮箱对象
     * @return bool
     */
    private function sendMail(string $email, string $url, Mail $mail) : bool{
        $mail->Subject = '恒盈通用户激活';
        $mail->Body = '点击链接, 激活邮箱<br><a>'.$url.'</a>';
        $mail->FromName = '恒盈通';
        $mail->AddAddress($email);
        return $mail->send();
    }
}
