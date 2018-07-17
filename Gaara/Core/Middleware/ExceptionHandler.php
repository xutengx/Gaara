<?php

declare(strict_types = 1);
namespace Gaara\Core\Middleware;

use Gaara\Core\{
	Middleware, Request, Response, Log
};
use Gaara\Exception\{
	MessageException, HttpException
};
use Whoops\Run;
use Whoops\Handler\{
	PlainTextHandler, JsonResponseHandler, PrettyPageHandler
};

/**
 * 异常处理
 */
class ExceptionHandler extends Middleware {

	protected $except = [];

	public function handle(Request $request) {
		$debug	 = app()->debug;
		$cli	 = app()->cli;
		$whoops	 = new Run;
		if ($debug) {
			if ($cli)
				$whoops->pushHandler(new PlainTextHandler);
			elseif ($request->isAjax)
				$whoops->pushHandler(new PrettyPageHandler);
			else
				$whoops->pushHandler(new PrettyPageHandler);
		}else {
			if ($cli)
				$whoops->pushHandler(new PlainTextHandler);
			elseif ($request->isAjax)
				$whoops->pushHandler(new JsonResponseHandler);
			else {
				$whoops->pushHandler(function($exception, $inspector, $run) {
					obj(Response::class)->setStatus(500)->view('500')->sendExit();
				});
			}
		}
		// 优先级高
		$whoops->pushHandler(function($exception, $inspector, $run) {
			// 记录异常
			obj(Log::class)->error($inspector->getException());

			if ($exception instanceof MessageException || $exception instanceof HttpException) {
				$msg	 = $exception->getMessage();
				$code	 = $exception->getCode();
				obj(Response::class)->fail($msg, $code)->sendExit();
			}
		});
		$whoops->register();
	}

}
