<?php

declare(strict_types = 1);
namespace Gaara\Core\Model;

use PDOStatement;
/**
 * 参数绑定, 重复调用
 */
class QueryPrepare {
    
    private $PDOStatement;
    
    public function __construct(PDOStatement $PDOStatement) {
        $this->PDOStatement = $PDOStatement;
    }
    
    /**
     * 查询一行
     * @param array $pars
     * @return array
     */
    public function getRow(array $pars = []): array {
        $this->PDOStatement->execute($pars);
        $re = $this->PDOStatement->fetch(\PDO::FETCH_ASSOC) ?? [];
        return $re ? $re : [];
    }
    
    /**
     * 查询多行
     * @param array $pars
     * @return array
     */
    public function getAll(array $pars = []): array {
        $this->PDOStatement->execute($pars);
        return $this->PDOStatement->fetchall(\PDO::FETCH_ASSOC) ?? [];
    }
    
    /**
     * 插入
     * @param array $pars
     * @return int 影响的行数
     */
    public function insert(array $pars = []): int {
        $this->PDOStatement->execute($pars);
        return $this->PDOStatement->rowCount();
    }
    
    /**
     * 更新
     * @param array $pars
     * @return int 影响的行数
     */
    public function update(array $pars = []): int {
        $this->PDOStatement->execute($pars);
        return $this->PDOStatement->rowCount();
    }
    
    /**
     * 删除
     * @param array $pars
     * @return int 影响的行数
     */
    public function delete(array $pars = []): int {
        return $this->update($pars);
    }
    
    /**
     * 插入or更新
     * @param array $pars
     * @return int
     */
    public function replace(array $pars = []): int {
        return $this->update($pars);
    }
}