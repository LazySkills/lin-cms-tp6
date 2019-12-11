<?php
declare (strict_types = 1);

namespace app\annotation\handler;

use Doctrine\Common\Annotations\Annotation;
use think\annotation\handler\Handler;

final class Logger extends Handler
{

    public function func(\ReflectionMethod $refMethod, Annotation $annotation, \think\route\RuleItem &$rule)
    {
        dump($annotation);die;
    }
}
