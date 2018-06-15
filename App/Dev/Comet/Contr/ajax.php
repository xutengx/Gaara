<?php

namespace App\Dev\Comet\Contr;

use Gaara\Core\{
	Controller, Cache, Request, Controller\Traits\CometTrait
};

class ajax extends Controller {

	use CometTrait;

	protected $view	 = 'App/Dev/Comet/View/';
	protected $title = 'ajax 长轮询 !';

	public function index() {
		$this->assignPhp('title', $this->title);
		return $this->display();
	}

	public function ajaxdo(Cache $Cache) {
		return $this->ajaxComet(function() use ($Cache) {
			if ($value = $Cache->rpop('ajax')) {
				return $value;
			}
		}, 10);
	}

}
