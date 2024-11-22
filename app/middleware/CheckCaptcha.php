<?php
/*
 * @Descripttion: 
 * @version: 
 * @Author: ZZP
 * @Date: 2023-10-15 15:46:32
 * @LastEditors: ZZP
 * @LastEditTime: 2023-11-13 15:28:34
 */
namespace app\middleware;

use Webman\Http\Request;
use Webman\Http\Response;
use app\validate\UserValidate;
use Webman\MiddlewareInterface;
use taoser\exception\ValidateException;

class CheckCaptcha implements MiddlewareInterface
{
    public function process(Request $request, callable $next) : Response
    {
        $param = $request->all();
        try {
            validate(UserValidate::class)->scene('captcha')->check($param);
            if (strtolower($param['captcha']) !== $request->session()->pull('captcha')) {
                return response_json(ERROR, '输入的验证码不正确 点击验证码刷新重新试试');
            }
        } catch (ValidateException $e) {
            return response_json(ERROR, $e->getMessage());
        }
        return $next($request);
    }
    
}
