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
 * cms权限管理
 * Class Admin
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
}
