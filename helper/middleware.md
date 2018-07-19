**gaara** `嘎啦`
==========================
以下的信息可以帮助你更好的使用这个框架 **gaara**, 更好的使用 **php**
****
#### Author : xuteng
#### E-mail : 68822684@qq.com
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
    * [已存在的中间件](#已存在的中间件)
        * [CrossDomainAccess](#CrossDomainAccess)
        * [PerformanceMonitoring](#PerformanceMonitoring)
        * [ReturnResponse](#ReturnResponse)
        * [StartSession](#StartSession)
        * [ThrottleRequests](#ThrottleRequests)
        * [ValidatePostSize](#ValidatePostSize)
        * [VerifyCsrfToken](#VerifyCsrfToken)
        * [ExceptionHandler](#ExceptionHandler)
* [控制器](/helper/controller.md)
* [数据库模型](/helper/model.md)
* [缓存](/helper/cache.md)
* [视图](/helper/view.md)
* [获取对象](/helper/getobj.md)
* [惰性js](/helper/inertjs.md)

## 总览

所有中间件,需要在`App\Kernel`中注册才会生效;
在`App\Kernel`的`$middlewareGlobel`中注册全局中间件, `$middlewareGroups`中注册路由中间件组

## 执行流程

> 当路由匹配成功后, 确定执行的中间件(a,b,c,d)
> 依次执行(a,b,c,d)的`handle`方法
> 执行业务(控制器or闭包)
> 依次执行(d,c,b,a)的`terminate`方法


**注: 先进后出堆模型**

## 中间件申明

申明一个中间件, 需要继承`Gaara\Core\Middleware`
```php
<?php
namespace App\yh\Middleware;
use Gaara\Core\Middleware;
class SignCheck extends Middleware {
    
}
```
**注: 命名空间, 遵循 psr-4**

## 中间件handle

```php
<?php
namespace App\yh\Middleware;
use Gaara\Core\Middleware;
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
use Gaara\Core\Middleware;
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
use Gaara\Core\Middleware;
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

## 已存在的中间件

`gaara`提供一些常用中间件,放置在`Gaara/Core/Middleware`目录中,可在`App\Kernel`中直接使用;

### CrossDomainAccess

允许跨域访问

复杂跨域请求, 会先发送`options`方法, 此时中间件将会中断并响应`200`

### PerformanceMonitoring

性能监控

借助谷歌浏览器的php-console插件显示,当前http请求的相关性能信息

### ReturnResponse

统一响应处理

移除意外输出, 使用PhpConsole捕获, 保障页面布局与响应格式

**注: 使用`terminate`,若程序中中断响应,比如文件下载,则不会生效**

### StartSession

开启sesssion

### ThrottleRequests

访问频率限制

加入标准的响应头, 当超过单位时间请求次数后, 此时中间件将会中断并响应`429`

### ValidatePostSize

验证 post 数据大小,避免大于php设定的post_max_size

当post 数据大小超过post_max_size后, 此时中间件将会中断并响应`413`

### VerifyCsrfToken

验证CsrfToken

此中间件,依赖session_start(), (调用StartSession 中间件), 对程序员完全透明;

### ExceptionHandler

顶层异常捕获

友好化输出未捕获的异常信息;
