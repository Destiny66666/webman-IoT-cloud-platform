<?php

namespace app\model;

use think\Model;

/**
 * devicetemplate 
 * @property integer $id 模板id(主键)
 * @property string $name 模板名称
 * @property string $picture 模板图片
 * @property string $introduction 模板介绍
 * @property integer $status 状态（1：正常，0；禁用）
 * @property string $createTime 模板创建时间
 * @property string $updateTime 模板更新时间
 */
class Devicetemplate extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'devicetemplate';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $pk = 'id';

    
}
