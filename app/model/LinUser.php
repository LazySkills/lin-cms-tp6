<?php
/** Created by 嗝嗝<china_wangyu@aliyun.com>. Date: 2019-12-11  */

namespace app\model;


use app\exception\cms\UserException;

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

    /** 验证用户信息 */
    public static function verify(string $username,string $password){
        try {
            $user = self::where('username', $username)->findOrFail();
        } catch (\Exception $ex) {
            throw new UserException();
        }

        if (!$user['active']) {
            throw new UserException('账户已被禁用，请联系管理员');
        }

        if (!self::checkPassword($user['password'], $password)) {
            throw new UserException('密码错误，请重新输入');
        }

        return $user;
    }

    /** 核验密码 */
    private static function checkPassword(string $md5Password,string $password)
    {
        return $md5Password === md5($password);
    }
}