<?php

declare(strict_types = 1);
namespace Gaara\Core\Model\Traits;

use Closure;
use PDOException;

/**
 * 数据库事务
 */
trait Transaction {

	/**
	 * 开启事务
	 * @return bool
	 */
	public function begin(): bool {
		return $this->db->begin();
	}

	/**
	 * 提交事务
	 * @return bool
	 */
	public function commit(): bool {
		return $this->db->commit();
	}

	/**
	 * 是否处在事务中, ( 并非使用的pdo->inTransaction() )
	 * @return bool
	 */
	public function inTransaction(): bool {
		return $this->db->inTransaction();
	}

	/**
	 * 回滚事务
	 * @return bool
	 */
	public function rollBack(): bool {
		return $this->db->rollBack();
	}

	/**
	 * 以闭包开始一个事务
	 * @param Closure $callback         闭包
	 * @param int $attempts             重试次数
	 * @param bool $throwException      事务失败后是否抛出异常
	 * @return boolean
	 * @throws PDOException
	 */
	public function transaction(Closure $callback, int $attempts = 1, bool $throwException = false) {
		for ($currentAttempt = 1; $currentAttempt <= $attempts; $currentAttempt++) {
			$this->begin();
			try {
				$callback($this);
				return $this->commit();
			} catch (PDOException $e) {
				$this->rollBack();
				if ($currentAttempt >= $attempts) {
					if ($throwException)
						throw $e;
					else
						return false;
				}
			}
		}
	}

}
