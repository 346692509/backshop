<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\Permission as PermissionModel; 
use app\admin\model\Permissioncate as PermissioncateModel; 
use Request;
use gmars\rbac\Rbac;
use Db;
class Permission extends Common{
    public function list(){
        $arr=Db::query('select p.id,p.name,pc.name as pcname,p.path,p.description,p.create_time,pc.id as pcid from permission as p inner join permission_category as pc on p.category_id=pc.id');
        $this->assign('arr',$arr);
        $crr=db('permission_category')->select();
        $this->assign('crr',$crr);
        return $this->fetch();      
    }
    public function addAction(){
		$data=Request::post();
        $validate = new \app\admin\validate\Permission;
        if (!$validate->check($data)) {
            $res=['status'=>'error','code'=>'2','message'=>$validate->getError()];
            echo $json=json_encode($res);
            die;
        }
        $sele=db('permission')->where('name',$data['name'])->find();
        if (!empty($sele)) {
            $res=['status'=>'error','code'=>'1','message'=>'权限节点名称已存在'];
            echo $json=json_encode($res);
            die;
        }
        $time=strtotime(date('Y-m-d H:i:s'));
		$permission =new PermissionModel;
		$permission->name = $data['name'];
		$permission->category_id = $data['cateid'];
        $permission->path = $data['path'];
        $permission->description = $data['description'];
        $permission->create_time = $time;
		$permission->save();	
		$res=['status'=>'ok','code'=>'0','message'=>'添加成功'];
		echo $json=json_encode($res);
        die;	
	}

    public function delAction(){
        $data=Request::post();
        $validate = new \app\admin\validate\Del;
        if (!$validate->check($data)) {
            $res=['status'=>$validate->getError(),'code'=>'1'];
            echo $json=json_encode($res);
            die;
        }
//        unset($data['__token__']);
//        $result=explode(',', $data);
        $data = explode(",", $data['id']);
        $rbac= new Rbac();
        $rbac->delPermission($data);
        $res=['status'=>'ok','code'=>'0','message'=>'删除成功'];
        echo $json=json_encode($res);
    }

    public function updateAction(){
        $data=Request::post();
        $validate = new \app\admin\validate\Permission;
        if (!$validate->check($data)) {
            $res=['status'=>'error','code'=>'2','message'=>$validate->getError()];
            echo $json=json_encode($res);
            die;
        }
        $sele=db('permission')->where('name',$data['name'])->find();
        if (!empty($sele)) {
            $res=['status'=>'error','code'=>'1','message'=>'该权限名称已存在'];
            echo $json=json_encode($res);
            die;
        }
        $sele1=db('permission')->where('path',$data['path'])->find();
        if (!empty($sele1)) {
            $res=['status'=>'error','code'=>'4','message'=>'该路径已存在'];
            echo $json=json_encode($res);
            die;
        }
        unset($data['__token__']);  
        $time=strtotime(date('Y-m-d H:i:s'));
        $date=['name'=>$data['name'],'description'=>$data['description'],'path'=>$data['path'],'category_id'=>$data['category_id'],'create_time'=>$time];
        $save=db('permission')->where('id',$data['id'])->update($date);
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
        $res = db('permission') ->where('id', $data['id'])->update([$data['key'] => $data['rolename']]);
        if ($res==true) {
            $res=['status'=>'修改成功','code'=>'0'];
            echo $json=json_encode($res);
            die;
        }

    }
    public function show(){
    	$Permissioncate =new PermissionModel;
		$obj=$Permissioncate->select();
        $this->assign('permission',$obj);
		// $arr=json_decode($obj,true);
		// $res=['status'=>'ok','code'=>'0','data'=>$arr];
		// echo $json=json_encode($res);
    	
    }
}
