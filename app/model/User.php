<?php
/*
 * @Descripttion: 
 * @version: 
 * @Author: ZZP
 * @Date: 2023-10-20 19:01:25
 * @LastEditors: ZZP
 * @LastEditTime: 2023-11-12 20:32:03
 */

namespace app\model;

use think\Model;

/**
 * user 
 * @property integer $id 用户主键ID(主键)
 * @property string $name 用户名称
 * @property string $avatar 头像
 * @property string $email 用户邮箱账号
 * @property string $password 用户密码
 * @property string $phone 手机号码
 * @property integer $status 用户状态（1：正常，0：黑名单）
 * @property integer $authority 权限（0：普通用户，1：管理员）
 * @property string $createTime 注册时间
 * @property string $updateTime 更新时间
 */
class User extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $pk = 'id';

    public function scopeUser($query)
    {
        $query->field(['id', 'name', 'avatar', 'email', 'phone', 'status', 'authority', 'createTime', 'updateTime']);
    }

    public function authority()
    {
        return $this->hasOne(Authority::class, 'id','authority');
    }
}
    

