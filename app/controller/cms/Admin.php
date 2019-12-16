<?php

namespace app\controller\cms;

use app\annotation\{Auth, Doc, Jwt as JwtAnnotation, Logger as LoggerAnnotation};
use app\common\cms\AuthMap;
use think\annotation\route\{
    Group,Route,Validate
};
use app\validate\cms\User as UserValidate;
use app\common\log\Logger;
use app\common\authorize\Jwt;
use app\model\LinUser;

/**
 * Class Admin  cms权限管理
 * @Group("cms/admin")
 * @package app\controller
 */
class Admin
{

    /**
     * @Route(value="users",method="GET")
     * @Doc(value="查询所有用户",group="管理.权限",hide="false")
     */
    public function getAdminUsers()
    {
        $result = LinUser::getAdminUsers(request()->get());
        return json($result,200);
    }

    /**
     * @Route(value="password/:uid",method="PUT")
     * @Auth(value="修改用户密码",group="管理员",hide="true")
     * @Doc(value="修改用户密码",group="管理.权限",hide="false")
     */
    public function changeUserPassword()
    {
        LinUser::resetPassword(request()->param());
        return writeJson(201, '', '密码修改成功');
    }

    /**
     * @Route(value="password/:uid",method="PUT")
     * @Auth(value="修改用户密码",group="管理员",hide="true")
     * @LoggerAnnotation(value="删除了用户id为' . $uid . '的用户")
     * @Doc(value="修改用户密码",group="管理.权限",hide="false")
     */
    public function deleteUser($uid)
    {
        LinUser::deleteUser($uid);
        Hook::listen('logger', '删除了用户id为' . $uid . '的用户');
        return writeJson(201, '', '操作成功');
    }

    /**
     * @Route(value="authority",method="GET")
     * @Auth(value="查询所有可分配的权限",group="管理员",hide="true")
     * @Doc(value="查询所有可分配的权限",group="管理.权限",hide="false")
     */
    public function authority()
    {
        return json(AuthMap::instance()->getData("false"),200);
    }
}
