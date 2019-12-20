<?php
namespace app;

// 应用请求对象类
class Request extends \think\Request
{
    public static function match($rule,$request){
        // 合并分组参数
        $option = $rule->mergeGroupOptions();
        $url = $rule->urlSuffixCheck($request, $request->url(), $option);
        return $rule->match($url, $option, false);
    }
}
