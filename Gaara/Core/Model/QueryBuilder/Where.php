<?php

declare(strict_types = 1);
namespace Gaara\Core\Model\QueryBuilder;

use Closure;
use Gaara\Core\Model\QueryBuilder;

trait Where {

	/**
	 * exists一句完整的sql
	 * @param string $sql
	 * @return QueryBuilder
	 */
	public function whereExistsRaw(string $sql): QueryBuilder {
		$sql = 'exists ' . $this->bracketFormat($sql);
		return $this->wherePush($sql);
	}

	/**
	 * exists一个闭包
	 * @param Closure $callback
	 * @return QueryBuilder
	 */
	public function whereExistsClosure(Closure $callback): QueryBuilder {
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
		return $this->whereExistsRaw($sql);
	}

	/**
	 * not exists一句完整的sql
	 * @param string $sql
	 * @return QueryBuilder
	 */
	public function whereNotExistsRaw(string $sql): QueryBuilder {
		$sql = 'not exists ' . $this->bracketFormat($sql);
		return $this->wherePush($sql);
	}

	/**
	 * not exists一个闭包
	 * @param Closure $callback
	 * @return QueryBuilder
	 */
	public function whereNotExistsClosure(Closure $callback): QueryBuilder {
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
		return $this->whereNotExistsRaw($sql);
	}

	/**
	 * 加入一个不做处理的条件
	 * @param string $sql
	 * @return QueryBuilder
	 */
	public function whereRaw(string $sql): QueryBuilder {
		return $this->wherePush($sql);
	}

	/**
	 * 且
	 * @param Closure $callback
	 * @return QueryBuilder
	 */
	public function andWhere(Closure $callback): QueryBuilder {
		$sql = $this->whereClosure($callback);
		return $this->wherePush($sql);
	}

	/**
	 * 或
	 * @param Closure $callback
	 * @return QueryBuilder
	 */
	public function orWhere(Closure $callback): QueryBuilder {
		$sql = $this->whereClosure($callback);
		return $this->wherePush($sql, 'or');
	}

	/**
	 * 比较字段与字段
	 * @param string $fieldOne
	 * @param string $symbol
	 * @param string $fieldTwo
	 * @return QueryBuilder
	 */
	public function whereColumn(string $fieldOne, string $symbol, string $fieldTwo): QueryBuilder {
		$sql = $this->fieldFormat($fieldOne) . $symbol . $this->fieldFormat($fieldTwo);
		return $this->wherePush($sql);
	}

	/**
	 * 比较字段与值
	 * @param string $field
	 * @param string $symbol
	 * @param string $value
	 * @return QueryBuilder
	 */
	public function whereValue(string $field, string $symbol, string $value): QueryBuilder {
		$sql = $this->fieldFormat($field) . $symbol . $this->valueFormat($value);
		return $this->wherePush($sql);
	}

	/**
	 * 子查询 一句完整的sql
	 * @param string $field
	 * @param string $symbol
	 * @param string $subquery
	 */
	public function whereSubqueryRaw(string $field, string $symbol, string $subquery): QueryBuilder {
		$sql = $this->fieldFormat($field) . $symbol . $this->bracketFormat($subquery);
		return $this->wherePush($sql);
	}

	/**
	 * 子查询 一个QueryBuilder对象
	 * @param string $field
	 * @param string $symbol
	 * @param QueryBuilder $QueryBuilder
	 */
	public function whereSubqueryQueryBuilder(string $field, string $symbol, QueryBuilder $QueryBuilder): QueryBuilder {
		$sql = $QueryBuilder->getAllToSql();
		return $this->whereSubqueryRaw($field, $symbol, $sql);
	}

	/**
	 * 子查询 一个闭包
	 * @param string $field
	 * @param string $symbol
	 * @param Closure $callback
	 */
	public function whereSubqueryClosure(string $field, string $symbol, Closure $callback): QueryBuilder {
		$res		 = $callback($QueryBuilder = $this->getSelf());
		// 调用方未调用return
		if (is_null($res)) {
			$sql = $QueryBuilder->getAllToSql();
		}
		// 调用方未调用toSql
		elseif ($res instanceof QueryBuilder) {
			$sql = $res->getAllToSql();
		}
		// 调用正常
		else
			$sql = $res;
		return $this->whereSubqueryRaw($field, $symbol, $sql);
	}

	/**
	 * 批量相等条件
	 * @param array $arr
	 * @return QueryBuilder
	 */
	public function whereArray(array $arr): QueryBuilder {
		foreach ($arr as $field => $value) {
			$this->whereValue((string)$field, '=', (string)$value);
		}
		return $this;
	}

	/**
	 * 字段值在2值之间
	 * @param string $field
	 * @param string $min
	 * @param string $max
	 * @return QueryBuilder
	 */
	public function whereBetweenString(string $field, string $min, string $max): QueryBuilder {
		$sql = $this->fieldFormat($field) . 'between' . $this->valueFormat($min) . 'and' . $this->valueFormat($max);
		return $this->wherePush($sql);
	}

	/**
	 * 字段值在2值之间
	 * @param string $field
	 * @param array $range
	 * @return QueryBuilder
	 */
	public function whereBetweenArray(string $field, array $range): QueryBuilder {
		$sql = $this->fieldFormat($field) . 'between' . $this->valueFormat(reset($range)) . 'and' . $this->valueFormat(end($range));
		return $this->wherePush($sql);
	}

	/**
	 * 字段值不在2值之间
	 * @param string $field
	 * @param string $min
	 * @param string $max
	 * @return QueryBuilder
	 */
	public function whereNotBetweenString(string $field, string $min, string $max): QueryBuilder {
		$sql = $this->fieldFormat($field) . 'not between' . $this->valueFormat($min) . 'and' . $this->valueFormat($max);
		return $this->wherePush($sql);
	}

	/**
	 * 字段值不在2值之间
	 * @param string $field
	 * @param array $range
	 * @return QueryBuilder
	 */
	public function whereNotBetweenArray(string $field, array $range): QueryBuilder {
		$sql = $this->fieldFormat($field) . 'not between' . $this->valueFormat(reset($range)) . 'and' . $this->valueFormat(end($range));
		return $this->wherePush($sql);
	}

	/**
	 * 字段值在范围内
	 * @param string $field
	 * @param array $values
	 * @return QueryBuilder
	 */
	public function whereInArray(string $field, array $values): QueryBuilder {
		$sql = $this->fieldFormat($field) . 'in' . $this->bracketFormat($this->valueFormat(implode('\',\'', $values)));
		return $this->wherePush($sql);
	}

	/**
	 * 字段值在范围内
	 * @param string $field
	 * @param string $value
	 * @param array $delimiter
	 * @return QueryBuilder
	 */
	public function whereInString(string $field, string $value, string $delimiter = ','): QueryBuilder {
		return $this->whereInArray($field, explode($delimiter, $value));
	}

	/**
	 * 字段值不在范围内
	 * @param string $field
	 * @param array $values
	 * @return QueryBuilder
	 */
	public function whereNotInArray(string $field, array $values): QueryBuilder {
		$sql = $this->fieldFormat($field) . 'not in' . $this->bracketFormat($this->valueFormat(implode('\',\'', $values)));
		return $this->wherePush($sql);
	}

	/**
	 * 字段值不在范围内
	 * @param string $field
	 * @param string $value
	 * @param array $delimiter
	 * @return QueryBuilder
	 */
	public function whereNotInString(string $field, string $value, string $delimiter = ','): QueryBuilder {
		return $this->whereNotInArray($field, explode($delimiter, $value));
	}

	/**
	 * 字段值为null
	 * @param string $field
	 * @return QueryBuilder
	 */
	public function whereNull(string $field): QueryBuilder {
		$sql = $this->fieldFormat($field) . 'is null';
		return $this->wherePush($sql);
	}

	/**
	 * 字段值不为null
	 * @param string $field
	 * @return QueryBuilder
	 */
	public function whereNotNull(string $field): QueryBuilder {
		$sql = $this->fieldFormat($field) . 'is not null';
		return $this->wherePush($sql);
	}

	/**
	 * 闭包
	 * @param Closure $callback
	 * @return string
	 */
	protected function whereClosure(Closure $callback): string {
		$str		 = '';
		$res		 = $callback($QueryBuilder = $this->getSelf());
		// 调用方未调用return
		if (is_null($res)) {
			$str = $QueryBuilder->toSql();
		}
		// 调用方未调用toSql
		elseif ($res instanceof QueryBuilder) {
			$str = $res->toSql();
		}
		// 调用正常
		else
			$str = $res;
		$sql = $this->bracketFormat($str);
		// 合并绑定数组
		$this->bindings += $QueryBuilder->getBindings();
		return $sql;
	}

	/**
	 * 将where片段加入where, 返回当前对象
	 * @param string $part
	 * @param string $relationship
	 * @return QueryBuilder
	 */
	protected function wherePush(string $part, string $relationship = 'and'): QueryBuilder {
		if (is_null($this->where)) {
			$this->where = $part;
		} else
			$this->where .= ' ' . $relationship . ' ' . $part;
		return $this;
	}

}
