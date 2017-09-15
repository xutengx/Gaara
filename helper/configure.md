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
    * [nginx](#nginx)
    * [apache](#apache)
    * [目录权限](#目录权限)
    * [其他配置](#其他配置)
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

## nginx

需要将 HTTP 服务器的 web 根目录指向 public 目录，该目录下的 index.php 文件将作为默认入口。

``` nginx
#站点根目录
root /mnt/hgfs/www/php/public;
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```
重启你的nginx
```
[centos7.3]$ systemctl restart nginx
```
## apache

将 HTTP 服务器的 web 根目录指向 public 目录。同时开启 mod_rewrite

## 目录权限

安装完毕后，需要配置一些目录的读写权限：data 和 public/open 目录应该是可写的
其中 data 是记录日志, 部分缓存以及上传文件存储的默认目录;
而 public/open 则是压缩后的 js 存放的位置;

## 其他配置

如同在 env.php 中可以见到的数据库配置 :
```php
'db_user' => 'root',
'db_host' => '127.0.0.1',
'db_passwd' => 'root',
'db_db'     => 'yh',
```
**注: 若env.php不见了, 将env.example.php重名为env.php**