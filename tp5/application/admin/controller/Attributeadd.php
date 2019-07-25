<?php
namespace app\admin\controller;
use gmars\rbac\Rbac;
use Request;
use Db;

class Attributeadd extends Common
{
    public function index()
    {
        return $this->fetch('goods/product_add');
    }
    function detailsshow(){
        $ibute_id=Request::post('ibute_id');
        $arr=db::query("select * from attr_category join attribute  on attr_category.cate_id=attribute.attrcate_id  join attr_details  on attribute.ibute_id=attr_details.attr_id where cate_id=$ibute_id");
        $my_res=[];
        foreach ($arr as $k => $v){
            $my_res[$v['ibute_name']][]=$v;
        }
        $res=['status'=>'ok','code'=>'0','data'=>$my_res];
        return json($res);
    }
    function goodsattr_add(){
        $goods_id=Request::post('aid');
        $cate_id=Request::post('cate_id');
        $category_id=Request::post('category_id');
        if (empty($category_id)){
            $res2=['status'=>'error','code'=>'1','message'=>'请选择属性'];
            return json($res2);
        }
        $arr=db('goods_attr')->where('goods_id',$goods_id)->select();
        if (!empty($arr)){
            db('goods_attr')->where('goods_id',$goods_id)->delete();
        }
        $category_id=explode(',',$category_id);
        foreach ($category_id as $category_id){
            $category_id=explode('-',$category_id);
            $res=db('goods_attr')->insert(['goods_id'=>$goods_id,'attr_details_id'=>$category_id[0],'attr_id'=>$category_id[1]]);
        }
        if ($res){
            db('goods')->where('goods_id',$goods_id)->update(['attr_category_id'=>$cate_id]);
            $res2=['status'=>'ok','code'=>'1','message'=>'添加成功'];
            return json($res2);
        }
    }
    function attr_category_id(){
        $goods_name=Request::post('goods_name');
        $arr=db('goods')->where('goods_name',"$goods_name")->find();
        $res2=['status'=>'ok','code'=>'1','data'=>$arr['attr_category_id']];
        return json($res2);
    }
    function attrbute_look(){
        $goods_id=Request::post('goods_id');
        $arr=db('goods')->where('goods_id',"$goods_id")->find();
        if(!empty($arr['attr_category_id'])){
            $arr=db::query("select attr_details_id from goods join goods_attr on goods.goods_id=goods_attr.goods_id where goods.goods_id=$goods_id");
            $res2=['status'=>'ok','code'=>'1','data'=>$arr];
            return json($res2);
        }
    }
    function product(){
        $goods_id=Request::post('goods_id');
        $arr=db::query("select goods.goods_id,goods.goods_name,attribute.ibute_name,attribute.ibute_id,attr_details.id,attr_details.details_name,goods_attr_id from goods join goods_attr on goods.goods_id=goods_attr.goods_id join attribute on goods_attr.attr_id=attribute.ibute_id join attr_details on goods_attr.attr_details_id=attr_details.id where goods.goods_id=$goods_id");
        foreach ($arr as $k => $v){
            $my_res[$v['ibute_name']][]=$v;
        }
        $res=['status'=>'ok','code'=>'0','data'=>$my_res];
        return json($res);
    }
    function productadd_action(){
        $data=Request::post();
        $number=$data['number'];
        $num=db('product')->where('number',$number)->find();
        if (!empty($num)){
            $res2=['status'=>'error','code'=>'1','message'=>'货号已经存在！'];
            return json($res2);
        }
        if (empty($data['price'])){
            $res2=['status'=>'error','code'=>'1','message'=>'请填写售价！'];
            return json($res2);
        }
        if (empty($data['inventory'])){
            $res2=['status'=>'error','code'=>'1','message'=>'请填写库存！'];
            return json($res2);
        }
//        var_dump($number);die;
        if (empty($number)){
            $str = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
            $num='mwt'.$str;
            $number=$num;
        }
        $arr=db('product')->insert(['product_goods_id'=>$data['goods_id'],'attr_details_id'=>$data['attr_details_id'],'attr_details_name'=>$data['attr_details_name'],'price'=>$data['price'],'inventory'=>$data['inventory'],'number'=>$number]);
        if ($arr){
            $res2=['status'=>'ok','code'=>'1','message'=>'添加成功'];
            return json($res2);
        }
    }
    /////////////////////////////////////////////////////////
    function goods_show(){
        $goods_id=Request::post('goods_id');
//        $arr=db::query("select * from product where product_goods_id=$goods_id");
        $arr=db('product')->where('product_goods_id',$goods_id)->select();
        for ($i=0;$i<count($arr);$i++){
                $new_arr=[];
//            echo $arr['goods_attr_id'];
            $arr1=explode(',',$arr[$i]['attr_details_id']);
            for ($o=0;$o<count($arr1);$o++){
//                echo "<pre>";
//                var_dump($arr1[$o]);
                $details_id=$arr1[$o];
                $res=db('attr_details')->where('id',$details_id)->select();
//                echo "<pre>";
//                var_dump($res);
                $new_arr[]=$res[0]['details_name'];
            }
            $arr1=implode('-',$new_arr);
            $arr[$i]['details_name']=$arr1;
        }
        $res2=['status'=>'ok','code'=>'1','data'=>$arr];
        return json($res2);
    }
}