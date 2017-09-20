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
* [响应](/helper/response.md)
* [中间件](/helper/middleware.md)
* [控制器](/helper/controller.md)
* [数据库模型](/helper/model.md)
    * [总览](#总览)
    * [一个模型](#一个模型)
    * [参数绑定](#参数绑定)
    * [闭包事务](#闭包事务)
    * [ORM](#ORM)
* [缓存](/helper/cache.md)
* [视图](/helper/view.md)
* [获取对象](/helper/getobj.md)
* [惰性js](/helper/inertjs.md)

## 总览

> 控制器一般继承`Main\Core\Model`

## 一个模型

```php
<?php

namespace App\yh\m;

class UserApplication extends \Main\Core\Model {
    // 主键的字段
    protected $key = 'id';
    // 表名
    protected $table = 'merchant';

    public function getAllByMerchantId(int $merchant_id): array {
        return $this
        ->where('merchant_id', $merchant_id)
        ->where('create_time', '>=', '2012-12-12')
        ->getAll();
    }
}
```

## 参数绑定

```php
<?php

namespace App\yh\m;

class UserApplication extends \Main\Core\Model {

    public function getInfoByIdWithMerchant(int $id, int $merchant_id): array {
        return $this->where('id', ':id')->where('merchant_id', $merchant_id)->getRow([':id' => $id]);
    }
}
```

## 闭包事务

```php
<?php
namespace App;
use App\Model;
class Dev extends \Main\Core\Controller\HttpController {
    public function index(Model\visitorInfoModel $visitorInfo){
    
        $res = $visitorInfo->transaction(function($obj){
        
            $obj->data(['name' => ':autoInsertName'])
                ->insert([':autoInsertName' => 'autoInsertName transaction']);
            $obj->data(['name' => ':autoInsertName'])
                ->insert([':autoInsertName' => 'autoInsertName transaction2']);
            $obj->data(['id' => ':autoInsertNam'])
                ->insert([':autoInsertNam' => '432']);
                
        },3);
        var_dump($res);
    }
}
```
## ORM

```php
<?php
namespace App;
use App\Model;
class Dev extends \Main\Core\Controller\HttpController {
    // orm新增
    public function index(Model\visitorInfoModel $visitorInfo){
        $data = $this->post();
        $visitorInfo->orm = $data;
        $visitorInfo->create();
    }
    // orm更新
    public function update(Model\visitorInfoModel $visitorInfo){
        $data = $this->put();
        $visitorInfo->orm = $data;
        $visitorInfo->save();
    }
}
```
