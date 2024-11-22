<?php
/*
 * @Descripttion: 
 * @version: 
 * @Author: ZZP
 * @Date: 2023-10-17 17:26:59
 * @LastEditors: ZZP
 * @LastEditTime: 2023-11-04 16:56:31
 */

namespace app\controller;

use support\Request;

class PageController
{
    public function error404(Request $request)
    {
        return view('404');
    }
    public function adminRegister(Request $request)
    {
        return view('adminregister');
    }
    public function login(Request $request)
    {
        return view('login');
    }
    public function register(Request $request)
    {
        return view('register');
    }
    public function forget(Request $request)
    {
        return view('forget');
    }
    public function index(Request $request)
    {
        return view('index');
    }
    public function emailCaptchaForm(Request $request)
    {
        return view('emailCaptchaForm');
    }
    public function userInfo(Request $request)
    {
        return view('set/user/info');
    }
    public function userPassword(Request $request)
    {
        return view('set/user/password');
    }
    public function userList(Request $request)
    {
        return view('user/user/list');
    }
    public function userForm(Request $request)
    {
        return view('user/user/userform');
    }
    public function adminList(Request $request)
    {
        return view('user/administrators/list');
    }
    public function deviceTemplatemanage(Request $request){
        return view('device/devicetemplatemanage');
    }
    public function deviceTemplate(Request $request){
        return view('device/devicetemplate');
    }
    public function mydevice(Request $request){
        return view('device/mydevice');
    }
    public function deviceTemplateForm(Request $request)
    {
        return view('device/devicetemplateform');
    }
    public function myDeviceForm(Request $request)
    {
        return view('device/mydeviceform');
    }
    public function deviceManage(Request $request)
    {
        return view('device/devicemanage');
    }

    
}
