<?php
namespace app\admin\controller;
use gmars\rbac\Rbac;
use Request;
use Db;
use think\facade\Cache;

class Attribute extends Common
{
    public function index()
    {
        return $this->fetch('index');
    }
    public function show(){
        $cate_id=Request::post('cate_id');
        $arr1=Cache::get('name');
        if (!$arr1){
            $arr=db('attribute')->where('attrcate_id',$cate_id)->select();
            Cache::set('name',$arr);
            $res=['status'=>'ok','code'=>'0','data'=>$arr];
            echo $json=json_encode($res);
        }else{
            $res=['status'=>'ok','code'=>'1','data'=>$arr1];
            echo $json=json_encode($res);
        }
    }
    public function detailsshow(){
        $ibute_id=Request::post('ibute_id');
        $arr=db::query("select * from attr_details where attr_id=$ibute_id");
        $res=['status'=>'ok','code'=>'0','data'=>$arr];
        return json($res);
    }
    public function attr_cate(){
        $arr=db('attr_category')->select();
        $res=['status'=>'ok','code'=>'0','data'=>$arr];
        return json($res);
    }
    public function attr_add(){
        $cate_name=Request::post('cate_name');
        $arr=db('attr_category')->where('cate_name',$cate_name)->find();
        if (!empty($arr)){
            $res2=['status'=>'error','code'=>'1','message'=>'已存在'];
            return json($res2);
        }else{
            $arr=db('attr_category')->insert(['cate_name'=>$cate_name]);
            if ($arr){
                $res2=['status'=>'ok','code'=>'1','message'=>'添加成功'];
                return json($res2);
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
    public function attr_details_del(){
        $data=Request::post();
        $data=substr($data['id'], 0, -1);
        $data = explode(",", $data);
        foreach ($data as $id){
            $res=db('attr_details')->where('id',$id)->delete();
        }
        if ($res){
            $res2=['status'=>'ok','code'=>'0','message'=>'删除成功'];
            return json($res2);
        }else{
            $res2=['status'=>'error','code'=>'1','message'=>'删除失败'];
            return json($res2);
        }
    }
    public function attr_details_add(){
        $data=Request::post();
        if (empty($data['details_name'])){
            $res2=['status'=>'error','code'=>'1','message'=>'请输入内容'];
            return json($res2);
        }else{
            $res=db('attr_details')->insert(['attr_id'=>$data['ibute_id'],'details_name'=>$data['details_name']]);
            if ($res){
                $res2=['status'=>'ok','code'=>'0','message'=>'添加成功'];
                return json($res2);
            }else{
                $res2=['status'=>'error','code'=>'0','message'=>'添加成功'];
                return json($res2);
            }
        }
    }
    public function attribute_add(){
        $cate_id=Request::post('cate_id');
        $ibute_name=Request::post('ibute_name');
        $arr=db('attribute')->where('ibute_name',$ibute_name)->where('attrcate_id',$cate_id)->select();
        if (!empty($arr)){
            $res2=['status'=>'error','code'=>'1','message'=>'已存在'];
            return json($res2);
        }if(empty($ibute_name)){
            $res2=['status'=>'error','code'=>'1','message'=>'不能为空'];
            return json($res2);
        } else{
            $arr=db('attribute')->insert(['attrcate_id'=>$cate_id,'ibute_name'=>$ibute_name]);
            if ($arr){
                $res2=['status'=>'ok','code'=>'1','message'=>'添加成功'];
                return json($res2);
            }
        }
    }
    public function attribute_del(){
        $ibute_id=Request::post('ibute_id');
        $arr=db('attribute')->delete(['ibute_id'=>$ibute_id]);
        if ($arr){
            db('attr_details')->where(['attr_id'=>$ibute_id])->delete();
            $res2=['status'=>'ok','code'=>'1','message'=>'删除成功'];
            return json($res2);
        }else{
            $res2=['status'=>'ok','code'=>'1','message'=>'添加失败'];
            return json($res2);
        }
    }
}