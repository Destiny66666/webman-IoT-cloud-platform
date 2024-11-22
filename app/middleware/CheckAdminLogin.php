<?php
/*
 * @Descripttion: 
 * @version: 
 * @Author: ZZP
 * @Date: 2023-10-22 00:56:49
 * @LastEditors: ZZP
 * @LastEditTime: 2023-10-22 20:15:42
 */
namespace app\middleware;

use app\model\User;
use Webman\Http\Request;
use Tinywan\Jwt\JwtToken;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

class CheckAdminLogin implements MiddlewareInterface
{
    public function process(Request $request, callable $next) : Response
    {
        try {
            $uid = JwtToken::getCurrentId();
            if(!User::where('authority', 1)->find($uid)){
                return response_json(ERROR,'没有权限访问');
            }
        } catch (\Throwable $th) {
            return response_json(ERROR, '访问失败，请重新登录！', ['error' => $th->getMessage()]);
        }
        $request->uid = $uid;
        return $next($request);
    }
    
}
