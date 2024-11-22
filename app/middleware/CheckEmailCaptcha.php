<?php
/*
 * @Descripttion: 
 * @version: 
 * @Author: ZZP
 * @Date: 2023-10-26 19:26:04
 * @LastEditors: ZZP
 * @LastEditTime: 2023-10-29 13:41:03
 */
namespace app\middleware;

use support\Cache;
use Webman\Http\Request;
use Webman\Http\Response;
use app\validate\UserValidate;
use Webman\MiddlewareInterface;
use taoser\exception\ValidateException;

class CheckEmailCaptcha implements MiddlewareInterface
{
    public function process(Request $request, callable $next) : Response
    {
        $requestData = $request->all();
        try {
            validate(UserValidate::class)->scene('CreateUser')->check($requestData);
            if (empty($captcha = Cache::get(str_replace(['@', ':'], ['_at_', '_'], $requestData['emailadd'] . ':captcha')))) {
                return response_json(ERROR, '验证码已过期,请返回重试');
            }
            if (strtolower($requestData['emailcaptcha']) !== $captcha) {
                return response_json(ERROR, '您输入的邮箱验证码不正确');
            }
            Cache::delete(str_replace(['@', ':'], ['_at_', '_'], $requestData['emailadd'] . ':captcha'));
        } catch (ValidateException $e) {
            return response_json(ERROR, $e->getMessage());
        }
        return $next($request);
    }
    
}
