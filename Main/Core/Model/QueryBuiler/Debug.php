<?php

declare(strict_types = 1);
namespace Main\Core\Model\QueryBuiler;

use ErrorException as Exception;

trait Debug {
    /**
     * 查询一行
     * @param array $pars
     * @return string
     */
    public function getRowToSql(array $pars = []): string {
        $this->sqlType = 'select';
        $this->limitTake(1);
        $sql = $this->toSql($pars);
        return $this->lastSql;
    }
    
    /**
     * 查询多行
     * @param array $pars
     * @return string
     */
    public function getAllToSql(array $pars = []): string {
        $this->sqlType = 'select';
        $sql = $this->toSql($pars);
        return $this->lastSql;
        
    }
    
    /**
     * 更新数据, 返回受影响的行数
     * @param array $pars
     * @return string
     * @throws Exception
     */
    public function updateToSql(array $pars = []): string {
        $this->sqlType = 'update';
        if (empty($this->data))
            throw new Exception('要执行UPDATE操作, 需要使用data方法设置更新的值');
        $sql = $this->toSql($pars);
        return $this->lastSql;
    }
    /**
     * 插入数据, 返回插入的主键
     * @param array $pars
     * @return string
     * @throws Exception
     */
    public function insertGetIdToSql(array $pars = []): string {
        $this->sqlType = 'insert';
        if (empty($this->data))
            throw new Exception('要执行INSERT操作, 需要使用data方法设置新增的值');
        $sql = $this->toSql($pars);
        return $this->lastSql;
    }
    
    /**
     * 插入数据
     * @param array $pars
     * @return string
     * @throws Exception
     */
    public function insertToSql(array $pars = []): string {
        $this->sqlType = 'insert';
        if (empty($this->data))
            throw new Exception('要执行INSERT操作, 需要使用data方法设置新增的值');
        $sql = $this->toSql($pars);
        return $this->lastSql;
    }
    
    /**
     * 删除数据, 返回受影响的行数
     * @param array $pars
     * @return string
     * @throws Exception
     */
    public function deleteToSql(array $pars = []): string {
        $this->sqlType = 'delete';
        if (empty($this->data))
            throw new Exception('执行 DELETE 操作并没有相应的 where 约束, 请确保操作正确, 使用where(1)将强制执行.');
        $sql = $this->toSql($pars);
        return $this->lastSql;
    }
    
    /**
     * 插入or更新数据, 返回受影响的行数
     * @param array $pars
     * @return string
     * @throws Exception
     */
    public function replaceToSql(array $pars = []): string {
        $this->sqlType = 'replace';
        if (empty($this->data))
            throw new Exception('要执行REPLACE操作, 需要使用data方法设置新增or修改的值');
        $sql = $this->toSql($pars);
        return $this->lastSql;
    }
    
   
}
