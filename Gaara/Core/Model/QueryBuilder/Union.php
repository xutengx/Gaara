<?php

declare(strict_types = 1);
namespace Gaara\Core\Model\QueryBuilder;

use Closure;
use Gaara\Core\Model\QueryBuilder;

trait Union {

	/**
	 * union一个完整的sql
	 * @param string $sql
	 * @param string $type union|union all
	 * @return QueryBuilder
	 */
	public function unionRaw(string $sql, string $type = 'union'): QueryBuilder {
		return $this->unionPush($sql, $type);
	}

	/**
	 * union一个QueryBuilder对象
	 * @param QueryBuilder $QueryBuilder
	 * @param string $type union|union all
	 * @return QueryBuilder
	 */
//	public function unionQueryBuilder(QueryBuilder $QueryBuilder, string $type = 'union'): QueryBuilder {
//		$sql = $QueryBuilder->getAllToSqlWithBindingsKey();
//		// 合并绑定数组
//		$this->bindings += $QueryBuilder->getBindings();
//		return $this->unionPush($sql, $type);
//	}

	/**
	 * union一个闭包
	 * @param Closure $callback
	 * @param string $type union|union all
	 * @return QueryBuilder
	 */
	public function unionClosure(Closure $callback, string $type = 'union'): QueryBuilder {
		$res		 = $callback($QueryBuilder = $this->getSelf());
		// 调用方未调用return
		if (is_null($res)) {
			$sql = $QueryBuilder->getAllToSqlWithBindingsKey();
		}
		// 调用方未调用toSql
		elseif ($res instanceof QueryBuilder) {
			$sql = $res->getAllToSqlWithBindingsKey();
		}
		// 调用正常
		else
			$sql = $res;
		// 合并绑定数组
		$this->bindings += $QueryBuilder->getBindings();
		return $this->unionPush($sql, $type);
	}

	/**
	 * 要联合的sql加入union, 返回当前对象
	 * @param string $sql
	 * @param string $type union|union all
	 * @return QueryBuilder
	 */
	protected function unionPush(string $sql, string $type): QueryBuilder {
		$this->union[$type][] = $sql;
		return $this;
	}

}
