<?php
namespace App\Dev\view;

use App\yh\s\Sign;

class index extends \Main\Core\Controller\HttpController{
    
    protected $view = 'App/Dev/view//view/html/';
    protected $language = 1;


    public function index(){
        
        
        $this->assignPhp('test', url('teet/tete/e',['name'=>'as','age'=> 12]));
        $this->assign('test', 'this is test string !');
        
        $this->display('demo');
    }
    
    public function getAjax(\Main\Core\Request $request){
        $param = $request->input;
        $token = $request->input('token');
        $timestamp = $request->input('timestamp');
        $sign = $request->input('sign');
        $re = Sign::checkSign($param, $token, $timestamp, $sign);
        return $this->returnData($re);
    }
    

}