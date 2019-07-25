<?php
namespace app\admin\controller;
use app\admin\model\Permissioncate as PermissioncateModel; 
use Request;
use gmars\rbac\Rbac;
class Permissioncate extends Common{

	public function index(){
		$Permissioncate =new PermissioncateModel;
		$obj=$Permissioncate->select();
		$this->assign('arr',$obj);        //查询权限表数据传值
		return $this->fetch('list');      
	}
	public function addAction(){
		$data=Request::post();
		$validate = new \app\admin\validate\Permissioncate;
		if (!$validate->check($data)) {
    		$res=['status'=>'error','code'=>'2','message'=>$validate->getError()];
    		echo $json=json_encode($res);
    		die;
		}
		$sele=db('permission_category')->where('name',$data['rolename'])->find();
		if (!empty($sele)) {
			$res=['status'=>'error','code'=>'1','message'=>'该权限名称已存在'];
			echo $json=json_encode($res);
			die;
		}	
		$permissioncate =new PermissioncateModel;
		$time=strtotime(date('Y-m-d H:i:s'));
		$permissioncate->name = $data['rolename'];
		$permissioncate->description = $data['dtion'];
		$permissioncate->create_time = $time;
		$save=$permissioncate->save();
		if ($save==true) {
			$res=['status'=>'ok','code'=>'0','message'=>'添加成功'];
			echo $json=json_encode($res);
			die;
		}			
	}
	public function updateAction(){
		$data=Request::post();
		$validate = new \app\admin\validate\Permissioncate;
		if (!$validate->check($data)) {
    		$res=['status'=>'error','code'=>'2','message'=>$validate->getError()];
    		echo $json=json_encode($res);
    		die;
		}
		$sele=db('permission_category')->where('name',$data['rolename'])->find();
		if (!empty($sele)) {
			$res=['status'=>'error','code'=>'1','message'=>'该权限名称已存在'];
			echo $json=json_encode($res);
			die;
		}
		unset($data['__token__']);
		$time=strtotime(date('Y-m-d H:i:s'));
		$date=['name'=>$data['rolename'],'description'=>$data['dtion'],'create_time'=>$time];
		$save=db('permission_category')->where('id',$data['id'])->update($date);
		if ($save==true) {
			$res=['status'=>'ok','code'=>'0','message'=>'修改成功'];
			echo $json=json_encode($res);
			die;
		}
	}
	public function updataAt(){
			$data=Request::post();
			if($data['name'] == $data['rolename'] || $data['rolename'] == ""){
				$res=['status'=>'无修改','code'=>'1'];
				echo $json=json_encode($res);
				die;
			}
			$res = db('permission_category') ->where('id', $data['id'])->update([$data['key'] => $data['rolename']]);
			if ($res==true) {
			$res=['status'=>'修改成功','code'=>'0'];
			echo $json=json_encode($res);
			die;
		}

	}
	public function delAction(){
		$data=Request::post();
		$validate = new \app\admin\validate\Del;
		if (!$validate->check($data)) {
    		$res=['status'=>$validate->getError(),'code'=>'1'];
    		echo $json=json_encode($res);
    		die;
		}
		$data = explode(",", $data['id']);
        $rbac= new Rbac();
        $resa=$rbac->delPermissionCategory($data);
        if ($resa) {
        	$res=['status'=>'ok','code'=>'0'];
       		 echo $json=json_encode($res); 
        } 
	}
	public function show(){
		$Permissioncate =new PermissioncateModel;
		$obj=$Permissioncate->select();
		$arr=json_decode($obj,true);
		$res=['status'=>'ok','code'=>'0','data'=>$arr];
		echo $json=json_encode($res);
	}
	
}
