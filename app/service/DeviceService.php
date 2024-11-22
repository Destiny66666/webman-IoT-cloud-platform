<?php
/*
 * @Descripttion: 
 * @version: 
 * @Author: ZZP
 * @Date: 2023-10-31 18:40:55
 * @LastEditors: ZZP
 * @LastEditTime: 2023-11-13 16:32:16
 */
namespace app\service;

use Throwable;
use support\Request;
use app\model\Device;
use app\validate\DeviceValidate;
use taoser\exception\ValidateException;

class DeviceService
{
    /**
     * @Descripttion: 根据ID获取设备信息
     * @Author: ZZP
     * @param {Request} $request
     * @return {*}
     */
    public function getDeviceById(Request $request){
        $param = $request->all();
        $uid = $request->uid;
        try {
            $device = Device::with('uid')->findOrFail($param['id']);
            if($device->uid != $uid) return response_json(ERROR, '此设备不是你的');
            return response_json(SUCCESS,'设备信息获取成功',['device' => $device] );
        } catch (Throwable $th) {
            return response_json(ERROR, '设备信息获取失败', $th->getMessage());
        }
    }

    /**
     * @Descripttion: 添加设备
     * @Author: ZZP
     * @param {Request} $request
     * @return {*}
     */
    public function insertDevice(Request $request){
        $param = $request->all();
        $uid = $request->uid;
        try {
            validate(DeviceValidate::class)->scene('insertDevice')->check($param);
        } catch (ValidateException $e) {
            return response_json(ERROR, $e->getMessage());
        }
        try {
            for ($i=1; $i <= $param['insertDeviceCount']; $i++) { 
                $device = new Device();
                $device->name = $param['name'];
                $device->room = $param['room'];
                $device->picture = $param['picture'];
                $device->uid = $uid;
                $device->status = 0;
                $device->save();
            }
            return response_json(SUCCESS, '添加设备信息成功');
        } catch (\Throwable $th) {
            return response_json(ERROR, '添加设备信息失败',$th->getMessage());
        }
    }
    /**
     * @Descripttion: 根据ID修改设备信息
     * @Author: ZZP
     * @param {Request} $request
     * @return {*}
     */
    public function updateDevice(Request $request){
        $param = $request->all();
        $uid = $request->uid;
        try {
            validate(DeviceValidate::class)->scene('updateDevice')->check($param);
        } catch (ValidateException $e) {
            return response_json(ERROR, $e->getMessage());
        }
        try {
            if(!$device = Device::where('id', $param['id'])->find()) return response_json(ERROR, '当前ID不存在');
            if($device->uid != $uid) return response_json(ERROR, '此设备不是你的');
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
     * @Descripttion: 根据token获取自己的设备列表
     * @Author: ZZP
     * @param {Request} $request
     * @return {*}
     */
    public function getDeviceListByToken(Request $request){
        $uid = $request->uid;
        $param = $request->all();
        try {
            $page = !empty($param['page']) ? $param['page'] : 1;
            $limit = !empty($param['limit']) ? $param['limit'] : 10;
            $name = $param['name'] ?? '';
            $room = $param['room'] ?? '';
            $count = Device::where('uid', $uid)->whereLike('name', '%' . $name . '%')
            ->whereLike('room', '%' . $room . '%')->count();
            $deviceList = Device::where('uid', $uid)->with('uid')->whereLike('name', '%' . $name . '%')
            ->whereLike('room', '%' . $room . '%')->page($page, $limit)->select();
            
            return response_json_count(SUCCESS, '获取设备列表成功', $deviceList, $count);
        } catch (\Throwable $th) {
            return response_json(ERROR, '获取信息列表失败', $th->getMessage());
        }
    }

    /**
     * @Descripttion: 更改设备状态
     * @Author: ZZP
     * @param {Request} $request
     * @return {*}
     */
    public function updateDeviceStatusByIdAndToken(Request $request){
        $uid = $request->uid;
        $param = $request->all();
        try {
            validate(DeviceValidate::class)->scene('updateDeviceStatus')->check($param);
        } catch (ValidateException $e) {
            return response_json(ERROR, $e->getMessage());
        }
        try {
            if(!$device = Device::where('id', $param['id'])->find()) return response_json(ERROR, '当前ID不存在');
            if($device->uid != $uid) return response_json(ERROR, '此设备不是你的');
            $device->status = $param['status'];
            $device->save();
            return response_json(SUCCESS, '设备转换成功');
        } catch (\Throwable $th) {
            return response_json(ERROR, '设备转换失败', $th->getMessage());
        }
    }

}
