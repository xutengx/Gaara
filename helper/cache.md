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
* [中间件](/helper/middleware.md)
* [控制器](/helper/controller.md)
* [数据库模型](/helper/model.md)
* [缓存](/helper/cache.md)
    * [总览](#总览)
    * [设置缓存](#设置缓存)
    * [获取缓存](#获取缓存)
    * [自动缓存](#自动缓存)
    * [执行缓存](#执行缓存)
    * [不缓存](#不缓存)
* [视图](/helper/view.md)
* [获取对象](/helper/getobj.md)
* [惰性js](/helper/inertjs.md)

## 总览

> 直接用`Gaara\Core\Cache`默认驱动为redis

> 快捷方式\Cache

## 设置缓存

### 最简缓存

```php
(new Gaara\Core\Cache)->set('key', 'value', 3600);

\Cache::set('key', 'value', 3600);
```

### 闭包缓存

```php
(new Gaara\Core\Cache)->set('key', function() {
    return 'value';
}, 3600);

\Cache::set('key',  function() {
    return 'value';
}, 3600);
```
## 获取缓存

```php
(new Gaara\Core\Cache)->get('key');

\Cache::get('key');
```

## 自动缓存

当传入的get的第一的参数是闭包时, 将会依据当前的代码上下文, 命名空间等特征, 生成缓存键名, 当缓存键名不存在时, 则执行闭包行数, 缓存并返回

```php
(new Gaara\Core\Cache)->get(function(){
    return $this->foo();
}, 3600);

\Cache::get(function(){
    return $this->foo();
}, 3600);
```
**注 : 在同一个项目中, 自动缓存的键名将只会被自己生成, 和使用, 在"同一调用方法"中, 不应使用多于一个的自动命名**

## 执行缓存

依据当前的`执行对象`, `执行方法`, `非限定参数` 生成缓存键名 
当缓存键名不存在时, 则执行`执行对象`->`执行方法`(`非限定参数`), 缓存并返回

```php
/*
 * @param object  $obj 执行对象
 * @param string  $func 执行方法
 * @param bool|true $cacheTime 缓存过期时间
 * @param $par 非限定参数 
 */
(new Gaara\Core\Cache)->call($obj, 'function_name_in_obj', 3600, $param_1, $param_2);

\Cache::call($obj, 'function_name_in_obj', 3600, $param_1, $param_2);
```
**注 : 与自动缓存的不同之处在于, 可以在项目的不同位置访问同一缓存**

## 不缓存

为了方便调试提供`dget`, `dcall`, 他们不会检测缓存是否存在, 而总是直接`执行闭包`or`执行方法`后返回

```php
(new Gaara\Core\Cache)->dcall($obj, 'function_name_in_obj', 3600, $param_1, $param_2);

\Cache::dget(function(){
    return 'no cache '. time();
}, 3600);
```