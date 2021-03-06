<?php

declare(strict_types = 1);
namespace Gaara\Core\Session\Driver;

class Mysql {

	/**
	 * session保存的数据库名
	 */
	protected $sessionTable;

	/**
	 * 数据库句柄
	 */
	protected $db;

	public function __construct($options = array()) {
		ini_set('session.save_handler', 'user');

		$conf				 = obj('conf');
		$this->db			 = obj('\Gaara\Core\DbConnection', $conf->db);
		$this->sessionTable	 = $conf->tablepre . 'session';
		if ($conf->debug)
			$this->checkSessionDb();

		session_set_save_handler(
		array(&$this, 'open'), array(&$this, 'close'), array(&$this, 'read'), array(&$this, 'write'), array(&$this, 'destroy'), array(&$this, 'gc')
		);
	}

	protected function checkSessionDb() {
		$sql = 'show tables like "' . $this->sessionTable . '"';
		$re	 = $this->db->getRow($sql);
		if (empty($re)) {
			$createDb = <<<EEE
CREATE TABLE {$this->sessionTable} (
session_id varchar(255) NOT NULL,
session_expire int(11) NOT NULL,
session_data blob,
UNIQUE KEY `session_id` (`session_id`)
)ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
EEE;
			$this->db->execute($createDb);
		}
		return true;
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
		$sql = "SELECT session_data AS data FROM " . $this->sessionTable . " WHERE session_id = '$sessID' AND session_expire >" . time();
		$re	 = $this->db->getRow($sql);
		return isset($re['data']) ? $re['data'] : '';
	}

	/**
	 * 写入Session
	 * @access public
	 * @param string $sessID
	 * @param String $sessData
	 */
	public function write($sessID, $sessData) {
		$expire	 = time() + $this->lifeTime;
		$sql	 = "REPLACE INTO " . $this->sessionTable . " (  session_id, session_expire, session_data)  VALUES( '$sessID', '$expire',  '$sessData')";
		return $this->db->execute($sql) ? true : false;
	}

	/**
	 * 删除Session
	 * @access public
	 * @param string $sessID
	 */
	public function destroy($sessID) {
		$sql = "DELETE FROM " . $this->sessionTable . " WHERE session_id = '$sessID'";
		return $this->db->execute($sql) ? true : false;
	}

	/**
	 * Session 垃圾回收
	 * @access public
	 * @param string $sessMaxLifeTime
	 */
	public function gc($sessMaxLifeTime) {
		$sql = 'DELETE FROM ' . $this->sessionTable . ' WHERE session_expire < ' . time();
		return $this->db->execute($sql) ? true : false;
	}

//--------------------------------------------------------------------------------------------------------------------//
//------------------------------------ 以下为手动 session 获取与写入 -------------------------------------------------//
//--------------------------------------------------------------------------------------------------------------------//
	/**
	 * 手动return session
	 * @return array
	 * @throws Exception
	 */
	public function getSession() {
		if (SESSIONMODULENAME !== 'user')
			throw new Exception('手动session 暂时只支持 数据库存储方式! ');
//        if(isset($_SERVER['HTTP_COOKIE']))
		return $this->session_decode($this->read($this->getPHPSESSID()));
	}

	/**
	 * 手动commit session
	 * @param array $data
	 * @return bool
	 */
	public function commit(array $data) {
		return $this->write($this->getPHPSESSID(), $this->session_encode($data));
	}

	/**
	 * 通过$_SERVER手动获取$_SESSION
	 * 用于webScoket的http阶段
	 * @return string PHPSESSID
	 */
	protected function getPHPSESSID() {
		if (isset($_SERVER['HTTP_COOKIE'])) {
			if (preg_match('#.*PHPSESSID=(.*)#', $_SERVER['HTTP_COOKIE'], $match)) {
				return $match[1];
			}
		}
	}

	/**
	 * 手动解析session
	 * @param $encodedData
	 * @return array
	 */
	protected function session_decode($encodedData) {
		$explodeIt = explode(';', $encodedData);
		for ($i = 0; $i < count($explodeIt) - 1; $i++) {
			$sessGet		 = explode("|", $explodeIt[$i]);
			$sessName[$i]	 = $sessGet[0];
			if (substr($sessGet[1], 0, 2) === 's:') {
				$sessData[$i] = str_replace('"', '', strstr($sessGet[1], '"'));
			} else {
				$sessData[$i] = substr($sessGet[1], 2);
			}
		}
		$result = array_combine($sessName, $sessData);
		return $result;
	}

	/**
	 * 手动编码session
	 * @param $array
	 * @param bool|true $safe
	 * @return string
	 */
	protected function session_encode($array) {
		$array	 = unserialize(serialize($array));
		$raw	 = '';
		$line	 = 0;
		$keys	 = array_keys($array);
		foreach ($keys as $key) {
			$value		 = $array[$key];
			$line ++;
			$raw		 .= $key . '|';
			if (is_array($value) && isset($value['huge_recursion_blocker_we_hope']))
				$raw		 .= 'R:' . $value['huge_recursion_blocker_we_hope'] . ';';
			else
				$raw		 .= serialize($value);
			$array[$key] = Array('huge_recursion_blocker_we_hope' => $line);
		}
		return $raw;
	}

}
