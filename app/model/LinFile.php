<?php
/** Created by 嗝嗝<china_wangyu@aliyun.com>. Date: 2019-12-19  */

namespace app\model;


use think\model\concern\SoftDelete;

class LinFile extends BaseModel
{
    use SoftDelete;
    protected $deleteTime="delete_time";
    protected $autoWriteTimestamp="timestamp";
}