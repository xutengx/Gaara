<?php

declare(strict_types = 1);
namespace Gaara\Core;

$gaara = new Container;

$gaara->singleton(Conf::class);
$gaara->singleton(Cache::class);
$gaara->singleton(DbConnection::class);
$gaara->singleton(Kernel::class, \App\Kernel::class);
$gaara->singleton(Log::class);
$gaara->singleton(Request::class);
$gaara->singleton(Response::class);
$gaara->singleton(Route::class);
$gaara->singleton(Secure::class);
$gaara->singleton(Session::class);
$gaara->singleton(Template::class);
$gaara->singleton(Tool::class);

$app = $gaara->make(Kernel::class);

return $app;
