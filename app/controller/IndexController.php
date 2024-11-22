<?php
/*
 * @Descripttion: 
 * @version: 
 * @Author: ZZP
 * @Date: 2023-10-10 12:24:56
 * @LastEditors: ZZP
 * @LastEditTime: 2023-11-05 21:26:25
 */

namespace app\controller;

use app\model\User;
use support\Request;
use Tinywan\Jwt\JwtToken;
use Webman\RedisQueue\Redis;

class IndexController
{
    public function index(Request $request)
    {
        return response_json(SUCCESS, '访问成功', ['uid' => $request->uid]);
    }

    public function queue(Request $request)
    {
        $queue = 'send-mail'; // 队列名称
        $waiting_queue_name = "{redis-queue}-waiting{$queue}";
        $waiting_count = Redis::connection()->lLen($waiting_queue_name); // 等待队列数量
        
        $delayed_queue_name = '{redis-queue}-delayed';
        $delayed_count = Redis::connection()->zLexCount($delayed_queue_name, '-', '+'); // 延迟队列数量
        
        $failed_queue_name = '{redis-queue}-failed';
        $failed_count = Redis::connection()->lLen($failed_queue_name); // 失败队列数量
        
        // echo '等待队列数量：', $waiting_count, PHP_EOL;
        // echo '延迟队列数量：', $delayed_count, PHP_EOL;
        // echo '失败队列数量：', $failed_count, PHP_EOL;
        return response_json(SUCCESS,"查询消息队列成功", [
            '等待队列数量：'=> $waiting_count,
            '延迟队列数量：'=> $delayed_count,
            '失败队列数量：'=> $failed_count,
        ]);
    }


    
}
