<?php

namespace app\controller\cms;

use app\common\cms\AuthMap;
use app\exception\cms\LinGroupException;
use app\annotation\{
    Auth, Doc, Logger
};
use think\annotation\route\{
    Group,Route
};
use app\model\{
    LinUser,LinGroup
};

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
        LinUser::resetPassword(request()->put());
        return writeJson(201, '', '密码修改成功');
    }

    /**
     * @Route(value=":uid",method="PUT")
     * @Auth(value="管理员更新用户信息",group="管理员",hide="true")
     * @Doc(value="管理员更新用户信息",group="管理.权限",hide="false")
     */
    public function updateUser()
    {
        $params = request()->param();

        LinUser::updateUserInfo($params['uid'],$params);

        return writeJson(201, '', '操作成功');
    }

    /**
     * @Route(value=":uid",method="DELETE")
     * @Auth(value="修改用户密码",group="管理员",hide="true")
     * @Logger(value="删除了用户id为 {uid} 的用户")
     * @Doc(value="修改用户密码",group="管理.权限",hide="false")
     */
    public function deleteUser($uid)
    {
        LinUser::deleteUser($uid);
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

    /**
     * @Route(value="group/all",method="GET")
     * @Auth(value="查询所有权限组",group="管理员",hide="true")
     * @Doc(value="查询所有权限组",group="管理.权限",hide="false")
     */
    public function getGroupAll()
    {
        try{
            $result = LinGroup::selectOrFail()->toArray();
        }catch (\Exception $exception){
            throw new LinGroupException('暂无数据');
        }
        return json($result,200);
    }

    /**
     * @Route(value="group/:id",method="GET")
     * @Auth(value="查询一个权限组及其权限",group="管理员",hide="true")
     * @Doc(value="查询一个权限组及其权限",group="管理.权限",hide="false")
     */
    public function getGroup($id)
    {
        $result = LinGroup::getGroupByID($id);

        return json($result,200);
    }

    /**
     * @Route(value="group/:id",method="DELETE")
     * @Auth(value="修改用户密码",group="管理员",hide="true")
     * @Logger(value="删除了权限组id为 {id} 的权限组")
     * @Doc(value="修改用户密码",group="管理.权限",hide="false")
     */
    public function deleteGroup($id)
    {
        //查询当前权限组下是否存在用户
        $hasUser = LinUser::where(['group_id'=>$id])->find();
        if ($hasUser)throw new LinGroupException('分组下存在用户，删除分组失败');

        LinGroup::deleteGroupAuth($id);
        return writeJson(201, '', '删除分组成功');
    }
}
