<?php
/*
 * @Descripttion: 
 * @version: 
 * @Author: ZZP
 * @Date: 2023-10-22 16:27:33
 * @LastEditors: ZZP
 * @LastEditTime: 2023-11-10 23:54:57
 */

namespace app\service;

use Throwable;
use support\Request;
use app\model\Devicetemplate;
use taoser\exception\ValidateException;
use app\validate\DeviceTemplateValidate;

class AdminDeviceTemplateService
{

    /**
     * @Descripttion: 新增设备模板
     * @Author: ZZP
     * @param {Request} $request
     * @return {*}
     */
    public function insertDeviceTemplate(Request $request){
        $param = $request->all();
        try {
            validate(DeviceTemplateValidate::class)->scene('insertDeviceTemplate')->check($param);
        } catch (ValidateException $e) {
            return response_json(ERROR, $e->getMessage());
        }
        try {
            $deviceTemplate = new Devicetemplate();
            $deviceTemplate->startTrans(); //开启事务处理
            $deviceTemplate->name = $param['name'];
            $deviceTemplate->picture = $param['picture'];
            $deviceTemplate->introduction = $param['introduction'];
            $deviceTemplate->status = $param['status'];
            $deviceTemplate->save();
            $deviceTemplate->commit();
            return response_json(SUCCESS,'设备模板添加成功');
        } catch (Throwable $th) {
            $deviceTemplate->rollback(); // 回滚事务
            return response_json(ERROR,'设备模板添加失败');
        }
    }


    /**
     * @Descripttion: 获取模板列表
     * @Author: ZZP
     * @param {Request} $request
     * @return {*}
     */
    public function getDeviceTemplateList(Request $request){
        $param = $request->all();
        try {
            $page = !empty($param['page']) ? $param['page'] : 1;
            $limit = !empty($param['limit']) ? $param['limit'] : 10;
            $name = $param['name'] ?? '';
            $introduction = $param['introduction'] ?? '';
            $count = Devicetemplate::whereLike('name', '%' . $name . '%')->whereLike('introduction', '%' . $introduction . '%')->count();
            $list = Devicetemplate::whereLike('name', '%' . $name . '%')->whereLike('introduction', '%' . $introduction . '%')->page($page, $limit)->select();
            return response_json_count(SUCCESS, '获取模板列表成功', $list, $count);
        } catch (\Throwable $th) {
            return response_json(ERROR,'获取模板列表失败');
        }
    }

    /**
     * @Descripttion: 根据ID获取设备模板信息
     * @Author: ZZP
     * @param {Request} $request
     * @return {*}
     */
    public function getDeviceTemplateById(Request $request){
        $param = $request->all();
        try {
            if(!$template = Devicetemplate::where('id', $param['id'])->find()){
                return response_json(ERROR, '当前id不存在');
            }
            return response_json(SUCCESS, '获取模板信息成功', [
                'template' => $template
            ]);
        } catch (\Throwable $th) {
            return response_json(ERROR, '获取模板信息失败');
        }
    }

    /**
     * @Descripttion: 修改设备模板信息
     * @Author: ZZP
     * @param {Request} $request
     * @return {*}
     */
    public function updateDeviceTemplateById(Request $request){
        $param = $request->all();
        try {
            validate(DeviceTemplateValidate::class)->scene('updateDeviceTemplate')->check($param);
        } catch (ValidateException $e) {
            return response_json(ERROR, $e->getMessage());
        }
        try {
            $deviceTemplate = Devicetemplate::findOrFail($param['id']);
            $deviceTemplate->startTrans(); //开启事务处理
            $deviceTemplate->name = $param['name'];
            $deviceTemplate->picture = $param['picture'];
            $deviceTemplate->introduction = $param['introduction'];
            $deviceTemplate->status = $param['status'];
            $deviceTemplate->save();
            $deviceTemplate->commit();
            return response_json(SUCCESS,'设备模板修改成功');
        } catch (\Throwable $th) {
            $deviceTemplate->rollback(); // 回滚事务
            return response_json(ERROR,'设备模板修改失败');
        }
    }

}
