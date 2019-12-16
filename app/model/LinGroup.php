<?php
/** Created by 嗝嗝<china_wangyu@aliyun.com>. Date: 2019-12-11  */

namespace app\model;

use app\exception\cms\LinGroupException;

/**
 * 管理系统平台用户分组
 * Class LinGroup
 * @package app\model
 */
class LinGroup extends BaseModel
{


    public static function getGroupByID($id)
    {
        try {
            $group = self::findOrFail($id)->toArray();
        } catch (\Exception $ex) {
            throw new LinGroupException('指定的分组不存在');
        }

        $auths = LinAuth::getAuthByGroupID($group['id']);

        $group['auths'] = empty($auths) ? [] : split_modules($auths);;

        return $group;

    }

    public static function createGroup($params)
    {
        $group = self::where('name', $params['name'])->find();
        if ($group) {
            throw new LinGroupException('分组已存在');
        }

        Db::startTrans();
        try {
            $group = (new LinGroup())->allowField(true)->create($params);

            $auths = [];

            foreach ($params['auths'] as $value) {
                $auth = findAuthModule($value);
                $auth['group_id'] = $group->id;
                array_push($auths, $auth);
            }

            (new LinAuth())->saveAll($auths);
            Db::commit();
        } catch (\Exception $ex) {
            Db::rollback();
            throw new LinGroupException('分组创建失败');
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function deleteGroupAuth($id)
    {
        $user = self::get($id,'auth');
        $deleteGroup = $user->together('auth')->delete();
        if(!$deleteGroup)
        {
            throw new GroupException([
                'error_code' => 30005,
                'msg' => '分组删除失败'
            ]);
        }
        return $deleteGroup;

    }

    /**
     * @return mixed
     */
    public function auth()
    {
        return $this -> hasMany('LinAuth','group_id','id');
    }
}