<?php
/*
 * @Descripttion: 
 * @version: 
 * @Author: ZZP
 * @Date: 2023-10-10 12:45:27
 * @LastEditors: ZZP
 * @LastEditTime: 2023-10-23 16:08:05
 */

namespace app\middleware;

use app\model\User;
use Webman\Http\Request;
use Tinywan\Jwt\JwtToken;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

class CheckLogin implements MiddlewareInterface
{
    public function process(Request $request, callable $next): Response
    {
        try {
            $uid = JwtToken::getCurrentId();
            if(User::where('status', 0)->find($uid)){
                return response_json(ERROR,'当前账号状态异常，请联系管理员！');
            }
        } catch (\Throwable $th) {
            return response_json(ERROR, '访问失败，请重新登录！', ['error' => $th->getMessage()]);
        }
        $request->uid = $uid;
        return $next($request);
    }


}
