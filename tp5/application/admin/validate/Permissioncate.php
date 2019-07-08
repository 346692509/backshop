<?php

namespace app\admin\validate;

use think\Validate;

class Permissioncate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'rolename'  =>  'require|max:100|min:2|token',
        'dtion'  =>  'require|max:100|min:2',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'rolename.require'     => '权限名称不能为空',
        'rolename.max'     => '权限名称不能超过100',
        'rolename.min'     => '权限名称不能小于2',
        'rolename.token'     => '令牌错误',
        'dtion.require'     => '权限描述不能为空',
        'dtion.max'     => '权限描述不能超过100',
        'dtion.min'     => '权限描述不能小于2',
    ];

}
