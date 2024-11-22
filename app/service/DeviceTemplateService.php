<?php
/*
 * @Descripttion: 
 * @version: 
 * @Author: ZZP
 * @Date: 2023-10-22 16:27:33
 * @LastEditors: ZZP
 * @LastEditTime: 2023-11-08 22:58:21
 */

namespace app\service;

use support\Request;
use app\model\Devicetemplate;
use app\validate\DeviceTemplateValidate;
use taoser\exception\ValidateException;

class DeviceTemplateService
{

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
            $count = Devicetemplate::whereLike('name', '%' . $name . '%')->whereLike('introduction', '%' . $introduction . '%')->where('status', 1)->count();
            $list = Devicetemplate::whereLike('name', '%' . $name . '%')->whereLike('introduction', '%' . $introduction . '%')->where('status', 1)->page($page, $limit)->select();
            return response_json_count(SUCCESS, '获取模板列表成功', $list, $count);
        } catch (\Throwable $th) {
            return response_json(ERROR,'获取模板列表失败');
        }
    }

    /**
     * @Descripttion: 
     * @Author: ZZP
     * @param {Request} $request
     * @return {*}
     */
    public function getDeviceTemplateById(Request $request){
        $param = $request->all();
        try {
            if(!$template = Devicetemplate::where('id', $param['id'])->where('status', 1)->find()){
                return response_json(ERROR, '当前id不存在');
            }
            return response_json(SUCCESS, '获取模板信息成功', [
                'template' => $template
            ]);
        } catch (\Throwable $th) {
            return response_json(ERROR, '获取模板信息失败');
        }
    }


}
