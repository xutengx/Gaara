**gaara** `嘎啦`
==========================
以下的信息可以帮助你更好的使用这个框架 **gaara**, 更好的使用 **php**
****
#### Author : xuteng
#### E-mail : 68822684@qq.com
****
## 目录
* [安装](/helper/install.md)
    * [composer](#composer)
    * [clone](#clone)
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
* [惰性js](/helper/inertjs.md)
## composer
在你希望建立项目的目录下通过 composer 进行安装。
```
$ composer create-project --prefer-dist xutengx/gaara project
```
将会生成 gaara 的文件夹，并自动安装依赖。
## clone
在你希望建立项目的目录下通过 git 进行安装。
```
$ git clone https://github.com/xutengx/gaara
```
将会生成 gaara 的文件夹，并不会自动安装依赖。
进入 gaara 文件夹执行 composer 依赖安装。
```
$ cd ./gaara
$ composer install
```
**注: composer方式更方便, clone则更新一些**