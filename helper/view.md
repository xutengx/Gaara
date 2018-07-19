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
    * [总览](#总览)
    * [视图渲染](#视图渲染)
    * [视图传值](#视图传值)
        * [js传值](#js传值)
        * [php传值](#php传值)
    * [资源文件引入](#资源文件引入)
    * [多语言](#多语言)
    * [视图组件](#视图组件)
        * [静态组件](#静态组件)
        * [动态组件](#动态组件)
* [获取对象](/helper/getobj.md)
* [惰性js](/helper/inertjs.md)

## 总览

> php自己就是最好的视图渲染器

`gaara`定义`控制器`既是`页面控制器`, 仅需要引入`视图构件`即可
```php
<?php

namespace App\yh\c\Dev;

class Dev extends \Gaara\Core\Controller {
    // 视图构件,页面相关函数引入
    use \Gaara\Core\Controller\Traits\RequestTrait;
}

```
**注 : 若`\Gaara\Core\Controller`已经引入视图构件`\Gaara\Core\Controller\Traits\RequestTrait`,则可以省略手动**



## 视图渲染

视图加载路径`$this->view` 
视图渲染`$this->display()`

```php
<?php

namespace App\yh\c\Dev;

class Dev extends \Gaara\Core\Controller {
    // 视图构件,页面相关函数引入
    use \Gaara\Core\Controller\Traits\RequestTrait;
    
    protected $view = 'App/yh/c/Dev/';
    
    public function index(){
         /**
         * 生成视图数据
         * @param string $file 模版文件名
         * @return string
         */
        return $this->display('index');
    }
}

```
如上将会响应 `ROOT`.`App/yh/c/Dev/index.html`文件的内容

**注 : 建议`视图文件`内容仅包含`<body>`内容`</body>`(不包含body标签)**

## 视图传值


### js传值

`$this->assign()`

```php
<?php

namespace App\yh\c\Dev;

class Dev extends \Gaara\Core\Controller {
    // 视图构件,页面相关函数引入
    use \Gaara\Core\Controller\Traits\RequestTrait;
    
    public function index(){
        /**
         * 将数据赋值到页面js 支持 int string array bool
         * @param string $name js对应键
         * @param mixed $val js对应值
         * @return void
         * @throws InvalidArgumentException
         */
        $this->assign('name', 'gaara');
        $this->assign('age', 18);
        
        return $this->display('index');
    }
}

```
视图文件内容
```html
<div>
    hello world
<div>
<script>
    console.log('name : '+ name);
    console.log('age : '+ age);
</script>
```
控制台将会打印`name : gaara`以及`age : 18`


### php传值

`$this->assignPhp()`

```php
<?php

namespace App\yh\c\Dev;

class Dev extends \Gaara\Core\Controller {
    // 视图构件,页面相关函数引入
    use \Gaara\Core\Controller\Traits\RequestTrait;
    
    public function index(){

        /**
         * 将数据赋值到页面php 以$DATA[$key]调用
         * @param string $key
         * @param mixed $val
         * @return void
         */
        $this->assignPhp('name', 'gaara');
        $this->assignPhp('age', 18);
        
        return $this->display('index');
    }
}

```
视图文件内容
```html
<div>
    name = <?php echo $name; ?> & age = <?php echo $age; ?>
<div>
```
页面显示`name : gaara & age : 18`

## 资源文件引入

`$this->js()` `$this->css()` `$this->js` `$this->css`

```php
<?php

namespace App\yh\c\Dev;

class Dev extends \Gaara\Core\Controller {
    // 视图构件,页面相关函数引入
    use \Gaara\Core\Controller\Traits\RequestTrait;
    
    protected $js = [
        'http://cdn.bootcss.com/blueimp-md5/1.1.0/js/md5.min.js'
    ];
    
    public function index(){
        /**
         * 定义将要引入的js文件
         * 第三方js文件,请使用绝对网路路径(以 http:// or https:// 开始)
         * @param string $file
         * @return void
         */
        $this->js('http://cdn.bootcss.com/blueimp-md5/1.1.0/js/md5.min.js');
        $this->js('test.js');
        
        $this->css('test.css');
        
        return $this->display('index');
    }
}

```
视图渲染时将会引入所指定的`js`与`css`文件

**注 : 引入的本地资源文件要放置于`/public`目录下**

## 多语言

`gaara`提供多语言支持

`$this->language` `$this->language_array`

```php
<?php

namespace App\yh\c\Dev;

class Dev extends \Gaara\Core\Controller {
    // 视图构件,页面相关函数引入
    use \Gaara\Core\Controller\Traits\RequestTrait;
    // 选择语种
    protected $language = 4;
    // 语种
    protected $language_array = [
        '时间' => [
            '时间','time','il tempo','le temps','Thời gian.'
        ]
    ];
    
    public function index(){
        return $this->display('index');
    }
}

```
视图文件内容
```html
<div>
    time = <script>$.lw('时间')</script>
<div>
<script>
    console.log($.lr('时间'));
</script>
```
视图响应`time = Thời gian.`, 控制台打印`Thời gian.`

**注 : `$.lr()`以及`$.lw()`,将会被自动加载**

## 视图组件

一个功能全面的页面, 有多个各自隔离的`视图组件`组成

### 静态组件

`$this->includeHtml()`

```php
<?php

namespace App\yh\c\Dev;

class Dev extends \Gaara\Core\Controller {
    // 视图构件,页面相关函数引入
    use \Gaara\Core\Controller\Traits\RequestTrait;
    // 选择语种
    protected $language = 4;
    // 语种
    protected $language_array = [
        '时间' => [
            '时间','time','il tempo','le temps','Thời gian.'
        ]
    ];
    
    public function index(){
        return $this->display('index');
    }
}

```
视图文件`index`内容
```html
<div>
    <?php $this->includeHtml('div.html'); ?>
<div>
```
视图文件`div`内容
```html
hello world !
```
视图响应`hello world !`

**注 : 如上`div`的文件路径,会拼接`ROOT`.`$this->view`**

### 动态组件

`$this->template()`

```php
<?php

namespace App\yh\c\Dev;

class Dev extends \Gaara\Core\Controller {
    // 视图构件,页面相关函数引入
    use \Gaara\Core\Controller\Traits\RequestTrait;
    // 选择语种
    protected $language = 4;
    // 语种
    protected $language_array = [
        '时间' => [
            '时间','time','il tempo','le temps','Thời gian.'
        ]
    ];
    
    public function index(){
        return $this->display('index');
    }
    
    public function html(){
        $this->js('test.js');
        $this->assignPhp('php', 'php content');
        // 使用 template 返回,确保视图组件的`赋值`以及`引入`正确执行 
        return $this-> template('dev');
    }
}

```
视图文件`index`内容
```html
<div>
    php content = <?php echo $this->html(); ?>
<div>
```

视图文件`dev`内容
```html
<div>
    <?php echo $php; ?>
<div>
```
视图响应`php content = php content`

**注 : 视图组件可以与原控制器完全无关,以上的例子中 视图文件`index`内容可以写成如下**

视图文件`index`内容
```html
<div>
    php content = <?php echo obj(App\Contr\view::class)->html(); ?>
<div>
```
**注 : 为确保视图组件的`赋值`以及`引入`正确执行, 请使用`$this->template()`返回你的视图组件**