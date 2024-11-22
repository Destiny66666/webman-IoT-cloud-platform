<?php
/*
 * @Descripttion: 
 * @version: 
 * @Author: ZZP
 * @Date: 2023-10-22 16:26:40
 * @LastEditors: ZZP
 * @LastEditTime: 2023-10-29 15:42:18
 */

namespace app\controller;

use app\service\AdminUserService;
use support\Request;

class AdminUserController
{
    private $service;
    /**
     * @description: 依赖注入UserService
     * @param {UserService} $service
     * @return {*}
     */
    public function __construct(AdminUserService $service)
    {
        $this->service = $service;
    }

    public function adminGetUserById(Request $request)
    {
        return $this->service->adminGetUserById($request);
    }

    public function adminUpdateUserById(Request $request)
    {
        return $this->service->adminUpdateUserById($request);
    }

    public function adminLikeUserListByIdOrNameOrEmail(Request $request)
    {
        return $this->service->adminLikeUserListByIdOrNameOrEmail($request);
    }

    public function adminCreateUser(Request $request)
    {
        return $this->service->adminCreateUser($request);
    }

    public function adminLikeAdminListByIdOrNameOrEmail(Request $request)
    {
        return $this->service->adminLikeAdminListByIdOrNameOrEmail($request);
    }
    public function createInvitationCode(Request $request)
    {
        return $this->service->createInvitationCode($request);
    }

    public function CreateAdmin(Request $request)
    {
        return $this->service->CreateAdmin($request);
    }

    public function getInvitationCode(Request $request)
    {
        return $this->service->getInvitationCode($request);
    }
}
