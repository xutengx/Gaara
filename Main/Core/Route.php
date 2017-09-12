<?php

namespace Main\Core;
defined('IN_SYS') || exit('ACC Denied');

/**
 * 显式自定义路由
 */
class Route {

    private static $pathInfo = null;
    private static $routeType = null;
    protected static $routeRule = [];
    // 路由别名, 默认为路由正则string
    private static $alias = null;
    // 域名参数
    private static $domainParam = [];
    // 路由参数
    private static $urlParam = [];
    // 当前路由可用http方法
    private static $methods = [];

    public static function Start() {
        self::getConf();
        // 得到 $pathInfo
        self::$pathInfo = self::getPathInfo();
        // 得到当前模式 cli ?
        self::$routeType = self::getRouteType();
        // 引入route规则
        self::$routeRule = self::getRouteRule();
        // 分析路由, 并执行
        self::routeAnalysis();
    }
    
    /**
     * 读取配置 ( 全局设置 )
     */
    private static function getConf(): void {
        $conf = obj(Conf::class)->app;
        date_default_timezone_set($conf['timezone']);
        if ($conf['debug'] === true) {
            ini_set('display_errors', '1');
            error_reporting(E_ALL);
        } else
            ini_set('display_errors', '0');
    }

    /**
     * 分析url,得到pathinfo  eg:http://192.168.64.128/git/php_/project/user/login/123/11?id=12 -> /user/login/123/11
     * @return string
     */
    private static function getPathInfo(): string {
        return '/' . \str_replace('?' . $_SERVER['QUERY_STRING'], '', \str_replace(\str_replace(\IN_SYS, '', $_SERVER['SCRIPT_NAME']), '', $_SERVER['REQUEST_URI']));
    }

    /**
     * 分析当前执行环境 CLI/http ?
     * @return type
     */
    private static function getRouteType(): string {
        return CLI ? 'cli' : 'http';
    }

    /**
     * 得到当前应该使用的route配置
     * 可以接收直接返回的数组格式, 也可以直接执行
     * @return array
     */
    private static function getRouteRule(): array {
        $fileRule = require(ROUTE . self::$routeType . '.php');
        return is_array($fileRule) ? array_merge(self::$routeRule, $fileRule) : self::$routeRule;
    }

    /**
     * 路由分析, 包含最终执行
     */
    private static function routeAnalysis(): void {
        foreach (self::$routeRule as $rule => $info) {
            // 路由分组
            if (is_int($rule)) {
                if (is_null($info))
                    continue;
                list($rule, $info) = each($info);
            }
            $parameter = [];
            $pathInfoPreg = self::ruleToPreg($rule, $parameter);
            // 确定路由匹配
            if (preg_match($pathInfoPreg, self::$pathInfo, $argument)) {
                // 确认 url 参数
                $urlParam = self::paramAnalysis($parameter, $argument);
                // 执行分析
                $check = self::infoAnalysis($rule, $info, $urlParam);

                // 域名不匹配, 则继续 foreach
                if ($check === false)
                    continue;
                else
                    exit();
            }
        }
        obj(Response::class)->returnData('', false, 404);
    }

    /**
     * 将路由规则翻译为正则表达式
     * @param string    $rule       url规则
     * @param array     &$param     url上的形参组成的一维数组
     * @return string               正则表达式
     * @return array    $param      形参数组
     */
    private static function ruleToPreg(string $rule = '', array &$param = []): string {
        $temp = explode('/', $rule);
        foreach ($temp as $k => $v) {
            $key = false;
            $temp[$k] = \preg_replace_callback("/{.*\?}/is", function($matches) use (&$param, &$key) {
                $param[] = trim(trim($matches[0], '?}'), '{');
                $key = true;
                return '?(/[^/]*)?';
            }, $v);
            if ($key)
                continue;
            $temp[$k] = \preg_replace_callback("/{.*}/is", function($matches) use (&$param) {
                $param[] = trim(trim($matches[0], '}'), '{');
                return '([^/]+)';
            }, $v);
        }
        return '#^' . implode('/', $temp) . '[/]?$#';
    }

    /**
     * url 参数分析
     * @param array $parameter  形参数组列表(一维数组)
     * @param array $argument   实参数组列表(一维数组)
     * @return array 可调用的参数数组(一维链表)
     */
    private static function paramAnalysis($parameter, $argument): array {
        $arr = [];
        if (!empty($parameter)) {
            foreach ($parameter as $k => $v) {
                // 当实参不全时, 填充为 null
                $argument[$k + 1] = !isset($argument[$k + 1]) ? '' : $argument[$k + 1];
                $arr[$v] = ($argument[$k + 1] === '') ? \null : ltrim($argument[$k + 1], '/');
            }
        }
        return $arr;
    }

    /**
     * 执行分析 : 路由别名, 域名分析, 中间件注册, 执行闭包
     * 申明 obj('request');
     * 申明 self::$domainParam
     * 申明 self::$alias
     * 申明 self::$urlParam
     * 申明 self::$methods
     * @param string $rule          路由匹配段
     * @param string|array $info    路由执行段 (可能是形如 'App\index\Contr\IndexContr@indexDo' 或者 闭包, 或者 数组包含以上2钟)
     * @param array $urlParam       url参数数组
     * @param array $request
     * @return bool
     */
    private static function infoAnalysis($rule, $info, $urlParam): bool {
        // 一致化格式
        $info = self::unifiedInfo($info);
        // 别名分析
        $alias = ( isset($info['as']) && !empty($info['as']) ) ? $info['as'] : $rule;
        // 域名分析
        if (isset($info['domain'])) {
            if (!is_array($domainParam = self::domainToPreg($info['domain']))) {
                return false;
            }
        }
        // http方法分析
        if (!in_array(strtolower($_SERVER['REQUEST_METHOD']), $info['method']))
            return false;

        // 中间件
        $middleware = $info['middleware'];

        // 执行
        $contr = $info['uses'];

        // 合并 域名参数 与 路由参数
        $request = array_merge($domainParam, $urlParam);

        // 初始化 Request
        \obj(Request::class, $urlParam, $domainParam);
        self::$domainParam = $domainParam;
        self::$urlParam = $urlParam;
        self::$alias = $alias;
        self::$methods = $info['method'];

        // 框架性能
        self::statistic();

        // 核心执行,管道模式中间件,以及控制器
        self::doKernel($middleware, $contr, $request);
        
        return true;
    }

    /**
     * info 一致化格式
     * @param \Closure $info
     */
    private static function unifiedInfo($info): array {
        $arr = [];
        if (is_string($info) || $info instanceof \Closure) {
            $arr = [
                'method' => self::$allowMethod,
                'middleware' => [],
                'domain' => $_SERVER['HTTP_HOST'],
                'as' => [],
                'uses' => $info
            ];
        } elseif (is_array($info)) {
            $arr = [
                'method' => isset($info['method']) ? $info['method'] : self::$allowMethod,
                'middleware' => isset($info['middleware']) ? $info['middleware'] : [],
                'domain' => isset($info['domain']) ? $info['domain'] : $_SERVER['HTTP_HOST'],
                'as' => isset($info['as']) ? $info['as'] : [],
                'uses' => $info['uses']
            ];
        }
        return $arr;
    }

    private static function doKernel($middleware, $contr, $request): void {
        obj(\App\Kernel::class)->run($middleware, $contr, $request);
    }

    /**
     * 将域名规则翻译为正则表达式 (不支持问号参数)
     * @param string    $rule       域名规则    eg: {admin}.{gitxt}.com
     * @return array|false
     */
    private static function domainToPreg($rule = '') {
        $param = [];
        $preg = \preg_replace_callback("/{[^\.]*}/is", function($matches) use (&$param) {
            $param[trim(trim($matches[0], '}'), '{')] = null;
            return '([^\.]+)';
        }, $rule);
        $preg = '#^' . $preg . '$#';
        $key = \preg_replace_callback($preg, function($matches) use (&$param) {
            $i = 1;
            foreach ($param as $k => $v) {
                $param[$k] = $matches[$i++];
            }
            return 'true';
        }, $_SERVER['HTTP_HOST']);
        // 若匹配失败 则返回false
        if ($key !== 'true') {
            return false;
        }
        return $param;
    }

    // 运行统计
    private static function statistic(): void {
        $GLOBALS['statistic']['_initTime'] = microtime(true);
        $GLOBALS['statistic']['_initMemory'] = memory_get_usage();
    }

    /**
     * 返回当前的路由别名
     * @return string
     */
    public static function getAlias(): string {
        return self::$alias;
    }

    /**
     * 返回 域名参数
     * @return array
     */
    public static function getDomainParam(): array {
        return self::$domainParam;
    }

    /**
     * 返回 路由参数
     * @return array
     */
    public static function getUrlParam(): array {
        return self::$urlParam;
    }

    /**
     * 返回 路由参数
     * @return array
     */
    public static function getMethods(): array {
        return self::$methods;
    }
    /*     * ************************************************** 分组以及静态方法申明路由 ******************************************** */

    // 可用的 http 动作
    private static $allowMethod = [
        'get', 'post', 'put', 'delete', 'head', 'patch', 'options'
    ];
    // 分组时的信息
    private static $group = [
        'domain' => [],
        'prefix' => [],
        'namespace' => [],
        'as' => [],
        'middleware' => [],
    ];

    public static function restful($url, string $action) {
        self::post($url, $action . '@create');
        self::delete($url, $action . '@destroy');
        self::get($url, $action . '@select');
        self::put($url, $action . '@update');
    }

    public static function options($url, $action) {
        return self::match(['options'], $url, $action);
    }

    public static function post($url, $action) {
        return self::match(['post', 'options'], $url, $action);
    }

    public static function get($url, $action) {
        return self::match(['get', 'options'], $url, $action);
    }

    public static function put($url, $action) {
        return self::match(['put', 'options'], $url, $action);
    }

    public static function delete($url, $action) {
        return self::match(['delete', 'options'], $url, $action);
    }

    public static function head($url, $action) {
        return self::match(['head'], $url, $action);
    }

    public static function patch($url, $action) {
        return self::match(['patch'], $url, $action);
    }

    /**
     * 
     * @param type $url
     * @param type $action
     * @return type
     */
    public static function any($url, $action) {
        return self::match(self::$allowMethod, $url, $action);
    }

    /**
     * 处理分析每个路由以及所在组环境, 并加入 self::$routeRule
     * @param type $method
     * @param string $url
     * @param type $action
     */
    public static function match($method, $url, $action) {
        // 格式化action
        $actionInfo = self::formatAction($action);

        // 处理得到 url
        {
            if (!empty(self::$group['prefix'])) {
                $prefix = '';
                foreach (self::$group['prefix'] as $v) {
                    if (empty($v))
                        continue;
                    $prefix .= $v;
                }
                $url = $prefix . $url;
            }
        }

        // 处理得到 完整uses
        {
            if ($actionInfo['uses'] instanceof \Closure) {
                $uses = $actionInfo['uses'];
            } elseif (!empty(self::$group['namespace'])) {
                $namespace = '';
                foreach (self::$group['namespace'] as $v) {
                    if (empty($v))
                        continue;
                    $namespace .= trim(str_replace('/', '\\', $v), '\\') . '\\';
                }
                $uses = $namespace . $actionInfo['uses'];
            } else {
                $namespace = trim(str_replace('/', '\\', $actionInfo['namespace']), '\\') . '\\';
                $uses = $namespace . $actionInfo['uses'];
            }
        }

        // 处理得到 完整 as 别名
        {
            $prefix = '';
            if (!empty(self::$group['as'])) {
                foreach (self::$group['as'] as $v) {
                    if (empty($v))
                        continue;
                    $prefix .= $v;
                }
            }
            $as = $prefix . $actionInfo['as'];
        }

        // 处理得到 最终 domain 
        {
            $domain = $_SERVER['HTTP_HOST'];
            if (!empty($actionInfo['domain'])) {
                $domain = $actionInfo['domain'];
            } elseif (!empty(self::$group['domain'])) {
                foreach (self::$group['domain'] as $v) {
                    if (!empty($v))
                        $domain = $v;
                }
            }
        }

        // 处理得到 完整 middleware 
        {
            $middleware = [];
            if (!empty(self::$group['middleware'])) {
                foreach (self::$group['middleware'] as $v) {
                    if (empty($v))
                        continue;
                    $middleware = array_merge($middleware, $v);
                }
            }
            $middleware = array_merge($middleware, $actionInfo['middleware']);
        }
        self::$routeRule[] = [
            $url => [
                'method' => $method,
                'middleware' => $middleware,
                'domain' => $domain,
                'as' => $as,
                'uses' => $uses
            ]
        ];
    }

    /**
     * 路由分组, 支持无线级嵌套
     * @param type $rule
     * @param \Closure $callback
     */
    public static function group($rule, \Closure $callback) {
        // 当前 group 分组信息填充
        self::$group['middleware'][] = isset($rule['middleware']) ? $rule['middleware'] : [];
        self::$group['namespace'][] = isset($rule['namespace']) ? $rule['namespace'] : '';
        self::$group['prefix'][] = isset($rule['prefix']) ? $rule['prefix'] : '';
        self::$group['as'][] = isset($rule['as']) ? $rule['as'] : '';
        self::$group['domain'][] = isset($rule['domain']) ? $rule['domain'] : '';

        // 执行闭包
        $callback();

        // 执行完当前 group 后 移除当前分组信息
        foreach (self::$group as $k => $v) {
            array_pop(self::$group[$k]);
        }
    }

    /**
     * 格式化 action 参数
     * @param type $action
     */
    private static function formatAction($action) {
        $actionInfo = [];
        if (is_array($action)) {
            if ($action['uses'] instanceof \Closure) {
                $actionInfo['middleware'] = isset($action['middleware']) ? $action['middleware'] : [];
                $actionInfo['namespace'] = '';
                $actionInfo['prefix'] = '';
                $actionInfo['as'] = isset($action['as']) ? $action['as'] : '';
                $actionInfo['domain'] = isset($action['domain']) ? $action['domain'] : '';
                $actionInfo['uses'] = $action['uses'];
            } elseif (is_string($action['uses'])) {
                $actionInfo['middleware'] = isset($action['middleware']) ? $action['middleware'] : [];
                $actionInfo['namespace'] = isset($action['namespace']) ? $action['namespace'] : '';
                $actionInfo['prefix'] = isset($action['prefix']) ? $action['prefix'] : '';
                $actionInfo['as'] = isset($action['as']) ? $action['as'] : '';
                $actionInfo['domain'] = isset($action['domain']) ? $action['domain'] : '';
                $actionInfo['uses'] = trim(str_replace('/', '\\', $action['uses']), '\\');
            }
        } elseif ($action instanceof \Closure) {
            $actionInfo['middleware'] = [];
            $actionInfo['namespace'] = '';
            $actionInfo['prefix'] = '';
            $actionInfo['as'] = '';
            $actionInfo['domain'] = '';
            $actionInfo['uses'] = $action;
        } elseif (is_string($action)) {
            $actionInfo['middleware'] = [];
            $actionInfo['namespace'] = '';
            $actionInfo['prefix'] = '';
            $actionInfo['as'] = '';
            $actionInfo['domain'] = '';
            $actionInfo['uses'] = trim(str_replace('/', '\\', $action), '\\');
        }
        return $actionInfo;
    }
}
