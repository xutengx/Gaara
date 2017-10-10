<?php

declare(strict_types = 1);
namespace Main\Core\Model\QueryBuiler;

use Main\Core\Model\QueryPrepare;

trait Prepare {

    /**
     * 按照select预执行sql
     * @return QueryPrepare
     */
    public function selectPrepare(): QueryPrepare{
        return $this->forPrepara('select');
    }
    
    /**
     * 按照insert预执行sql
     * @return QueryPrepare
     */
    public function insertPrepare(): QueryPrepare{
        return $this->forPrepara('insert');
    }
    
    /**
     * 按照update预执行sql
     * @return QueryPrepare
     */
    public function updatePrepare(): QueryPrepare{
        return $this->forPrepara('update');
    }

    /**
     * 按照delete预执行sql
     * @return QueryPrepare
     */
    public function deletePrepare(): QueryPrepare{
        return $this->forPrepara('delete');
    }

    /**
     * 按照replace预执行sql
     * @return QueryPrepare
     */
    public function replacePrepare(): QueryPrepare{
        return $this->forPrepara('replace');
    }
    
    /**
     * 预执行sql
     * @return QueryPrepare
     */
    private function forPrepara(string $type): QueryPrepare{
        $this->sqlType = $type;
        $sql = $this->toSql();
        $PDOStatement = $this->db->prepare($sql, $type);
        return new QueryPrepare($PDOStatement);
    }
}
