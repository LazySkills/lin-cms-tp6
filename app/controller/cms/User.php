<?php

namespace app\controller\cms;

use app\annotation\{
    Doc,Jwt,Param
};
use app\model\BaseModel;
use app\model\LinUser;
use think\annotation\route\{
    Group,Route,Validate
};
use app\validate\cms\User as UserValidate;

/**
 * Class User
 * @Group("cms/user")
 * @package app\controller
 */
class User
{

    /**
     * cms管理系统登陆
     * @Route(value="login",method="POST")
     * @Validate(value=UserValidate::class,scene="login")
     * @Doc(value="测试应用",group="管理.应用",hide="false")
     */
    public function login()
    {
        $user = LinUser::verify(input('username'), input('password'));
        $result = (new \app\common\authorize\Jwt())->encode(strval($user->id),json_encode($user->toArray()));
        return json($result,200);
    }



}
