<?php
/*
 * @Descripttion: 
 * @version: 
 * @Author: ZZP
 * @Date: 2023-10-31 14:16:41
 * @LastEditors: ZZP
 * @LastEditTime: 2023-10-31 15:07:43
 */

namespace app\controller;

use support\Request;
use app\service\DeviceTemplateService;

class DeviceTemplateController
{
    private $service;

    /**
     * @description: 依赖注入DeviceTemplateService
     * @param {AdminDeviceTemplateService} $service
     * @return {*}
     */
    public function __construct(DeviceTemplateService $service)
    {
        $this->service = $service;
    }
    public function getDeviceTemplateList(Request $request)
    {
        return $this->service->getDeviceTemplateList($request);
    }
    public function getDeviceTemplateById(Request $request)
    {
        return $this->service->getDeviceTemplateById($request);
    }
}
