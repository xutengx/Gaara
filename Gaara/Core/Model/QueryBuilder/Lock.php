<?php

declare(strict_types = 1);
namespace Gaara\Core\Model\QueryBuilder;

use Gaara\Core\Model\QueryBuilder;

trait Lock {

	/**
	 * lock in share mode 共享锁
	 * 获取事物之外的目标数据的最新状态, 并上锁(其他事物`更新`等待)直至本事物提交
	 * lock in share mode适用于两张表存在业务关系时的一致性要求
	 */
	public function lockForShared(): QueryBuilder {
		return $this->lockPush('lock in share mode');
	}

	/**
	 * for update 排他锁 (mysql 推荐使用)
	 * 获取事物之外的目标数据的最新状态, 并上锁(其他事物`更新`&`上锁查询`等待)直至本事物提交
	 * 普通的非锁定读取读取依然可以读取到目标行，只有 sharedLock 和 lockForUpdate 的读取会被阻止
	 * for update适用于操作同一张表时的一致性要求 (避免了`lock in share mode`的死锁情况)
	 */
	public function lockForUpdate(): QueryBuilder {
		return $this->lockPush('for update');
	}

	/**
	 * 将lock片段加入lock, 返回当前对象
	 * @param string $part
	 * @return QueryBuilder
	 */
	protected function lockPush(string $part): QueryBuilder {
		$this->lock = $part;
		return $this;
	}

}
