<?php

declare(strict_types = 1);
namespace Main\Core\Model\Traits;
defined('IN_SYS') || exit('ACC Denied');

use Closure;

/**
 * 数据库事务
 */
trait Transaction {

    public function begin() {
        return $this->db->begin();
    }

    public function commit() {
        return $this->db->commit();
    }

    public function rollBack() {
        return $this->db->rollBack();
    }

    /**
     * 开始事务
     * @param Closure $callback
     * @param type $attempts
     */
    protected function transaction(Closure $callback, $attempts = 1) {
        for ($currentAttempt = 1; $currentAttempt <= $attempts; $currentAttempt++) {
            $this->begin();

            try {

                return $this->tap($callback($this), function ($result) {
                            $this->commit();
                        });
            } catch (Exception $e) {
                if($currentAttempt >= $attempts)
                    throw $e;
            } catch (Throwable $e) {
                $this->rollBack();
                throw $e;
            }
        }
    }
}
