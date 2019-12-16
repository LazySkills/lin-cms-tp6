<?php

namespace app\controller\cms;

use app\annotation\{
    Doc,Jwt as JwtAnnotation,Logger as LoggerAnnotation
};
use think\annotation\route\{
    Group,Route,Validate
};
use app\validate\cms\User as UserValidate;
use app\common\log\Logger;
use app\common\authorize\Jwt;
use app\model\LinUser;

/**
 * Class User cms用户管理
 * @Group("cms/user")
 * @package app\controller
 */
class User
{

    /**
     * @Route(value="login",method="POST")
     * @Validate(value=UserValidate::class,scene="login")
     * @Doc(value="登陆",group="管理.用户",hide="false")
     */
    public function login()
    {
        $user = LinUser::verify(input('username'), input('password'));
        $result = Jwt::encode(strval($user->id),json_encode($user->toArray()));
        Logger::create($user->id,$user->username,'登陆成功获取了令牌');
        return json($result,200);
    }

    /**
     * @Route(value="register",method="POST")
     * @Validate(value=UserValidate::class,scene="register")
     * @LoggerAnnotation(value="创建了一个用户")
     * @Doc(value="创建用户",group="管理.用户",hide="false")
     */
    public function register()
    {
        LinUser::createUser(request()->post());

        return writeJson(201, '', '用户创建成功');
    }

    /**
     * @Route(value="refresh",method="GET")
     * @JwtAnnotation()
     * @Doc(value="刷新授权",group="管理.用户",hide="false")
     */
    public function refresh()
    {
        $result = Jwt::refresh();
        return json($result,200);
    }

    /**
     * @Route(value=" ",method="PUT")
     * @JwtAnnotation()
     * @Doc(value="更新用户信息",group="管理.用户",hide="false")
     */
    public function update()
    {
        $result = Jwt::decode();
        LinUser::updateUserInfo($result['uniqueId'], request()->put());
        return writeJson(201,'','操作成功');
    }

    /**
     * @Route(value="information",method="GET")
     * @JwtAnnotation()
     * @Doc(value="获取用户信息",group="管理.用户",hide="false")
     */
    public function information()
    {
        $jwt = Jwt::decode();
        return json(json_decode($jwt['signature'],true),200);
    }

    /**
     * @Route(value="auths",method="GET")
     * @JwtAnnotation()
     * @Doc(value="查询自己拥有的权限",group="管理.用户",hide="false")
     */
    public function getAllowedApis()
    {
        $jwt = Jwt::decode();
        $result = LinUser::getUserByUID($jwt['uniqueId']);
        return json($result,200);
    }

    /**
     * @Route(value="avatar",method="PUT")
     * @JwtAnnotation()
     * @Doc(value="设置用户头像",group="管理.用户",hide="false")
     */
    public function setAvatar()
    {
        $jwt = Jwt::decode();
        LinUser::updateUserAvatar($jwt['uniqueId'],request()->put('avatar'));
        return writeJson(201, '', '更新头像成功');
    }

    /**
     * @Route(value="change_password",method="PUT")
     * @LoggerAnnotation(value="修改了自己的密码")
     * @Doc(value="修改用户密码",group="管理.用户",hide="false")
     */
    public function changePassword()
    {
        $jwt = Jwt::decode();
        LinUser::changePassword($jwt['uniqueId'], request()->put());

        return writeJson(201, '', '密码修改成功');
    }
}
