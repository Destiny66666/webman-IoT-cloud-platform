<?php
/*
 * @Descripttion: 
 * @version: 
 * @Author: ZZP
 * @Date: 2023-11-01 15:24:22
 * @LastEditors: ZZP
 * @LastEditTime: 2023-11-08 22:58:01
 */

namespace app\model;

use think\Model;
use app\model\User;

/**
 * device 
 * @property integer $id 设备ID(主键)
 * @property string $name 设备名称
 * @property string $room 设备房间
 * @property string $picture 设备图片
 * @property integer $uid 用户ID
 * @property integer $status 设备状态（1：开启， 0：关闭）
 * @property string $createTime 创建时间
 * @property string $updateTime 修改时间
 */
class Device extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'device';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $pk = 'id';

    public function scopeDevice($query)
    {
        $query->field(['d.id', 'd.name', 'd.room', 'd.picture', 'd.uid', 'd.status', 'd.createTime', 'd.updateTime']);
    }

    public function uid()
    {
        return $this->hasOne(User::class, 'id','uid')->field(['id', 'name', 'email']);
    }
}
