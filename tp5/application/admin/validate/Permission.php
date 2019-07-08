<?php

namespace app\admin\validate;

use think\Validate;

class Permission extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'name'  =>  'require|max:100|min:2|token',
        'description'  =>  'require|max:100|min:2',
        'path'  =>  'require',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'name.require'     => '权限节点名称不能为空',
        'name.max'     => '权限节点名称不能超过100',
        'name.min'     => '权限节点名称不能小于2',
        'rolename.token'     => '令牌错误',
        'description.require'     => '描述名称不能为空',
        'description.max'     => '描述不能超过100',
        'description.min'     => '描述不能小于2',
        'path.require'     => '路径不能为空',
    ];

}
