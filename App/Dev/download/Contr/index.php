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

//		$this->downloadfile();

//		var_dump(ob_get_level());exit;

        $data = $model->limit(14000)->getChunk();
//        $data = $model->limit(14000)->getAll();
//		return $Response->setContent($data);
//		var_dump($data);exit;
//return;

        $filename = 'TransactionDaily_.csv';
        $mimetype = 'mime/type';
//
//		$Response->header()->set('Content-Type',  $mimetype);
//		$Response->header()->set('Content-Disposition', 'attachment; filename="' . $filename . '"');
//
		$Response->setContent('"id","name","phone","scene","test","note","created_at","updated_at","is_del"' . "\n")->send();
//
		$data  = $this->download($data);

//		var_dump($Response->header()->get());
//		var_dump($Response->header()->getSent());exit;

		return $Response->file()->downloadGenerator($data);


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

//	private function downloadfile(){
//		$file = './data/upload/201711/01/Downloads.zip';
//		$file = obj(\Tool::class)->absoluteDir($file);
//
//		obj(\Tool::class)->download('./data/upload/201711/01/', 'Downloads.zip', 'test.zip');
//
//
//	}

}
