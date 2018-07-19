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
    * [总览](#总览)
    * [方法列表](#方法列表)
        * [ajaxComet](#ajaxComet)
        * [url](#url)
        * [submitData](#submitData)
        * [多语言](#多语言)

## 总览

> 实现依赖jquery

> 当使用一个指定方法时, 对应的js及css文件将会被异步加载一次

> 所有预置的静态文件将被压缩后放于`public/open/minStatic`

## 方法列表

### ajaxComet

长轮询, 单页面锁定保证数据正确性, 提前退出便会结束当前轮询进程

**注:依赖`session`,`redis`**

js段,调用

```js
$.ajaxComet({
	url: '/ajax/do',     // 指向php段接口
	dataType: 'json',
	data: {'param1': Date.parse(new Date())}
}, function(res){
	console.log(res);
});
```
**注:`ajaxComet(obj, success_function) `**

php端,接受请求

```php
<?php

namespace App\Dev\Comet\Contr;

use Gaara\Core\{
	Controller, Cache, Request, Controller\Traits\CometTrait
};

class ajax extends Controller {

	use CometTrait;     // 引入`ajaxComet()`

	protected $view	 = 'App/Dev/Comet/View/';
	protected $title = 'ajax 长轮询 !';

    // 页面渲染 `display会提供可用js`
	public function index() {
		$this->assignPhp('title', $this->title);
		return $this->display();
	}

    // 轮询入口
	public function ajaxdo(Cache $Cache) {
		return $this->ajaxComet(function() use ($Cache) {
		    // 不符合条件,无需返回,进程将padding
			if ($value = $Cache->rpop('ajax')) {
				return $value;
			}
		}, 10);
	}

}
```
**注:`ajaxComet(Closure $callback, int $timeout = 30, float $sleep = 0.1): string `**

### url

生成url

```js
var u = $.url('ajax/do', {
    'name':'xuteng',
    'param1':'value'
});
console.log(u); // http://www.gaara.com/ajax/do?name=xuteng&param1=value
```


### submitData

提取`form`中的数据, 效验, 组装后提交

```html
<form action="action">
    <span>邮箱</span><input type="text" name="email" value="1771033392@qq.com"/><br>
    <span>url</span><input type="text" name="url" value="http://前端地址,我要注册#拼接参数=" /><br>
    <input id="userreg" type="button" value="submit">
</form>

<script>
    $('#userreg').submitData('/user/reg', function (re) {
        console.log(re);
    }, 'post');
</script>
```

### 多语言

多语言切换

```js

console.log($.lr('time')+date);

$.lw('time');

```
```php
<?php

namespace App\yh\c\Dev;

use Gaara\Core\Controller;

class Date extends Controller {

    protected $view = 'App/yh/c/Dev/';

    protected $language = 0;        // 确定语言键

    protected $language_array = [   // 语言数组
        'time' => [
            '时间','time'
        ]
    ];

    public function index() {
        $this->assign('date', date('Y-m-d H;i:s'));

        return $this->display('assembly/date');

    }

}

```