<?php
/*
 * @Descripttion: 
 * @version: 
 * @Author: ZZP
 * @Date: 2023-10-10 12:24:56
 * @LastEditors: ZZP
 * @LastEditTime: 2023-11-13 15:28:18
 */

/**
 * This file is part of webman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link      http://www.workerman.net/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

use Webman\Route;

// 关闭自动路由功能
Route::disableDefaultRoute();

//接口不存在返回404界面
Route::fallback(function () {
    return redirect('/404');
});


// 用户接口
Route::group('/user', function () {
    // 用户注册发送邮箱验证码接口
    Route::any('/register', [app\controller\UserController::class, 'register'])->middleware([app\middleware\CheckCaptcha::class]);
    //用户注册保存信息接口
    Route::any('/CreateUser', [app\controller\UserController::class, 'CreateUser'])->middleware([app\middleware\CheckEmailCaptcha::class]);
    // 刷新用户注册邮箱验证码接口
    Route::any('/registerRefreshEmailCaptcha', [app\controller\UserController::class, 'registerRefreshEmailCaptcha']);
    // 输出验证码接口
    Route::any('/captcha', [app\controller\UserController::class, 'captcha']);
    // 判断验证码接口
    Route::any('/check', [app\controller\UserController::class, 'check']);
    // 登录接口
    Route::any('/login', [app\controller\UserController::class, 'login'])->middleware([app\middleware\CheckCaptcha::class]);
    // 忘记密码接口
    Route::any('/forgotPassword', [app\controller\UserController::class, 'forgotPassword'])->middleware([app\middleware\CheckCaptcha::class]);
    // 刷新用户忘记密码邮箱验证码接口
    Route::any('/forgotRefreshEmailCaptcha', [app\controller\UserController::class, 'forgotRefreshEmailCaptcha']);
    //用户重置密码接口
    Route::any('/resetPassword', [app\controller\UserController::class, 'resetPassword'])->middleware([app\middleware\CheckEmailCaptcha::class]);
    // 管理员注册
    Route::any('/CreateAdmin', [app\controller\AdminUserController::class, 'CreateAdmin'])->middleware([app\middleware\CheckCaptcha::class]);;

    // 用户Api接口（需要验证token）
    Route::group('/api', function () {
        // 获取用户信息
        Route::any('/getUserByToken', [app\controller\UserController::class, 'getUserByToken']);
        // 上传图片
        Route::any('/uploadImage', [app\controller\UserController::class, 'uploadImage']);
        // 修改用户信息
        Route::any('/updateUserByToken', [app\controller\UserController::class, 'updateUserByToken']);
        // 修改密码
        Route::any('/updateUserPasswordByToken', [app\controller\UserController::class, 'updateUserPasswordByToken']);
    })->middleware([app\middleware\CheckLogin::class]);

    // 用户设备接口（需要验证token）
    Route::group('/Device', function () {
        // 获取模板列表
        Route::any('/getDeviceTemplateList', [app\controller\DeviceTemplateController::class, 'getDeviceTemplateList']);
        // 获取模板列表
        Route::any('/getDeviceTemplateById', [app\controller\DeviceTemplateController::class, 'getDeviceTemplateById']);
        // 新增设备
        Route::any('/insertDevice', [app\controller\DeviceController::class, 'insertDevice']);
        // 修改设备
        Route::any('/updateDevice', [app\controller\DeviceController::class, 'updateDevice']);
        // 根据ID和token获取自己的设备
        Route::any('/getDeviceById', [app\controller\DeviceController::class, 'getDeviceById']);
        // 根据token获取自己的设备
        Route::any('/getDeviceListByToken', [app\controller\DeviceController::class, 'getDeviceListByToken']);
        // 根据token和id修改设备状态
        Route::any('/updateDeviceStatusByIdAndToken', [app\controller\DeviceController::class, 'updateDeviceStatusByIdAndToken']);
    })->middleware([app\middleware\CheckLogin::class]);
});

// 管理员接口（需要验证token）
Route::group('/admin/api', function () {

    // 管理员设置用户接口
    Route::group('/users', function () {
        // 根据ID获取用户信息
        Route::any('/adminGetUserById', [app\controller\AdminUserController::class, 'adminGetUserById']);
        // 根据用户ID修改信息
        Route::any('/adminUpdateUserById', [app\controller\AdminUserController::class, 'adminUpdateUserById']);
        // 根据id,name,email进行模糊查询用户信息
        Route::any('/adminLikeUserListByIdOrNameOrEmail', [app\controller\AdminUserController::class, 'adminLikeUserListByIdOrNameOrEmail']);
        // 创建用户
        Route::any('/adminCreateUser', [app\controller\AdminUserController::class, 'adminCreateUser']);
        // 根据id,name,email进行模糊查询管理员信息
        Route::any('/adminLikeAdminListByIdOrNameOrEmail', [app\controller\AdminUserController::class, 'adminLikeAdminListByIdOrNameOrEmail']);
        // 发送管理员注册邀请码
        Route::any('/createInvitationCode', [app\controller\AdminUserController::class, 'createInvitationCode']);
        // 发送管理员注册邀请码
        Route::any('/getInvitationCode', [app\controller\AdminUserController::class, 'getInvitationCode']);
    });

    //管理员设置设备模板接口
    Route::group('/DeviceTemplate', function () {
        // 添加设备模板
        Route::any('/insertDeviceTemplate', [app\controller\AdminDeviceTemplateController::class, 'insertDeviceTemplate']);
        // 获取设备模板列表
        Route::any('/getDeviceTemplateList', [app\controller\AdminDeviceTemplateController::class, 'getDeviceTemplateList']);
        // 根据ID获取设备信息
        Route::any('/getDeviceTemplateById', [app\controller\AdminDeviceTemplateController::class, 'getDeviceTemplateById']);
        // 根据ID修改设备信息
        Route::any('/updateDeviceTemplateById', [app\controller\AdminDeviceTemplateController::class, 'updateDeviceTemplateById']);
    });

    // 管理员设备接口（需要验证token）
    Route::group('/Device', function () {
        // 获取全部设备列表
        Route::any('/getDeviceList', [app\controller\AdminDeviceController::class, 'getDeviceList']);
        // 更改设备
        Route::any('/updateDeviceById', [app\controller\AdminDeviceController::class, 'updateDeviceById']);
        // 根据ID获取设备
        Route::any('/getDeviceById', [app\controller\AdminDeviceController::class, 'getDeviceById']);
    });
    Route::any('/queue', [app\controller\IndexController::class, 'queue']);
})->middleware([app\middleware\CheckAdminLogin::class]);


// 404界面
Route::any('/404', [app\controller\PageController::class, 'error404']);
// 登录界面
Route::any('/login', [app\controller\PageController::class, 'login']);
// 注册界面
Route::any('/register', [app\controller\PageController::class, 'register']);
// 管理员注册界面
Route::any('/adminRegister', [app\controller\PageController::class, 'adminRegister']);
// 注册界面
Route::any('/emailCaptchaForm', [app\controller\PageController::class, 'emailCaptchaForm']);
// 忘记密码页面
Route::any('/forget', [app\controller\PageController::class, 'forget']);
// 首页
Route::any('/index', [app\controller\PageController::class, 'index']);
// 首页
Route::any('/', [app\controller\PageController::class, 'index']);
// 用户信息界面
Route::any('/set/user/info', [app\controller\PageController::class, 'userInfo']);
// 修改密码界面
Route::any('/set/user/password', [app\controller\PageController::class, 'userPassword']);
// 用户列表界面（管理员）
Route::any('/user/user/list', [app\controller\PageController::class, 'userList']);
//更改用户信息（管理员）
Route::any('/user/user/userform', [app\controller\PageController::class, 'userForm']);
// 管理员列表界面（管理员）
Route::any('/user/administrators/list', [app\controller\PageController::class, 'adminList']);
// 设备模板列表界面（管理员）
Route::any('/device/deviceTemplatemanage', [app\controller\PageController::class, 'deviceTemplatemanage']);
// 设备模板界面
Route::any('/device/deviceTemplate', [app\controller\PageController::class, 'deviceTemplate']);
// 我的设备界面
Route::any('/device/mydevice', [app\controller\PageController::class, 'mydevice']);
// 管理员列表界面（管理员）
Route::any('/device/deviceTemplateForm', [app\controller\PageController::class, 'deviceTemplateForm']);
// 管理员列表界面（管理员）
Route::any('/device/myDeviceForm', [app\controller\PageController::class, 'myDeviceForm']);
// 管理员列表界面（管理员）
Route::any('/device/deviceManage', [app\controller\PageController::class, 'deviceManage']);
