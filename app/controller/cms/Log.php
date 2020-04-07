<?php
/** Created by 嗝嗝<china_wangyu@aliyun.com>. Date: 2019-12-19  */

namespace app\controller\cms;

use LinCmsTp6\annotation\Auth;
use LinCmsTp6\model\LinLog;
use paa\annotation\Doc;
use think\annotation\Route;
use think\annotation\route\Group;

/**
 * Class Log
 * @Group(value="cms/log")
 * @package app\controller\cms
 */
class Log
{
    /**
     * @Route(value="",method="GET")
     * @Auth(value="查询所有日志",group="日志",hide="false")
     * @Doc(value="查询所有日志",group="管理.日志",hide="false")
     */
    public function getLogs()
    {
        $result = LinLog::getLogs(request()->get());
        return json($result,200);
    }

    /**
     * @Route(value="search",method="GET")
     * @Auth(value="搜索日志",group="日志",hide="false")
     * @Doc(value="搜索日志",group="管理.日志",hide="false")
     */
    public function getUserLogs()
    {
        $result = LinLog::getLogs(request()->get());
        return json($result,200);
    }

    /**
     * @Route(value="users",method="GET")
     * @Auth(value="查询日志记录的用户",group="日志",hide="false")
     * @Doc(value="查询日志记录的用户",group="管理.日志",hide="false")
     */
    public function getUsers()
    {
        $users = LinLog::column('user_name');
        $result = array_unique($users);
        return json($result,200);
    }
}
