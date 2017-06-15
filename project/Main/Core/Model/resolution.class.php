<?php
namespace Main\Core\Model;
/**
 * 由 \Main\Core\Model->field 分析返回相关字段信息
 */
final class resolution{
    // 自动新增时间, 尝试值
    private $created_time_array = [
        'created_time',
        'createdTime',
        'create_time',
        'createTime',
        'created_at',
        'createAt',
        'createdAt'
    ];
    // 自动更新时间, 尝试值
    private $updated_time_array = [
        'updated_time',
        'updatedTime',
        'update_time',
        'updateTime',
        'updated_at',
        'updateAt',
        'updatedAt',
        'modified_time',
        'modifiedTime',
        'modify_time',
        'modifyTime',
        'modified_at',
        'modifyAt',
        'modifyiedAt',
    ];
    // 自动新增/更新时间, 的类型尝试值
    private $time_type_array = [
        'datetime',
        'int',
        'bigint',
        'timestamp'
    ];
    // 主键
    private $key = null;
    // 新增时间
    private $created_time = null;
    // 更改时间
    private $updated_time = null;
    // 新增时间类型
    private $created_time_type = null;
    // 更改时间类型
    private $updated_time_type = null;
    
    public function getKey(array $field){
        foreach ($field as $v) {
            if ($v['Extra'] == 'auto_increment') {
                $this->key = $v['Field'];
                continue;
            }
            if(in_array($v['Field'], $this->created_time_array, true) && in_array($v['Type'], $this->time_type_array, true)){
                $this->created_time = $v['Field'];
                $this->created_time_type = $v['Type'];
                continue;
            }
            if(in_array($v['Field'], $this->updated_time_array, true) && in_array($v['Type'], $this->time_type_array, true)){
                $this->updated_time = $v['Field'];
                $this->updated_time_type = $v['Type'];
                continue;
            }
        }
        return $this->returnData();
    }
    
    // 返回分析结果
    private function returnData(){
        return [
            $this->key,
            $this->created_time,
            $this->created_time_type,
            $this->updated_time,
            $this->updated_time_type,
        ];
    }
}