<?php
/*
 * @Descripttion: 
 * @version: 
 * @Author: ZZP
 * @Date: 2023-10-13 13:44:12
 * @LastEditors: ZZP
 * @LastEditTime: 2023-11-18 17:59:27
 */
namespace app\service;

use support\Cache;
use app\model\User;
use support\Request;
use Tinywan\Jwt\JwtToken;
use app\validate\UserValidate;
use Tinywan\Storage\Storage;
use Webman\Captcha\CaptchaBuilder;
use taoser\exception\ValidateException;

class UserService{

    /**
     * @Descripttion: 注册时发送邮箱验证码
     * @Author: ZZP
     * @param {Request} $request
     * @return {*}
     */    
    public function register(Request $request){
        $param = $request->all();
        try {
            validate(UserValidate::class)->scene('register')->check($param);
        } catch (ValidateException $e) {
            return response_json(ERROR, $e->getMessage());
        }
        try {
            if(User::where("email", $param['emailadd'])->find()){
                return response_json(ERROR, '系统已存在该用户，请重新输入邮箱！');
            }
            if($cacheData = Cache::get(str_replace(['@', ':'], ['_at_', '_'], $param['emailadd'] . ':register'))){
                if (!password_verify($param['password'], $cacheData['password'])) {
                    return response_json(ERROR, '系统已缓存该注册请求,如需使用该邮箱注册请等待十分钟分钟后重新注册,或使用刚才注册的邮箱密码进行注册！');
                }
            }
            if($param['confirmpassword'] != $param['password']){
                return response_json(ERROR, '确认密码错误！');
            }
            $registrationData = [
                'emailadd' => $param['emailadd'], 
                'password' => password_hash($param['password'], PASSWORD_DEFAULT), 
                'name'=>$param['name'], 'phone'=>$param['phone']
            ];
            $captcha = bin2hex(random_bytes(2)); //随机生成验证码
            Cache::set(str_replace(['@', ':'], ['_at_', '_'], $param['emailadd'] . ':register'), $registrationData, 1800); //用户注册信息存入Redis 保存30分钟
            Cache::set(str_replace(['@', ':'], ['_at_', '_'], $param['emailadd'] . ':captcha'), $captcha, 600); //验证码存入Redis 保存10分钟
            Cache::set(str_replace(['@', ':'], ['_at_', '_'], $param['emailadd'] . ':captchaCooling'), 1, 30); //验证码刷新冷却时间30秒
            return push_email_captcha($param['emailadd'], $captcha); //发送邮箱验证码
        } catch (\Throwable $th) {
            return response_json(ERROR, '注册失败,请重试。建议使用网易邮箱', [$th->getMessage()]);
        }
    }

    /**
     * @Descripttion: 注册场景刷新邮箱验证码
     * @Author: ZZP
     * @param {Request} $request
     * @return {*}
     */
    public function registerRefreshEmailCaptcha(Request $request)
    {
        $param = $request->all();
        try {
            validate(UserValidate::class)->scene('RefreshEmailCaptcha')->check($param);
        } catch (ValidateException $e) {
            return response_json(ERROR, $e->getMessage());
        }
        if (User::where('email', $param['emailadd'])->where('status', 1)->find()) {
            return response_json(ERROR, '您输入的邮箱账户已正常激活系统请勿再次注册!');
        }
        if (Cache::get(str_replace(['@', ':'], ['_at_', '_'], $param['emailadd'] . ':register'))) {
            if (Cache::get(str_replace(['@', ':'], ['_at_', '_'], $param['emailadd'] . ':captchaCooling'))) {
                return response_json(ERROR, '操作太频繁啦!,请等待30秒后重新发起请求!建议使用网易邮箱');
            }
            try {
                $captcha = bin2hex(random_bytes(2)); //随机生成验证码
                Cache::set(str_replace(['@', ':'], ['_at_', '_'], $param['emailadd'] . ':captcha'), $captcha, 600); //验证码存入Redis 保存10分钟
                Cache::set(str_replace(['@', ':'], ['_at_', '_'], $param['emailadd'] . ':captchaCooling'), 1, 30); //验证码刷新冷却时间30秒
                return push_email_captcha($param['emailadd'], $captcha); //发送邮箱验证码
            } catch (\Exception $th) {
                return response_json(ERROR, '刷新邮箱验证码失败,请重试!', [$th->getMessage()]);
            }
        } else {
            return response_json(ERROR, '该信息已过期,请返回重新注册 建议使用网易邮箱');
        }
    }

    /**
     * @Descripttion: 登录
     * @Author: ZZP
     * @param {Request} $request
     * @return {*}
     */
    public function login(Request $request){
        $param = $request->all();
        try {
            validate(UserValidate::class)->scene('login')->check($param);
        } catch (ValidateException $e) {
            return response_json(ERROR, $e->getMessage());
        }
        try {
            if(!$user = User::where("email", $param['email'])->find()){
                return response_json(ERROR, '该用户不存在');
            }
            if(!password_verify($param['password'], $user->password)){
                return response_json(ERROR, '密码错误请重试，或点击忘记密码进行修改！');
            }
            if($user->status == 0){
                return response_json(ERROR, '当前账号状态异常，请联系管理员！');
            }
            $jwt = [
                'id'  => $user->id,
                'email' => $user->email
            ];
            $token = JwtToken::generateToken($jwt);
            return response_json(SUCCESS, '登陆成功', $token);
        } catch (\Throwable $th) {
            return response_json(ERROR, '登录失败，请重试！', [$th->getMessage()]);
        }
    }


    /**
     * @Descripttion: 创建用户
     * @Author: ZZP
     * @param {Request} $request
     * @return {*}
     */
    public function CreateUser(Request $request){
        $param = $request->all();
        try {
            $create = new User();
            $create->startTrans(); //开启事务处理
            $registrationData = Cache::get(str_replace(['@', ':'], ['_at_', '_'], $param['emailadd'] . ':register'));
            $create->name = $registrationData['name'];
            $create->email = $registrationData['emailadd'];
            $create->password = $registrationData['password'];
            $create->phone = $registrationData['phone'];
            $create->avatar = '/image/avatar.jpg';
            $create->status = 1; //激活用户
            $create->authority = 0;//用户权限
            $create->save();
            $create->commit(); // 提交事务
            Cache::delete(str_replace(['@', ':'], ['_at_', '_'], $param['emailadd'] . ':register'));
            Cache::delete(str_replace(['@', ':'], ['_at_', '_'], $param['emailadd'] . ':captcha'));
            return response_json(SUCCESS, "注册成功");
        } catch (\Exception $th) {
            $create->rollback(); // 回滚事务
            return response_json(ERROR, "注册失败,请重试", [$th->getMessage()]);
        }
    }

    /**
     * @Descripttion: 获取用户信息
     * @Author: ZZP
     * @param {Request} $request
     * @return {*}
     */
    public function getUserByToken(Request $request){
        $uid = $request->uid;
        try {
            if(!$user = User::scope('User')->with('authority')->where('id', $uid)->find()){
                return response_json(ERROR, '当前id不存在');
            }
            return response_json(SUCCESS, '访问成功', [
                'user' => $user
            ]);
        } catch (\Throwable $th) {
            return response_json(ERROR, '获取用户信息失败',[$th->getMessage()]);
        }
    }

    /**
     * @Descripttion: 上传图片
     * @Author: ZZP
     * @param {Request} $request
     * @return {*}
     */
    public function uploadImage(Request $request){
        Storage::config();
        $res = Storage::uploadFile();
        return response_json(SUCCESS, '上传图片成功', [
            'url' => $res[0]['url'],
            'save_name' => $res[0]['save_name'],
        ]);
    }

    /**
     * @Descripttion: 修改信息
     * @Author: ZZP
     * @param {Request} $request
     * @return {*}
     */
    public function updateUserByToken(Request $request){
        $uid = $request->uid;
        $param = $request->all();
        try {
            validate(UserValidate::class)->scene('UpdateUserInfo')->check($param);
        } catch (ValidateException $e) {
            return response_json(ERROR, $e->getMessage());
        }
        try {
            $user = User::findOrFail($uid);
            $user->startTrans(); //开启事务处理
            $user->name = $param['name'];
            $user->phone = $param['phone'];
            $user->avatar = $param['avatar'];
            $user->save();
            $user->commit(); // 提交事务
            return response_json(SUCCESS, '修改用户信息成功');
        } catch (\Throwable $th) {
            $user->rollback(); // 回滚事务
            return response_json(ERROR, '修改用户信息失败',[$th->getMessage()]);
        }
    }
    
    /**
     * @Descripttion: 修改密码
     * @Author: ZZP
     * @param {Request} $request
     * @return {*}
     */
    public function updateUserPasswordByToken(Request $request){
        $uid = $request->uid;
        $param = $request->all();
        try {
            validate(UserValidate::class)->scene('UpdateUserPassword')->check($param);
        } catch (ValidateException $e) {
            return response_json(ERROR, $e->getMessage());
        }
        if($param['confirmpassword'] != $param['newpassword']){
            return response_json(ERROR, '确认密码错误！');
        }
        $user = User::findOrFail($uid);
        if (!password_verify($param['oldpassword'], $user->password)) {
            return response_json(ERROR, "当前密码输入不正确请重试！");
        }
        try {
            $user->password = password_hash($param['newpassword'], PASSWORD_DEFAULT);
            $user->save();
            return response_json(SUCCESS, '修改密码成功');
        } catch (\Throwable $th) {
            return response_json(ERROR, '修改密码失败',[$th->getMessage()]);
        }
    }

    /**
     * @Descripttion: 忘记密码
     * @Author: ZZP
     * @param {Request} $request
     * @return {*}
     */
    public function forgotPassword(Request $request){
        $param = $request->all();
        try {
            validate(UserValidate::class)->scene('forgotPassword')->check($param);
        } catch (ValidateException $e) {
            return response_json(ERROR, $e->getMessage());
        }
        try {
            if(!User::where("email", $param['emailadd'])->find()){
                return response_json(ERROR, '系统不存在该用户，请重新输入邮箱！');
            }
            if($param['confirmpassword'] != $param['password']){
                return response_json(ERROR, '确认密码错误！');
            }
            if($cacheData = Cache::get($param['emailadd'] . ':forgot')){
                if (!password_verify($param['password'], $cacheData['password'])) {
                    return response_json(ERROR, '系统已缓存该忘记密码请求,如需使用该邮箱重新设置密码请等待十分钟分钟后重新设置！');
                }
            }
            $registrationData = ['emailadd' => $param['emailadd'], 'password' => password_hash($param['password'], PASSWORD_DEFAULT)];
            $captcha = bin2hex(random_bytes(2)); //随机生成验证码
            Cache::set(str_replace(['@', ':'], ['_at_', '_'], $param['emailadd'] . ':forgot'), $registrationData, 1800); //用户忘记信息存入Redis 保存30分钟
            Cache::set(str_replace(['@', ':'], ['_at_', '_'], $param['emailadd'] . ':captcha'), $captcha, 600); //验证码存入Redis 保存10分钟
            Cache::set(str_replace(['@', ':'], ['_at_', '_'], $param['emailadd'] . ':captchaCooling'), 1, 30); //验证码刷新冷却时间30秒
            return push_email_captcha($param['emailadd'], $captcha); //发送邮箱验证码
        } catch (\Throwable $th) {
            return response_json(ERROR, '重置密码失败，请重试！', [$th->getMessage()]);
        }
    }

    /**
     * @Descripttion: 忘记密码场景刷新邮箱验证码
     * @Author: ZZP
     * @param {Request} $request
     * @return {*}
     */
    public function forgotRefreshEmailCaptcha(Request $request)
    {
        $param = $request->all();
        try {
            validate(UserValidate::class)->scene('RefreshEmailCaptcha')->check($param);
        } catch (ValidateException $e) {
            return response_json(ERROR, $e->getMessage());
        }
        if (Cache::get(str_replace(['@', ':'], ['_at_', '_'], $param['emailadd'] . ':forgot'))) {
            if (Cache::get(str_replace(['@', ':'], ['_at_', '_'], $param['emailadd'] . ':captchaCooling'))) {
                return response_json(ERROR, '操作太频繁啦!,请等待30秒后重新发起请求!');
            }
            try {
                $captcha = bin2hex(random_bytes(2)); //随机生成验证码
                Cache::set(str_replace(['@', ':'], ['_at_', '_'], $param['emailadd'] . ':captcha'), $captcha, 600); //验证码存入Redis 保存10分钟
                Cache::set(str_replace(['@', ':'], ['_at_', '_'], $param['emailadd'] . ':captchaCooling'), 1, 30); //验证码刷新冷却时间30秒
                return push_email_captcha($param['emailadd'], $captcha); //发送邮箱验证码
            } catch (\Exception $th) {
                return response_json(ERROR, '刷新邮箱验证码失败,请重试!', [$th->getMessage()]);
            }
        } else {
            return response_json(ERROR, '该信息已过期,请返回重新注册 建议使用网易邮箱');
        }
    }

    /**
     * @Descripttion: 重置密码
     * @Author: ZZP
     * @param {Request} $request
     * @return {*}
     */
    public function resetPassword(Request $request){
        $param = $request->all();
        try {
            $registrationData = Cache::get(str_replace(['@', ':'], ['_at_', '_'], $param['emailadd'] . ':forgot'));
            $user = User::where('email', $registrationData['emailadd'])->find();
            $user->startTrans(); //开启事务处理
            $user->password = $registrationData['password'];
            $user->save();
            $user->commit(); // 提交事务
            Cache::delete(str_replace(['@', ':'], ['_at_', '_'], $param['emailadd'] . ':forgot'));
            Cache::delete(str_replace(['@', ':'], ['_at_', '_'], $param['emailadd'] . ':captcha'));
            return response_json(SUCCESS, "重置密码成功");
        } catch (\Exception $th) {
            $user->rollback(); // 回滚事务
            return response_json(ERROR, "重置密码成功,请重试", [$th->getMessage()]);
        }
    }

    /**
     * @Descripttion: 输出图像验证码
     * @Author: ZZP
     * @param {Request} $request
     * @return {*}
     */
    public function captcha(Request $request)
    {
        // 初始化验证码类
        $builder = new CaptchaBuilder();
        // 生成验证码
        $builder->build();
        // 将验证码的值存储到session中
        $request->session()->set('captcha', strtolower($builder->getPhrase()));
        // 获得验证码图片二进制数据
        $img_content = $builder->get();
        // 输出验证码二进制数据
        return response($img_content, 200, ['Content-Type' => 'image/jpeg']);
    }

}
