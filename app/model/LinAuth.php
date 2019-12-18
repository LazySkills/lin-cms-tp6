<?php
/** Created by 嗝嗝<china_wangyu@aliyun.com>. Date: 2019-12-11  */

namespace app\model;


/**
 * 管理系统平台权限
 * Class LinAuth
 * @package app\model
 */
class LinAuth extends BaseModel
{
    protected $hidden = ['id'];

    public static function getAuthByGroupID(?int $group_id){
        if (empty($group_id)) return [];
        $result = self::where('group_id', $group_id)
            ->select()
            ->toArray();
        return $result;
    }

    public static function dispatchAuths(array $params = []){
        foreach ($params['auths'] as $value) {
            $auth = self::where(['group_id' => $params['group_id'], 'auth' => $value])->find();
            if (!$auth) {
                $authItem = findAuthModule($value);
                $authItem['group_id'] = $params['group_id'];
                self::create($authItem);
            }
        }
    }
}