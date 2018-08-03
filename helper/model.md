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
    * [总览](#总览)
    * [数据库配置文件](#数据库配置文件)
    * [一个模型](#一个模型)
    * [参数绑定](#参数绑定)
    * [闭包事务](#闭包事务)
    * [ORM](#ORM)
    * [查询构造器](#查询构造器)
        * [获取](#获取)
        * [插入](#插入)
        * [更新](#更新)
        * [删除](#删除)
        * [聚合函数](#聚合函数)
        * [自增或自减](#自增或自减)
        * [随机获取](#随机获取)
        * [select](#select)
        * [where](#where)
        * [having](#having)
        * [order](#order)
        * [group](#group)
        * [join](#join)
        * [limit](#limit)
        * [table](#table)
        * [data](#data)
        * [union](#union)
        * [index](#index)
        * [lock](#lock)
    * [debug](#debug)
    * [where子查询](#where子查询)
    * [分块查询](#分块查询)
    * [预处理语句复用](#预处理语句复用)
    * [原生sql](#原生sql)
    * [注册查询方法](#注册查询方法)
* [缓存](/helper/cache.md)
* [视图](/helper/view.md)
* [获取对象](/helper/getobj.md)
* [惰性js](/helper/inertjs.md)

## 总览

> 控制器一般继承`Gaara\Core\Model`

`gaara`的数据库模型, 支持链式操作来构建查询语句, 分布式数据库配置支持, 分块数据获取, 预处理语句复用等等

## 数据库配置

配置文件`Config/db.php`;
其中`default`对应的是所有`model`默认的数据库连接, 其效果可被各个`model`的`$connection`属性所覆盖;
`connections`的每个子项则为具体的数据库连接属性, 由`write`与`read`两部分组成, 每个部分由一个,或者多个`详细信息数组`构成;
```php
<?php
return [
    'default' => env('DB_CONNECTION', '_test'),
    'connections' => [
        '_test' => [
            'write' => [
                [
                    'weight' => 10,
                    'type' => 'mysql',
                    'host' => env('db_host'),
                    'port' => 3306,
                    'user' => env('db_user'),
                    'pwd' => env('db_passwd'),
                    'char' => 'UTF8',
                    'db' => env('db_db'),
                ]
            ],
            'read' => [
                [
                    'weight' => 1,
                    'type' => 'mysql',
                    'host' => env('db_host'),
                    'port' => 3306,
                    'user' => env('db_user'),
                    'pwd' => env('db_passwd'),
                    'char' => 'UTF8',
                    'db' => env('db_db'),
                ]
            ]
        ]
    ]
];
```
## 一个模型

申明一个模型

```php
<?php

namespace App\yh\m;

class UserApplication extends \Gaara\Core\Model {
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

class UserApplication extends \Gaara\Core\Model {

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
class Dev extends \Gaara\Core\Controller {
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
**注: `transaction()`返回`bool`, 表示事物是否提交成功, 要手动终止事物, 可在闭包内抛出任意异常**

## ORM

```php
<?php
namespace App;
use App\Model;
class Dev extends \Gaara\Core\Controller {
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
**注: 如果`$data`中不存在数据表的主键,则需要在`save($key)`中传入**

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
$row = $yourModel::where('id',12)->delete();

```

### 聚合函数

> 可以在创建查询后调用 count、 max、 min、 avg 和 sum 其中的任意一个方法, 他们均返回 int

```php

<?php
// name=prepare 的行数
$visitorInfo->where('name','prepare')->count('*');
// 兼容group的count()
$visitorInfo->select('name')->where('name','prepare')->group('name,note')->count('note');
// 最大id
$visitorInfo->max('id');
// 最小id
$visitorInfo->min('id');
// 平均价格
$visitorInfo->avg('price');
// 总价格
$visitorInfo->sum('price');

```

### 自增或自减

```php

<?php
// num字段自加4
$visitorInfo->dataIncrement('num', 4)->update();
// num字段自减1
$visitorInfo->dataDecrement('num')->update();

```

### 随机获取

inRandomOrder

> inRandomOrder, 接收一个参数,默认为主键字段作为随机依据,当主键非常不均匀时应传入此字段(优先选用连续计数类型字段).此方法大数据下表现良好.

```php

<?php
// 随机返回5条数据
$res = $visitorInfo->inRandomOrder()->limit(5)->getAll();

```

**注: 若使用查询生成器同样可以实现**

```php

<?php
// 随机返回5条数据
$res = $visitorInfo->whereSubQuery('id','>=',function($query){
    // floor转化为int
    $query->select('floor', function($query){
        // 需要2个初始查询构造器
        $query_b = clone $query;
        // 查询最大id的sql
        $maxSql = $query->select('max',function(){
            return 'id';
        })->sql();
        // 查询最小id的sql
        $minSql = $query_b->select('min',function(){
            return 'id';
        })->sql();
        // sql拼接返回
        return 'rand()*(('.$maxSql.')-('.$minSql.'))+('.$minSql.')';
    })
        // 不拼接from片段
        ->noFrom();
})->limit(5)->getAll();

```

### select

查询字段

```php
<?php
// select方法接收string或者array,分别对应selectString与selectArray方法
$row = $yourModel::select('name,age')->select(['sex','height'])->getRow();

// 以上等价于
$row = $yourModel::selectString('name,age')->selectArray(['sex','height'])->getRow();

// 以上不完全等价于
$row = $yourModel::selectRaw('name,age,sex,height')->getRow();
```
**注:每个select仅接受一个string或者一个array作为参数**

查询一个数据库函数的结果

```php
<?php
// 统计最大的id
$query->select('max',function(){
    return 'id';
})->getRow();

// 以上等价于
$query->selectFunction('max',function(){
    return 'id';
})->getRow();

// 使用别名
$query->select('max',function(){
    return 'id';
},'max_id')->getRow();

```

### where

原生where

```php
<?php
$row = $yourModel::where('id=12')->getRow();

// 以上等价于
$row = $yourModel::whereRaw('id=12')->getRow();
```

字段与值比较

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

字段与字段比较

```php
<?php
// 返回一行, age字段大于其id字段
$row = $yourModel::whereColumn('age','>=','id')
->getRow();

```
**注:whereValue,whereColumn必须接收3个string参数**

whereBetween whereNotBetween


```php
<?php

$row = $yourModel::whereBetween('id', ['100','103' ])
->whereBetween('age',18, 23)
->whereNotBetween('height',156, 189)
->whereNotBetween('weight',[40, 120])
->getAll();

// 以上等价于
$row = $yourModel::whereBetweenArray('id', ['100','103' ])
->whereBetweenString('age','18', '23')
->whereNotBetweenString('height','156', '189')
->whereNotBetweenArray('weight',[40, 120])
->getAll();
```
**注: `whereBetweenString`与`whereNotBetweenString`参数必须是string**

whereIn whereNotIn

```php
<?php

$row = $yourModel::whereIn('id', ['100','103' ])
->whereIn('age','18,23')
->whereNotIn('height','156,189')
->whereNotIn('weight',[40, 120])
->getAll();

// 以上等价于
$row = $yourModel::whereInArray('id', ['100','103','26' ])
->whereInString('age','18,23,46')
->whereNotInString('height','156,189')
->whereNotInArray('weight',[40, 120, 88])
->getAll();
```
**注: `whereInString`与`whereNotInString`参数必须是string**

闭包where orWhere
> 支持无限嵌套

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

whereNotNull whereNull

```php
<?php
// where `name`is not null
$row = Model\visitorInfoDev::whereNotNull('name')->getAll();

// where `name`is null
$row = Model\visitorInfoDev::whereNull('name')->getAll();

```

whereExists whereNotExists
> 可接收Closure,String,2种参数

```php
<?php
$first = $visitorInfo->select(['id', 'name', 'phone'])->whereBetween('id','100','103');

$res = Model\visitorInfoDev::select(['id', 'name', 'phone'])
    ->whereBetween('id','100','103')
    // 接收String
    ->whereExists($first->getAllToSql())
    // 接收Closure 推荐
    ->whereExists(function($obj){
        $obj->select(['id', 'name', 'phone'])
        ->whereBetween('id','100','103');
    })
    ->getAll();

```

### having

> 同 where

### order

```php
<?php
$row = $yourModel::order('id')->order('name', 'desc')->getAll();
```
### group

```php
<?php
$row = $yourModel::group('time')->group(['name', 'age'])->getAll();
```
### join

```php
<?php
$row = $yourModel::join('表名','字段一','=','字段二','inner join')->getAll();
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
$row = $yourModel::data('name','bob')->data(['age'=> '12'])->update();

```
### union

> union 可接收Closure,String,2种参数. 也可使用 unionAll 方法，它和 union 方法有着相同的用法

```php
<?php
$first = $visitorInfo->select(['id', 'name', 'phone'])->whereBetween('id','100','103');

$res = Model\visitorInfoDev::select(['id', 'name', 'phone'])
    ->whereBetween('id','100','103')
    // 接收String
    ->unionAll($first->getAllToSql())
    // 接收Closure 推荐
    ->union(function($obj){
        $obj->select(['id', 'name', 'phone'])
        ->whereBetween('id','100','103');
    })
    ->getAll();

```
### index

> 当你在调用 getAll() 方法时，它将返回一个以连续的整型数值为索引的数组。 而有时候你可能希望使用一个特定的字段或者表达式的值来作为索引结果集数组。

```php

<?php
// 返回 [100 => ['id' => 100, 'name' => 'Bob', ...], 101 => [...], 103 => [...], ...]
$res = $visitorInfo->select(['id', 'name', 'phone'])
->whereBetween('id','100','104')
->index('id')
->getAll();

```
> 如需使用表达式的值做为索引，那么只需要传递一个匿名函数给 index() 方法即可：

```php
<?php
// 返回 ['100_Bob' => ['id' => 100, 'name' => 'Bob', ...], 101_Peter => [...], 103_Alice => [...], ...]
$res = $visitorInfo->select(['id', 'name', 'phone'])
->whereBetween('id','100','104')
->index(function($row){
    return $row['id'].'_'.$row['name'];
})
->getAll();
```

**注: index()的键名若发生重复,将会覆盖。index()同样可作用于getChunk()方法返回的`QueryChunk`对象**

### lock

> 获取事物之外的目标数据的最新状态,若目标数据不可独占(其他锁,其他事物修改但未提交)便等待,获取成功并上锁(`更新`&`上锁查询`等待直至本事物提交)。

```php

<?php
$visitorInfo->transaction(function($obj) {
    $obj->where('id', '>=', "1")->where('id', '<=', "256")
    ->having('id','<>','256')->order('id', 'desc')
    ->select('id')->group('id')->lock()->getRow();
}, 3);

```

**注: `gaara`还同时提供`lockForShared()`与`lockForUpdate()`方法**

**注: 配合`mysql`的`Repeatable read`设置, 可有效解决`幻读`**

## debug

> 返回已执行的最近sql

```php
<?php
$res = Model\visitorInfoDev::select(['id', 'name', 'phone'])
    ->where( 'scene', '&', '1')
    ->where( 'phone', '13849494949')
    ->whereIn('id',['100','101','102','103'])
    ->orWhere(function($queryBuiler){
        $queryBuiler->where('id', '102')->where('name','xuteng')->orWhere(function($re){
                   $re->where('phone','13849494949')->whereNotNull('id');
                });
    })
    ->getAll();

$sql = Model\visitorInfoDev::getLastSql();
```
> 返回未执行的sql

```php
<?php
$sql = Model\visitorInfoDev::select(['id', 'name', 'phone'])
    ->where( 'scene', '&', '1')
    ->where( 'phone', '13849494949')
    ->whereIn('id',['100','101','102','103'])
    ->orWhere(function($queryBuiler){
        $queryBuiler->where('id', '102')->where('name','xuteng')->orWhere(function($re){
                    $re->where('phone','13849494949')->whereNotNull('id');
                });
    })
    ->getAllToSql();
```

## where子查询

> 链式操作对象`QueryBuiler`中包含`whereSubquery`方法, 接收 string $field, string $symbol, (string $subquery | Closure $Closure)

```php
<?php
// 以下是使用`闭包`子查询的一个例子
// select sum(`id`) from `visitor_info` where `id`in(select `id` from `visitor_info` where `id`in("155","166")) limit 1"
$res = $visitorInfo->whereSubquery('id','in', function($queryBiler){
    $queryBiler->select('id')->whereIn('id',[155,166]);
})->sum('id');

var_dump($res);  // int(321)
```

## 分块查询

> 链式操作对象`QueryBuiler`中包含`getChunk`方法, 接收参数绑定数组(与getAll()相同), 返回`QueryChunk`对象实现Iterator 接口

```php
<?php
$chunk = $visitorInfo->getChunk();
foreach($chunk as $k => $v){
    var_dump($k);
    var_export($v);
}
```
**注: getChunk()返回的`QueryChunk`对象只能被遍历一次,采用如下的写法即可遍历多次**

```php
<?php
$res = $visitorInfo->where('id','>','200');
// 遍历第一次
foreach($res->getChunk() as $k => $v){
    var_dump($k);
    var_export($v);
}
// 遍历第二次
foreach($res->getChunk() as $k => $v){
    var_dump($k);
    var_export($v);
}
```
**注: `分块获取`基于`PDO::MYSQL_ATTR_USE_BUFFERED_QUERY = false`,在`PHP`取回所有结果前,当前数据库连接下不能发送其他的查询请求**
```php
<?php
$res = $visitorInfo->where('id','>','200');
// 以下写法将会报错`SQLSTATE[HY000]: General error: 2014 Cannot execute queries while other unbuffered queries are active. `
foreach($res->getChunk() as $k => $v){
    $chunk = $visitorInfo->where('id',$v['id'])->getChunk();
    foreach($chunk as $v2){
        var_dump($v2);
    }
}
// 以下写法正确
foreach($res->getAll() as $k => $v){
    $chunk = $visitorInfo->where('id',$v['id'])->getChunk();
    foreach($chunk as $v2){
        var_dump($v2);
    }
}
```
**注: 手动释放当前查询`QueryChunk::closeCursor():bool`**
```php
<?php
$QueryChunk = $res->getChunk();
foreach($QueryChunk as $k => $v){
    var_dump($k);
    var_export($v);
	$QueryChunk->closeCursor(); // 提前退出, 须要手动释放当前查询
	break;
}
```

## 预处理语句复用

> 链式操作对象`QueryBuiler`中包含`selectPrepare`,`detelePrepare`,`updatePrepare`,`insertPrepare`,`replacePrepare`五个方法, 他们均返回`QueryPrepare`对象;

> `QueryPrepare`中包含`getRow`,`getAll`,`delete`,`update`,`insert`,`replace`六个方法,可每次接收不同绑定参数重复调用

```php
<?php
$p = $visitorInfo->where('id',':id')->selectPrepare();
var_dump($p->getRow([':id' => '12']));
var_dump($p->getRow([':id' => '11']));
var_dump($p->getRow([':id' => '102']));
```

## 原生sql

> Model的`query`方法返回`已`执行的`PDOStatement`对象;

```php
<?php
$sql = 'select * from visitor_info limit 1';
$PDOStatement = $visitorInfo->query($sql, 'select');
$res = ($PDOStatement->fetchall(\PDO::FETCH_ASSOC));

$sql = 'insert into visitor_info set name="原生sql插入"';
$PDOStatement = $visitorInfo->query($sql, 'insert');
$res = ($PDOStatement->rowCount());

```
> Model的`prepare`方法返回`未`执行的`PDOStatement`对象;

```php

<?php
$sql = 'insert into visitor_info set name=:name';
$PDOStatement = $visitorInfo->prepare($sql, 'insert');
// 手动执行
$PDOStatement->execute([':name' => '手动执行']);
$res = ($PDOStatement->rowCount());
```
## 注册查询方法

`gaara`提供可拓展的接口, 以便增加制定的查询方法

首先, 重载`Model`中的`registerMethodForQueryBuilder()`,以下的例子将注册`ID_is_bigger_than_1770()`以及`ID_rule()`两个方法.
```php
<?php
class visitorInfoDev extends \Gaara\Core\Model{

    public function registerMethodForQueryBuilder():array{
        return [
            'ID_is_bigger_than_1770' => function(QueryBuiler $queryBuiler): QueryBuiler{
                return $queryBuiler->where('id','>','1770');
            },
            'ID_rule' => function(QueryBuiler $queryBuiler, $id = 1800): QueryBuiler{
                return $queryBuiler->ID_is_bigger_than_1770()->where('id','<',$id);
            },
        ];
    }

}
```
**注 : `注册查询方法`是可以相互依赖的, 比如`ID_rule()`是依赖`ID_is_bigger_than_1770()`, 但需要注意注册顺序**

然后, 便可以使用它
```php
<?php
// 注册查询方法 ID_rule(),可以接受一个参数
$chunk = $visitorInfo->ID_rule(2100)->limit(10000);

foreach($res->getChunk() as $k => $v){
    var_dump($k);
    var_export($v);
}
```
**注 : `注册查询方法`属于`Model`隔离, 若希望提高生效范围, 可以重载父类**