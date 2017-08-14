<?php
namespace App\Dev\view;

class index extends \Main\Core\Controller\HttpController{
    
    protected $view = 'App/Dev/view//view/html/';
    protected $language = 1;


    public function index(){
        
        
        $this->assignPhp('test', url('teet/tete/e',['name'=>'as','age'=> 12]));
        $this->assign('test', 'this is test string !');
        
        $this->display('demo');
    }
    
    public function getAjax(){
        return $this->returnData(1);
    }
    

}