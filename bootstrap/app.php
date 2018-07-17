<?php

declare(strict_types = 1);
namespace Gaara\Core {

	$app = \App\Kernel::getInstance();

	$app->singleton(Conf::class);
	$app->singleton(Cache::class);
	$app->singleton(DbConnection::class);
	$app->singleton(Log::class);
	$app->singleton(Pipeline::class);
	$app->singleton(Request::class);
	$app->singleton(Response::class);
	$app->singleton(Route::class);
	$app->singleton(Secure::class);
	$app->singleton(Session::class);
	$app->singleton(Template::class);
	$app->singleton(Tool::class);
}
namespace Gaara\Expand {
	$app->singleton(Image::class);
	$app->singleton(JavaScriptPacker::class);
	$app->singleton(Mail::class);
	$app->singleton(PhpConsole::class);
	$app->singleton(QRCode::class);
}

namespace {
	return $app;
}
