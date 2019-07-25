<?php
namespace app\admin\controller;
use gmars\rbac\Rbac;
use app\admin\model\Brand as BrandModel; 
use Request;

class Goodsbrand extends Common{
	public function initialize(){
		parent::initialize();
//		$action=Request::action();
//		$action='admin/brand/'.$action;
//		$rbac= new Rbac();
//		$a=$rbac->can($action);
//		// var_dump($a);die;
//		$param=Request::has('html_type','param');
//		if ($a==false&&$param) {
//			$res=['status'=>'error','code'=>'0','data'=>'没有权限'];
//			echo $json=json_encode($res);
//			die;
//
//		}else if($a==false){
//			echo "您没权限";
//			$this->redirect('index/noControl');
//			die;
//		}
	}

	public function index(){
		return $this->fetch('list');
	}
    public function show(){
//        $brand =new BrandModel;
//        $obj=$brand->select();
        $arr=db('goods_brand')->select();
//        $arr=json_decode($obj,true);
        $res=['status'=>'ok','code'=>'0','data'=>$arr];
        echo $json=json_encode($res);
    }

	public function addAction(){
        $myfile = request()->file('myfile');
		$data=Request::post();
        $name=$data['name'];
        $validate = new \app\admin\validate\Brand;
        if (!$validate->check($data)) {
            $res=["code"=>"1","status"=>"error","message"=>$validate->getError()];
            return json($res);
        }
        if (empty($myfile)){
            $res=["code"=>"1","status"=>"error","message"=>'LOGO不能为空'];
            return json($res);
        }
        $res=db('goods_brand')->where('brand_name',$name)->find();
        if (!empty($res)){
            $res=["code"=>"1","status"=>"error","message"=>'该品牌已存在'];
            return json($res);
        }else{
            // 移动到框架应用根目录/uploads/ 目录下
            $info = $myfile->validate(['size'=>1024*1024,'ext'=>'jpg,png,gif'])->move( './uploads');
            if($info){
                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                //echo $info->getSaveName();
                $logo=str_replace("\\","/",$info->getSaveName());
                db('goods_brand')->insert(['brand_name'=>$data['name'],'brand_description'=>$data['brand_description'],'brand_logo'=>$logo]);
                $res=["code"=>"0","status"=>"ok","message"=>'添加成功'];
                return json($res);
            }else{
                // 上传失败获取错误信息
                $res=["code"=>"0","status"=>"error","message"=>'图片太大了！'];
                return json($res);
            }
        }
	}
    public function updateAction(){
        $file = request()->file('file');
        $data=Request::post();
        $name=$data['name'];
        $validate = new \app\admin\validate\Brand;
        if (!$validate->check($data)) {
            $res=["code"=>"1","status"=>"error","message"=>$validate->getError()];
            return json($res);
        }
        $res=db('goods_brand')->where('brand_name',$name)->find();
        if (empty($file)){
            if (empty($res)||!empty($res)&&$res['brand_id']==$data['id']){
                db('goods_brand')->where('brand_id',$data['id'])->update(['brand_name'=>$name,'brand_description'=>$data['brand_description']]);
                $res=["code"=>"0","status"=>"ok","message"=>'修改成功'];
                return json($res);
            }else{
                $res=["code"=>"1","status"=>"error","message"=>'该品牌已存在'];
                return json($res);
            }
        }else{
            if (empty($res)||!empty($res)&&$res['brand_id']==$data['id']){
                $res1=$data['logo'];
                $a=$_SERVER['DOCUMENT_ROOT'];
                $to_link ="$a/backshop/tp5/public/uploads/$res1";
                unlink($to_link);
                $info = $file->validate(['size'=>1024*1024,'ext'=>'jpg,png,gif'])->move( './uploads');
                $logo=str_replace("\\","/",$info->getSaveName());
                db('goods_brand')->where('brand_id',$data['id'])->update(['brand_name'=>$name,'brand_description'=>$data['brand_description'],'brand_logo'=>$logo]);
                $res=["code"=>"0","status"=>"ok","message"=>'修改成功'];
                return json($res);
            }else{
                $res=["code"=>"1","status"=>"error","message"=>'该品牌已存在'];
                return json($res);
            }
        }
    }
    public function delAction(){
        $data=Request::post();
//        $data1 = explode(",", $data['id']);
        $res1=db('goods_brand')->where('brand_id',$data['id'])->find();
        $res=db('goods_brand')->where('brand_id',$data['id'])->delete();
        if ($res) {
            $res1=$res1['brand_logo'];
            $a=$_SERVER['DOCUMENT_ROOT'];
            $to_link ="$a/backshop/tp5/public/uploads/$res1";
            unlink($to_link);
            $res2=['status'=>'ok','code'=>'1','message'=>'删除成功'];
            return json($res2);
        }
    }
}
