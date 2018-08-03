<?php

declare(strict_types = 1);
namespace Gaara\Core\Model\QueryBuilder;

use Gaara\Core\Model\QueryBuilder;

trait Group {

	/**
	 * 单个group
	 * @param string $field
	 * @return QueryBuilder
	 */
	public function groupString(string $field, string $delimiter = ','): QueryBuilder {
		return $this->groupArray(explode($delimiter, $field));
	}

	/**
	 * 批量group
	 * @param array $arr
	 * @return QueryBuilder
	 */
	public function groupArray(array $arr): QueryBuilder {
		$str = '';
		foreach ($arr as $field) {
			$str .= $this->fieldFormat($field) . ',';
		}
		$sql = rtrim($str, ',');
		return $this->groupPush($sql);
	}

	/**
	 * 将Group片段加入Group, 返回当前对象
	 * @param string $part
	 * @return QueryBuilder
	 */
	protected function groupPush(string $part): QueryBuilder {
		if (is_null($this->group)) {
			$this->group = $part;
		} else
			$this->group .= ',' . $part;
		return $this;
	}

}
