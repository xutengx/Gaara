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
    * [总览](#总览)
    * [全局响应格式](#全局响应格式)
    * [响应主体](#响应主体)
    * [HTTP头部](#HTTP头部)
    * [HTTP异常](#HTTP异常)
    * [中断响应](#中断响应)
    * [下载文件](#下载文件)
        * [从数据导出csv](#从数据导出csv)
        * [下载超大的文件](#下载超大的文件)
    * [视图友好响应](#视图友好响应)
    * [其他方法](#其他方法)
* [中间件](/helper/middleware.md)
* [控制器](/helper/controller.md)
* [数据库模型](/helper/model.md)
* [缓存](/helper/cache.md)
* [视图](/helper/view.md)
* [获取对象](/helper/getobj.md)
* [惰性js](/helper/inertjs.md)

## 总览

响应类`Gaara\Core\Response`, 主题方法(`控制器`,`闭包`)必须返回此类

中间件`Gaara\Core\Middleware\ReturnResponse`启用的情况下, 将会开启输出缓冲区(兼容php有可能已默认开启的情况),最终将会丢弃所有非法输出。

## 全局响应格式

`Gaara`中的响应格式遵循http协议, 使用http状态码标明语义

`Gaara\Core\Response`中提供`fail()`与`success()`方法, `Gaara`中(`控制器`, `中间件`)均使用此格式响应;

> Response::fail(string $msg = 'Fail', int $httpCode = 400): Response

> Response::success($data = [], string $msg = 'Success', int $httpCode = 200): Response

```php
<?php
namespace App\yh\c\user;
class Login extends Controller {

    /**
     * 用户注册
     */
    public function index(Request $request) {
        // 业务

        // 成功响应
        // $data = ['email' => 'newuser@163.com'];
        return $this->success($data);
        // 失败响应
        // $msg = 'reg fail !';
        return $this->fail($msg);
    }
}
```

> 客户端收到成功响应 http 200
{"data":{"email":"newuser@163.com"},"msg":"Success"}

> 客户端收到失败响应 http 400
{"msg":"reg fail !"}


## 响应主体

业务中一般情况都是只需要使用 `return $response->setContent()`

```php
<?php
// put请求域名 http://eg.com/?name=gaara,
// 响应头 Content-Type:application/xml; charset=utf-8
// 响应 <?xml version="1.0" encoding="utf-8"?><root>gaara</root>
Route::put('/', function(\Gaara\Core\Reuqest $request, \Gaara\Core\Response $response){
    retrun $response->setContentType('xml')
    ->setContent($request->input('name'))
    ->setStatus(200);
});
```

**注: `ContentType`缺省值为 `$_SERVER['ACCEPY_TYPE'] ?? $_SERVER['ACCEPY']`**

**注: 非200状态码, 在非https协议下, 可能被运营商等劫持**


## HTTP头部

可在 `response` 组件中操控 `header` 来发送HTTP头部信息， 例如

```php
<?php

$response = obj(\Gaara\Core\Response::class);

// 获取 http 头对象
$headers = $response->header();

// 增加一个 Pragma 头，已存在的Pragma 头不会被覆盖
$headers->add('Pragma', 'no-cache');

// 设置一个Pragma 头，任何已存在的Pragma 头都会被丢弃
$headers->set('Pragma', 'no-cache');

// 删除Pragma 头
$headers->remove('Pragma');

// 查询已设置的响应头
$headers->get();

// 查询已发送的响应头
$headers->getSent();

// 主动发送
$headers->send();

```
**注: `$headers->send()`方法将发送响应头到当前缓冲而非客户端, 若之后存在抛弃缓冲区的操作(如:`Response::sendExit()`), 将使得响应头的发送无效化, 简言之, 大多数情况下都不需要手动调用`$headers->send()`, 在`Response::send()`等方法调用时,`Gaara`将帮你处理**

## HTTP异常

在由于`Gaara\Core\Middleware\ExceptionHandler`中间件的存在, 在业务中的任意地方抛出`HttpException`异常, 都将转化为对应的http状态与信息

> `HttpException`异常在 `\Gaara\Exception\Http\`命名空间下

下面是个常用的例子, 关于参数验证的

```php
<?php
namespace App\yh\c\user;

use Gaara\Core\Controller;
use Gaara\Core\Request;
use Gaara\Exception\Http\UnprocessableEntityHttpException;

class Login extends Controller {

    /**
     * 用户注册
     */
    public function index(Request $request) {
        $email = $this->input('email');
        $age = $this->input('age');
        if($age < 18){
            throw new UnprocessableEntityHttpException('U A too young')
        }
    }
}
```

## 中断响应

立即发送响应头和响应体
移除所有缓冲区中待发送的内容(包含之前调用`Response::send()`发送到缓冲区的内容)
发送完成后立即结束进程

```php
<?php
// 响应 gaara
// 状态码 200
Route::get('/', function(Gaara\Core\Response $response){
    $response
    ->setStatus(200)->setContentType('html')
    ->setContent('gaara')
    ->sendExit();
});
```

**注:由于立即结束进程, 所以不会执行中间件 terminate**

## 下载文件


**注:`gaara`在下载时并不会结束进程, 将会继续执行之后的业务以及中间件`terminate`**
**注:`gaara`为了性能将会在下载时直接发送对应的响应头到客户端, 需要避免后续业务上的意外输出以及响应头重复的问题**

### 从数据导出csv

从数据库查询的结果, 可以快速的转化为csv文件并发送下载
在使用数据模型的`分块`作为数据源时, 将会节省大量内存



```php
<?php

namespace App\Dev\download\Contr;

class index extends \Gaara\Core\Controller {

	public function index(\App\Model\VisitorInfo $model, \Gaara\Core\Response $Response) {
        // 传统数组作为数据源
        $data = $model->limit(14000)->getAll();

        // 数据模型的`分块`作为数据源时, 将会节省大量内存
        $data = $model->limit(14000)->getChunk();

        // 立刻发送下载
		$Response->file()->exportCsv($data, 'new_filename.csv');

		// 下载完成后, 记录日志
		\Log::info('log some info ...');
	}
```

### 下载超大的文件


```php
<?php

namespace App\Dev\download\Contr;

class index extends \Gaara\Core\Controller {

	public function index(\Gaara\Core\Response $Response) {
        // 文件
        $file = './data/upload/201711/01/Downloads.zip';

        // 立刻发送下载
		$Response->file()->download($file, 'new_filename.zip');

		// 下载完成后, 记录日志
		\Log::info('log some info ...');
	}
```

## 视图友好响应

当通过控制器下的`display()`生成的视图, 会附带以下响应相关的功能

```js
$.ajaxSetup({
    error: function (a, b, http_msg) {
		try {
			a = JSON.parse(a.responseText);
			void 0 !== a.msg ? alert(a.msg) : void 0 !== a.error.message && alert(a.error.message);
		} catch (e) {
			alert(http_msg);
		}
	}
});
```
