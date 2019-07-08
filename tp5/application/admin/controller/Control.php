<?php
namespace app\admin\controller;
use gmars\rbac\Rbac;
class Control extends Common{
	public function permissionCategoryAdd(){
		
		$this->fetch();
	}

	public function permissionCategoryAddAction(){
		$rbac= new Rbac();
		$rbac->savePermissionCategory([
			'name' => '品牌管理',
			'description' => '网站品牌的管理',
			'status' => 1
			]);
	}
	public function permissionAdd(){
		$this->fetch();
	}
	public function permissionAddAction(){
		$rbac= new Rbac();
		$rbac->createPermission([
			'name' => '品牌列表查询',
			'description' => '品牌列表查询',
			'status' => 1,
			'type' => 1,
			'category_id' => 1,
			'path' => 'admin/brand/list',
			]);
	}
	public function roleAdd(){
		$this->fetch();
	}
	public function roleaddAddAction(){
		$rbac= new Rbac();
		$rbac->createRole([
			'name' => '内容管理员',
			'description' => '负责网站内容管理',
			'status' => 1
			], '1,2,3');
	}
	public function assignUserRole(){
		$rbac= new Rbac();
		$rbac->assignUserRole(1, [1]);
	}
	
}