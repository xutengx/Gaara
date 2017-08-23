<?php
namespace Main\Core;
use \Main\Core\Session\Driver;
/**
 * Class Session 数据库session存储
 * @package Main\Core
 */
class Session {

    /**
     * @var bool 是否手动
     */
    private $Manual = false;

    final public function __construct($Manual = false){
        $conf = obj(Conf::class)->session;

        $driver = ( isset($conf['driver']) && !is_null($conf['driver']) ) ? $conf['driver'] : 'file';
        $httponly = ( isset($conf['httponly']) && !is_null($conf['httponly']) ) ? $conf['httponly'] : true;
        $lifetime = ( isset($conf['lifetime']) && !is_null($conf['lifetime']) ) ? $conf['lifetime'] : 600000;
        $autostart = ( isset($conf['autostart']) && !is_null($conf['autostart']) ) ? $conf['autostart'] : true;
  
        ini_set('session.cookie_httponly', $httponly);
        ini_set('session.cookie_lifetime', $lifetime);
        ini_set('session.gc_maxlifetime', $lifetime);

        if ($driver === 'redis')
            $this->Drivers['redis'] = new Driver\Redis($conf[$driver]);
        elseif ($driver === 'file')
            $this->Drivers['file'] = new Driver\File($conf[$driver]);
        elseif ($driver === 'mysql')
            $this->Drivers['mysql'] = new Driver\Mysql($conf[$driver]);
        
        // 重写后 未完成
        $this->Manual = $Manual;
        
        if($autostart) 
            session_start();
    }
}