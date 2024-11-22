<?php
/*
 * @Descripttion: 
 * @version: 
 * @Author: ZZP
 * @Date: 2023-10-13 16:43:31
 * @LastEditors: ZZP
 * @LastEditTime: 2023-11-09 17:48:05
 */

namespace app\validate;

use taoser\Validate;

class DeviceTemplateValidate extends Validate
{
    // 定义规则
    protected $rule = [
        'id' => 'require',
        'name'  =>  'require|max:10',
        'introduction' =>  'require|max:20',
        'picture' => 'require',
        'status'   => 'require|number|between:0,1',
    ];

    // 定义信息
    protected $message  =   [
        'id.require' => 'ID不能为空',
        'name.require' => '模板名称不能为空',
        'name.max'     => '模板名称最多不能超过10个字符',
        'introduction.require' => '模板介绍不能为空',
        'introduction.max'     => '模板名称最多不能超过20个字符',
        'picture.require' => '模板图片不能为空',
        'status.require'   => '状态不能为空',
        'status.number'   => '状态必须是数字',
        'status.between'  => '状态只能是0，1',

    ];

    //定义场景
    protected $scene = [
        'insertDeviceTemplate'  =>  ['name', 'picture', 'introduction', 'status'], // 添加模板场景
        'updateDeviceTemplate' => ['id', 'name', 'picture', 'introduction', 'status'], // 更新模板场景
    ];
}
