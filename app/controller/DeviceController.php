<?php
/*
 * @Descripttion: 
 * @version: 
 * @Author: ZZP
 * @Date: 2023-10-31 19:07:16
 * @LastEditors: ZZP
 * @LastEditTime: 2023-11-01 15:40:21
 */

namespace app\controller;

use app\service\DeviceService;
use support\Request;

class DeviceController
{
    private $service;

    public function __construct(DeviceService $service)
    {
        $this->service = $service;
    }
    public function insertDevice(Request $request)
    {
        return $this->service->insertDevice($request);
    }
    public function updateDevice(Request $request)
    {
        return $this->service->updateDevice($request);
    }
    public function getDeviceById(Request $request)
    {
        return $this->service->getDeviceById($request);
    }
    public function getDeviceListByToken(Request $request)
    {
        return $this->service->getDeviceListByToken($request);
    }
    public function updateDeviceStatusByIdAndToken(Request $request)
    {
        return $this->service->updateDeviceStatusByIdAndToken($request);
    }
    
}
