<?php

declare(strict_types = 1);
namespace App\Dev\download\Contr;

use Gaara\Core\Controller;
use App\Dev\download\Model\VisitorInfo;
use Iterator;
use Generator;
use Gaara\Core\Response;

class index extends Controller{

    public function index(VisitorInfo $model, Response $Response){

		return $this->downloadfile();

        $data = $model->limit(14000)->getChunk();
//        $data = $model->limit(14000)->getAll();
//		return $Response->setContent($data)->send();

		return $Response->file()->exportCsv($data);


//		return $Response->file()->downloadGenerator($data);


//        $Response->setHeaders([
//            'Content-Type' => $mimetype,
//            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
//        ]);
//
//        echo '"time","t_id","provider","price","type","currency"' . "\n";
//
//        return $this->download($data);
    }

    private function download($data): Generator{
        foreach($data as $v){
            yield '"\''.$v['id'].'","\''.$v['name'].'","'.$v['phone'].'","\''.$v['scene'].'","'.$v['test'].'","'.$v['note'].'","'.$v['created_at'].'","'.$v['updated_at'].'","'.$v['is_del'].'"'."\n";
        }
    }

	private function downloadfile(){
		$file = './data/upload/201711/01/Downloads.zip';
		$file = obj(\Tool::class)->absoluteDir($file);

		return \Response::file()->download($file);
//		obj(\Tool::class)->download('./data/upload/201711/01/', 'Downloads.zip', 'test.zip');


	}

}
