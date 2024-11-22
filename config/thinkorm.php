<?php
/*
 * @Descripttion: 
 * @version: 
 * @Author: ZZP
 * @Date: 2023-10-10 12:34:36
 * @LastEditors: ZZP
 * @LastEditTime: 2023-11-18 17:25:14
 */

return [
    'default' => 'mysql',
    'connections' => [
        'mysql' => [
            // 数据库类型
            'type' => 'mysql',
            // 服务器地址（必填）
            'hostname' => '121.199.32.90',
            // 数据库名（必填）
            'database' => 'php_login',
            // 数据库用户名（必填）
            'username' => 'php_login',
            // 数据库密码（必填）
            'password' => 'zheng1554',
            // 数据库连接端口（必填）
            'hostport' => '3306',
            // 数据库连接参数
            'params' => [
                // 连接超时3秒
                \PDO::ATTR_TIMEOUT => 3,
            ],
            // 数据库编码默认采用utf8
            'charset' => 'utf8',
            // 数据库表前缀
            'prefix' => '',
            // 断线重连
            'break_reconnect' => true,
            // 关闭SQL监听日志
            'trigger_sql' => false,
            // 自定义分页类
            'bootstrap' =>  ''
        ],
    ],
];
