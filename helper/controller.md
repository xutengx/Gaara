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
    * [总览](#总览)
    * [控制器响应](#控制器响应)
    * [参数过滤](#参数过滤)
        * [自动过滤响应](#自动过滤响应)
    * [视图赋值](#视图赋值)
* [数据库模型](/helper/model.md)
* [缓存](/helper/cache.md)
* [视图](/helper/view.md)
* [获取对象](/helper/getobj.md)
* [惰性js](/helper/inertjs.md)

## 总览

> 控制器一般继承`Gaara\Core\Controller`

> 路由执行的第一个方法`(入口方法)`是可以依赖注入的, 对于`路由参数`则须要与`路由形参`一一对应

## 控制器响应

控制器的一般使用

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

**注: `returnData()`具备捕获异常的能力**

控制器返回一个`http 404`响应

```php
<?php

namespace App\yh\c\merchant;

use Gaara\Core\Controller;
use Gaara\Core\Request;
use App\yh\m\UserApplication;

class Application extends Controller {

    /**
     * 查询商户下应用信息
     * @param Request $request
     * @param UserApplication $application
     * @return type
     */
    public function select(Request $request, UserApplication $application) {
        $merchant_id = (int)$request->userinfo['id'];

        if($data = $application->getOneByMerchantId( $merchant_id )){
            return $this->success($data, 'Success!!!', 200);
        }else{
            return $this->fail('Fail !', 404);
        }
    }
}
```
**注: `Controller::success($data = [], string $msg = 'Success', int $statusCode = 200): string`**

**注: `Controller::fail(string $msg = 'Fail', int $statusCode = 400): string`**



## 参数过滤

```php
<?php

namespace App\yh\c\merchant;

use Gaara\Core\Controller;

class Application extends Controller {

    public function index() {
        // 获取post中的name
        $name = $this->post('name','name','name字段不合法');
        // 获取post中的name
        $name = $this->post('age','/^-?\d+$/','Invalid request argument age');
        // 获取全部post
        $post = $this->post();
        return $this->returnData($name);
    }
}
```

**注: `$this->post('name','name','name字段不合法')`的第2个参数为正则校验规则的键, 也可以直接传入正则公式**

### 自动过滤响应

通过合理的重载父类方法可以做到统一的响应

```php
<?php

namespace App\yh\c\merchant;

use Gaara\Core\Controller;

class Application extends Controller {

	/**
	 * 定义当参数不合法时的响应
	 * @param string $key
	 * @param string $fun
	 * @param string $msg
	 * @param string $rule
	 */
	protected function requestArgumentInvalid(string $key, string $fun, string $msg, string $rule) {
		$message = $msg ?? 'Invalid request argument : ' . $key . ' [ Rule : ' . $rule . ' ]';
		exit($this->fail($message, 422));
	}

	/**
	 * 定义当参数不存在时的响应
	 * @param string $key
	 * @param string $fun
	 * @param string $msg
	 * @param string $rule
	 */
	protected function requestArgumentNotFound(string $key, string $fun, string $msg, string $rule) {
		$message = $msg ?? 'Not found request argument : ' . $key . ' [ Method : ' . $fun . ' ]';
		exit($this->fail($message, 422));
	}
}
```

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

<?php echo $url; ?>

```

同时也可以使用

```javascript

<script> console.log('test'); </script>

```
