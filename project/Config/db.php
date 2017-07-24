<?php

defined('IN_SYS')||exit('ACC Denied');
return [
    'write'=>array(
        array(
            'weight'=>10,
            'type'=>'mysql',
            'host'=>'127.0.0.1',
            'port'=>3306,
            'user'=>'root',
            'pwd'=>'root',
            'char'=>'UTF8',
            'db'=>'test'
        )
    ),
    'read'=>array(
        array(
            'weight'=>1,
            'type'=>'mysql',
            'host'=>'127.0.0.1',
            'port'=>3306,
            'user'=>'root',
            'pwd'=>'root',
            'char'=>'UTF8',
            'db'=>'test'
        ),
        array(
            'weight'=>2,
            'type'=>'mysql',
            'host'=>'127.0.0.1',
            'port'=>3306,
            'user'=>'root',
            'pwd'=>'root',
            'char'=>'UTF8',
            'db'=>'test'
        )
    )

];