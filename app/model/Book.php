<?php
/** Created by 嗝嗝<china_wangyu@aliyun.com>. Date: 2019-12-19  */

namespace app\model;


use LinCmsTp6\model\BaseModel;
use think\model\concern\SoftDelete;

class Book extends BaseModel
{
    use SoftDelete;
    protected $deleteTime="delete_time";
    protected $autoWriteTimestamp="timestamp";
}