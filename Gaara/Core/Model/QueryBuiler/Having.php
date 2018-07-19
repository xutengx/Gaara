<?php

declare(strict_types = 1);
namespace Gaara\Core\Model\QueryBuiler;

use Closure;
use Gaara\Core\Model\QueryBuiler;

trait Having {

	/**
	 * 加入一个不做处理的条件
	 * @param string $sql
	 * @return QueryBuiler
	 */
	public function havingRaw(string $sql): QueryBuiler {
		return $this->havingPush($sql);
	}

	/**
	 * 且
	 * @param Closure $callback
	 * @return QueryBuiler
	 */
	public function andHaving(Closure $callback): QueryBuiler {
		$sql = $this->havingClosure($callback);
		return $this->havingPush($sql);
	}

	/**
	 * 或
	 * @param Closure $callback
	 * @return QueryBuiler
	 */
	public function orHaving(Closure $callback): QueryBuiler {
		$sql = $this->havingClosure($callback);
		return $this->havingPush($sql, 'or');
	}

	/**
	 * 比较字段与字段
	 * @param string $fieldOne
	 * @param string $symbol
	 * @param string $fieldTwo
	 * @return QueryBuiler
	 */
	public function havingColumn(string $fieldOne, string $symbol, string $fieldTwo): QueryBuiler {
		$sql = $this->fieldFormat($fieldOne) . $symbol . $this->fieldFormat($fieldTwo);
		return $this->havingPush($sql);
	}

	/**
	 * 比较字段与值
	 * @param string $field
	 * @param string $symbol
	 * @param string $value
	 * @return QueryBuiler
	 */
	public function havingValue(string $field, string $symbol, string $value): QueryBuiler {
		$sql = $this->fieldFormat($field) . $symbol . $this->valueFormat($value);
		return $this->havingPush($sql);
	}

	/**
	 * 字段值在2值之间
	 * @param string $field
	 * @param string $min
	 * @param string $max
	 * @return QueryBuiler
	 */
	public function havingBetweenString(string $field, string $min, string $max): QueryBuiler {
		$sql = $this->fieldFormat($field) . 'between' . $this->valueFormat($min) . 'and' . $this->valueFormat($max);
		return $this->havingPush($sql);
	}

	/**
	 * 字段值不在2值之间
	 * @param string $field
	 * @param string $min
	 * @param string $max
	 * @return QueryBuiler
	 */
	public function havingNotBetweenString(string $field, string $min, string $max): QueryBuiler {
		$sql = $this->fieldFormat($field) . 'not between' . $this->valueFormat($min) . 'and' . $this->valueFormat($max);
		return $this->havingPush($sql);
	}

	/**
	 * 字段值在2值之间
	 * @param string $field
	 * @param array $range
	 * @return QueryBuiler
	 */
	public function havingBetweenArray(string $field, array $range): QueryBuiler {
		$sql = $this->fieldFormat($field) . 'between' . $this->valueFormat(reset($range)) . 'and' . $this->valueFormat(end($range));
		return $this->havingPush($sql);
	}

	/**
	 * 字段值不在2值之间
	 * @param string $field
	 * @param array $range
	 * @return QueryBuiler
	 */
	public function havingNotBetweenArray(string $field, array $range): QueryBuiler {
		$sql = $this->fieldFormat($field) . 'not between' . $this->valueFormat(reset($range)) . 'and' . $this->valueFormat(end($range));
		return $this->havingPush($sql);
	}

	/**
	 * 字段值在范围内
	 * @param string $field
	 * @param array $values
	 * @return QueryBuiler
	 */
	public function havingInArray(string $field, array $values): QueryBuiler {
		$sql = $this->fieldFormat($field) . 'in' . $this->bracketFormat($this->valueFormat(implode('\',\'', $values)));
		return $this->havingPush($sql);
	}

	/**
	 * 字段值不在范围内
	 * @param string $field
	 * @param array $values
	 * @return QueryBuiler
	 */
	public function havingNotInArray(string $field, array $values): QueryBuiler {
		$sql = $this->fieldFormat($field) . 'not in' . $this->bracketFormat($this->valueFormat(implode('\',\'', $values)));
		return $this->havingPush($sql);
	}

	/**
	 * 字段值在范围内
	 * @param string $field
	 * @param string $value
	 * @param array $delimiter
	 * @return QueryBuiler
	 */
	public function havingInString(string $field, string $value, string $delimiter = ','): QueryBuiler {
		return $this->havingInArray($field, explode($delimiter, $value));
	}

	/**
	 * 字段值不在范围内
	 * @param string $field
	 * @param string $value
	 * @param array $delimiter
	 * @return QueryBuiler
	 */
	public function havingNotInString(string $field, string $value, string $delimiter = ','): QueryBuiler {
		return $this->havingNotInArray($field, explode($delimiter, $value));
	}

	/**
	 * 字段值为null
	 * @param string $field
	 * @return QueryBuiler
	 */
	public function havingNull(string $field): QueryBuiler {
		$sql = $this->fieldFormat($field) . 'is null';
		return $this->havingPush($sql);
	}

	/**
	 * 字段值不为null
	 * @param string $field
	 * @return QueryBuiler
	 */
	public function havingNotNull(string $field): QueryBuiler {
		$sql = $this->fieldFormat($field) . 'is not null';
		return $this->havingPush($sql);
	}

	/**
	 * 闭包
	 * @param Closure $callback
	 * @return string
	 */
	protected function havingClosure(Closure $callback): string {
		$str		 = '';
		$res		 = $callback($queryBuiler = $this->getSelf());
		// 调用方未调用return
		if (is_null($res)) {
			$str = $queryBuiler->toSql();
		}
		// 调用方未调用toSql
		elseif ($res instanceof QueryBuiler) {
			$str = $res->toSql();
		}
		// 正常
		else
			$str = $res;
		$sql = $this->bracketFormat($str);
		// 合并绑定数组
		$this->bindings += $queryBuiler->getBindings();
		return $sql;
	}

	/**
	 * 将having片段加入having, 返回当前对象
	 * @param string $part
	 * @param string $relationship
	 * @return QueryBuiler
	 */
	protected function havingPush(string $part, string $relationship = 'and'): QueryBuiler {
		if (is_null($this->having)) {
			$this->having = $part;
		} else
			$this->having .= ' ' . $relationship . ' ' . $part;
		return $this;
	}

}
