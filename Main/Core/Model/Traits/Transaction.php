<?php

declare(strict_types = 1);
namespace Main\Core\Model\Traits;
defined('IN_SYS') || exit('ACC Denied');

use Closure;
use PDOException;

/**
 * 数据库事务
 */
trait Transaction {

    public function begin() : bool {
        return $this->db->begin();
    }

    public function commit() : bool {
        return $this->db->commit();
    }

    public function inTransaction() : bool {
        return $this->db->inTransaction();
    }

    public function rollBack() : bool {
        return $this->db->rollBack();
    }

    /**
     * 开始事务
     * @param Closure $callback
     * @param type $attempts
     */
    public function transaction(Closure $callback,int $attempts = 1, bool $throwException = false) {
        for ($currentAttempt = 1; $currentAttempt <= $attempts; $currentAttempt++) {
            $this->begin();
            try {
                $callback($this);
                return $this->commit();
            } catch (PDOException $e) {
                $this->rollBack();
                if($currentAttempt >= $attempts){
                    if($throwException)
                        throw $e;
                    else 
                        return false;
                }
            }
        }
    }
}