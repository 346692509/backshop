<?php
namespace app\admin\controller;
use app\admin\model\Permissioncate as PermissioncateModel; 
use Db;
use Request;
class User extends Common{

	public function list(){
		$res=db::query('select `user`.id ,`user`.user_name,`user`.mobile,`user`.create_time,role.name,role.`status`,role.id as rid from `user` inner join user_role  on user.id=user_role.user_id inner join role on user_role.role_id=role.id');
		$arr=db('role')->select();  //role表的信息
		$this->assign('arr',$arr);        
		$this->assign('res',$res);
		return $this->fetch('list');
	}
	//添加
	public function addAction(){
        $data=Request::post();
		$validate = new \app\admin\validate\User;
        if (!$validate->check($data)) {
            $res=['status'=>'error','code'=>'1','message'=>$validate->getError()];
            echo $json=json_encode($res);
            die;
        }
		$time=strtotime(date('Y-m-d H:i:s'));
		$name=db('user')->where('user_name','=',$data['user_name'])->find();
		$phone=db('user')->where('mobile','=',$data['phone'])->find();
		if (!empty($name)) {
            $res=['status'=>'error','code'=>'1','message'=>'用户名已存在'];
			echo $json=json_encode($res);
			die;
		}
		if (!empty($phone)) {
            $res=['status'=>'error','code'=>'1','message'=>'手机号已存在'];
			echo $json=json_encode($res);
			die;
		}else{
			$date=['user_name'=>$data['user_name'],'password'=>md5($data['password']),'mobile'=>$data['phone'],'create_time'=>$time,'status'=>'1'];
			$res=db('user')->insert($date);
			if ($res==true) {
				$res=['status'=>'ok','code'=>'0','message'=>'添加成功'];
				echo $json=json_encode($res);
				$arr=db('user')->where('user_name','=',$data['user_name'])->find();
				$date1=['user_id'=>$arr['id'],'role_id'=>$data['role_id']];
				db('user_role')->insert($date1);
			}			
		}
	}
	public function updateAction(){
		$data=Request::post();
		$validate = new \app\admin\validate\User;
        if (!$validate->check($data)) {
            $res=['status'=>'error','code'=>'0','message'=>$validate->getError()];
            echo $json=json_encode($res);
            die;
        }
		$id=$data['id'];
        $phone=$data['phone'];
		$name=$data['user_name'];
		$user=db::query("select * from user where user_name = '$name' or mobile='$phone'");
		if (empty($user)||!empty($user)&&$id==$user[0]['id']) {
            $time=strtotime(date('Y-m-d H:i:s'));
            $date=['user_name'=>$data['user_name'],'password'=>md5($data['password']),'mobile'=>$data['phone'],'update_time'=>$time,'status'=>'1'];
            $res=db('user')->where('id',$id)->update($date);
            if ($res==true) {
                $res=['status'=>'ok','code'=>'0','message'=>'修改成功'];
                echo $json=json_encode($res);
                // $arr=db('user')->where('user_name','=',$user_name)->find();
                $date1=['role_id'=>$data['role_id1']];
                db('user_role')->where('user_id',$id)->update($date1);
            }
		}else{
                $res=['status'=>'error','code'=>'1','message'=>'手机号或者名称存在'];
                echo $json=json_encode($res);
		}
	}
	public function delAction(){
		$data=Request::post();
		$data1 = explode(",", $data['id']);
		$res=db('user')->where('id',$data['id'])->delete();
		if ($res) {
			$res1=db('user_role')->where('user_id',$data['id'])->delete();
			if ($res1) {
				$res=['status'=>'ok','code'=>'0','message'=>'删除成功'];
				return json($res);
			}
		}else{
			$res=['status'=>'error','code'=>'1','message'=>'删除失败'];
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
        $res = db('user') ->where('id', $data['id'])->update([$data['key'] => $data['rolename']]);
        if ($res==true) {
            $res=['status'=>'修改成功','code'=>'0'];
            echo $json=json_encode($res);
            die;
        }

    }
}