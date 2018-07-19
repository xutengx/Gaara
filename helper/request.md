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
    * [总览](#总览)
    * [获取参数](#获取参数)
    * [过滤参数](#过滤参数)
        * [request对象过滤](#request对象过滤)
        * [控制器过滤](#控制器过滤)
        * [预定义正则](#控制器过滤)
    * [文件参数](#文件参数)
    * [请求信息](#请求信息)
* [响应](/helper/response.md)
* [中间件](/helper/middleware.md)
* [控制器](/helper/controller.md)
* [数据库模型](/helper/model.md)
* [缓存](/helper/cache.md)
* [视图](/helper/view.md)
* [获取对象](/helper/getobj.md)
* [惰性js](/helper/inertjs.md)
## 总览

所有请求参数处理在`Gaara\Core\Reuqest`类中，将各类http请求参数，处理成统一的格式。
`Gaara\Core\Reuqest`不会改变`$_POST`, `$_FILES`等php提供的数据，而优先按照请求头提供的数据类型进行解析，并放入自身对应属性。


## 获取参数
### 获取单个参数
```php
<?php
// 获取put请求下的get参数
// put请求域名 http://eg.com/?name=gaara, 响应 gaara
Route::put('/', function(Gaara\Core\Reuqest $request){
    return $request->get('name');
});
```
### 获取全部参数
```php
<?php
// 获取put请求下的get参数
// put请求域名 http://eg.com/?name=gaara&age=18
Route::put('/', function(Gaara\Core\Reuqest $request){
    return $request->get;           // 返回 ['name'=>'gaara','age'=>'18']

    return $request->get('name');   // 返回 'gaara'

    return $request->get('sex');    // 返回 ''
});
```

```php
<?php
// 获取put请求下的全部参数
// put请求域名 http://eg.com/?name=gaara&age=18
Route::put('/', function(Gaara\Core\Reuqest $request){
    // input会返回当前http请求方法put
    // 请求体为空, $request->input返回 []
    return $request->input;
});
```
**注: `input`方法将会返回当前`http方法`的请求体内容, 若当前`http方法`为`get`,则视`query_string`为请求体**

## 过滤参数

**注: 所有参数类型均为`string`, 有键无值时,值为''(空字符, 而非null)**

### request对象过滤

> 以`值`的形式返回结果, 参数不存在返回 null, 验证不通过返回 false

```php
<?php
// 获取put请求下的get参数
// put请求域名 http://eg.com/?name=18, 响应 ''
Route::put('/', function(Gaara\Core\Reuqest $request){
    // 允许5-32字节，允许字母数字下划线
    // 过滤请求参数, 参数不存在返回 null, 验证不通过返回 false
    return $request->get('name','/^[\w]{5,32}$/');
});
```

### 控制器过滤

> 以`响应`的形式返回结果, 响应形式在父类中有实现, 可在子类中重载

```php
<?php
namespace App\yh\c\Dev;

use Gaara\Exception\Http\UnprocessableEntityHttpException;

class Dev extends \Gaara\Core\Controller {

    public function index(){
        // 使用正则
        return $this->get('name','/^[\w]{5,32}$/','自定义的响应文本'););
    }

/********************* 以下方法在父类中已存在,可重载 **********************/

    /**
     * 定义当参数不合法时的响应
     * @param string $key
     * @param string $fun
     * @param string $msg
     */
    protected function requestArgumentInvalid(string $key, string $fun, string $msg = null) {
        $msg = $msg ?? 'Invalid request argument : '.$key.' ['.$fun.']';
		throw new UnprocessableEntityHttpException($message);
    }

    /**
     * 定义当参数不存在时的响应
     * @param string $key
     * @param string $fun
     * @param string $msg
     */
    protected function requestArgumentNotFound(string $key, string $fun, string $msg = null){
        $msg = $msg ?? 'Not found request argument : '.$key.' ['.$fun.']';
		throw new UnprocessableEntityHttpException($message);
    }

}

```

### 预定义正则

以上的校验都需要手动编写正则, 以下给出部分可用正则

| 预定义键      | 正则表达式    | 描述  |
| :-------------: |:-------------:|:-----:|
| `email`      | `/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/` | $1600 |
| `url`      | `/\b(([\w-]+:\/\/?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|\/)))/`      |   $12 |
| `int` | `/^-?\d+$/`      |    `正负数字` |
| `passwd` | `/^[\w]{5,32}$/`      |    `允许5-32字节，允许字母数字下划线` |
| `account` | `/^[a-zA-Z][a-zA-Z0-9_]{5,16}$/`      |    `字母开头，允许5-16字节，允许字母数字下划线` |
| `idcard` | `/^\d{15}|\d{18}$/`      |    `中国的身份证为15位或18位` |
| `mail` | `/^[1-9]\d{5}(?!\d)$/`      |    `中国邮政编码` |
| `qq` | `/^[1-9][0-9]{4,}$/`      |    `腾讯QQ号 腾讯QQ号从10000开始` |
| `telephone` | `/^\d{3}-\d{8}|\d{4}-\d{7}$/`      |    `国内电话号码 匹配形式如 0511-4405222 或 021-87888822` |
| `tel` | `/^1[3|4|5|7|8][0-9]\d{8}$/`      |   `中国手机号码` |
| `string` | `/^\w+$/`      |    `大小写字母,数字,下划线` |
| `token` | `/^[\w-]+$/`      |   `大小写字母,数字,下划线,减号'-'` |
| `sign` | `/^[\w]{32}$/`      |   `大小写字母,数字,下划线 32位` |
| `name` | `/^[_\w\d\x{4e00}-\x{9fa5}]{2,8}$/iu`      |    `2-8位中文` |

使用以上正则

```php
<?php

\Request::get('age','int'); // 使用 /^-?\d+$/ 验证 get 中的 age 字段

```
**注: `\Request::get()`等价于`obj(Request::class)->get()`**

拓展预定义正则

> 在任何一个通用入口处拓展`Request`对象的`filterArr`, 因为`Request`对象保持单例

```php
<?php

obj(Request::class)->filterArr['test_1_2_3'] => '/^[1,2,3]{1,}$/';

```

## 文件参数
Gaara\Core\Reuqest->file 即 Gaara\Core\Request\UploadFile，实现迭代器接口
下面这个例子可以快速验证文件类型后保存

```php
<?php
Route::put('/', function(Gaara\Core\Reuqest $request){
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
## 请求信息

```php
<?php

Class Request{
    public $inSys; // 入口文件名 eg:index.php
	public $isAjax; // 是否异步请求
	public $scheme; // https or http
	public $host; // example.com
	public $scriptName; // /index.php
	public $requestUrl; // /admin/index.php/product?id=100
	public $queryString; //返回 id=100,问号之后的部分。
	public $absoluteUrl; //返回 http://example.com/admin/index.php/product?id=100, 包含host infode的整个URL。
	public $hostInfo; //返回 http://example.com, 只有host info部分。
	public $pathInfo; //返回 /admin/index.php/product， 这问号之前（查询字符串）的部分。
	public $staticUrl; //返回 http://example.com/admin/index.php/product, 包含host pathInfo。
	public $serverName; //返回 example.com, URL中的host name。
	public $serverPort; //返回 80, 这是web服务中使用的端口。
	public $method; // 当前http方法
	public $alias; // 当前路由别名
	public $methods; // 当前路由可用的http方法数组
	public $userHost; // 来访者的host
	public $userIp; // 来访者的ip
	public $contentType; // 请求体格式
	public $acceptType; // 需求的相应体格式
	public $MatchedRouting = null; // 路由匹配成功后,由`Kernel`赋值的`MatchedRouting`对象


    // 设置cookie
    public function setcookie(string $name, $value = '', int $expire = 0, string $path = "", string $domain = "", bool $secure = false, bool $httponly = true);

}
