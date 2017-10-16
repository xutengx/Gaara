<?php

declare(strict_types = 1);
namespace Main\Core;

use Closure;
use Main\Core\Route;

abstract class Kernel {

    // 管道对象
    protected $pipeline = null;
    // 全局中间件
    protected $middlewareGlobel = [];
    // 路由中间件
    protected $middlewareGroups = [];

    public function __construct(Pipeline $pipeline) {
        $this->pipeline = $pipeline;
    }
    
    public function Init(){
        $conf = obj(Conf::class)->app;
        date_default_timezone_set($conf['timezone']);
        if ($conf['debug'] === true) {
            ini_set('display_errors', '1');
            error_reporting(E_ALL);
        } else{
            ini_set('display_errors', '0');
        }
        return $this;
    }
    
    public function Start(){
        Route::Start();
    }

    /**
     * 执行中间件以及用户业务代码
     * @param array $middlewareGroups
     * @param string|callback|array $contr
     * @param array $request
     * @return void
     */
    public function run(array $middlewareGroups, $contr, array $request): void {
        $this->pipeline->setPipes($this->addMiddleware($middlewareGroups));
        $this->pipeline->setDefaultClosure($this->doController($contr, $request));
        $this->pipeline->then();
    }

    /**
     * 将中间件数组, 加入管道流程 Pipeline::pipesPush(string)
     * @param array $middlewareGroups
     * @return void
     */
    protected function addMiddleware(array $middlewareGroups): array {
        $arr = [];
        // 全局中间件
        foreach ($this->middlewareGlobel as $middleware) {
            $arr[] = $middleware;
        }
        // 路由中间件
        foreach ($middlewareGroups as $middlewareGroup) {
            foreach ($this->middlewareGroups[$middlewareGroup] as $middleware) {
                $arr[] = $middleware;
            }
        }
        return $arr;
    }

    /**
     * 方法依赖注入,执行,支持闭包函数
     * @param string|callback|array $contr 将要执行的方法
     * @param array $request 请求参数
     * @return void
     */
    protected function doController($contr, array $request): Closure {
        return function () use ($contr, $request) {
            /**
             * 方法依赖注入
             * @param array $parameters 由反射类获取的方法依赖参数链表
             * @return array 参数数组
             */
            $injection = function($parameters) use ($request) {
                // 定义实参数组
                $argument = [];
                // 遍历所有形参
                foreach ($parameters as $param) {
                    // 判断参数类型 是类
                    if ($paramClass = $param->getClass()) {
                        // 获得参数类型名称
                        $paramClassName = $paramClass->getName();
                        // 加入对象到参数列表
                        $argument[] = Integrator::getWithoutAlias($paramClassName);
                    } else {
                        if (isset($request[$param->name])) {
                            // 加入实参到参数列表
                            $argument[] = $request[$param->name];
                        } else {
                            $argument[] = \null;
                        }
                    }
                }
                return $argument;
            };
            try {
                // 形如 'App\index\Contr\IndexContr@indexDo'
                if (is_string($contr)) {
                    $temp = explode('@', $contr);
                    $reflectionClass = new \ReflectionClass($temp[0]);
                    $methodClass = $reflectionClass->getMethod($temp[1]);
                    $parameters = $methodClass->getParameters();

                    $argument = $injection($parameters);
                    $return = call_user_func_array(array(Integrator::getWithoutAlias($temp[0]), $temp[1]), $argument);
                }
                // 形如 function($param_1, $param_2 ) {return 'this is a function !';}
                elseif ($contr instanceof \Closure) {
                    $reflectionFunction = new \ReflectionFunction($contr);
                    $parameters = $reflectionFunction->getParameters();

                    $argument = $injection($parameters);
                    $return = call_user_func_array($contr, $argument);
                }
                return $return;
            } catch (Exception $exc) {
                obj(Response::class)->doException($exc);
            } finally {
                
            }
        };
    }

    final public function __get(string $param) {
        return $this->$param;
    }

}
