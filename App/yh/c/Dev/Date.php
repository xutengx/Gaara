<?php

declare(strict_types = 1);
namespace App\yh\c\Dev;

use Main\Core\Controller\HttpController;


class Date extends HttpController {
        
    protected $view = 'App/yh/c/Dev/';
    
    protected $language = 0;
    
    protected $language_array = [
        'time' => [
            '时间','time'
        ]
    ];

    public function index() {
        
        $this->js('js/test.js');
        
        $this->assignPhp('date', date('Y-m-d H;i:s'));
        $this->assign('date', date('Y-m-d H;i:s'));
        
        return $this->template('assembly/date');
        
    }

}
