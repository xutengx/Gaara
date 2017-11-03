<?php

declare(strict_types = 1);
namespace App\Dev\download\Contr;

use Gaara\Core\Controller\HttpController;
use App\Dev\download\Model\Best;
use Iterator;
use Generator;

/*
 * 数据库开发测试类
 */

class index extends HttpController{
    
    public function index(Best $Best){
        $data = $Best->limit(4000)->getChunk();
        
        $filename = 'TransactionDaily_.csv';
        $mimetype = 'mime/type';
        header('Content-Type: ' . $mimetype);
        header('Content-Disposition: attachment; filename="' . $filename . '"');  //下载后的新文件名
        echo '"time","t_id","provider","price","type","currency"' . "\n";
        
        return $this->download($data);
    }
    
    private function download(Iterator $data): Generator{
        foreach($data as $v){
            yield '"\''.$v['create_time'].'","\''.$v['transactionid'].'","'.$v['operator_id'].'","\''.$v['price'].'","'.$v['actiontype'].'","'.$v['currency'].'"'."\n";         
        }
    }
}