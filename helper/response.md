**gaara** `嘎啦`
==========================
以下的信息可以帮助你更好的使用这个框架 **gaara**, 更好的使用 **php**
****
#### Author:xuteng
#### E-mail:68822684@qq.com
****
## 目录
* [安装](/helper/install.md)
* [配置](/helper/configure.md)
* [目录结构](/helper/catalog.md)
* [生命周期](/helper/cycle.md)
* [路由](/helper/route.md)
* [请求参数](/helper/request.md)
* [响应](/helper/response.md)
    * [总览](#总览)
    * [设定ContentType](#设定ContentType)
    * [设定http状态码](#设定http状态码)
    * [链式返回响应](#链式返回响应)
    * [中断响应](#中断响应)
    * [其他方法](#其他方法)
* [中间件](/helper/middleware.md)
* [控制器](/helper/controller.md)
* [数据库模型](/helper/model.md)
* [缓存](/helper/cache.md)
* [视图](/helper/view.md)
* [获取对象](/helper/getobj.md)
* [惰性js](/helper/inertjs.md)

## 总览

响应处理在`Gaara\Core\Response`类中, 中间件`Gaara\Core\Middleware\ReturnResponse`, 启用的情况下依据http协议自动处理响应结果, 否则需要手动处理。

## 设定ContentType

```php
<?php
// put请求域名 http://eg.com/?name=gaara, 
// 响应头 Content-Type:application/xml; charset=utf-8
// 响应 <?xml version="1.0" encoding="utf-8"?><root>gaara</root> 
Route::put('/', function(Gaara\Core\Reuqest $request, Gaara\Core\Response $response){
    $response->setContentType('xml');
    return $request->get('name');
});
```
**注: 需要 Gaara\Core\Middleware\ReturnResponse 中间件支持, 响应体中的内容才转化为指定类型**

## http状态码

```php
<?php
// put请求域名 http://eg.com/?name=gaara, 
// 响应 状态码 400
// 响应 gaara 
Route::put('/', function(Gaara\Core\Reuqest $request, Gaara\Core\Response $response){
    $response->setStatus(400);
    return $request->get('name');
});
```
**注: 非200状态码, 在非https协议下, 可能被运营商等劫持**

## 链式返回响应

```php
<?php
// 响应 gaara 
// 状态码 200
Route::get('/', function(Gaara\Core\Response $response){
    return $response
    ->setStatus(200)->setContentType('html')
    ->setStatus(301)->setContentType('xml')
    ->returnData('gaara');
});
```
**注: 在一次http请求中, 只有第一次`setStatus()`与`setContentType()`会生效**

## 中断响应

```php
<?php
// 响应 gaara 
// 状态码 200
// 不会执行中间件 terminate
Route::get('/', function(Gaara\Core\Response $response){
    return $response
    ->setStatus(200)->setContentType('html')
    ->setStatus(301)->setContentType('xml')
    ->exitData('gaara');
});
```

## 其他方法

```php
<?php
Route::get('/', function(Gaara\Core\Response $response){
    // 加入响应头
    $response->setHeader('userHeadr:www');
    // 批量加入响应头
    $header_arr = [
        'header1' => 'header1',
        'header2' => 'header2',
    ];
    $response->setHeaders($header_arr);
});
```
