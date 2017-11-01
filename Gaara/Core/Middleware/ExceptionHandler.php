<?php

declare(strict_types = 1);
namespace Gaara\Core\Middleware;

use Response;
use Gaara\Core\Middleware;
use Gaara\Core\Request;
use Whoops\Run;
use Whoops\Handler\PlainTextHandler;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PrettyPageHandler;
use Log;

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
            elseif ($request->isAjax())
                $whoops->pushHandler(new PrettyPageHandler);
            else
                $whoops->pushHandler(new PrettyPageHandler);
        }else {
            if (CLI)
                $whoops->pushHandler(new PlainTextHandler);
            elseif ($request->isAjax())
                $whoops->pushHandler(new JsonResponseHandler);
            else {
                $whoops->pushHandler(function($exception, $inspector, $run) {
                    exit(Response::setStatus(500)->view('500'));
                });
            }
        }
        $whoops->pushHandler(function($exception, $inspector, $run) {
            Log::error($inspector->getExceptionMessage(), $exception->getTrace());
        });
        $whoops->register();
    }
}
