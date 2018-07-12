<?php

declare(strict_types = 1);
namespace App\Dev\download\Contr;

use Gaara\Core\Controller;
use App\Dev\download\Model\VisitorInfo;
use Iterator;
use Generator;
use Gaara\Core\Response;

class index extends Controller {

	public function index(VisitorInfo $model, Response $Response) {

		return $this->downloadfile();

//        $data = $model->limit(14000)->getChunk();
//        $data = $model->limit(14000)->getAll();
		return $Response->file()->exportCsv($data);
	}

	private function download($data): Generator {
		foreach ($data as $v) {
			yield '"\'' . $v['id'] . '","\'' . $v['name'] . '","' . $v['phone'] . '","\'' . $v['scene'] . '","' . $v['test'] . '","' . $v['note'] . '","' . $v['created_at'] . '","' . $v['updated_at'] . '","' . $v['is_del'] . '"' . "\n";
		}
	}

	private function downloadfile() {
		$file = './data/upload/201711/01/Downloads.zip';

		return \Response::file()->download($file);
	}

}
