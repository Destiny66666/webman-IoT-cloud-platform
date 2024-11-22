<?php
/*
 * @Descripttion: 
 * @version: 
 * @Author: ZZP
 * @Date: 2023-10-31 18:47:43
 * @LastEditors: ZZP
 * @LastEditTime: 2023-11-08 14:32:37
 */
/*
 * @Descripttion: 
 * @version: 
 * @Author: ZZP
 * @Date: 2023-10-13 16:43:31
 * @LastEditors: ZZP
 * @LastEditTime: 2023-10-30 15:26:34
 */

namespace app\validate;

use taoser\Validate;

class DeviceValidate extends Validate
{

    protected $rule = [
        'id' => 'require',
        'name'  =>  'require|max:10',
        'room' =>  'require|max:10',
        'picture' => 'require',
        'status'   => 'require|number|between:0,1',
        'insertDeviceCount'   => 'require|number|between:1,100',
    ];

    // 定义信息
    protected $message  =   [
        'id.require' => 'ID不能为空',
        'name.require' => '设备名称不能为空',
        'name.max'     => '设备名称最多不能超过10个字符',
        'room.require' => '设备房间不能为空',
        'room.max'     => '设备房间最多不能超过10个字符',
        'picture.require' => '设备图片不能为空',
        'picture.require' => '设备图片不能为空',
        'status.require'   => '状态不能为空',
        'status.number'   => '状态必须是数字',
        'status.between'  => '状态只能是0，1',
        'insertDeviceCount.require'   => '新增数量不能为空',
        'insertDeviceCount.number'   => '新增数量必须是数字',
        'insertDeviceCount.between'  => '新增数量只能是1-100',
    ];

    //定义场景
    protected $scene = [
        'insertDevice'  =>  ['name', 'room', 'picture', 'insertDeviceCount'], // 添加设备场景
        'updateDevice' => ['id', 'name', 'room', 'picture'], // 更新设备场景
        'updateDeviceStatus'=>['id', 'status'], // 更新设备状态场景
    ];
}
