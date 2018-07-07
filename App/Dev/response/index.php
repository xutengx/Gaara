<?php

namespace App\Dev\response;

use Gaara\Core\Controller;
use Cache;
use Gaara\Core\Secure;
use Response;
use Request;

class index extends Controller {

	public function indexDo(){

//		var_dump(obj(Request::class));exit;

//		throw new \Gaara\Core\Exception\Http\NotFoundHttpException();
//		Response::body()->setContent(['data' => 'body content']);

//		return $this->fail('errorrrrrrrrrrrr', 416);
		return $this->success(['qweqwe','qweqwe'], 'bbb');

//return obj(Response::class);
		Response::send();
		exit;
	}

}
