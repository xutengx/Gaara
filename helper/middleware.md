**gaara** `嘎啦`
==========================
以下的信息可以帮助你更好的使用这个框架 **gaara**, 更好的使用 **php**
****
#### Author:xuteng
#### E-mail:1771033392@qq.com
****
## 目录
* [安装](/helper/install.md)
* [配置](/helper/configure.md)
* [目录结构](/helper/catalog.md)
* [生命周期](/helper/cycle.md)
* [路由](/helper/route.md)
* [请求参数](/helper/request.md)
* [响应](/helper/response.md)
* [中间件](/helper/middleware.md)
    * [总览](#总览)
    * [执行流程](#执行流程)
    * [中间件申明](#中间件申明)
    * [中间件handle](#中间件handle)
    * [中间件terminate](#中间件terminate)
    * [中间件传参](#中间件传参)
* [控制器](/helper/controller.md)
* [数据库模型](/helper/model.md)
* [缓存](/helper/cache.md)
* [视图](/helper/view.md)
* [获取对象](/helper/getobj.md)
* [惰性js](/helper/inertjs.md)

## 总览

在`App\Kernel`的`$middlewareGlobel`中定义全局中间件, `$middlewareGroups`中定义路由中间件组

## 执行流程

> 当路由匹配成功后, 确定执行的中间件(a,b,c,d)
> 依次执行(a,b,c,d)的`handle`方法
> 执行业务(控制器or闭包)
> 依次执行(d,c,b,a)的`terminate`方法


**注: 先进后出堆模型**

## 中间件申明

申明一个中间件, 需要继承`Main\Core\Middleware`
```php
<?php
namespace App\yh\Middleware;
use Main\Core\Middleware;
class SignCheck extends Middleware {
    
}
```
**注: 命名空间, 遵循 psr-4**

## 中间件handle

```php
<?php
namespace App\yh\Middleware;
use Main\Core\Middleware;
class SignCheck extends Middleware {

    public function handle() {
       echo 'the handle of SignCheck';
    }
}
```
**注: handle可以依赖注入,且不需要返回值,需要传递的信息可以写入request**
```php
<?php
namespace App\yh\Middleware;
use Main\Core\Middleware;
use Mian\Core\Request;
class SignCheck extends Middleware {

    public function handle(Request $Request) {
        $Request->something = 'the handle had done';
        echo 'the handle of SignCheck';
    }
}
```
## 中间件terminate

`terminate`方法的形参`$response`是来自`业务逻辑`以及中间件`terminate`方法传递
当一个中间件未实现`terminate`方法时`$response`将自动传递
```php
<?php
namespace App\yh\Middleware;
use Main\Core\Middleware;
class SignCheck extends Middleware {

    public function terminate($response) {
        //todo something
        
        return $response;
    }
}

```
**注: terminate同样可以依赖注入,且`$response`形参绑定,注意传递`$response`到下一个中间件**

## 中间件传参

在`App\Kernel`中定义中间件时可通过`@`区分若干个参数, 这些参数用于实例化中间件
