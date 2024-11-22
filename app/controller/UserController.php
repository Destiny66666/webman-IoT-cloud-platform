<?php
/*
 * @Descripttion: 
 * @version: 
 * @Author: ZZP
 * @Date: 2023-10-11 16:50:17
 * @LastEditors: ZZP
 * @LastEditTime: 2023-10-29 15:47:42
 */
/*
 * @Descripttion: 
 * @version: 
 * @Author: ZZP
 * @Date: 2023-10-11 16:50:17
 * @LastEditors: ZZP
 * @LastEditTime: 2023-10-15 15:54:48
 */

namespace app\controller;

use app\model\User;
use app\service\UserService;
use Error;
use PHPMailer\PHPMailer\PHPMailer;
use support\Cache;
use support\Db;
use support\Request;

class UserController{   
    private $service;

    /**
     * @description: 依赖注入UserService
     * @param {UserService} $service
     * @return {*}
     */
    public function __construct(UserService $service){
        $this->service = $service;
    }
    
    /**
     * @Descripttion: 
     * @Author: ZZP
     * @msg: 
     * @param {Request} $request
     * @return {*}
     */    
    public function register(Request $request)
    {
        return $this->service->register($request);
    }
    public function login(Request $request){
        return $this->service->login($request);
    }

    public function CreateUser(Request $request){
        return $this->service->CreateUser($request);
    }
    /**
     * @description: 刷新邮箱验证码操作
     * @param {Request} $request
     * @return {*}
     */
    public function registerRefreshEmailCaptcha(Request $request)
    {
        return $this->service->registerRefreshEmailCaptcha($request);
    }
    /**
     * @description: 获取登录验证码
     * @param {Request} $request
     * @return {*}
     */
    public function captcha(Request $request)
    {
        return $this->service->captcha($request);
    }

    public function getUserByToken(Request $request){
        return $this->service->getUserByToken($request);
    }

    public function uploadImage(Request $request){
        return $this->service->uploadImage($request);
    }
    public function updateUserByToken(Request $request){
        return $this->service->updateUserByToken($request);
    }
    
    public function updateUserPasswordByToken(Request $request){
        return $this->service->updateUserPasswordByToken($request);
    }
    public function forgotPassword(Request $request){
        return $this->service->forgotPassword($request);
    }
    public function forgotRefreshEmailCaptcha(Request $request){
        return $this->service->forgotRefreshEmailCaptcha($request);
    }
    public function resetPassword(Request $request){
        return $this->service->resetPassword($request);
    }
    

        /**
     * 检查验证码
     */
    public function check(Request $request)
    {
        // 获取post请求中的captcha字段
        $captcha = $request->post('captcha');
        echo "\n";
        echo "获取的captcha：".$captcha;
        echo "\n";
        echo "session的captcha：".$request->session()->get('captcha');
        if (strtolower($captcha) !== $request->session()->get('captcha')) {
            return json(['code' => 400, 'msg' => '输入的验证码不正确']);
        }
        return json(['code' => 0, 'msg' => 'ok']);
    }
}
