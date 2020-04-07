<?php

namespace app\controller\cms;

use LinCmsTp6\common\AuthMap;
use LinCmsTp6\exception\LinGroupException;
use paa\annotation\Doc;
use LinCmsTp6\annotation\{
    Auth,Logger
};

use LinCmsTp6\model\{LinAuth, LinUser, LinGroup};
use think\annotation\Route;
use think\annotation\route\Group;

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
     * @Route(value="remove",method="POST")
     * @Auth(value="删除多个权限",group="管理员",hide="true")
     * @Doc(value="删除多个权限",group="管理.权限",hide="false")
     */
    public function removeAuths()
    {
        LinAuth::where(['group_id' => request()->post('group_id'), 'auth' => request()->post('auths')])
            ->delete();
        return writeJson(201, '', '删除权限成功');
    }

    /**
     * @Route(value="/dispatch/patch",method="POST")
     * @Auth(value="分配多个权限",group="管理员",hide="true")
     * @Logger(value="修改了id为 {group_id} 的权限")
     * @Doc(value="分配多个权限",group="管理.权限",hide="false")
     */
    public function dispatchAuths()
    {
        LinAuth::dispatchAuths(request()->post());
        return writeJson(201, '', '添加权限成功');
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
     * @Route(value="group",method="POST")
     * @Auth(value="新建权限组",group="管理员",hide="true")
     * @Doc(value="新建权限组",group="管理.权限",hide="false")
     */
    public function createGroup()
    {
        LinGroup::createGroup(request()->post());
        return writeJson(201, '', '成功');
    }

    /**
     * @Route(value="group/:id",method="PUT")
     * @Auth(value="更新一个权限组",group="管理员",hide="true")
     * @Doc(value="更新一个权限组",group="管理.权限",hide="false")
     */
    public function updateGroup($id)
    {

        $group = LinGroup::where('id',$id)->find();
        if (!$group) {
            throw new LinGroupException('指定的分组不存在');
        }
        $group->save(request()->put());
        return writeJson(201, '', '更新分组成功');
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
