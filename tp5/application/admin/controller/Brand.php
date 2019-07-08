<?php
namespace app\admin\controller;
use gmars\rbac\Rbac;
use app\admin\model\Brand as BrandModel; 
use Request;

class Brand extends Common{
	public function initialize(){
		parent::initialize();
		$action=Request::action();
		$action='admin/brand/'.$action;
		$rbac= new Rbac();
		$a=$rbac->can($action);
		// var_dump($a);die;
		$param=Request::has('html_type','param');
		if ($a==false&&$param) {
			$res=['status'=>'error','code'=>'0','data'=>'没有权限'];
			echo $json=json_encode($res);
			die;
			 	
		}else if($a==false){
			echo "您没权限";
			$this->redirect('index/noControl'); 
			die;
		}
	}

	public function list()
	{
		return $this->fetch();
	}

	public function addAction(){
		$brand_name=Request::post('brand_name');
		$brand = new BrandModel;
		$brand->name = $brand_name;
		$brand->save();		
	}
	public function show(){
		$brand =new BrandModel;
		$obj=$brand->select();
		$arr=json_decode($obj,true);
		$res=['status'=>'ok','code'=>'0','data'=>$arr];
		echo $json=json_encode($res);
	}
}
