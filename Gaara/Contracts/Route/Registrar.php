<?php

namespace Gaara\Contracts\Route;

use Closure;

interface Registrar {

	/**
	 * Register a new GET route with the router.
	 *
	 * @param  string  $uri
	 * @param  \Closure|array|string  $action
	 * @return void
	 */
	public function get(string $uri, $action): void;

	/**
	 * Register a new POST route with the router.
	 *
	 * @param  string  $uri
	 * @param  \Closure|array|string  $action
	 * @return void
	 */
	public function post(string $uri, $action): void;

	/**
	 * Register a new PUT route with the router.
	 *
	 * @param  string  $uri
	 * @param  \Closure|array|string  $action
	 * @return void
	 */
	public function put(string $uri, $action): void;

	/**
	 * Register a new DELETE route with the router.
	 *
	 * @param  string  $uri
	 * @param  \Closure|array|string  $action
	 * @return void
	 */
	public function delete(string $uri, $action): void;

	/**
	 * Register a new PATCH route with the router.
	 *
	 * @param  string  $uri
	 * @param  \Closure|array|string  $action
	 * @return void
	 */
	public function patch(string $uri, $action): void;

	/**
	 * Register a new OPTIONS route with the router.
	 *
	 * @param  string  $uri
	 * @param  \Closure|array|string  $action
	 * @return void
	 */
	public function options(string $uri, $action): void;

	/**
	 * Register a new route with the given verbs.
	 *
	 * @param array $methods
	 * @param string $uri
	 * @param \Closure|array|string  $action
	 * @return void
	 */
	public function match(array $methods, string $uri, $action): void;

	/**
	 * Create a route group with shared attributes.
	 *
	 * @param  array  $attributes
	 * @param  \Closure $routes
	 * @return void
	 */
	public function group(array $attributes, Closure $routes): void;
}
