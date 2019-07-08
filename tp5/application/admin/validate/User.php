<?php

namespace app\admin\validate;

use think\Validate;

class User extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'user_name'  => 'require|min:2|max:12|token',
        'password'   => 'require|max:16|min:3',
        'phone' => 'require|/^1[3-8]{1}[0-9]{9}$/', 
        ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'user_name.require' => '名称不能为空',
        'user_name.min'     => '名称最少不能小于2个字符',
        'user_name.max'     => '名称最多不能超过12个字符',
        'password.require' => '密码不能为空',
        'password.max'     => '名称最多不能超过16个字符',
        'password.min'     => '密码最少不能小于3个字符',
        'phone.require'   => '手机号不能为空',
        'phone'   => '手机格式不对',
    ];

}
