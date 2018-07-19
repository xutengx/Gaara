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
* [控制器](/helper/controller.md)
* [数据库模型](/helper/model.md)
* [缓存](/helper/cache.md)
    * [总览](#总览)
    * [后期驱动更改](#后期驱动更改)
    * [设置缓存](#设置缓存)
        * [最简缓存](#最简缓存)
        * [闭包缓存](#闭包缓存)
    * [获取缓存](#获取缓存)
    * [查询过期时间](#查询过期时间)
    * [获取&存储](#获取&存储)
        * [手动键](#手动键)
        * [自动键](#自动键)
    * [执行缓存](#执行缓存)
    * [不缓存](#不缓存)
    * [自增自减](#自增自减)
    * [移除缓存](#移除缓存)
* [视图](/helper/view.md)
* [获取对象](/helper/getobj.md)
* [惰性js](/helper/inertjs.md)

## 总览

> 缓存相关配置(默认) `Config/cache.php`, 目前支持 `redis`,`file`

> 直接用`Gaara\Core\Cache`默认驱动为`redis`

> 快捷方式\Cache, 总是单例

## 后期驱动更改

除了在配置文件中指定使用的驱动之外,也可以随时使用`store()`切换

> 多次切换不会重复产生多个驱动连接

```php
<?php
// 切换到 file, 并设置一个缓存
obj(Cache::class)->store('file')->set('key', 'value', 3600);

// 上面切换到 file, 将继续保持
\Cache::set('key2', 'value', 3600);

// 切换会默认(配置文件)驱动
\Cache::store()->set('key3', 'value', 3600);

```

## 设置缓存

### 最简缓存

返回 bool

```php
<?php

obj(Cache::class)->set('key', 'value', 3600);

\Cache::set('key', 'value', 3600);

// 使用默认过期时间
\Cache::set('key', 'value');

// 无过期时间
\Cache::set('key', 'value', -1);
```

**注: 过期时间若省略, 将使用配置文件中的默认时间. 无过期时间则传值`-1`.**


### 闭包缓存

> 除开`值`是闭包, 其他均和 [最简缓存](#最简缓存) 一致.

```php
<?php
obj(Cache::class)->set('key', function() {
    return 'value';
}, 3600);

\Cache::set('key',  function() {
    return 'value';
}, 3600);
```
## 获取缓存

缓存不存在则返回 null

```php
<?php
obj(Cache::class)->get('key');

\Cache::get('key');
```
## 查询过期时间

return int 
> 0表示过期, -1表示无过期时间, -2表示未找到key

```php
<?php
\Cache::ttl('key');
```

## 获取&存储

有时候你可能想要获取缓存项，但如果请求的缓存项不存在时给它存储一个默认值

### 手动键


```php
<?php
obj(Cache::class)->remember('user', function(){
    return $this->foo();
}, 3600);

\Cache::remember('user', function(){
    return $this->foo();
}, 3600);
```

### 自动键

当传入的`remember`的第一的参数是闭包时, 将会依据当前的代码上下文, 命名空间等特征, 生成缓存键名, 当缓存键名不存在时, 则执行闭包行数, 缓存并返回

```php
<?php
obj(Cache::class)->remember(function(){
    return $this->foo();
}, 3600);

\Cache::remember(function(){
    return $this->foo();
}, 3600);
```
**注 : 自动缓存的键名将只会被自己生成**

## 执行缓存

依据当前的`执行对象`, `执行方法`, `非限定参数` 生成缓存键名 
当缓存键名不存在时, 则执行`执行对象`->`执行方法`(`非限定参数`), 缓存并返回

```php
<?php
/*
 * @param object  $obj 执行对象
 * @param string  $func 执行方法
 * @param int|null $cacheTime 缓存过期时间
 * @param mixed ...$par 非限定参数 
 */
obj(Cache::class)->call($obj, 'function_name_in_obj', 3600, $param_1, $param_2);

\Cache::call($obj, 'function_name_in_obj', 3600, $param_1, $param_2);
```
**注 : 与自动缓存的不同之处在于, 可以在项目的不同位置访问同一缓存**

## 不缓存

为了方便调试提供`dremember`, `dcall`, 他们不会检测缓存是否存在, 而总是直接`执行闭包`or`执行方法`后写入缓存并返回

```php
<?php
obj(Cache::class)->dcall($obj, 'function_name_in_obj', 3600, $param_1, $param_2);

\Cache::dremember(function(){
    return 'no cache '. time();
}, 3600);
```

## 自增自减

`increment` 和 `decrement` 方法可用于调整缓存中的整型数值, 这两个方法都可以接收第二个参数来指明缓存项数值增加和减少的数目

```php
<?php
$amount = 2;

\Cache::increment('key');
\Cache::increment('key', $amount);

\Cache::decrement('key');
\Cache::decrement('key', $amount);
```
**注 : 自增一个不存在的键,将从0自增,没有过期时间**

## 移除缓存

```php
<?php
// 普通移除
\Cache::rm('key');

// 对call方法的缓存移除
\Cache::clear($obj, 'function_name_in_obj', $param_1, $param_2);

// 全部删除
\Cache::flush();
```
**注 : `clear()`接受参数与`call()`十分近似, 但不要包含`有效时间`**

**注 : `flush()`并不管什么缓存键前缀，而是从`当前缓存驱动`中移除所有数据，所以在使用这个方法时如果其他应用与本应用有共享缓存时需要格外注意**