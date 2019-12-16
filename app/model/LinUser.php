<?php
/** Created by 嗝嗝<china_wangyu@aliyun.com>. Date: 2019-12-11  */

namespace app\model;


use app\exception\cms\LinUserException;

/**
 * 管理系统平台用户
 * Class LinUser
 * @package app\model
 */
class LinUser extends BaseModel
{
    protected $defaultSoftDelete="delete_time";
    protected $autoWriteTimestamp="timestamp";
    protected $hidden = ['delete_time','update_time','password'];

    public static function getAdminUsers(array $params = [])
    {
        $group = [];
        if (array_key_exists('group_id', $params)) $group = ['group_id' => $params['group_id']];

        list($start, $count) = paginate();

        $userList = self::where('admin', '<>', 2)
            ->where($group);
        $totalNums = $userList->count();
        $userList = $userList->limit($start, $count)->select();

        $userList = array_map(function ($item) {
            $item['group_name'] = empty($item['group_id'])
                ? '超级管理员'
                : LinGroup::where('id','=',$item['group_id'])->column('name');
            return $item;
        }, $userList->toArray());

        return pageDate($userList,$totalNums,$start,$count);
    }

    /** 验证用户信息 */
    public static function verify(string $username,string $password){
        try {
            $user = self::where('username', $username)->findOrFail();
        } catch (\Exception $ex) {
            throw new LinUserException();
        }

        if (!$user['active']) {
            throw new LinUserException('账户已被禁用，请联系管理员');
        }

        if (!self::checkPassword($user['password'], $password)) {
            throw new LinUserException('密码错误，请重新输入');
        }

        return $user;
    }

    /** 修改用户信息 */
    public static function updateUserInfo(int $uid,array $params = [])
    {
        try{
            $user = self::where('id','=',$uid)->findOrFail();
        }catch (\Exception $exception){
            throw new LinUserException();
        }
        if (isset($params['email']) && $user['email'] != $params['email']) {
            $exists = self::where('email', $params['email'])
                ->field('email')
                ->find();

            if ($exists) throw  new LinUserException([
                'code' => 400,
                'msg' => '注册邮箱重复，请重新输入',
                'error_code' => 10030
            ]);
        }
        $user->save($params);
    }

    /** 获取用户权限 */
    public static function getUserByUID(int $uid)
    {
        try {
            $user = self::where('id','=',$uid)->findOrFail()->toArray();
        } catch (\Exception $ex) {
            throw new LinUserException();
        }
        $auths = LinAuth::getAuthByGroupID($user['group_id']);

        $auths = empty($auths) ? [] : split_modules($auths);

        $user['auths'] = $auths;

        return $user;
    }

    public static function createUser($params)
    {
        $user = self::where('username', $params['username'])->find();
        if ($user) {
            throw new LinUserException('用户名重复，请重新输入');
        }
        $user = self::where('email', $params['email'])->find();
        if ($user) {
            throw new LinUserException('注册邮箱重复，请重新输入');
        }
        $params['password'] = md5($params['password']);
        $params['admin'] = 1;
        $params['active'] = 1;
        self::create($params);
    }

    public static function updateUserAvatar($uid, $url)
    {
        $user = LinUser::find($uid);
        if (!$user) {
            throw new LinUserException();
        }
        $user->avatar = $url;
        $user->save();
    }

    public static function changePassword($uid, $params)
    {
        $user = self::find($uid);
        if (!self::checkPassword($user->password, $params['old_password'])) {
            throw new LinUserException('原始密码错误，请重新输入');
        }

        $user->password = md5($params['new_password']);
        $user->save();
    }

    /** 重置密码 */
    public static function resetPassword(array $params = [])
    {
        $user = LinUser::find($params['uid']);
        if (!$user) {
            throw new LinUserException();
        }

        $user->password = md5($params['new_password']);
        $user->save();
    }

    /** 核验密码 */
    private static function checkPassword(string $md5Password,string $password)
    {
        return $md5Password === md5($password);
    }
}