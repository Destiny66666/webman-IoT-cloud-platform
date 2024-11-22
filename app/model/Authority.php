<?php
/*
 * @Descripttion: 
 * @version: 
 * @Author: ZZP
 * @Date: 2023-10-19 22:21:26
 * @LastEditors: ZZP
 * @LastEditTime: 2023-10-20 10:13:45
 */

namespace app\model;

use think\Model;

/**
 * authority 
 * @property integer $id 权限ID(主键)
 * @property string $name 权限名称
 */
class Authority extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'authority';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $pk = 'id';


}
