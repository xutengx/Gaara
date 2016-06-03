<?php
namespace Main\Core;
/**
 * Class Session 数据库session存储
 * @package Main\Core
 */
class Session {

    /**
     * Session有效时间
     */
    private $lifeTime      = 3600*24*7;

    /**
     * session保存的数据库名
     */
    private $sessionTable ;

    /**
     * 数据库句柄
     */
    private $db ;

    final public function __construct(){
        ini_set('session.cookie_lifetime', SESSIONLIFE);
        ini_set('session.gc_maxlifetime', SESSIONLIFE);
        switch(SESSIONMODULENAME){
            case 'user':
                $this->db = obj('mysql');
                $this->sessionTable = $this->db->tablepre.'session';
//                $this->lifeTime = SESSIONLIFE;
                if(DEBUG)
                    $this->checkSessionDb();
                session_module_name('user');
                session_set_save_handler(
                    array(&$this, 'open'),
                    array(&$this, 'close'),
                    array(&$this, 'read'),
                    array(&$this, 'write'),
                    array(&$this, 'destroy'),
                    array(&$this, 'gc')
                );
                break;
            default:
                ini_set('session.save_path',SESSIONPATH);
                if(!is_dir(SESSIONPATH)) obj('tool')->__mkdir(SESSIONPATH);
                break;

        }
    }
    private function checkSessionDb(){
        $sql = 'show tables like "'.$this->sessionTable.'"';
        if($this->db->query($sql)->num_rows) ;
        else{
            $createDb = <<<EEE
CREATE TABLE {$this->sessionTable} (
session_id varchar(255) NOT NULL,
session_expire int(11) NOT NULL,
session_data blob,
UNIQUE KEY `session_id` (`session_id`)
)ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
EEE;
            $this->db->query($createDb);
            return true;
        }

    }
    /**
     * 打开Session
     * @access public
     * @param string $savePath
     * @param mixed $sessName
     */
    public function open($savePath, $sessName) {
        $this->lifeTime = ini_get('session.gc_maxlifetime');
        return true;
    }

    /**
     * 关闭Session
     * @access public
     */
    public function close() {
        return $this->gc($this->lifeTime);
    }

    /**
     * 读取Session
     * @access public
     * @param string $sessID
     */
    public function read($sessID) {
        $sql = "SELECT session_data AS data FROM ".$this->sessionTable." WHERE session_id = '$sessID' AND session_expire >".time();
        $re = $this->db->getRow($sql);
        return isset($re['data']) ? $re['data'] : '';
    }

    /**
     * 写入Session
     * @access public
     * @param string $sessID
     * @param String $sessData
     */
    public function write($sessID,$sessData) {
        $expire = time() + $this->lifeTime;
        $sql = "REPLACE INTO ".$this->sessionTable." (  session_id, session_expire, session_data)  VALUES( '$sessID', '$expire',  '$sessData')";
        return $this->db->execute($sql) ? true : false;
    }

    /**
     * 删除Session
     * @access public
     * @param string $sessID
     */
    public function destroy($sessID) {
        $sql = "DELETE FROM ".$this->sessionTable." WHERE session_id = '$sessID'";
        return $this->db->execute($sql) ? true : false;
    }

    /**
     * Session 垃圾回收
     * @access public
     * @param string $sessMaxLifeTime
     */
    public function gc($sessMaxLifeTime) {
        $sql = "DELETE FROM ".$this->sessionTable." WHERE session_expire < ".time();
        return $this->db->execute($sql) ? true : false;
    }

}