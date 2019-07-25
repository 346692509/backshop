<?php

namespace app\admin\validate;

use think\Validate;

class Brand extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'name'  => 'require|min:2|max:12|token',
        'brand_description'  => 'require|min:2|max:12',

    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */
    protected $message = [
        'name.require' => '名称不能为空',
        'name.min'     => '名称最少不能小于2个字符',
        'name.max'     => '名称最多不能超过12个字符',
        'name.token'     => '令牌错误',
        'brand_description.require' => '描述不能为空',
        'brand_description.min'     => '描述最少不能小于2个字符',
        'brand_description.max'     => '描述最多不能超过12个字符',
    ];

}
