<?php

declare(strict_types = 1);
namespace Gaara\Core;

use App\Kernel;
use Closure;
use Generator;
use Gaara\Core\Route\Traits;

/**
 * 显式自定义路由
 */
class Route {
    // 分组以及静态方法申明路由
    use Traits\SetRoute;
    
    // 当前 $pathInfo
    private static $pathInfo = null;
    // 全部路由规则
    protected static $routeRule = [];

    public static function Start(): void {
        // 得到 $pathInfo
        self::$pathInfo = self::getPathInfo();
        // 引入route规则
        self::$routeRule = self::getRouteRule();
        // 分析路由, 并执行
        self::routeAnalysis();
    }

    /**
     * 分析url,得到pathinfo  
     * eg:http://192.168.64.128/git/php_/project/user/login/123/11?id=12 -> /user/login/123/11
     * eg:http://git.gitxt.com/data/upload?id=123 -> /data/upload
     * @return string
     */
    private static function getPathInfo(): string {
        return $_SERVER['path_info'] ?? '/' . \str_replace('?' . $_SERVER['QUERY_STRING'], '', \substr_replace($_SERVER['REQUEST_URI'], '', 0, strlen(\str_replace(\IN_SYS, '', $_SERVER['SCRIPT_NAME']))));
    }

    /**
     * 得到当前应该使用的route配置
     * 可以接收直接返回的数组格式, 也可以直接执行
     * @return array
     */
    private static function getRouteRule(): array {
        $file = obj(Conf::class)->route['file'];
        $fileRule = require(ROUTE . $file);
        return is_array($fileRule) ? array_merge(self::$routeRule, $fileRule) : self::$routeRule;
    }

    /**
     * 路由分析, 包含最终执行
     * 路由匹配失败, 则响应404
     * @return void
     */
    private static function routeAnalysis(): void {
        foreach (self::pretreatment() as $rule => $info) {
            // 形参数组
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
            }
        }
        obj(Response::class)->setStatus(404)->exitData('Not Found ..');
    }
    
    /**
     * 预处理路由数组信息
     * @return Generator
     */
    private static function pretreatment(): Generator {
        foreach (self::$routeRule as $rule => $info) {
            // 兼容式路由
            if (is_int($rule)) {
                if (is_null($info)) {
                    continue;
                }
                $rule = key($info);
                $info = reset($info);
            }
            yield $rule => $info;
        }
    }

    /**
     * 将路由规则翻译为正则表达式
     * @param string $rule url规则
     * @param array &$param url上的形参组成的一维数组
     * @return string 正则表达式
     * @return array $param 形参数组
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
     * @param array $parameter 形参数组列表(一维数组)
     * @param array $argument 实参数组列表(一维数组)
     * @return array 可调用的参数数组(一维链表)
     */
    private static function paramAnalysis(array $parameter, array $argument): array {
        $arr = [];
        foreach ($parameter as $k => $v) {
            // 当实参不全时, 填充为 null
            $argument[$k + 1] = !isset($argument[$k + 1]) ? '' : $argument[$k + 1];
            $arr[$v] = ($argument[$k + 1] === '') ? \null : ltrim($argument[$k + 1], '/');
        }
        return $arr;
    }

    /**
     * 执行分析 : 路由别名, 域名分析, 中间件注册, 执行闭包
     * 申明 obj('request');
     * @param string $rule 路由匹配段
     * @param string|array $info 路由执行段 (可能是形如 'App\index\Contr\IndexContr@indexDo' 或者 闭包, 或者 数组包含以上2钟)
     * @param array $urlParam url参数数组
     * @return bool
     */
    private static function infoAnalysis(string $rule, $info, array $urlParam = []): bool {
        // 一致化格式
        $info = self::unifiedInfo($info);

        // 别名分析
        $alias = $info['as'] ?? $rule;
        // 域名分析
        if (!is_array($domainParam = self::domainToPreg($info['domain']))) {
            return false;
        }
        // http方法分析
        if (!in_array(strtolower($_SERVER['REQUEST_METHOD']), $info['method'], true))
            return false;

        // 中间件
        $middleware = $info['middleware'];

        // 执行
        $contr = $info['uses'];

        // 合并 域名参数 与 路由参数
        $wholeParam = array_merge($domainParam, $urlParam);

        // 初始化 Request
        $request = obj(Request::class, $urlParam, $domainParam);
        $request->alias = $alias;
        $request->methods = $info['method'];
        
        // 核心执行,管道模式中间件,以及控制器
        self::doKernel($middleware, $contr, $wholeParam);

        return true;
    }

    /**
     * 执行中间件, 控制器
     * @param array $middleware
     * @param string|callback|array $contr
     * @param array $wholeParam
     * @return void
     */
    private static function doKernel(array $middleware, $contr, array $wholeParam): void {
        obj(Kernel::class)->run($middleware, $contr, $wholeParam);
    }
    
    /**
     * info 一致化格式
     * @param \Closure $info
     * @return void
     */
    private static function unifiedInfo($info): array {
        $arr = [];
        if (is_string($info) || $info instanceof Closure) {
            $arr = [
                'method' => self::$allowMethod,
                'middleware' => [],
                'domain' => $_SERVER['HTTP_HOST'],
                'as' => null,
                'uses' => $info
            ];
        } elseif (is_array($info)) {
            $arr = [
                'method' => $info['method'] ?? self::$allowMethod,
                'middleware' => $info['middleware'] ?? [],
                'domain' => $info['domain'] ?? $_SERVER['HTTP_HOST'],
                'as' => $info['as'] ?? null,
                'uses' => $info['uses']
            ];
        }
        return $arr;
    }
    /**
     * 将域名规则翻译为正则表达式 (不支持问号参数)
     * @param string $rule 域名规则 eg: {admin}.{gitxt}.com
     * @return array|false
     */
    private static function domainToPreg(string $rule = '') {
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
}
