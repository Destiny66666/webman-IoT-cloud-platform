<?php
/*
 * @Descripttion: 
 * @version: 
 * @Author: ZZP
 * @Date: 2023-10-14 18:52:26
 * @LastEditors: ZZP
 * @LastEditTime: 2023-11-05 20:32:34
 */

namespace app\queue\redis;

use Webman\RedisQueue\Consumer;

class SendEmail implements Consumer
{
    // 要消费的队列名
    public $queue = 'send-mail';

    // 连接名，对应 plugin/webman/redis-queue/redis.php 里的连接`
    public $connection = 'default';

    // 消费
    public function consume($data)
    {
        var_export($data); // 输出 ['to' => 'tom@gmail.com', 'content' => 'hello']
    }
}