<?php

declare(strict_types = 1);
namespace Main\Core\Model\QueryBuiler;

use Main\Core\Exception;

trait Execute {

    /**
     * 查询一行
     * @param array $pars
     * @return array
     */
    public function getRow(array $pars = []): array {
        $this->sqlType = 'select';
        $this->limitTake(1);
        $sql = $this->toSql($pars);
        return $this->db->getRow($sql, $pars);
    }
    
    /**
     * 查询多行
     * @param array $pars
     * @return array
     */
    public function getAll(array $pars = []): array {
        $this->sqlType = 'select';
        $sql = $this->toSql($pars);
        return $this->db->getAll($sql, $pars);
        
    }
    
    /**
     * 更新数据, 返回受影响的行数
     * @param array $pars
     * @return int
     * @throws Exception
     */
    public function update(array $pars = []): int {
        $this->sqlType = 'update';
        if (empty($this->data))
            throw new Exception('要执行UPDATE操作, 需要使用data方法设置更新的值');
        $sql = $this->toSql($pars);
        return $this->db->update($sql, $pars);
    }
    /**
     * 插入数据, 返回插入的主键
     * @param array $pars
     * @return int
     * @throws Exception
     */
    public function insertGetId(array $pars = []): int {
        $this->sqlType = 'insert';
        if (empty($this->data))
            throw new Exception('要执行INSERT操作, 需要使用data方法设置新增的值');
        $sql = $this->toSql($pars);
        return $this->db->insertGetId($sql, $pars);
    }
    
    /**
     * 插入数据
     * @param array $pars
     * @return bool
     * @throws Exception
     */
    public function insert(array $pars = []): bool {
        $this->sqlType = 'insert';
        if (empty($this->data))
            throw new Exception('要执行INSERT操作, 需要使用data方法设置新增的值');
        $sql = $this->toSql($pars);
        return $this->db->insert($sql, $pars);
    }
    
    /**
     * 删除数据, 返回受影响的行数
     * @param array $pars
     * @return int
     * @throws Exception
     */
    public function delete(array $pars = []): int {
        $this->sqlType = 'delete';
        if (empty($this->where))
            throw new Exception('执行 DELETE 操作并没有相应的 where 约束, 请确保操作正确, 使用where(1)将强制执行.');
        $sql = $this->toSql($pars);
        return $this->db->update($sql, $pars);
    }
    
    /**
     * 插入or更新数据, 返回受影响的行数
     * @param array $pars
     * @return int
     * @throws Exception
     */
    public function replace(array $pars = []): int {
        $this->sqlType = 'replace';
        if (empty($this->data))
            throw new Exception('要执行REPLACE操作, 需要使用data方法设置新增or修改的值');
        $sql = $this->toSql($pars);
        return $this->db->update($sql, $pars);
    }
    
}
