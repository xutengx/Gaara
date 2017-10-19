<?php

declare(strict_types = 1);
namespace Main\Core\Model;

use PDOStatement;
use Iterator;
use Closure;

/**
 * 块状数据对象
 */
class QueryChunk implements Iterator {

    public $PDOStatement;
    // 当前位置是否有效
    private $is_over = true;
    // 当前元素的键的来源
    private $index = null;
    // 当前元素的键
    private $key = null;
    // 当前元素的值
    private $value = [];

    public function __construct(PDOStatement $PDOStatement, $index = null) {
        $this->PDOStatement = $PDOStatement;
        $this->index = $index;
    }
    
    /**
     * 获取结果集value, 键key自增, 判断is_over
     * @return void
     */
    private function fetchData(): void {
        $value = $this->PDOStatement->fetch(\PDO::FETCH_ASSOC);
        if ($value === false) {
            $this->is_over = false;
        } else {
            if (is_null($this->index))
                $this->key = is_null($this->key) ? 0 : ++$this->key;
            elseif ($this->index instanceof Closure) {
                $this->key = call_user_func($this->index, $value);
            } else {
                $this->key = $value[$this->index];
            }
            $this->value = $value;
        }
    }

    /**
     * 直接操作 PDOStatement
     * @param string $method
     * @param array $parameters
     * @return type
     */
    public function __call(string $method, array $parameters = []) {
        return $this->PDOStatement->$method(...$parameters);
        
    }
    
    /************************************************ 以下 Iterator 实现 ***************************************************/

    public function rewind(): void {
        $this->key = null;
        $this->is_over = true;
        $this->fetchData();
    }

    public function current(): array {
        return $this->value;
    }

    public function key(): string {
        return (string)$this->key;
    }

    public function next(): void {
        $this->fetchData();
    }

    public function valid(): bool {
        if(!$this->is_over){
            $this->PDOStatement->closeCursor();
        }
        return $this->is_over;   
    }
}
