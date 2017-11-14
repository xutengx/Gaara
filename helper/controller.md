**gaara** `嘎啦`
==========================
以下的信息可以帮助你更好的使用这个框架 **gaara**, 更好的使用 **php**
****
#### Author:xuteng
#### E-mail:68822684@qq.com
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
    * [总览](#总览)
    * [一个控制器](#一个控制器)
    * [参数过滤](#参数过滤)
    * [视图赋值](#视图赋值)
* [数据库模型](/helper/model.md)
* [缓存](/helper/cache.md)
* [视图](/helper/view.md)
* [获取对象](/helper/getobj.md)
* [惰性js](/helper/inertjs.md)

## 总览

> 控制器一般继承`Gaara\Core\Controller`
> 路由执行的第一个方法(入口方法)是可以依赖注入的

## 一个控制器

```php
<?php

namespace App\yh\c\merchant;

use Gaara\Core\Controller;
use Gaara\Core\Request;
use App\yh\m\UserApplication;

class Application extends Controller {
    
    /**
     * 查询商户下所有应用信息
     * @param Request $request
     * @param UserApplication $application
     * @return type
     */
    public function select(Request $request, UserApplication $application) {
        $merchant_id = (int)$request->userinfo['id'];
        
        return $this->returnData(function() use ($application, $merchant_id){
            return $application->getAllByMerchantId( $merchant_id );
        });
    }
}
```
**注: 以上例子中`$request->userinfo`是在中间件的校验过程新增的属性**

## 参数过滤

```php
<?php

namespace App\yh\c\merchant;

use Gaara\Core\Controller;

class Application extends Controller {

    public function index() {
        // 获取全部post中的name
        $name = $this->post('name','name','name字段不合法');
        // 获取全部post
        $post = $this->post();
        return $this->returnData($name);
    }
}
```

**注: `$this->post('name','name','name字段不合法')`的第2个参数为正则校验规则的键, 也可以直接传入正则公式**

## 视图赋值

```php

<?php
namespace App;
class Dev extends \Gaara\Core\Controller {
    // 页面文件路径
    protected $view = 'App/yh/c/Dev/';
    // 中英文 0 中文 , 1 英文
    protected $language = 1;

    public function index() {
        // 赋值到php
        $this->assignPhp('url', url(''));
        // 赋值到js
        $this->assign('test', 'this is test string !');
        // 渲染视图
        return $this->display('demo');
    }
}

```

如上在`App/yh/c/Dev/demo.html`可以使用

```php

<?php echo $DATA['url']; ?>

```

也可以使用

```javascript

<script> console.log('test'); </script>

```
