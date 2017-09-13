<?php

declare(strict_types = 1);
namespace Main\Core\Request\Component;
defined('IN_SYS') || exit('ACC Denied');

use \Main\Core\Tool;

/**
 * 每个上传的文件对象
 */
class File {

    // type of img
    private static $imgType = [
        'image/gif' => 'gif',
        'image/jpeg' => 'jpg',
        'image/pjpeg' => 'jpg',
        'image/png' => 'png',
        'image/x-png' => 'png',
        'image/bmp' => 'bmp',
        'image/x-icon' => 'ico',
    ];
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
    // 默认文件保存的路径
    private static $default_save_path = 'data/upload/';
    // 文件保存的路径
    public $saveFilename = '';
    // 文件保存的路径是否为绝对路径
    private $absolute = false;
    // 文件是否保存成功
    public $is_save = false;

    /**
     * 获取本对象的内容
     */
    private function getContent(): string {
        if (!is_null($this->tmp_name)) {
            return file_get_contents($this->tmp_name);
        } else {
            return $this->content;
        }
    }

    /**
     * 由上传的文件名通过字符串截取, 获得文件后缀
     * @return string eg:png
     */
    public function getExt(): string {
        return substr(strrchr($this->name, '.'), 1);
    }

    /**
     * 当前文件是否是图像
     * 检测 type 于 $imgType中, 且文件名后缀 于 $imgType 中
     * @return bool
     */
    public function is_img(): bool {
        return array_key_exists($this->type, self::$imgType) && in_array($this->getExt(), self::$imgType);
    }

    /**
     * 当前文件是否过小
     * @return bool
     */
    public function is_greater(int $size = 2): bool {
        return ($this->size > $size);
    }

    /**
     * 当前文件是否过大 8388608 8M
     * @return bool
     */
    public function is_less(int $size = 8388608): bool {
        return ($this->size < $size);
    }

    /**
     * 设置路径
     * @param string $dir   路径
     * @param bool $absolute   是否绝对路径(相对路径,相对ROOT (init.php), 而非 index.php)
     * @return File 
     */
    public function makeFilename(string $dir = '', bool $absolute = false): File {
        $this->saveFilename = obj(Tool::class)->makeFilename($dir, $this->getExt(), $this->key_name);
        $this->absolute = $absolute;
        return $this;
    }

    /**
     * 保存上传的文件, 优先使用 \move_uploaded_file
     * @return bool
     */
    public function move_uploaded_file(): bool {
        if ($this->saveFilename === '')
            $this->makeFilename(self::$default_save_path . date('Ym/d/'));
        $newFileNameWithDir = $this->absolute ? $this->saveFilename : ROOT . $this->saveFilename;
        if (!is_dir(dirname($newFileNameWithDir)))
            obj(Tool::class)->__mkdir(dirname($newFileNameWithDir));
        if (!is_null($this->tmp_name)) {
            return $this->is_save = \move_uploaded_file($this->tmp_name, $newFileNameWithDir);
        } else {
            return $this->is_save = obj(Tool::class)->printInFile($newFileNameWithDir, $this->getContent());
        }
    }

    /**
     * 根据 $this->save_path 删除保存的文件,一般情况下在数据库回滚时调用
     * @return bool
     */
    public function clean(): bool {
        if ($this->is_save === true)
            return $this->absolute ? unlink($this->saveFilename) : unlink(ROOT . $this->saveFilename);
        else
            return false;
    }

    public function __set(string $attr, string $value) {
        if ($attr === 'content') {
            return $this->content = $value;
        }
    }

    public function __get($attr) {
        if ($attr === 'content') {
            return $this->getContent();
        }
    }
}
