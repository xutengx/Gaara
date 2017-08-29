<?php

declare(strict_types = 1);
namespace Main\Core\Request\Component;
defined('IN_SYS') || exit('ACC Denied');

use \Main\Core\Tool;
/**
 * 每个上传的文件对象
 */
class File {

    // 上传的文件名
    public $name;
    
    // 文件类型
    public $type;
    
    // 文件大小
    public $size;
    
    // 提交过来的键名
    public $key_name;
    
    // $_FILES 中的临时文件名 (post 上传文件时, 由php生成, 其他方式则没有此项)
    public $tmp_name;
    
    // 文件的内容 (非post 上传文件时, 由框架获取, post方式则没有此项)
    private $content;
   
    /**
     * 获取本对象的内容
     */
    private function getContent() : string{
        if(!is_null($this->tmp_name)){
            return file_get_contents($this->tmp_name);
        }else{
            return $this->content;
        }
    }
    
    /**
     * 保存上传的文件, 优先使用 \move_uploaded_file
     * @param string $newFileNameWithDir    新的文件路径(包含名称后缀)
     * @return bool
     */
    public function move_uploaded_file(string $newFileNameWithDir) : bool{
        if(!is_null($this->tmp_name)){
            return \move_uploaded_file($newFileNameWithDir);
        }else{
            obj(Tool::class)->printInFile($newFileNameWithDir, $this->getContent());
        }
    }
    
    /**
     * 保存上传的文件, 由内存写入
     * @param string $newFileNameWithDir    新的文件路径(包含名称后缀)
     * @return bool
     */
    public function save(string $newFileNameWithDir) : bool{
        obj(Tool::class)->printInFile($newFileNameWithDir, $this->getContent());
    }
    
    
    public function __set(string $attr, string $value){
        if($attr === 'content'){
            return $this->content = $value;
        }
    }
    
    public function __get($attr) {
        if($attr === 'content'){
            return $this->getContent();
        }
    }

}
