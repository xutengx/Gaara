<?php

namespace App\Dev\response;

use Gaara\Core\Controller;
use Cache;
use Gaara\Core\Secure;
use Response;
use Request;

class index extends Controller {

	public function indexDo(){

		echo 'qwewqe1';

//		\Response::setContent('test111  ')->sendReal();
		\Response::setContent('test222   ')->send();
//		\Response::setContent('test333  ')->sendReal();

		echo 'qwewqe2';
//		var_dump(headers_list());
//		var_dump(headers_sent($file, $line));
//		var_dump($file);
//		var_dump($line);
//		exit;
//		var_dump(\Response::header()->getSent());exit;
//		var_dump(obj(Request::class));exit;
//		throw new \Gaara\Core\Exception\MessageException("'msg', 'error'");
//		throw new \Exception('error ');
//		throw new \Gaara\Core\Exception\Http\NotFoundHttpException();
//		Response::body()->setContent(['data' => 'body content']);

//		return $this->fail('errorrrrrrrrrrrr', 416);
		return $this->success(['email' => 'newuser@163.com']);

//return obj(Response::class);
		Response::send();
		exit;
	}

}
