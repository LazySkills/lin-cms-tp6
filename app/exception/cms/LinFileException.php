<?php
/** Created by 嗝嗝<china_wangyu@aliyun.com>. Date: 2019-11-20  */

namespace app\exception\cms;


class LinFileException extends \Exception
{
    protected $message = '文件不存在';
    protected $code = 400;
    protected $error_code = 1104;
}