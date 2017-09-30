<?php

declare(strict_types = 1);
namespace Main\Core\Model;

use Main\Core\Model\QueryBuiler;
use Closure;

/**
 * 链式操作
 */
class QueryBuiler {

    use QueryBuiler\Support;
    use QueryBuiler\Where;
    use QueryBuiler\Select;
    use QueryBuiler\Data;
    use QueryBuiler\From;
    use QueryBuiler\Join;
    use QueryBuiler\Group;
    use QueryBuiler\Order;
    use QueryBuiler\Limit;
    use QueryBuiler\Having;

    // 绑定的表名
    private $table;
    // 主键
    private $primaryKey;
    // 当前语句类别
    private $sqlType;
    private $select;
    private $data;
    private $from;
    private $where;
    private $join;
    private $group;
    private $having;
    private $order;
    private $limit;

    public function __construct(string $table, string $primaryKey) {
        $this->table = $table;
        $this->primaryKey = $primaryKey;
    }

    /**
     * 查询条件
     * @return QueryBuiler
     */
    public function where(): QueryBuiler {
        $params = func_get_args();
        switch (func_num_args()) {
            case 1:
                switch (gettype($params[0])) {
                    case 'object':
                        return $this->andWhere(...$params);
                    default :
                        return $this->whereRaw('1');
                }
            case 2:
                return $this->whereValue($params[0], '=', $params[1]);
            case 3:
                return $this->whereValue(...$params);
        }
        return $this;
    }
    
    /**
     * 字段值在范围内
     * @return QueryBuiler
     */
    public function whereIn(): QueryBuiler {
        $params = func_get_args();
        switch (func_num_args()) {
            case 2:
                switch (gettype($params[1])) {
                    case 'array':
                        return $this->whereInArray(...$params);
                    default :
                        return $this->whereInString(...$params);
                }
        }
    }
    
    /**
     * 字段值不在范围内
     * @return QueryBuiler
     */
    public function whereNotIn(): QueryBuiler {
        $params = func_get_args();
        switch (func_num_args()) {
            case 2:
                switch (gettype($params[1])) {
                    case 'array':
                        return $this->whereNotInArray(...$params);
                    default :
                        return $this->whereNotInString(...$params);
                }
        }
    }

    /**
     * 字段值在2值之间
     * @return QueryBuiler
     */
    public function whereBetween(): QueryBuiler {
        $params = func_get_args();
        switch (func_num_args()) {
            case 2:
                return $this->whereBetweenArray(...$params);
            case 3:
                return $this->whereBetweenString(...$params);
        }
    }
    
    /**
     * 字段值不在2值之间
     * @return QueryBuiler
     */
    public function whereNotBetween(): QueryBuiler {
        $params = func_get_args();
        switch (func_num_args()) {
            case 2:
                return $this->whereNotBetweenArray(...$params);
            case 3:
                return $this->whereNotBetweenString(...$params);
        }
    }

    /**
     * 查询字段
     * @return QueryBuiler
     */
    public function select(): QueryBuiler {
        $params = func_get_args();
        switch (func_num_args()) {
            case 1:
                switch (gettype(reset($params))) {
                    case 'array':
                        return $this->selectArray(...$params);
                    case 'string':
                        return $this->selectString(...$params);
                }
        }
    }

    /**
     * 更新字段
     * @return QueryBuiler
     */
    public function data(): QueryBuiler {
        $params = func_get_args();
        switch (func_num_args()) {
            case 1:
                switch (gettype(reset($params))) {
                    case 'array':
                        return $this->dataArray(...$params);
                    case 'string':
                        return $this->dataString(...$params);
                }
        }
    }

    /**
     * 设置数据表
     * @return QueryBuiler
     */
    public function from(): QueryBuiler {
        $params = func_get_args();
        switch (func_num_args()) {
            case 1:
                return $this->fromString(...$params);
        }
    }

    /**
     * 连接
     * @return QueryBuiler
     */
    public function join(): QueryBuiler {
        $params = func_get_args();
        switch (func_num_args()) {
            case 5:
                return $this->joinString(...$params);
        }
    }


    public function having() {
        
    }

    /**
     * 分组
     * @return QueryBuiler
     */
    public function group(): QueryBuiler {
        $params = func_get_args();
        switch (func_num_args()) {
            case 1:
                switch (gettype(reset($params))) {
                    case 'array':
                        return $this->groupArray(...$params);
                    case 'string':
                        return $this->groupString(...$params);
                }
        }
    }

    /**
     * 排序
     * @return QueryBuiler
     */
    public function order(): QueryBuiler {
        $params = func_get_args();
        switch (func_num_args()) {
            case 1:
                return $this->orderString(...$params);
            case 2:
                return $this->orderString(...$params);
        }
    }

    /**
     * 限制
     * @return QueryBuiler
     */
    public function limit(): QueryBuiler {
        $params = func_get_args();
        switch (func_num_args()) {
            case 1:
                return $this->limitTake(...$params);
            case 2:
                return $this->limitOffsetTake(...$params);
        }
    }

    public function getRow() {
        $this->sqlType = 'select';
        return $this->toSql();
    }
}
