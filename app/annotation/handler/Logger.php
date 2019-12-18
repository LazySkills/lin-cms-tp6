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
            $params = $this->getParams($rule);
            $message = $this->getMessage($annotation->value,$params);
            \app\common\log\Logger::annotation($message);
        }else{
            return ;
        }
    }

    public function getMessage(string $message,array $params = []){
        $args = [];
        $value = [];
        preg_match_all('/({.*})/U', $message, $args);
        foreach ($args[0] as $arg){
            $key = str_replace(['{','}'],'',$arg);
            array_push($value,$params[$key] ?? '');
        }
        return str_replace($args[0],$value,$message) ?? '';
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
