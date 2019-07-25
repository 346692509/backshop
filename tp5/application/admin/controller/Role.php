<?php
namespace app\admin\controller;
use Request;
use Db;
use gmars\rbac\Rbac;
class Role extends Common{

	public function index(){
		$arr=db::query('select * from role ');
		$this->assign('arr',$arr);
		return $this->fetch('role');
	}
	public function updata(){
		$data=Request::post();
		$id=$data['id'];
		$arr=db::query("select permission_id  from role_permission  where role_id=$id");
		$arr = ['code'=>0,'status'=>'success','data'=>$arr];
		return json($arr);

	}
	public function permissionShow(){
		$sql = "select p.id,p.name,p.category_id,p.path,p.description,pc.name as pc_name from permission p inner join permission_category pc on p.category_id=pc.id";
		$res=Db::query($sql);
		$my_res=[];
		foreach ($res as $k => $v){
			$my_res[$v['pc_name']][]=$v;
		}
		$arr = ['code'=>0,'status'=>'success','data'=>$my_res];
		return json($arr);
	}
	public function addAction(){
		$data=Request::post();
		$validate = new \app\admin\validate\Role;
		if (!$validate->check($data)) {
			$res=['status'=>'error','code'=>'2','message'=>$validate->getError()];
			return json($res);
		}
		$res=db('role')->where('name',$data['name'])->find();
		if (!empty($res)) {
			$res=['status'=>'error','code'=>'3','message'=>'角色名已经存在'];
			return json($res);
		}
		$res=db('role')->where('description',$data['description'])->find();
		if (!empty($res)) {
			$res=['status'=>'error','code'=>'4','message'=>'描述已经存在'];
			return json($res);
		}
		$rbac = new Rbac();
		$res=$rbac->createRole([
			'name' => $data['name'],
			'description' => $data['description'],
			'status' => 1
		], $data['category_id']);
		if ($res==true) {
			$res=['status'=>'ok','code'=>'4','message'=>'添加成功'];
			return json($res);
		}
	}
	public function updateAction(){
		$data=Request::post();
		$name=$data['name'];
        $id=$data['id'];
        $validate = new \app\admin\validate\Role;
        if (!$validate->check($data)) {
            $res=["code"=>"1","status"=>"error","message"=>$validate->getError()];
            return json($res);
        }else{
            $sel=Db::query("select * from role where name='$name'");
            if (empty($sel)||!empty($sel)&&$sel[0]['id']==$id){

                $uprole=Db::name('role')->where('id', $id)->update(['name' => $name,'description' => $data['description']]);
                //删除
                $del=Db::table('role_permission')->where('role_id',$data['id'])->delete();

                $p_id=explode(",",$data['category_id']);
                foreach ($p_id as $k=>$v){
                    $add=Db::query("insert into `role_permission` (`role_id`,`permission_id`) values ('$id','$v')");
                }
                $res=["code"=>"0","status"=>"ok","message"=>"修改成功"];
            }else{
                $res=["code"=>"1","status"=>"error","message"=>"您要修改的角色名或权限已存在！"];
            }
            return json($res);
            die;
        }
    }
	public function delAction(){
		$data=Request::post();
		$data = explode(",", $data['id']);
		$rbac = new Rbac();
		$res=$rbac->delRole($data);
		if ($res) {
			$res=['status'=>'ok','code'=>'4','message'=>'删除成功'];
			return json($res);
		}
	}
    public function updataAt(){
        $data=Request::post();
        if($data['name'] == $data['rolename'] || $data['rolename'] == ""){
            $res=['status'=>'无修改','code'=>'1'];
            echo $json=json_encode($res);
            die;
        }
        $res = db('role') ->where('id', $data['id'])->update([$data['key'] => $data['rolename']]);
        if ($res==true) {
            $res=['status'=>'修改成功','code'=>'0'];
            echo $json=json_encode($res);
            die;
        }

    }
//    public function show(){
//        $arr=db::query('select * from role ');
//        $res=['status'=>'error','code'=>'3','data'=>$arr];
//        return json($res);
//    }
}

