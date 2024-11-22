<?php
/*
 * @Descripttion: 
 * @version: 
 * @Author: ZZP
 * @Date: 2023-10-10 12:24:56
 * @LastEditors: ZZP
 * @LastEditTime: 2023-11-18 18:10:14
 */

/**
 * Here is your custom functions.
 */

use support\Response;
use Webman\RedisQueue\Redis;
use PHPMailer\PHPMailer\PHPMailer;
define('SUCCESS', 0); // 成功状态码

define('ERROR', -1); // 错误状态码

define('WARNING', -2); // 警告状态码

if (!function_exists('response_json_count')) {
    /**
     * @description: 响应Json方法
     * @param {*} $type
     * @param {*} $msg
     * @param {*} $data
     * @return Response
     */
    function response_json_count($type, $msg, $data = [], $count = null): Response
    {
        return json(['code' => $type, 'msg' => $msg, 'data' => $data, 'count' => $count]);
    }
}

if (!function_exists('response_json')) {
    /**
     * @description: 响应Json方法
     * @param {*} $type
     * @param {*} $msg
     * @param {*} $data
     * @return Response
     */
    function response_json($type, $msg, $data = []): Response
    {
        return json(['code' => $type, 'msg' => $msg, 'data' => $data]);
    }
}
if (!function_exists('MailSender')) {
        /**
     * @description: 发送邮件
     * @param {*} $emailAddr 邮件地址
     * @param {*} $message 邮件内容
     * @return null
     */
    function MailSender($emailAddr, $body, $title = null)
    {
        $config = config('phpmailer');
        $mail = new PHPMailer(true);
        $mail->CharSet = "UTF-8"; //设定邮件编码 
        $mail->SMTPDebug = 0; // 调试模式输出 
        $mail->isSMTP(); // 使用SMTP 
        $mail->Host = $config['Host']; // SMTP服务器 
        $mail->SMTPAuth = true; // 允许 SMTP 认证 
        $mail->Username = $config['Username']; // SMTP 用户名  即邮箱的用户名 
        $mail->Password = $config['Password']; // SMTP 密码  部分邮箱是授权码(例如163邮箱) 
        $mail->SMTPSecure = $config['SMTPSecure']; // 允许 TLS 或者ssl协议 
        $mail->Port = $config['Port']; // 服务器端口 25 或者465 具体要看邮箱服务器支持 
        $mail->setFrom($config['Username'], $config['SenderUsername']); //发件人 
        $mail->addAddress($emailAddr, $emailAddr); // 收件人 
        $mail->addReplyTo($config['Username'], $config['Username']); //回复的时候回复给哪个邮箱 建议和发件人一致 
        $mail->Subject = $title == null ? $config['EmailTitle'] : $title; //邮件标题
        $mail->MsgHTML($body);
        $mail->send();
    }
}

if (!function_exists('push_email_captcha')) {
    /**
     * @description: 发送邮箱验证码
     * @param {*} $emailAddr
     * @param {*} $captcha
     * @return {*}
     */
    function push_email_captcha($emailAddr, $captcha)
    {
        try {
            // 投递消息
            if(Redis::send('send-mail', MailSender($emailAddr, "您好! 您邮箱的验证码是:{$captcha}，
            请妥善保管您的验证码，此验证码十分钟内有效，当前输入信息保留30分钟，30分钟后消除信息，请不要随意外泄!(这是一封系统邮件，请勿回复)"))){
                return response_json(SUCCESS, '邮箱验证码发送成功,请前往邮箱查看');
            }
        } catch (\Exception $th) {
            throw new Exception('邮箱验证码发送失败!');
            return response_json(ERROR, '邮箱验证码发送失败!', $th->getMessage());
        }
    }
}

