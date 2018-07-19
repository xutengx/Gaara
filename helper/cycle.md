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

![图](/helper/img/cycle.png)

```
graph TD
index.php[public\index.php]-->init.php[init.php]
init.php-->校验路由{路由}
校验路由-->|成功|Middlewarehandle[中间件 handle]
校验路由-->|失败|路由匹配完结{路由匹配完结}
路由匹配完结-->|否|校验路由
路由匹配完结-->|是|Response
Middlewarehandle-->|顺序执行|Middlewarehandle
Middlewarehandle-->main[业务执行]
main-->Middlewareterminate[中间件 terminate]
main-->缓存
缓存-->数据
数据-->缓存
缓存-->main
Middlewareterminate-->|倒序执行 传递Response|Middlewareterminate
Middlewareterminate-->Response

```