<?php
declare (strict_types = 1);

namespace app\annotation\handler;

use Doctrine\Common\Annotations\Annotation;
use think\annotation\handler\Handler;
use think\route\RuleItem;

final class Logger extends Handler
{

    public function func(\ReflectionMethod $refMethod, Annotation $annotation, \think\route\RuleItem &$rule)
    {
        if ($this->isCurrentMethod($refMethod,$rule)){
            $message = $annotation->value;
            $args = [];
            $params = $this->getParams($rule);
            preg_match('/(^{%[\.?*]%})/', $message, $args);
            dump($args);die;
            $message = sprintf($message,$args);
            dump($args);
            dump($message);
            \app\common\log\Logger::annotation($annotation,$params);
        }else{
            return ;
        }
    }


    public function getParams($rule){
        $url =  str_replace('/','|',trim(request()->url(),'/'));
        $options = $rule->mergeGroupOptions();
        $getUrlParam = function ()use ($url,$options){
            if ($this instanceof RuleItem){
                $options = $this->match($url, $options, false);
                return $options;
            }
            return [];
        };
        $urlParams = $getUrlParam->call($rule);
        $methodParams = request()->{strtolower(request()->method())}();
        return array_merge($methodParams,$urlParams);
    }
}
