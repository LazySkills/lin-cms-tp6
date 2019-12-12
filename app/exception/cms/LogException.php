<?php
/** Created by 嗝嗝<china_wangyu@aliyun.com>. Date: 2019-11-20  */

namespace app\exception\cms;


class LogException extends \Exception
{
    protected $message = '暂无数据';
    protected $code = 400;
    protected $error_code = 1101;
}