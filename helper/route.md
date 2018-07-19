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
    * [总览](#总览)
    * [快捷路由](#快捷路由)
    * [静态路由](#静态路由)
    * [路由参数](#路由参数)
        * [必填参数](#必填参数)
        * [可选参数](#可选参数)
    * [路由分组](#路由分组)
    * [restful](#restful)
    * [设置404](#设置404)
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

简单的如同一个数组, 所有http方法都可访问

```php
<?php
return [
    // 访问域名 http://eg.com/, 响应 hello world
    '/' => funtion(){
        return 'hello world'.PHP_EOL;
    },
    // 访问域名 http://eg.com/contr, 响应 App\Contr 文件(可以并非是控制器)下的 index 方法
    '/contr' => 'App\Contr@index',
];
```
## 静态路由
Route 静态方法包含`get`,`post`,`put`,`delete`,`patch`,`head`,`option`;

接收2个参数,第一个为`匹配路由`,第二个为`匹配后的执行`;

其中第二个参数`匹配后的执行`,可以为`string`, 如`App/Contr/index@action`; 可以为`Closure`, 如`function(){return "hello world";}`;可以为`array`;

```php
<?php
Route::get($uri, $callback);
Route::post($uri, $callback);
Route::put($uri, $callback);
Route::delete($uri, $callback);
Route::patch($uri, $callback);
Route::head($uri, $callback);
Route::options($uri, $callback);

```

有的时候你可能需要注册一个可响应多个 HTTP 请求的路由，这时你可以使用 match 方法，也可以使用 any 方法注册一个实现响应所有 HTTP 请求的路由：

```php
<?php
Route::match(['get', 'post'], '/', function () {
    //
});

Route::any('foo', function () {
    //
});
```

## 路由参数

回调或者控制器的参数名称将会严格对应, 路由参数对依赖注入没有影响

### 必填参数

当然，有时需要在路由中捕获一些 URL 片段。
路由的参数通常都会被放在 {} 内。例如，从 URL 中捕获用户的 ID，可以通过定义路由参数来执行此操作：
```php
<?php
Route::get('user/{id}', function ($id) {
    return 'User '.$id;
});

// 也可以根据需要在路由中定义多个参数：
Route::get('posts/{post}/comments/{comment}', `App/Contr/Posts@post`);
```

**注:回调或者控制器的参数名称将会严格对应!**

下面的例子可见一斑

```php
<?php
// get请求的域名 http://eg.com/add/1/3 响应4
Route::get('/add/{num1}/{num2}', function ($num2, $num1) {
    return $num1 + $num2;
});

// get请求的域名 http://eg.com/add/1/3 响应10
Route::get('/add/{num1}/{num2}', function ($num0 = 9, $num1) {
    return $num1 + $num0;
});
```

### 可选参数

有时，你可能需要指定一个路由参数，但你希望这个参数是可选的。你可以在参数后面加上 ? 标记来实现，但前提是要确保路由的相应变量有默认值：

```php
<?php
Route::get('user/{name?}', function ($name = null) {
    return $name;
});
```

```php
<?php
// get请求的域名 http://eg.com/, 响应 hello world
Route::get('/',function(){
    return \Response::setContentType('html')->returnData('hello world');
});
// post请求的域名 http://eg.com/post方法下的url参数, 响应 post方法下的url参数
Route::post('/{urlpost?}',function($urlpost = 'post'){
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
// 数组形式
Route::delete('/id/{id}',[
    'middleware'=>'web',
    'namespace'=>'App/dev',
    'prefix'=>'',
    'as'=>'',
    'domain'=>'',
    'uses' => 'index@delete'
]);
```
**注:`匹配路由`信息应以'/'开头**

## 路由分组
路由组允许你在大量路由之间共享路由属性，例如中间件或命名空间，而不需要为每个路由单独定义这些属性。共享属性应该以数组的形式传入 `Route::group` 方法的第一个参数中。

无限级路由分组, 下面是一个相对复杂的例子
```php
<?php
// 设定一个路由组, 以/group开头, 并使用 web 中件间组
// 限制 192.168.43.128 域名可访问, 组内成员都在 App\index 命名空间下
Route::group(['prefix'=>'group','middleware'=>['web'],'domain'=> '192.168.43.128','namespace'=> 'App\index' ], function(){
        // 设定一个路由组, 以group开头(加上父类便是group/group开头)
        // 组内成员都在 group 命名空间下(加上父类便是App\index\group命名空间下)
        Route::group(['prefix'=>'group','namespace'=> 'group'], function(){
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
**注:`prefix`,`namespace`两个信息均不应以'/'or'\\'开头结尾**
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
## 设置404
`gaara`拥有默认的`404`响应, 在全部路由匹配均失败后出发, 同样你也是可以通过`Route::set404()`设置;

目前传参为`$pathinfo`(当前url的pathinfo)
```php
<?php
//Route::set404('App\errorPage\indexContr@indexDo');
Route::set404(function($pathinfo){
	obj(Response::class)->setStatus(404)->exitData('Not Found .. ' . $pathinfo);
});
```
**注:`gaara`默认的404响应, 会给出http状态404, 而自主设置需要手动给出**

**注:404响应会使用全局中间件,而不会使用路由中间件**
