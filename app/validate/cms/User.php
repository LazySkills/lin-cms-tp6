<?php
declare (strict_types = 1);

namespace app\validate\cms;


use think\Validate;

class User extends Validate
{
	protected $rule = [
        'password|密码' => 'require',
        'confirm_password|确认密码' => 'require|confirm:password',
        'username|用户名' => 'require|length:2,10',
        'group_id|分组ID' => 'require|>:0|number',
        'email|邮箱' => 'email'
    ];

    protected $scene = [
	    'login' => ['password','username'],
	    'register' => ['password','username','confirm_password','group_id','email'],
    ];
}
