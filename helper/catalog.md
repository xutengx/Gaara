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
    * [总览](#总览)
    * [规划你的工程目录](#规划你的工程目录)
        * [多个小型项目](#多个小型项目)
        * [单个普通项目](#单个普通项目)
        * [一个大型项目](#一个大型项目)
    * [总结](#总结)
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

## 总览

~~~
├─ App                      `应用目录`
│   ├─ ( 新建 )             [`规划你的工程目录`](#规划你的工程目录)
│   └─ Kernel.php           `核心执行(虽说是核心,常用功能还是定义中间件以及异常捕获后的处理)`
│
├─ Config                   `以下是各种配置项目, 按需建立, 敏感信息写env.php中, 按env('配置名')读取`
│   └─...
│
├─ Gaara                     `框架目录`
│   ├─ Core                 `核心目录`
│   │   └─...
│   │
│   ├─ Quick                `快捷方式目录`
│   │   ├─ ClassAilas.php   `根命名空间类别名`
│   │   └─Function.php      `全局方法`
│   │
│   └─Views                 `前端静态文件目录`
│        ├─include          `惰性引入的前端文件`
│        └─tpl              `框架使用的模版`
│
├─ Route                    `路由目录`
│   └─ http.php             `http路由`
│
├─ data                     `临时文件目录(需要可写)`
│
├─ helper                   `你看的这个markdown就这里面藏着`
│
├─ public                   `WEB目录（对外访问目录）`
│   ├─ open                 `生成的外部访问文件目录(需要可写)`
│   ├─ index.php            `入口文件`
│   └─ .htaccess            `用于apache的重写`
│
├─ vendor                   `第三方类库目录（Composer依赖库）`
├─ init.php                 `初始化`
~~~

## 规划你的工程目录

如你所见，组织和新建你的工程目录，框架没有任何限制，你可以随意的发挥你的创意！
当然，为了表示作者不是单纯因为懒而已，所以下面还会给出一些建议供参考。

### 多个小型项目

> 特点：许多小的创意和功能程序，放在一台服务器上面,他们使用着同样的数据库的不同的数据表。

~~~

├─ App
│   ├─ clockingProgram      `单次会议打卡程序(php接口)`
│   │   ├─ checkMan.php     `控制器1`
│   │   └─ checkWoman.php   `控制器2`
│   │
│   ├─ TheLanternFestival   `元宵节抽奖html5(php前后)`
│   │   ├─ getPrice.php     `控制器1`
│   │   ├─ SendPrize.php    `控制器2`
│   │   ├─ getPrice.html    `页面1`
│   │   └─ SendPrize.html   `页面2`
│   │
│   ├─ common                `你的可复用的代码库目录`
│   │
│   └─ Model                `数据库模型目录, 一一对应各个数据表` 
│   │   ├─ User.php         `对应user表`
│   │   └─ Meeting.php      `对应meeting表`
│   │
│   └─ Kernel.php
│ 
├─ public                   `WEB目录（对外访问目录）`
│   ├─ TheLanternFestival   `可访问的静态文件`
│   │   ├─ TheLanternFestival.css  `css1`
│   │   └─ TheLanternFestival2.js  `js2`
│   │
│   └─ index.php            `入口文件`
~~~

### 单个普通项目

> 特点：大多数项目之初都是如此，没人知道上线后你的项目能不能在市场站住脚，过早的优化的万恶之源，警示着你。

~~~
├─ App
│   ├─ Controller           `控制器目录`
│   │   ├─ User             `用户相关控制器目录`
│   │   │  ├─ login.php     `登入控制器`
│   │   │  └─ logout.php    `登出控制器` （如果是单单清除session或者redis，那么是没有必要滴）
│   │   ├─ Article          `控制器目录`
│   │   ├─ Score            `控制器目录`
│   │   ├─ ....
│   │   └─ Prize            `奖品相关`
│   │
│   ├─ Middleware           `中间件目录`
│   │   ├─ Sign.php         `签名校验`
│   │   ├─ WhoUA.php        `用户信息获取`
│   │   ├─ ....
│   │   └─ SendMessage.php `奖品库存同步消息`
│   │
│   ├─ Server               `一组功能的集合`
│   │   ├─ Sign.php         `Sign生成`
│   │   ├─ Filter.php       `参数过滤`
│   │   ├─ ....
│   │   └─ WinPrize.php     `减积分，得奖品，发邮件的一组动作`
│   │
│   ├─ View                 `视图（纯api自然是没有的，建议是与Controller命名对应）`
│   │   ├─ User             `用户相关视图目录`
│   │   │  ├─ login.html     `登入视图`
│   │   │  └─ logout.html    `登出视图`
│   │   ├─ Article          `视图`
│   │   ├─ Score            `视图`
│   │   ├─ ....
│   │   └─ Prize            `奖品相关视图`
│   │ 
│   ├─ common                `你的可复用的代码库目录`
│   │
│   └─ Model                `数据库模型目录, 一一对应各个数据表` 
│   │   ├─ User.php         `对应user表`
│   │   └─ Meeting.php      `对应meeting表`
│   │
│   └─ Kernel.php
│ 
├─ public                   `WEB目录（对外访问目录）`
│   ├─ static                 `视图静态资源（纯api自然是没有的，建议是与Controller命名对应）`
│   │   ├─ User             `用户相关目录`
│   │   │  ├─ login.js      
│   │   │  └─ logout.css 
│   │   ├─ Article         
│   │   ├─ Score 
│   │   ├─ ....
│   │   └─ Prize
│   │
│   └─ index.php            `入口文件`
~~~

### 一个大型项目

> 特点：当写一个项目的2.0或更高版本时，可能会遇到一些情况，除了目录的更细的划分外，下面还给出多模型的划分。

~~~
├─ App
│   ├─ Controller           `控制器目录`
│   │   ├─ User             `用户相关控制器目录`
│   │   │  ├─ login.php     `登入控制器`
│   │   │  └─ logout.php    `登出控制器` （如果是单单清除session或者redis，那么是没有必要滴）
│   │   ├─ Article          `控制器目录`
│   │   ├─ Score            `控制器目录`
│   │   ├─ ....
│   │   └─ Prize            `奖品相关`
│   │
│   ├─ Middleware           `中间件目录`
│   │   ├─ Sign.php         `签名校验`
│   │   ├─ WhoUA.php        `用户信息获取`
│   │   ├─ ....
│   │   └─ SendMessage.php `奖品库存同步消息`
│   │
│   ├─ Server               `一组功能的集合`
│   │   ├─ Sign.php         `Sign生成`
│   │   ├─ Filter.php       `参数过滤`
│   │   ├─ ....
│   │   └─ WinPrize.php     `减积分，得奖品，发邮件的一组动作`
│   │
│   ├─ View                 `视图（纯api自然是没有的，建议是与Controller命名对应）`
│   │   ├─ User             `用户相关视图目录`
│   │   │  ├─ login.html     `登入视图`
│   │   │  └─ logout.html    `登出视图`
│   │   ├─ Article          `视图`
│   │   ├─ Score            `视图`
│   │   ├─ ....
│   │   └─ Prize            `奖品相关视图`
│   │ 
│   ├─ common                `你的可复用的代码库目录`
│   │
│   └─ Model                `数据库模型目录, 一一对应各个数据表` 
│   │   ├─ Asia             `亚洲数据库`
│   │   │  ├─ User.php      `对应亚洲数据库user表`
│   │   │  └─ Meeting.php   `对应亚洲数据库meeting表`
│   │   ├─ Thailand         `泰国数据库模型目录`
│   │   ├─ Indonesia        `印尼数据库模型目录`
│   │   ├─ ....
│   │   ├─ User.php         `对应user表`
│   │   └─ Meeting.php      `对应meeting表`
│   │
│   └─ Kernel.php
│ 
├─ public                   `WEB目录（对外访问目录）`
│   ├─ static                 `视图静态资源（纯api自然是没有的，建议是与Controller命名对应）`
│   │   ├─ User             `用户相关目录`
│   │   │  ├─ login.js      
│   │   │  └─ logout.css 
│   │   ├─ Article         
│   │   ├─ Score 
│   │   ├─ ....
│   │   └─ Prize
│   │
│   └─ index.php            `入口文件`
~~~

## 总结

最合适就是最好的。