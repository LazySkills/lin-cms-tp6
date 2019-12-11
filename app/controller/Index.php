<?php

namespace app\controller;

use app\BaseController;
use app\exception\AuthenticationException;
use think\annotation\route\Group;
use app\annotation\Doc;
use app\annotation\Jwt;
use app\annotation\Param;
use think\annotation\route\Route;
use think\annotation\route\Validate;
use app\validate\User;

/**
 * Class Index
 * @package app\controller
 */
class Index extends BaseController
{
    public function index()
    {
        return "<style type=\"text/css\">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: \"Century Gothic\",\"Microsoft yahei\"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style=\"padding: 24px 48px;\"> <h1>:) </h1><h1 style='font-size: 45px'>源于<a href=\"https://github.com/LazySkills/PAA-thinkphp6\">PAA</a>的美好生活💑</h1><p>版本：ThinkPHP V6<br/><span style=\"font-size:30px\">初心不改 - 🥰幸福感满满的PHP框架</span></p></div>";
    }


    public function hello($name = 'ThinkPHP6')
    {
        return 'hello,' . $name;
    }
}
