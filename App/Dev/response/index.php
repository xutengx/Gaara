<?php

namespace App\Dev\response;

use Gaara\Core\Controller;
use Cache;
use Gaara\Core\Secure;
use Response;
use Request;

class index extends Controller {

	public function indexDo(\Gaara\Core\Request $request){

		var_dump($request->userIp);exit;

//		app()->bind('redis_2', \Gaara\Core\Cache::class, true);
//
//		$reids_2 = app()->make('redis_2');
//
		\Log::info('test', ['this is response']);
		$reids = app()->make(\Gaara\Core\Cache::class);

		app()->bindOnce(\Gaara\Core\Cache\Driver\Redis::class, function(){
			return new \Gaara\Core\Cache\Driver\Redis('con2');
		});

//		$Redis_2 = app()->make(\Gaara\Core\Cache\Driver\Redis::class,['conn' => 'con2']);
//		var_dump($Redis_2);exit;

		app()->bind('Cache_2', \Gaara\Core\Cache::class);

//exit('qqqaqq');
		$Cache_2 = app()->make('Cache_2');
		$Cache_3 = app()->make('Cache_2');
		$reids_3 = app()->make(\Gaara\Core\Cache::class);

		var_dump($reids);
		var_dump($reids_3);
		var_dump($Cache_2);
		var_dump($Cache_3);exit;


		$redis = app()->make(\Gaara\Core\Cache\Driver\Redis::class, [
			'connection' => 'default'
		]);

		var_dump($redis);exit;
		var_dump($reids_2 === $reids);
		var_dump($reids_3 === $reids);exit;


//		var_dump(ob_get_level());exit;
		echo 'qwewqe1';

//		echo  str_repeat(" q ",410112);
//		return \Response::setContent(str_repeat("t ",4096));

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
//		throw new \Gaara\Exception\MessageException("'msg', 'error'");
//		throw new \Exception('error ');
//		throw new \Gaara\Exception\Http\NotFoundHttpException();
//		Response::body()->setContent(['data' => 'body content']);

//		return $this->fail('errorrrrrrrrrrrr', 416);
		return $this->success(['email' => 'newuser@163.com']);

//return obj(Response::class);
		Response::send();
		exit;
	}

}
