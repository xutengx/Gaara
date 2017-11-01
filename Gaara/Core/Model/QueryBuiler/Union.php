<?php

declare(strict_types = 1);
namespace Gaara\Core\Model\QueryBuiler;

use Closure;
use Gaara\Core\Model\QueryBuiler;

trait Union {

    /**
     * union一个完整的sql
     * @param string $sql
     * @param string $type union|union all
     * @return QueryBuiler
     */
    public function unionRaw(string $sql, string $type = 'union'): QueryBuiler {
        return $this->unionPush($sql, $type);
    }
    
    /**
     * union一个QueryBuiler对象
     * @param QueryBuiler $queryBuiler
     * @param string $type union|union all
     * @return QueryBuiler
     */
    public function unionQueryBuiler(QueryBuiler $queryBuiler, string $type = 'union'): QueryBuiler {
        $sql = $queryBuiler->getAllToSql();
        return $this->unionPush($sql, $type);
    }
    
    /**
     * union一个闭包
     * @param Closure $callback
     * @param string $type union|union all
     * @return QueryBuiler
     */
    public function unionClosure(Closure $callback, string $type = 'union'): QueryBuiler  {
        $res = $callback($queryBuiler = $this->getSelf());
        // 调用方未调用return
        if (is_null($res)) {
            $sql = $queryBuiler->getAllToSql();
        }
        // 调用方未调用toSql
        elseif ($res instanceof QueryBuiler) {
            $sql = $res->getAllToSql();
        }
        // 调用正常
        else
            $sql = $res;
        return $this->unionPush($sql, $type);
    }

    /**
     * 要联合的sql加入union, 返回当前对象
     * @param string $sql
     * @param string $type union|union all
     * @return QueryBuiler
     */
    private function unionPush(string $sql, string $type): QueryBuiler {
        $this->union[$type][] = $sql;
        return $this;
    }
}
