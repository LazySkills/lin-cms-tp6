<?php

return [
    'inject' => [
        'enable'     => true,
        'namespaces' => [],
    ],
    'route'  => [
        'enable'      => true,
        'controllers' => [],
    ],
    'ignore' => [],
    'custom' => [
        # 格式：注解类 => 注解操作类
        \paa\annotation\Param::class => \paa\annotation\handler\Param::class, # 单个参数验证器
        \paa\annotation\Jwt::class => \paa\annotation\handler\Jwt::class, # JWT注解验证器
        \paa\annotation\Doc::class => \paa\annotation\handler\Doc::class, # 文档管理器
        \LinCmsTp6\annotation\Auth::class => \LinCmsTp6\annotation\handler\Auth::class, # lin-cms 权限控制器
        \LinCmsTp6\annotation\Logger::class => \LinCmsTp6\annotation\handler\Logger::class, # lin-cms 日志监控器
    ]
];