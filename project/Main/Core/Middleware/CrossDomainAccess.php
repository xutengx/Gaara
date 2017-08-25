<?php

declare(strict_types = 1);
namespace Main\Core\Middleware;
defined('IN_SYS') || exit('ACC Denied');

use Main\Core\Middleware;
use Main\Core\Request;
use Main\Core\Response;

/**
 * 允许跨域访问
 * 此中间件, 应该在其他中间件之前执行, 
 */
class CrossDomainAccess extends Middleware {

    public function handle(Request $request, Response $response): void {
        $headers['Access-Control-Allow-Origin'] = '*';
//        $headers['Access-Control-Allow-Headers'] = 'X-Requested-With,scrftoken';
        $headers['Access-Control-Allow-Headers'] = '*';
        $headers['Access-Control-Allow-Methods'] = $this->allowMothods();
        $response->setHeaders($headers);
        if ($request->method === 'options') {
            $response->returnData();
        }
    }

    private function allowMothods(): string {
        return strtoupper(implode(',', \Route::getMethods()));
    }
}
