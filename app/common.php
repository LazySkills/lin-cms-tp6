<?php
// 应用公共文件
use app\exception\ParameterException;

function paginate(){
    $count = request()->get('count') ?? 10;
    $start = request()->get('page') ?? 0;
    if ($start < 0 || $count < 0) throw new ParameterException();
    $start = $start * $count;
    return [$start, $count];
}

function pageDate($list,$total,$start,$count)
{
    return [
        'items' => $list,
        'total' => $total,
        'count' => $count,
        'page' => $start / $count,
        'total_page' => ceil($total / $count)
    ];
}

function writeJson($code, $data, $msg = 'ok', $errorCode = 0)
{
    $data = [
        'error_code' => $errorCode,
        'result' => $data,
        'msg' => $msg
    ];
    return json($data, $code);
}

function split_modules($auths, $module = 'module')
{
    if (empty($auths)) {
        return [];
    }

    $items = [];
    $result = [];

    foreach ($auths as $key => $value) {
        if (isset($items[$value[$module]])) {
            $items[$value[$module]][] = $value;
        } else {
            $items[$value[$module]] = [$value];
        }
    }
    foreach ($items as $key => $value) {
        $item = [
            $key => $value
        ];
        array_push($result, $item);
    }
    return $result;

}

function findAuthModule($auth)
{
    $authMap = (new AuthMap())->run();
    foreach ($authMap as $key => $value) {
        foreach ($value as $k => $v) {
            if ($auth === $k) {
                return [
                    'auth' => $k,
                    'module' => $key
                ];
            }
        }
    }
}