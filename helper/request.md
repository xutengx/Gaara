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
    * [总览](#总览)
    * [获取参数](#获取参数)
    * [过滤参数](#过滤参数)
    * [文件参数](#文件参数)
    * [其他方法](#其他方法)
* [响应](/helper/response.md)
* [中间件](/helper/middleware.md)
* [控制器](/helper/controller.md)
* [数据库模型](/helper/model.md)
* [缓存](/helper/cache.md)
* [视图](/helper/view.md)
* [获取对象](/helper/getobj.md)
* [惰性js](/helper/inertjs.md)
## 总览

所有请求参数处理在`Main\Core\Reuqest`类中，将各类http请求参数，处理成统一的格式。
`Main\Core\Reuqest`不会改变`$_POST`, `$_FILES`等php提供的数据，而优先按照请求头提供的数据类型进行解析，并放入自身对应属性。


## 获取参数
### 获取单个参数
```php
<?php
// 获取put请求下的get参数
// put请求域名 http://eg.com/?name=gaara, 响应 gaara
Route::put('/', function(Main\Core\Reuqest $request){
    return $request->get('name');
});
```
### 获取全部参数
```php
<?php
// 获取put请求下的get参数
// put请求域名 http://eg.com/?name=gaara&age=18, 响应 {'name':'gaara','age':'18'}
Route::put('/', function(Main\Core\Reuqest $request){
    return $request->get;
});
```

```php
<?php
// 获取put请求下的全部参数
// put请求域名 http://eg.com/?name=gaara&age=18, 响应 '';
Route::put('/', function(Main\Core\Reuqest $request){
    // input会返回当前http请求方法put
    // 请求体为空, $request->input返回 []
    return $request->input;
});
```
## 过滤参数

```php
<?php
// 获取put请求下的get参数
// put请求域名 http://eg.com/?name=18, 响应 ''
Route::put('/', function(Main\Core\Reuqest $request){
    // 允许5-32字节，允许字母数字下划线
    // 正则不通过，返回\null
    return $request->get('name','/^[\w]{5,32}$/');
});
```
## 文件参数
Main\Core\Reuqest->file 即 Main\Core\Request\UploadFile，实现迭代器接口
下面这个例子可以快速验证文件类型后保存

```php
<?php
Route::put('/', function(Main\Core\Reuqest $request){
    // 保存文件
    foreach($request->file as $k => $file){
        // 文件是img, 且小于8M
        if($file->is_img() && $file->is_less(8388608)){
            // 不使用`makeFilename`时默认路径 'data/upload/'. date('Ym/d/')
            // `makeFilename`方法指定相对路径, 若为绝对路径则指定第2个参数true
            if($file->makeFilename('data/upload/')->move_uploaded_file())
                // 获得文件保存的路径 
                echo '文件保存的路径是'.$file->saveFilename;
        }else {
            echo '上传类型不为图片, 或者大于8m';
        }
    }
});
```
## 其他方法

```php
<?php
Route::get('/', function(Main\Core\Reuqest $request){
    // 域名参数
    $request->domain;
    // cookie
    $request->cookie;
    // 来访ip
    $request->ip;
    // 当前完整url
    $request->url;
    // 是否ajax请求
    $request->isAjax;
    // 设置cookie
    $request->setcookie(string $name, $value = '', int $expire = 0, string $path = "", string $domain = "", bool $secure = false, bool $httponly = true);
    
});
```
