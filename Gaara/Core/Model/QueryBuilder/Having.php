<?php

declare(strict_types = 1);
namespace Gaara\Core\Model\QueryBuilder;

use Closure;
use Gaara\Core\Model\QueryBuilder;

trait Having {

	/**
	 * 加入一个不做处理的条件
	 * @param string $sql
	 * @return QueryBuilder
	 */
	public function havingRaw(string $sql): QueryBuilder {
		return $this->havingPush($sql);
	}

	/**
	 * 将having片段加入having, 返回当前对象
	 * @param string $part
	 * @param string $relationship
	 * @return QueryBuilder
	 */
	protected function havingPush(string $part, string $relationship = 'and'): QueryBuilder {
		if (is_null($this->having)) {
			$this->having = $part;
		}
		else
			$this->having .= ' ' . $relationship . ' ' . $part;
		return $this;
	}

	/**
	 * 且
	 * @param Closure $callback
	 * @return QueryBuilder
	 */
	public function andHaving(Closure $callback): QueryBuilder {
		$sql = $this->havingClosure($callback);
		return $this->havingPush($sql);
	}

	/**
	 * 闭包
	 * @param Closure $callback
	 * @return string
	 */
	protected function havingClosure(Closure $callback): string {
		$res = $callback($QueryBuilder = $this->getSelf());
		// 调用方未调用return
		if (is_null($res)) {
			$str = $QueryBuilder->toSql();
		}
		// 调用方未调用toSql
		elseif ($res instanceof QueryBuilder) {
			$str = $res->toSql();
		}
		// 正常
		else
			$str = $res;
		$sql = $this->bracketFormat($str);
		// 合并绑定数组
		$this->bindings += $QueryBuilder->getBindings();
		return $sql;
	}

	/**
	 * 或
	 * @param Closure $callback
	 * @return QueryBuilder
	 */
	public function orHaving(Closure $callback): QueryBuilder {
		$sql = $this->havingClosure($callback);
		return $this->havingPush($sql, 'or');
	}

	/**
	 * 比较字段与字段
	 * @param string $fieldOne
	 * @param string $symbol
	 * @param string $fieldTwo
	 * @return QueryBuilder
	 */
	public function havingColumn(string $fieldOne, string $symbol, string $fieldTwo): QueryBuilder {
		$sql = $this->fieldFormat($fieldOne) . $symbol . $this->fieldFormat($fieldTwo);
		return $this->havingPush($sql);
	}

	/**
	 * 比较字段与值
	 * @param string $field
	 * @param string $symbol
	 * @param string $value
	 * @return QueryBuilder
	 */
	public function havingValue(string $field, string $symbol, string $value): QueryBuilder {
		$sql = $this->fieldFormat($field) . $symbol . $this->valueFormat($value);
		return $this->havingPush($sql);
	}

	/**
	 * 字段值在2值之间
	 * @param string $field
	 * @param string $min
	 * @param string $max
	 * @return QueryBuilder
	 */
	public function havingBetweenString(string $field, string $min, string $max): QueryBuilder {
		$sql = $this->fieldFormat($field) . 'between' . $this->valueFormat($min) . 'and' . $this->valueFormat($max);
		return $this->havingPush($sql);
	}

	/**
	 * 字段值不在2值之间
	 * @param string $field
	 * @param string $min
	 * @param string $max
	 * @return QueryBuilder
	 */
	public function havingNotBetweenString(string $field, string $min, string $max): QueryBuilder {
		$sql = $this->fieldFormat($field) . 'not between' . $this->valueFormat($min) . 'and' . $this->valueFormat($max);
		return $this->havingPush($sql);
	}

	/**
	 * 字段值在2值之间
	 * @param string $field
	 * @param array $range
	 * @return QueryBuilder
	 */
	public function havingBetweenArray(string $field, array $range): QueryBuilder {
		$sql = $this->fieldFormat($field) . 'between' . $this->valueFormat(reset($range)) . 'and' .
		       $this->valueFormat(end($range));
		return $this->havingPush($sql);
	}

	/**
	 * 字段值不在2值之间
	 * @param string $field
	 * @param array $range
	 * @return QueryBuilder
	 */
	public function havingNotBetweenArray(string $field, array $range): QueryBuilder {
		$sql = $this->fieldFormat($field) . 'not between' . $this->valueFormat(reset($range)) . 'and' .
		       $this->valueFormat(end($range));
		return $this->havingPush($sql);
	}

	/**
	 * 字段值在范围内
	 * @param string $field
	 * @param string $value
	 * @param array $delimiter
	 * @return QueryBuilder
	 */
	public function havingInString(string $field, string $value, string $delimiter = ','): QueryBuilder {
		return $this->havingInArray($field, explode($delimiter, $value));
	}

	/**
	 * 字段值在范围内
	 * @param string $field
	 * @param array $values
	 * @return QueryBuilder
	 */
	public function havingInArray(string $field, array $values): QueryBuilder {
		$sql = $this->fieldFormat($field) . 'in' . $this->bracketFormat($this->valueFormat(implode('\',\'', $values)));
		return $this->havingPush($sql);
	}

	/**
	 * 字段值不在范围内
	 * @param string $field
	 * @param string $value
	 * @param array $delimiter
	 * @return QueryBuilder
	 */
	public function havingNotInString(string $field, string $value, string $delimiter = ','): QueryBuilder {
		return $this->havingNotInArray($field, explode($delimiter, $value));
	}

	/**
	 * 字段值不在范围内
	 * @param string $field
	 * @param array $values
	 * @return QueryBuilder
	 */
	public function havingNotInArray(string $field, array $values): QueryBuilder {
		$sql = $this->fieldFormat($field) . 'not in' .
		       $this->bracketFormat($this->valueFormat(implode('\',\'', $values)));
		return $this->havingPush($sql);
	}

	/**
	 * 字段值为null
	 * @param string $field
	 * @return QueryBuilder
	 */
	public function havingNull(string $field): QueryBuilder {
		$sql = $this->fieldFormat($field) . 'is null';
		return $this->havingPush($sql);
	}

	/**
	 * 字段值不为null
	 * @param string $field
	 * @return QueryBuilder
	 */
	public function havingNotNull(string $field): QueryBuilder {
		$sql = $this->fieldFormat($field) . 'is not null';
		return $this->havingPush($sql);
	}

}
