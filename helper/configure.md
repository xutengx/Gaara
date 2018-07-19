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
    * [nginx](#nginx)
    * [apache](#apache)
    * [目录权限](#目录权限)
    * [环境变量](#环境变量)
        * [变量选择](#变量选择)
        * [变量获取](#变量获取)
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

## 环境变量

`env.php` 文件不应该提交到应用程序的源代码控制系统中，因为每个使用你的应用程序的开发人员/服务器可能需要有一个不同的环境配置。

**注: 若.env不见了, 将.env.example重名为.env**

### 变量选择

`gaara`允许并鼓励在`.env`,定义多个重复变量,并使用`section`获取正确的那个,摈弃人为操作带来的不便;

```
;Choose the section
ENV=local

[local]
MAIL_USERNAMAE=1771033392@qq.com
MAIL_PASSWORD=
MAIL_FROM=1771033392@qq.com
MAIL_HOST=smtp.qq.com

[yh]
MAIL_USERNAMAE=mailserver@shinehua.cn
MAIL_PASSWORD=
MAIL_FROM=mailserver@shinehua.cn
MAIL_HOST=smtp.mxhichina.com
```

### 变量获取

env('配置名','不存在则使用的默认值');
建议,仅在`Config/*`配置文件中调用env()方法;

```php
<?php
env('DB_CONNECTION', '_test');
```

## 其他配置

配置文件统一放置于`Config`目录下, 在各个地方被调用;
每个配置文件的内容规则,并不相同,由使用者确定;
