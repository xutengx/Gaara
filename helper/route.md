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
    * [总览](#总览)
    * [快捷路由](#快捷路由)
    * [静态路由](#静态路由)
    * [路由分组](#路由分组)
    * [restful](#restful)
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
默认的 http 路由文件是 Route/http.php , 可以在 init.php 中重新定义
所有风格的路由都可以依赖注入`类`以及`形参`
## 快捷路由
```php
<?php
return [
    // 访问域名 http://eg.com/, 响应 hello world
    '/' => funtion(){
        return 'hello world'.PHP_EOL;
    },
    // 访问域名 http://eg.com/contr, 响应 App\Contr 文件(不一定是控制器)下的 index 方法
    '/contr' => 'App\Contr@index',
];
```
## 静态路由
静态 Route 类申明路由, 以及传参
```php
<?php
// get请求的域名 http://eg.com/, 响应 hello world
Route::get('/',function(){
    return \Response::setContentType('html')->returnData('hello world');
});
// post请求的域名 http://eg.com/post方法下的url参数, 响应 post方法下的url参数
Route::post('/{urlpost}',function($urlpost){
    return \Response::setContentType('html')->returnData($urlpost);
});
// put请求的域名 http://eg.com/art/28/name/sakya方法下的url参数, 响应 sakya28
// 参数将按照形参给予(并非顺序), 不存在的形参将为 null
Route::put('/art/{id}/name/{name}',function($name, $id){
    return $name.$id;
});
// delete请求的域名 http://eg.com/id/28/方法下的url参数, 响应'' (没有返回)
// 依赖注入
Route::delete('/id/{id}',function($id, App\Model\User $user){
    $user->detele($id);
});
```
## 路由分组
无限级路由分组, 下面是一个相对复杂的例子
```php
<?php
// 设定一个路由组, 以/group开头, 并使用 web 中件间组
// 限制 192.168.43.128 域名可访问, 组内成员都在 App\index 命名空间下
Route::group(['prefix'=>'/group','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index' ], function(){
        // 设定一个路由组, 以/group开头(加上父类便是/group/group开头)
        // 组内成员都在 group 命名空间下(加上父类便是App\index\group命名空间下)
        Route::group(['prefix'=>'/group','namespace'=> 'group'], function(){
            // 以任何请求访问/group/group/hello1 ,都不会进入这条路由
            // 因为域名总是无法同时为 192.168.43.128 与 192.168.43.1281
            Route::any('/hello1 ,',['as' => 'hello','middleware'=>['web3'],'domain'=> '192.168.43.1281', 'uses' =>  'Contr\IndexContr@indexDo']);
            // post 请求访问/group/group/hello , 响应 hello 
            Route::post('/hello/{ww?}',['uses' => function ($ww){
                return 'hello';
            }]);
            // get 请求访问/group/group/hello/something , 不会进入这条路由
            // 因为上一条路由总是包含这条路由的匹配
            Route::get('/hello/something',['uses' => function (){
                return '路由';
            }]);
            // get 请求访问/group/group/contr/13
            // 将会执行 App\index\group\index::index($id)
            Route::get('/contr/{id}',['uses' => 'index@index']);
    })
```
## restful
一句话申明restful
```php
<?php
// get 请求的域名 http://eg.com/merchant, 响应 App\merchant\Info::select()
// post 请求的域名 http://eg.com/merchant, 响应 App\merchant\Info::create()
// put 请求的域名 http://eg.com/merchant, 响应 App\merchant\Info::update()
// delete 请求的域名 http://eg.com/merchant, 响应 App\merchant\Info::destroy()
Route::restful('/merchant','App\merchant\Info');
```