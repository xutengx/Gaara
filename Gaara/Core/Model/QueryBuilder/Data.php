<?php

declare(strict_types = 1);
namespace Gaara\Core\Model\QueryBuilder;

use Gaara\Core\Model\QueryBuilder;

trait Data {

	/**
	 * 加入一个不做处理的data
	 * @param string $sql
	 * @return QueryBuilder
	 */
	public function dataRaw(string $sql): QueryBuilder {
		return $this->dataPush($sql);
	}

	/**
	 * 字段自增
	 * @param string $field 字段
	 * @param int $steps
	 * @return QueryBuilder
	 */
	public function dataIncrement(string $field, int $steps = 1): QueryBuilder {
		$sql = $this->fieldFormat($field) . '=' . $this->fieldFormat($field) . '+' . $steps;
		return $this->dataPush($sql);
	}

	/**
	 * 字段自减
	 * @param string $field 字段
	 * @param int $steps
	 * @return QueryBuilder
	 */
	public function dataDecrement(string $field, int $steps = 1): QueryBuilder {
		$sql = $this->fieldFormat($field) . '=' . $this->fieldFormat($field) . '-' . $steps;
		return $this->dataPush($sql);
	}

	/**
	 * 字段$field赋值$value
	 * @param string $str
	 * @return QueryBuilder
	 */
	public function dataString(string $field, string $value): QueryBuilder {
		$sql = $this->fieldFormat($field) . '=' . $this->valueFormat($value);
		return $this->dataPush($sql);
	}

	/**
	 * 批量数组赋值
	 * @param array $arr
	 * @return QueryBuilder
	 */
	public function dataArray(array $arr): QueryBuilder {
		foreach ($arr as $field => $value) {
			$this->dataString((string) $field, (string) $value);
		}
		return $this;
	}

	/**
	 * 将data片段加入data, 返回当前对象
	 * @param string $part
	 * @return QueryBuilder
	 */
	protected function dataPush(string $part): QueryBuilder {
		if (is_null($this->data)) {
			$this->data = $part;
		} else
			$this->data .= ',' . $part;
		return $this;
	}

}
