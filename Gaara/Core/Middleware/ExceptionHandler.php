<?php

declare(strict_types = 1);
namespace Gaara\Core\Middleware;

use Log;
use Response;
use Gaara\Core\{
	Middleware, Request, Exception\MessageException
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
					$level = ob_get_level();
					for ($i = 0; $i <= $level; $i++) {
						ob_end_clean();
					}
					exit(Response::setStatus(500)->view('500'));
				});
			}
		}
		$whoops->pushHandler(function($exception, $inspector, $run) {
			// 记录异常
			Log::error($inspector->getException());

			if ($exception instanceof MessageException) {
				$msg = $exception->getMessage();
				Response::setStatus(500)->exitData([
					'code'	 => '500',
					'msg'	 => $msg
				]);
			}
		});
		$whoops->register();
	}

}
