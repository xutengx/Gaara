README
===========================
以下的信息可以帮助你更好的使用这个框架, 更好的使用php
****
### Author:xuteng
### E-mail:1771033392@qq.com
****
## 目录
* [安装](/helper/install.md)
* [配置](/helper/configure.md)
* [目录结构](/helper/catalog.md)
    * [总览](#总览)
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
##总览
* App
    * `建议的项目目录(新建)`
    * Kernel.php    `核心执行(虽说是核心,常用功能还是定义中间件以及异常捕获后的处理)`
* Config    `以下是各种配置项目, 按需建立, 敏感信息写env.php中, 按env('配置名')读取`
* Main  `框架文件`
* Route  `路由文件`
    * http.php   `http路由`
* data  `临时文件,上传文件(默认)都这里面`
* helper `你看的这个markdown就这里面藏着`
* public `网站根目录`
* vendor `各种包`
    * init.php `初始化`