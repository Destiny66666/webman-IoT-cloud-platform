<?php
/*
 * @Descripttion: 
 * @version: 
 * @Author: ZZP
 * @Date: 2023-10-31 20:08:08
 * @LastEditors: ZZP
 * @LastEditTime: 2023-11-08 22:58:23
 */

namespace app\controller;

use app\service\AdminDeviceService;
use support\Request;

class AdminDeviceController
{
    private $service;

    public function __construct(AdminDeviceService $service)
    {
        $this->service = $service;
    }
    public function getDeviceById(Request $request){
        return $this->service->getDeviceById($request);
    }
    public function updateDeviceById(Request $request){
        return $this->service->updateDeviceById($request);
    }
    public function getDeviceList(Request $request){
        return $this->service->getDeviceList($request);
    }

}
