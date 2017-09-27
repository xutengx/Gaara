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
* [控制器](/helper/controller.md)
* [数据库模型](/helper/model.md)
* [缓存](/helper/cache.md)
* [视图](/helper/view.md)
* [获取对象](/helper/getobj.md)
    * [总览](#总览)
    * [别名获取](#别名获取)
    * [通常获取](#通常获取)
* [惰性js](/helper/inertjs.md)

## 总览

> 通过`\Main\Core\Integrator::get(string $class_name, array $param_arr)`获取一个对象。
> 快捷方式 `obj(string $class_name, mix $param_1)`
> 将会自动解决富依赖问题
> 获取一次后, 类将被缓存, 下次将直接返回。

## 别名获取

```php
<?php
namespace App\yh\c\merchant;

use Main\Core\Cache;
use \Cache as AilasCahce;
use Main\Core\Controller\HttpController;

class Test extends HttpController {
    public function Index(Main\Core\Cache $c0) {
        $c1 = obj(Main\Core\Cache::class);
        $c2 = obj(AilasCahce::class);
        $c3 = obj('Main\Core\Cache');
        $c4 = obj('Cache');
        $c5 = AilasCahce::getInstance();
        $c6 = obj(Cache::class);
        
        // 以上的7个对象全部 ===
    }
```
**注 : 哪些类有别名可以参看\Main\Quick\ClassAilas.php**

## 通常获取

```php
<?php
obj(App\Model\User::class, '构造参数1', '构造参数2');

```
**注 : 因为类会被缓存, 显然只有第一次实例化时, 参数才会被使用!**