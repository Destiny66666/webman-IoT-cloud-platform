<?php
/*
 * @Descripttion: 
 * @version: 
 * @Author: ZZP
 * @Date: 2023-10-29 15:04:11
 * @LastEditors: ZZP
 * @LastEditTime: 2023-10-29 16:45:55
 */

namespace app\controller;

use app\service\AdminDeviceTemplateService;
use support\Request;

class AdminDeviceTemplateController
{

    private $service;

    /**
     * @description: 依赖注入AdminDeviceTemplateService
     * @param {AdminDeviceTemplateService} $service
     * @return {*}
     */
    public function __construct(AdminDeviceTemplateService $service)
    {
        $this->service = $service;
    }

    public function insertDeviceTemplate(Request $request)
    {
        return $this->service->insertDeviceTemplate($request);
    }
    public function getDeviceTemplateList(Request $request)
    {
        return $this->service->getDeviceTemplateList($request);
    }
    public function getDeviceTemplateById(Request $request)
    {
        return $this->service->getDeviceTemplateById($request);
    }
    public function updateDeviceTemplateById(Request $request)
    {
        return $this->service->updateDeviceTemplateById($request);
    }
    
}
