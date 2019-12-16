<?php
/** Created by 嗝嗝<china_wangyu@aliyun.com>. Date: 2019-12-16  */

namespace app\common\cms;


use Doctrine\Common\Annotations\Annotation\Enum;

class AuthMap
{
    public static $instance;

    protected $data;

    private function __construct(){}

    public static function instance():self {
        if (!isset(static::$instance)){
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function getData(string $type)
    {
        return $this->data[$type] ?? [];
    }

    public function setData(array $annotation): void
    {
        $this->data[$annotation['hide']][$annotation['group']][$annotation['value']] = [];
    }

    public static function init(array $annotation):self {
        $self = static::instance();
        $self->setData($annotation);
        return $self;
    }
}