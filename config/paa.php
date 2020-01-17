<?php
/** Created by 嗝嗝<china_wangyu@aliyun.com>. Date: 2019-12-17  */
return [
    'jwt' => \config('lincms.jwt'),
    "management"=> [ # 接口管理平台
        'enable' => true, # 开关控制，true：开启｜false：关闭
        'member' => [
            'admin' => [
                'password' => 'supper',
                'admin' => true, # true：超级管理员｜false：浏览者
            ],
            'web' => [
                'password' => '123456',
                'admin' => false, # true：超级管理员｜false：浏览者
            ]
        ],
    ]
];