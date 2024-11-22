<?php
/*
 * @Descripttion: 
 * @version: 
 * @Author: ZZP
 * @Date: 2023-10-14 18:31:27
 * @LastEditors: ZZP
 * @LastEditTime: 2023-11-18 17:45:15
 */
return [
    'default' => [
        // redis地址 如：redis://127.0.0.1:6379
        'host' => 'redis://121.199.32.90:6379',
        'options' => [
            'auth' => 'zheng2002',       // 密码，字符串类型，可选参数
            'db' => 0,            // 数据库
            'prefix' => '',       // key 前缀
            'max_attempts'  => 5, // 消费失败后，重试次数
            'retry_seconds' => 5, // 重试间隔，单位秒
        ]
    ],
];
