<?php

namespace app\admin\validate;

use think\Validate;

class Role extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'name'  => 'require|min:2|max:12|token',
        'description'   => 'require|max:16|min:2',
        'category_id' => 'require', 
        ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'name.require' => '角色名称不能为空',
        'name.min'     => '角色名称最少不能小于2个字符',
        'name.max'     => '角色名称最多不能超过12个字符',
        'description.require' => '描述不能为空',
        'description.max'     => '描述最多不能超过16个字符',
        'description.min'     => '描述最少不能小于2个字符',
        'category_id.require'   => '权限至少选一个',
    ];

}
