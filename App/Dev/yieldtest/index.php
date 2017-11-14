<?php

declare(strict_types = 1);
namespace App\Dev\yieldtest;

use Gaara\Core\Controller;
use Iterator;
use Generator;
use Gaara\Core\Response;
/*
 * 数据库开发测试类
 */

class index extends Controller {

    public function index() {
        $this->middleware([$this->a(),$this->b(),$this->o()]);
    }

    public function middleware($handlers, $arguments = []) {
//函数栈
        $stack = [];
        $result = null;

        foreach ($handlers as $handler) {
// 每次循环之前重置，只能保存最后一个处理程序的返回值
            $result = null;
            $generator = call_user_func_array($handler, $arguments);

            if ($generator instanceof \Generator) {
//将协程函数入栈,为重入做准备
                $stack[] = $generator;

//获取协程返回参数
                $yieldValue = $generator->current();

//检查是否重入函数栈
                if ($yieldValue === false) {
                    break;
                }
            } elseif ($generator !== null) {
//重入协程参数
                $result = $generator;
            }
        }

        $return = ($result !== null);
//将协程函数出栈
        while ($generator = array_pop($stack)) {
            if ($return) {
                $generator->send($result);
            } else {
                $generator->next();
            }
        }
    }

    public function a() {
        return function() {
            echo "this is abc start \n";
            yield;
            echo "this is abc end \n";
        };
    }

    public function b() {
        return function() {
            echo "this is qwe start \n";
            $a = yield;
            echo $a . "\n";
            echo "this is qwe end \n";
        };
    }

    public function o() {
        return function() {
//            throw new \Exception('qweqweqwewqeqwe');
            return 1;
        };
    }
}
