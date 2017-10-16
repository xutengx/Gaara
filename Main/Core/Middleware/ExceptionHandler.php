<?php

declare(strict_types = 1);
namespace Main\Core\Middleware;

use Response;
use Main\Core\Middleware;
use Main\Core\Request;
use Whoops\Run;
use Whoops\Handler\PlainTextHandler;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PrettyPageHandler;

/**
 * 异常处理
 */
class ExceptionHandler extends Middleware {

    protected $except = [];

    public function handle(Request $request) {
        $whoops = new Run;
        if (DEBUG) {
            if (CLI)
                $whoops->pushHandler(new PlainTextHandler);
            elseif ($request->isAjax)
                $whoops->pushHandler(new PrettyPageHandler);
            else
                $whoops->pushHandler(new PrettyPageHandler);
        }else{
            if (CLI)
                $whoops->pushHandler(new PlainTextHandler);
            elseif ($request->isAjax)
                $whoops->pushHandler(new JsonResponseHandler);
            else{
                exit(Response::setStatus(500)->view('500'));
            }
                
        }
        $whoops->register();
    }
}
