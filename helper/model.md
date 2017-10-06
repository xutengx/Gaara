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
    * [查询构造器](#查询构造器)
        * [获取](#获取)
        * [插入](#插入)
        * [更新](#更新)
        * [删除](#删除)
        * [select](#select)
        * [where](#where)
        * [having](#having)
        * [order](#order)
        * [group](#group)
        * [join](#join)
        * [limit](#limit)
        * [table](#table)
        * [data](#data)
    * [debug](#debug)
* [缓存](/helper/cache.md)
* [视图](/helper/view.md)
* [获取对象](/helper/getobj.md)
* [惰性js](/helper/inertjs.md)

## 总览

> 控制器一般继承`Main\Core\Model`

## 一个模型

申明一个模型

```php
<?php

namespace App\yh\m;

class UserApplication extends \Main\Core\Model {
    // 主键的字段
    protected $primaryKey = 'id';
    // 表名
    protected $table = 'merchant';
    // 一个外部调用的方法
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
        return $this->where('id', ':id')
        ->where('merchant_id', $merchant_id)
        ->getRow([':id' => $id]);
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
## 查询构造器

### 获取

```php
<?php
// 返回一维数组
$row = $yourModel::getRow();

// 返回二维数组
$row = $yourModel::getAll();

```
### 插入

```php

<?php
// 返回 bool
$row = $yourModel::data(['name','bob'])->insert();
// 返回 int 插入的主键
$row = $yourModel::data(['name','bob'])->insertGetId();

```
### 更新

```php

<?php
// 返回 int 受影响的行数
$row = $yourModel::data(['name','bob'])->where('id',12)->update();

```
### 删除

```php

<?php
// 返回 int 受影响的行数
$row = $yourModel::->where('id',12)->delete();

```

### select

```php
<?php
// select方法接收string或者array,分别对应selectString与selectArray方法
$row = $yourModel::select('name,age')->select(['sex','height'])->getRow();

// 以上等价于
$row = $yourModel::selectString('name,age')->selectArray(['sex','height'])->getRow();
```
### where

> 字段与值比较

```php
<?php
$row = $yourModel::where('id','12')
->where('age','>=','19')
->where(['name' => 'Bob', 'sex'=>'2'])
->getRow();

// 以上等价于
$row = $yourModel::whereValue('id','=','12')
->whereValue('age','>=','19')
->whereArray(['name' => 'Bob', 'sex'=>'2'])
->getRow();
```

> between


```php
<?php

$row = $yourModel::whereBetween('id', ['100','103' ])
->whereBetween('age',18, 23)
->whereNotBetween('height',156, 189)
->whereNotBetween('weight'[40, 120])
->getAll();

// 以上等价于
$row = $yourModel::whereBetweenArray('id', ['100','103' ])
->whereBetweenString('age','18', '23')
->whereNotBetweenString('height','156', '189')
->whereNotBetweenArray('weight'[40, 120])
->getAll();
```
**注: `whereBetweenString`与`whereNotBetweenString`参数必须是string**

> in

```php
<?php

$row = $yourModel::whereIn('id', ['100','103' ])
->whereIn('age',18, 23)
->whereNotIn('height',156, 189)
->whereNotIn('weight'[40, 120])
->getAll();

// 以上等价于
$row = $yourModel::whereInArray('id', ['100','103','26' ])
->whereInString('age','18,23,46')
->whereNotInString('height','156,189')
->whereNotInArray('weight'[40, 120, 88])
->getAll();
```
**注: `whereInString`与`whereNotInString`参数必须是string**

> 闭包where orWhere 支持无限嵌套

```php
<?php
// where `id`="102"
$row = Model\visitorInfoDev::where(function($queryBuiler){
        $queryBuiler->where('id', '102');
    })->getAll();
    
// where `id`="102" or (`id`="103")
$row = Model\visitorInfoDev::where('id','102')
    ->orWhere(function($queryBuiler){
        $queryBuiler->where('id', '103');
    })->getAll();
    

```

> whereNotNull whereNull

```php
<?php
// where `name`is not null
$row = Model\visitorInfoDev::whereNotNull('name')->getAll();
  
// where `name`is null  
$row = Model\visitorInfoDev::whereNull('name')->getAll();

```
### having

> 同 where, 但没有快捷方法

### order

```php
<?php
$row = $yourModel::order('id')->order('name', 'desc')
->getAll();

```
### group

```php
<?php
$row = $yourModel::group('time')->group(['name', 'desc'])
->getAll();

```
### join

```php
<?php
$row = $yourModel::join('表名','字段一','=','字段二','inner join')
->getAll();

```
### limit

```php
<?php
$row = $yourModel::limit(1)->getAll();
$row = $yourModel::limit(1,4)->getAll();

```
### table

```php
<?php
$row = $yourModel::table('表名')->getAll();

```
### data

```php
<?php
$row = $yourModel::data('name','bob')->data([
'age'=> '12'])->update();

```
## debug

> 返回已执行的最近sql

```php
$res = Model\visitorInfoDev::select(['id', 'name', 'phone'])
    ->where( 'scene', '&', ':scene_1')
    ->where( 'phone', '13849494949')
    ->whereIn('id',['100','101','102','103'])
    ->orWhere(function($queryBuiler){
        $queryBuiler->where('id', '102')->where('name','xuteng')->orWhere(function($re){
                   $re->where('phone','13849494949')->whereNotNull('id');
                });
    })
    ->getAll([':scene_1' => '1']);
            
$sql = Model\visitorInfoDev::getLastSql();
```
> 返回未执行的sql

```php
sql = Model\visitorInfoDev::select(['id', 'name', 'phone'])
    ->where( 'scene', '&', ':scene_1')
    ->where( 'phone', '13849494949')
    ->whereIn('id',['100','101','102','103'])
    ->orWhere(function($queryBuiler){
        $queryBuiler->where('id', '102')->where('name','xuteng')->orWhere(function($re){
                    $re->where('phone','13849494949')->whereNotNull('id');
                });
    })
    ->getAllToSql([':scene_1' => '1']);
```