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
* [惰性js](/helper/inertjs.md)

## 安装

gaara 可以通过 composer 进行安装。`叫 xuteng 的人还蛮多的 T_T`

```
$ composer create-project xutengx/gaara
```

## nginx配置

需要将 HTTP 服务器的 web 根目录指向 public 目录，该目录下的 index.php 文件将作为默认入口。

``` nginx
# 站点根目录
root /mnt/hgfs/www/php/public;
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```
重启你的nginx
```
[centos7.3]$ systemctl restart nginx
```
## 目录权限
安装完毕后，需要配置一些目录的读写权限：data 和 public/open 目录应该是可写的
## 路由
建立路由 : Route/http.php 中写入如下内容
``` php
<?php
return [
    '/' => funtion(){
        return 'hello world'.PHP_EOL;
    }
];
```
或者
``` php
<?php
Route::get('/', function(){
    return 'hello world'.PHP_EOL;
});
```
那么在终端或者浏览器中, 将如约而见 hello world
```
[centos7.3]$ curl 127.0.0.1 
hello world
```
## 控制器
建立路由 : Route/http.php 写入如下内容
``` php
<?php
Route::get('/showcontr', 'App\showcontr@index');
```
建立控制器 : App/showcontr.php (新建)写入如下内容
``` php
<?php
namespace App;
class showcontr{
    public function index(){
        return 'this is a controller'.PHP_EOL;
    }
}
```
那么在终端或者浏览器中
```
[centos7.3]$ curl 127.0.0.1/showcontr
this is a controller
```
## 数据库
写入配置 : env.php 写入如下内容
``` php
<?php
return [
    'db_user' => 'root',
    'db_host' => '127.0.0.1',
    'db_passwd' => 'root',
    'db_db'     => 'ts'
];
```
建立路由 : Route/http.php 写入如下内容
``` php
<?php
Route::get('/showdb', 'App\showdb@index');
```
建立模型 : App/m/db.php (新建)写入如下内容
``` php
<?php
namespace App\m;
class db extends \Gaara\Core\Model {
    protected $table = '你的数据表';
}
```
建立控制器 : 在 App/showdb.php (新建)写入如下内容
``` php
<?php
namespace App;
use App/m/db;
class showdb {
    public function index(db $db) {
        return $db->getRow();
    }
}
```
那么在终端或者浏览器中, 将会输出 '你的数据表' 中的第一行数据 ( 如果有的话 )
```
[centos7.3]$ curl 127.0.0.1/showdb
{"id":100,"name":"公式一","key":"$10$fF2AyitnU9jm1wx/udJr3uVblCvMXIZCHgbogsVuQeG5uYvlTNbj2"}
```
返回 json ?
****
更多详细内容，请移步查看 [github][github] or [码云][oschina]
--------------------------------
[oschina]:http://git.oschina.net/dianlaoshu_xT/php "码云"
[github]:https://github.com/xutengx/gaara "github"