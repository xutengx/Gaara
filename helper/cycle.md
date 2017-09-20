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
    * [总览](#总览)
* [路由](/helper/route.md)
* [请求参数](/helper/request.md)
* [响应](/helper/response.md)
* [中间件](/helper/middleware.md)
* [控制器](/helper/controller.md)
* [数据库模型](/helper/model.md)
* [缓存](/helper/cache.md)
* [视图](/helper/view.md)
* [获取对象](/helper/getobj.md)
* [惰性js](/helper/inertjs.md)
## 总览

```flow

index.php=>start: public\index.php
init.php=>operation: init.php
Route.php=>operation: Main\Core\Route.php
校验路由=>condition: 是否匹配成功?
路由匹配完结=>condition: 是否匹配完结?
sub=>subroutine: Your Subroutine
Kernel=>operation: App\Kernel.php
Middlewarehandle=>operation: 中间件(handle)
mainFunc=>operation: 主要执行(业务)
Middlewareterminate=>operation: 中间件(terminate)
e=>end: Response

index.php->init.php->Route.php->校验路由
校验路由(no)->路由匹配完结
路由匹配完结(no)->Route.php
路由匹配完结(yes)->e
校验路由(yes)->Kernel->Middlewarehandle->mainFunc->Middlewareterminate->e

```