<?php
declare (strict_types = 1);

namespace app\annotation\handler;

use app\common\cms\AuthMap;
use Doctrine\Common\Annotations\Annotation;
use think\annotation\handler\Handler;

final class Auth extends Handler
{

    public function func(\ReflectionMethod $refMethod, Annotation $annotation, \think\route\RuleItem &$rule)
    {
        AuthMap::init((array)$annotation);
    }

}
