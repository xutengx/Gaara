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
* [视图](/helper/view.md)
* [获取对象](/helper/getobj.md)
    * [总览](#总览)
    * [绑定](#绑定)
        * [简单的绑定](#简单的绑定)
        * [单例的绑定](#单例的绑定)
        * [临时的绑定](#临时的绑定)
    * [解析](#解析)
    * [执行](#执行)
    * [总览](#总览)
    * [别名获取](#别名获取)
    * [通常获取](#通常获取)
* [惰性js](/helper/inertjs.md)

## 总览
`gaara`通过容器获取对象、服务等等。

> `gaara`的内核对象（`Gaara\Core\Kernel`）继承容器对象（`Gaara\Core\Container`），提供管理类依赖和执行依赖注入。
> `app()`方法可以让你在全局得到`内核容器`

## 绑定

绑定即是告诉`内核容器`当需要一个对象或者接口时，使用对应的值。

### 简单的绑定

```php
<?php
app()->bind('Cache', \App\Comm\Cache::class);

```

### 单例的绑定

解析的结果将被缓存

```php
<?php
app()->bind('Cache', \App\Comm\Cache::class, true);

app()->singleton('Cache', \App\Comm\Cache::class);

```

**注:当对象实现了`Gaara\Contracts\ServiceProvider\Single`接口时,也会被缓存,无论是否主动指定**

### 临时的绑定

只会被解析一次，之后绑定规则失效

```php
<?php
app()->bindOnce('Cache', \App\Comm\Cache::class);

app()->singleton('Cache', \App\Comm\Cache::class, true);

```

## 解析

上文中绑定的对象将会被解析

```php
<?php
app()->make('Cache');

// 可以传入参数, 要注意是形参注入而非顺序注入
app()->make('Cache', []);

```

## 执行

将会自动解决依赖，也可执行非`public`方法

```php
<?php
// 执行某个对象的某个方法
app()->execute('Cache', 'function', []);

// 执行某个闭包
app()->executeClosure($Closure, []);

// 执行某个闭包
app()->executeClosure(function(){

}, []);

```

## 别名获取

```php
<?php
namespace App\yh\c\merchant;

use Gaara\Core\Cache;
use \Cache as AilasCahce;
use Gaara\Core\Controller;

class Test extends Controller {
    public function Index(\Gaara\Core\Cache $c0) {
        $c1 = obj(Gaara\Core\Cache::class);
        $c2 = obj(AilasCahce::class);
        $c3 = obj('Gaara\Core\Cache');
        $c4 = obj('Cache');
        $c5 = obj(Cache::class);

        // 以上的7个对象全部 ===
    }
```
**注 : 哪些类有别名可以参看\Gaara\Quick\ClassAilas.php**

## 通常获取

```php
<?php
obj(App\Model\User::class, ['形参' => '实参']);

```
**注 : 因为类会被缓存, 显然只有第一次实例化时, 参数才会被使用!**