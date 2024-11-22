<?php
/*
 * @Descripttion: 
 * @version: 
 * @Author: ZZP
 * @Date: 2023-10-13 16:43:31
 * @LastEditors: ZZP
 * @LastEditTime: 2023-11-07 16:50:06
 */
namespace app\validate;

use taoser\Validate;

class UserValidate extends Validate{

    protected $rule = [
        'id'=> 'require',
        'email' => 'require|email',
        'password' => 'require|max:18|min:6',
        'emailadd' => 'require|email',
        'confirmpassword' => 'require|max:18|min:6',
        'captcha' => 'require|alphaNum|max:5',
        'emailcaptcha' => 'require|alphaNum|max:4',
        'avatar' => 'require',
        'name' => 'require|max:18',
        'phone'=>'require|alphaNum|max:11',
        'oldpassword' => 'require|max:18|min:6',
        'newpassword' => 'require|max:18|min:6',
        'authority'   => 'require|number|between:0,1',
        'status'   => 'require|number|between:0,1',
        'InvitationCode' => 'require|alphaNum|max:10|min:6',
    ];

    protected $message  =   [
        'id.require' => 'ID为必填项',
        'email.require' => '邮箱为必填项',
        'email.email' => '邮箱格式错误',
        'password.require' => '密码为必填项',
        'password.max' => '密码最多不能超过18个字符',
        'password.min' => '密码最少要6个字符',
        'confirmpassword.require' => '确认密码为必填项',
        'confirmpassword.max' => '确认密码最多不能超过18个字符',
        'confirmpassword.min' => '确认密码最少要6个字符',
        'emailadd.require' => '邮箱为必填项',
        'emailadd.email' => '邮箱格式错误',
        'captcha.require' => '验证码为必填项',
        'captcha.alphaNum' => '验证码必须为入字母或数字',
        'captcha.max' => '验证码最多不能超过5个字符',
        'emailcaptcha.require' => '邮箱验证码为必填项',
        'emailcaptcha.alphaNum' => '邮箱验证码必须为入字母或数字',
        'emailcaptcha.max' => '邮箱验证码最多不能超过4个字符',
        'avatar.require' => '头像为必填项',
        'name.require' => '用户名为必填项',
        'name.max' => '用户名最多不超过18个字符',
        'phone.require' => '手机号为必填项',
        'phone.alphaNum' => '手机号必须为数字',
        'phone.max' => '验证码最多不能超过11个字符',
        'newpassword.require' => '新密码为必填项',
        'newpassword.max' => '新密码最多不能超过18个字符',
        'newpassword.min' => '新密码最少要6个字符',
        'oldpassword.require' => '旧密码为必填项',
        'oldpassword.max' => '旧密码最多不能超过18个字符',
        'oldpassword.min' => '旧密码最少要6个字符',
        'authority.require'   => '权限不能为空',
        'authority.number'   => '权限必须是数字',
        'authority.between'  => '权限只能是0，1',
        'status.require'   => '状态不能为空',
        'status.number'   => '状态必须是数字',
        'status.between'  => '状态只能是0，1',
        'InvitationCode.require' => '邀请码为必填项',
        'InvitationCode.alphaNum' => '邀请码必须为入字母或数字',
        'InvitationCode.max' => '邀请码最多不能超过10个字符',
        'InvitationCode.min' => '邀请码最少不低于6个字符',
    ];
    protected $scene = [
        'register' => ['emailadd', 'password', 'confirmpassword','name', 'phone'], //注册场景
        'login' => ['email', 'password'], //登录
        'RefreshEmailCaptcha' => ['emailadd'], //刷新邮箱验证码
        'captcha' => ['captcha'], //验证码验证
        'CreateUser' => ['emailcaptcha', 'emailadd'], //创建用户
        'UpdateUserInfo' => ['email', 'name', 'avatar', 'phone'],//修改基本信息
        'UpdateUserPassword'=>['oldpassword','newpassword' ,'confirmpassword'],// 修改用户密码
        'AdminUpdateUserInfo' => ['id', 'email', 'name', 'avatar', 'phone', 'authority'],//管理员修改用户信息
        'AdminCreateUser' => ['email', 'name', 'avatar', 'phone', 'authority', 'status'], //管理员创建用户
        'InvitationCode' => ['InvitationCode'], //验证码验证
        'forgotPassword' => ['emailadd', 'password', 'confirmpassword'] //忘记密码场景
    ];
    
}
