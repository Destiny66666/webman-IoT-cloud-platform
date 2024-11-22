<?php
/*
 * @Descripttion: 
 * @version: 
 * @Author: ZZP
 * @Date: 2023-10-22 16:27:33
 * @LastEditors: ZZP
 * @LastEditTime: 2023-11-13 20:02:44
 */

namespace app\service;

use support\Cache;
use app\model\User;
use support\Request;
use app\validate\UserValidate;
use taoser\exception\ValidateException;

class AdminUserService
{
    /**
     * @Descripttion: 根据id,name,email获取用户信息列表
     * @Author: ZZP
     * @param {Request} $request
     * @return {*}
     */
    public function adminLikeUserListByIdOrNameOrEmail(Request $request)
    {
        $param = $request->all();
        try {
            $page = !empty($param['page']) ? $param['page'] : 1;
            $limit = !empty($param['limit']) ? $param['limit'] : 10;
            $id = $param['id'] ?? '';
            $name = $param['name'] ?? '';
            $email = $param['email'] ?? '';
            $userListCount = User::where('authority', 0)->whereLike('id', '%' . $id . '%')
            ->whereLike('name', '%' . $name . '%')->whereLike('email', '%' . $email . '%')->count();
            $userList = User::scope('User')->with('authority')->where('authority', 0)
            ->whereLike('id', '%' . $id . '%')->whereLike('name', '%' . $name . '%')
            ->whereLike('email', '%' . $email . '%')->page($page, $limit)->select();
            if ($userList->isEmpty()) {
                return response_json(ERROR, '没有此条件的数据');
            }
            return response_json_count(SUCCESS, '获取用户信息列表成功', $userList, $userListCount);
        } catch (\Throwable $th) {
            return response_json(ERROR, '获取用户信息列表失败', [$th->getMessage()]);
        }
    }
    /**
     * @Descripttion: 获取根据用户ID获取用户信息
     * @Author: ZZP
     * @param {Request} $request
     * @return {*}
     */
    public function adminGetUserById(Request $request)
    {
        $param = $request->all();
        try {
            if (!$user = User::scope('User')->with('authority')->where([['id','=',$param['id']], ['authority','=', 0]])->find()) {
                return response_json(ERROR, '当前id不存在');
            }
            return response_json(SUCCESS, '访问成功', [
                'user' => $user
            ]);
        } catch (\Throwable $th) {
            return response_json(ERROR, '获取用户信息失败', [$th->getMessage()]);
        }
    }

    /**
     * @Descripttion: 管理员根据用户ID修改用户信息
     * @Author: ZZP
     * @param {Request} $request
     * @return {*}
     */
    public function adminUpdateUserById(Request $request)
    {
        $param = $request->all();
        try {
            validate(UserValidate::class)->scene('AdminUpdateUserInfo')->check($param);
        } catch (ValidateException $e) {
            return response_json(ERROR, $e->getMessage());
        }
        try {
            if (!$user = User::find($param['id'])) {
                return response_json(ERROR, '当前id不存在');
            }
            $user->authority = $param['authority'];
            $user->name = $param['name'];
            $user->phone = $param['phone'];
            $user->avatar = $param['avatar'];
            $user->status = $param['status'];
            $user->save();
            return response_json(SUCCESS, '修改信息成功');
        } catch (\Throwable $th) {
            return response_json(ERROR, '修改用户信息失败', [$th->getMessage()]);
        }
    }

    /**
     * @Descripttion: 管理员创建用户
     * @Author: ZZP
     * @param {Request} $request
     * @return {*}
     */
    public function adminCreateUser(Request $request)
    {
        $param = $request->all();
        try {
            validate(UserValidate::class)->scene('AdminCreateUser')->check($param);
        } catch (ValidateException $e) {
            return response_json(ERROR, $e->getMessage());
        }
        if (User::where('email', $param['email'])->find()) {
            return response_json(ERROR, '当前邮箱已注册');
        }
        try {
            $create = new User();
            $create->startTrans(); //开启事务处理
            $create->name = $param['name'];
            $create->avatar = $param['avatar'];
            $create->email = $param['email'];
            $create->password = password_hash('123456', PASSWORD_DEFAULT);
            $create->phone = $param['phone'];
            $create->status = $param['status']; //激活用户
            $create->authority = $param['authority'];; //用户权限
            $create->save();
            $create->commit(); // 提交事务
            return response_json(SUCCESS, "注册成功");
        } catch (\Exception $th) {
            $create->rollback(); // 回滚事务
            return response_json(ERROR, "注册失败,请重试", [$th->getMessage()]);
        }
    }

    /**
     * @Descripttion: 根据id,name,email获取管理员信息列表
     * @Author: ZZP
     * @param {Request} $request
     * @return {*}
     */
    public function adminLikeAdminListByIdOrNameOrEmail(Request $request)
    {
        $param = $request->all();
        try {
            $page = !empty($param['page']) ? $param['page'] : 1;
            $limit = !empty($param['limit']) ? $param['limit'] : 10;
            $id = $param['id'] ?? '';
            $name = $param['name'] ?? '';
            $email = $param['email'] ?? '';
            $userListCount = User::where('authority', 1)->whereLike('id', '%' . $id . '%')
            ->whereLike('name', '%' . $name . '%')->whereLike('email', '%' . $email . '%')->count();
            $userList = User::scope('User')->with('authority')->where('authority', 1)
            ->whereLike('id', '%' . $id . '%')->whereLike('name', '%' . $name . '%')
            ->whereLike('email', '%' . $email . '%')->page($page, $limit)->select();
            if ($userList->isEmpty()) {
                return response_json(ERROR, '没有此条件的数据');
            }
            return response_json_count(SUCCESS, '获取管理员信息列表成功', $userList, $userListCount);
        } catch (\Throwable $th) {
            return response_json(ERROR, '获取管理员信息列表失败', [$th->getMessage()]);
        }
    }

    /**
     * @Descripttion: 把邀请码存入redis
     * @Author: ZZP
     * @param {Request} $request
     * @return {*}
     */
    public function createInvitationCode(Request $request)
    {
        $param = $request->all();
        try {
            validate(UserValidate::class)->scene('InvitationCode')->check($param);
        } catch (ValidateException $e) {
            return response_json(ERROR, $e->getMessage());
        }
        try {
            if (Cache::get('InvitationCodeCooling')) {
                return response_json(ERROR, "邀请码已发送，请30秒后重新发送！");
            }
            Cache::set('InvitationCode', $param['InvitationCode'], 3600*24); //注册码存入Redis 保存1天
            Cache::set('InvitationCodeCooling', 1, 30); //注册码刷新冷却时间30秒
            return response_json(SUCCESS, "管理员注册邀请码，已保存，请联系需要注册人的尽快注册！");
        } catch (\Throwable $th) {
            return response_json(ERROR, '邀请码设置失败，请重试！', [$th->getMessage()]);
        }
    }

    /**
     * @Descripttion: 通过要邀请码注册管理员账户
     * @Author: ZZP
     * @param {Request} $request
     * @return {*}
     */
    public function CreateAdmin(Request $request)
    {
        $param = $request->all();
        try {
            validate(UserValidate::class)->scene('register')->scene('InvitationCode')->check($param);
        } catch (ValidateException $e) {
            return response_json(ERROR, $e->getMessage());
        }
        if (User::where("email", $param['emailadd'])->find()) {
            return response_json(ERROR, '系统已存在该用户，请重新输入邮箱！');
        }
        if ($param['confirmpassword'] != $param['password']) {
            return response_json(ERROR, '确认密码错误！');
        }
        if (Cache::get('InvitationCode') != $param['InvitationCode']) {
            return response_json(ERROR, "邀请码错误，请重新输入！");
        }
        try {
            $user = new User();
            $user->startTrans(); //开启事务处理
            $user->name = $param['name'];
            $user->email = $param['emailadd'];
            $user->password = password_hash($param['password'], PASSWORD_DEFAULT);
            $user->phone = $param['phone'];
            $user->status = 1;
            $user->authority = 1;
            $user->save();
            $user->commit(); // 提交事务
            Cache::delete('InvitationCode');
            return response_json(SUCCESS, '注册成功');
        } catch (\Throwable $th) {
            $user->rollback(); // 回滚事务
            return response_json(ERROR, '注册失败，请重试', [$th->getMessage()]);
        }
    }

    /**
     * @Descripttion: 获取管理员注册邀请码
     * @Author: ZZP
     * @param {Request} $request
     * @return {*}
     */
    public function getInvitationCode(Request $request){
        try {
            return response_json(SUCCESS,'获取成功',['InvitationCode'=>Cache::get('InvitationCode')]);
        } catch (\Throwable $th) {
            return response_json(ERROR, '获取失败', [$th->getMessage()]);
        }
    }
}
