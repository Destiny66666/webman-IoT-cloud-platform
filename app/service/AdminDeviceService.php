<?php
/*
 * @Descripttion: 
 * @version: 
 * @Author: ZZP
 * @Date: 2023-10-31 20:03:26
 * @LastEditors: ZZP
 * @LastEditTime: 2023-11-10 23:54:04
 */
/*
 * @Descripttion: 
 * @version: 
 * @Author: ZZP
 * @Date: 2023-10-31 18:40:55
 * @LastEditors: ZZP
 * @LastEditTime: 2023-10-31 19:48:10
 */
namespace app\service;

use Throwable;
use support\Request;
use app\model\Device;
use app\validate\DeviceValidate;
use taoser\exception\ValidateException;

class AdminDeviceService
{
    /**
     * @Descripttion: 根据ID获取设备信息
     * @Author: ZZP
     * @param {Request} $request
     * @return {*}
     */
    public function getDeviceById(Request $request){
        $param = $request->all();
        try {
            $device = Device::with('uid')->findOrFail($param['id']);
            return response_json(SUCCESS,'设备信息获取成功',['device' => $device]);
        } catch (Throwable $th) {
            return response_json(ERROR, '设备信息获取失败', $th->getMessage());
        }
    }

    /**
     * @Descripttion: 根据ID修改设备信息
     * @Author: ZZP
     * @param {Request} $request
     * @return {*}
     */
    public function updateDeviceById(Request $request){
        $param = $request->all();
        try {
            validate(DeviceValidate::class)->scene('updateDevice')->check($param);
        } catch (ValidateException $e) {
            return response_json(ERROR, $e->getMessage());
        }
        try {
            if(!$device = Device::where('id', $param['id'])->find()) return response_json(ERROR, '当前ID不存在');
            $device->startTrans();
            $device->name = $param['name'];
            $device->room = $param['room'];
            $device->picture = $param['picture'];
            $device->save();
            $device->commit();
            return response_json(SUCCESS, '设备信息修改成功');
        } catch (\Throwable $th) {
            $device->rollback();
            return response_json(ERROR, '设备信息修改失败', $th->getMessage());
        }
    }

    /**
     * @Descripttion: 获取全部设备列表
     * @Author: ZZP
     * @param {Request} $request
     * @return {*}
     */
    public function getDeviceList(Request $request){
        $param = $request->all();
        try {
            $page = !empty($param['page']) ? $param['page'] : 1;
            $limit = !empty($param['limit']) ? $param['limit'] : 10;
            $name = $param['name'] ?? '';
            $email = $param['email'] ?? '';
            $deviceList = Device::alias('d')->scope('Device')->with('uid')->join('user u','u.id = d.uid')
            ->whereLike('d.name', '%' . $name . '%')->whereLike('u.email', '%' . $email . '%')->page($page, $limit)->select();
            $count = Device::alias('d')->scope('Device')->with('uid')->join('user u','u.id = d.uid')
            ->whereLike('d.name', '%' . $name . '%')->whereLike('u.email', '%' . $email . '%')->count();
            if ($deviceList->isEmpty()) {
                return response_json(ERROR, '没有此条件的数据');
            }
            return response_json_count(SUCCESS, '获取设备列表成功', $deviceList, $count);
        } catch (\Throwable $th) {
            return response_json(ERROR, '获取信息列表失败', $th->getMessage());
        }
    }
}
