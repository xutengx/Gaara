<?php

declare(strict_types = 1);
namespace Main\Core\Model;

use Main\Core\Model\QueryBuiler;
use Main\Core\DbConnection;

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
    use QueryBuiler\Index;
    use QueryBuiler\Union;
    use QueryBuiler\Prepare;

    use QueryBuiler\Execute;
    use QueryBuiler\Debug;
    use QueryBuiler\Aggregates;

    // 绑定的表名
    private $table;
    // 主键
    private $primaryKey;
    // 当前语句类别
    private $sqlType;
    // 数据库链接
    private $db;
    // 所属模型
    private $model;
    // 最近次执行的sql
    private $lastSql;
    private $select;
    private $data;
    private $from;
    private $where;
    private $join;
    private $group;
    private $having;
    private $order;
    private $limit;
    private $union = [];
    // 预期的查询2维数组的索引
    private $index = null;

    public function __construct(string $table, string $primaryKey, DbConnection $db, $model) {
        $this->table = $table;
        $this->primaryKey = $primaryKey;
        $this->db = $db;
        $this->model = $model;
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
                    case 'array':
                        return $this->whereArray(...$params);
                    default :
                        return $this->whereRaw('1');
                }
            case 2:
                return $this->whereValue((string)$params[0], '=', (string)$params[1]);
            case 3:
                return $this->whereValue((string)$params[0], (string)$params[1], (string)$params[2]);
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
                        return $this->whereNotInString((string)$params[0], (string)$params[1], (string)$params[2]);
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
                return $this->whereBetweenString((string)$params[0], (string)$params[1], (string)$params[2]);
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
                return $this->whereNotBetweenString((string)$params[0], (string)$params[1], (string)$params[2]);
        }
    }
    
    /**
     * where exists
     * @return QueryBuiler
     */
    public function whereExists(): QueryBuiler {
        $params = func_get_args();
        switch (func_num_args()) {
            case 1:
                switch (gettype($obj = reset($params))) {
                    case 'object':
                        if($obj instanceof \Closure){
                            return $this->whereExistsClosure($obj);
                        }elseif($obj instanceof QueryBuiler){
                            return $this->whereExistsQueryBuiler($obj);
                        }
                    case 'string':
                        return $this->whereExistsRaw($obj);
                }
        }
    }
    
    /**
     * where not exists
     * @return QueryBuiler
     */
    public function whereNotExists(): QueryBuiler {
        $params = func_get_args();
        switch (func_num_args()) {
            case 1:
                switch (gettype($obj = reset($params))) {
                    case 'object':
                        if($obj instanceof \Closure){
                            return $this->whereNotExistsClosure($obj);
                        }elseif($obj instanceof QueryBuiler){
                            return $this->whereNotExistsQueryBuiler($obj);
                        }
                    case 'string':
                        return $this->whereNotExistsRaw($obj);
                }
        }
    }
    
    /**
     * where 子查询
     * @return QueryBuiler
     */
    public function whereSubquery(): QueryBuiler {
        $params = func_get_args();
        switch (func_num_args()) {
            case 3:
                switch (gettype($obj = end($params))) {
                    case 'object':
                        if($obj instanceof \Closure){
                            return $this->whereSubqueryClosure(...$params);
                        }elseif($obj instanceof QueryBuiler){
                            return $this->whereSubqueryQueryBuiler(...$params);
                        }
                    case 'string':
                        return $this->whereSubqueryRaw(...$params);
                }
        }
    }

    /**
     * having条件
     * @return QueryBuiler
     */
    public function having(): QueryBuiler {
        $params = func_get_args();
        switch (func_num_args()) {
            case 1:
                switch (gettype($params[0])) {
                    case 'object':
                        return $this->andHaving(...$params);
                    case 'array':
                        return $this->havingArray(...$params);
                    default :
                        return $this->havingRaw('1');
                }
            case 2:
                return $this->havingValue((string)$params[0], '=', (string)$params[1]);
            case 3:
                return $this->havingValue((string)$params[0], (string)$params[1], (string)$params[2]);
        }
        return $this;
    }

    /**
     * having字段值在范围内
     * @return QueryBuiler
     */
    public function havingIn(): QueryBuiler {
        $params = func_get_args();
        switch (func_num_args()) {
            case 2:
                switch (gettype($params[1])) {
                    case 'array':
                        return $this->havingInArray(...$params);
                    default :
                        return $this->havingInString(...$params);
                }
        }
    }

    /**
     * having字段值不在范围内
     * @return QueryBuiler
     */
    public function havingNotIn(): QueryBuiler {
        $params = func_get_args();
        switch (func_num_args()) {
            case 2:
                switch (gettype($params[1])) {
                    case 'array':
                        return $this->havingNotInArray(...$params);
                    default :
                        return $this->havingNotInString((string)$params[0], (string)$params[1], (string)$params[2]);
                }
        }
    }

    /**
     * having字段值在2值之间
     * @return QueryBuiler
     */
    public function havingBetween(): QueryBuiler {
        $params = func_get_args();
        switch (func_num_args()) {
            case 2:
                return $this->havingBetweenArray(...$params);
            case 3:
                return $this->havingBetweenString((string)$params[0], (string)$params[1], (string)$params[2]);
        }
    }

    /**
     * having字段值不在2值之间
     * @return QueryBuiler
     */
    public function havingNotBetween(): QueryBuiler {
        $params = func_get_args();
        switch (func_num_args()) {
            case 2:
                return $this->havingNotBetweenArray(...$params);
            case 3:
                return $this->havingNotBetweenString((string)$params[0], (string)$params[1], (string)$params[2]);
        }
    }
    
    /**
     * 左链接
     * @return QueryBuiler
     */
    public function leftJoin(string $table, string $fieldOne, string $symbol, string $fieldTwo): QueryBuiler {
        return $this->joinString($table, $fieldOne, $symbol, $fieldTwo, 'left join');
    }
    
    /**
     * 右链接
     * @return QueryBuiler
     */
    public function rightJoin(string $table, string $fieldOne, string $symbol, string $fieldTwo): QueryBuiler {
        return $this->joinString($table, $fieldOne, $symbol, $fieldTwo, 'right join');
    }
    
    /**
     * 内链接
     * @return QueryBuiler
     */
    public function join(): QueryBuiler {
        $params = func_get_args();
        return $this->joinString(...$params);
    }
    
    /**
     * 自定义二维数组的键
     * @return QueryBuiler
     */
    public function index(): QueryBuiler {
        $params = func_get_args();
        switch (func_num_args()) {
            case 1:
                switch (gettype(reset($params))) {
                    case 'string':
                        return $this->indexString(...$params);
                    case 'object':
                        return $this->indexClosure(...$params);
                }
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
                return $this->dataArray(...$params);
            case 2:
                return $this->dataString(...$params);
        }
    }

    /**
     * from数据表
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
     * 设置数据表
     * @return QueryBuiler
     */
    public function table(string $table): QueryBuiler {
        $this->table = $table;
        return $this;
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

    /**
     * union 联合查询
     * @return QueryBuiler
     */
    public function union(): QueryBuiler {
        $params = func_get_args();
        switch (func_num_args()) {
            case 1:
                switch (gettype($obj = reset($params))) {
                    case 'object':
                        if($obj instanceof \Closure){
                            return $this->unionClosure($obj);
                        }elseif($obj instanceof QueryBuiler){
                            return $this->unionQueryBuiler($obj);
                        }
                    case 'string':
                        return $this->unionRaw(...$params);
                }
        }
    }

    /**
     * union all 联合查询
     * @return QueryBuiler
     */
    public function unionAll(): QueryBuiler {
        $params = func_get_args();
        switch (func_num_args()) {
            case 1:
                switch (gettype($obj = reset($params))) {
                    case 'object':
                        if($obj instanceof \Closure){
                            return $this->unionClosure($obj, 'union all');
                        }elseif($obj instanceof QueryBuiler){
                            return $this->unionQueryBuiler($obj, 'union all');
                        }
                    case 'string':
                        return $this->unionRaw($obj, 'union all');
                }
        }
    }
}