<?php

declare(strict_types = 1);
namespace Gaara\Core\Middleware;

use Gaara\Core\{Middleware, Session};

/**
 * 开启 session
 */
class StartSession extends Middleware {

	protected $except = [];

	public function handle(Session $Session) { }

}
