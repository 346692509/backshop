<?php
namespace app\admin\controller;
use gmars\rbac\Rbac;
use Request;
use Db;

class Goods extends Common{
    public function index(){
        return $this->fetch('index');
    }
    public function show(){
        $id=request::post('id');
        $name=request::post('name');
        $arr=db::query("select * from goods left join goods_brand  on goods.brand_id1=goods_brand.brand_id left join goods_cate as cate on cate.id=goods.cate_id left join goods_img on goods.goods_id=goods_img.good_id where cate_id=$id");
        $cate=db('goods_cate')->where('name',$name)->find();
        $cate=db('goods_cate')->where('pid',$cate['id'])->select();
        $res=['status'=>'ok','code'=>'1','data'=>$arr,'cate'=>$cate];
        return json($res);
    }
    function getTree($array, $pid =0, $level = 0){
        //声明静态数组,避免递归调用时,多次声明导致数组覆盖
        static $list = [];
        foreach ($array as $key => $value){
            //第一次遍历,找到父节点为根节点的节点 也就是pid=0的节点
            if ($value['pid'] == $pid){
                //父节点为根节点的节点,级别为0，也就是第一级
                $flg = str_repeat('|--',$level);
                // 更新 名称值
                $value['name'] = $flg.$value['name'];
                // 输出 名称
                $id = $value['id'];
                echo "<option value='$id'>";
                echo $value['name'];
                echo "</option>";
                //把数组放到list中
                $list[] = $value;
                //把这个节点从数组中移除,减少后续递归消耗
                unset($array[$key]);
                //开始递归,查找父ID为该节点ID的节点,级别则为原级别+1
                $this->getTree($array, $value['id'], $level+1);
            }

        }
        return $list;
    }
    public function cate(){
        $data = db('goods_cate')->select();
        $this->getTree($data);
    }

    public  function product_add(){
        return $this->fetch('product_add');
    }
    public  function goods_show(){
        return $this->fetch('show');
    }
    public  function addaction(){
        $data=request::post();
        $res=db('goods')->insert($data);
        if ($res){
            $res=['status'=>'ok','code'=>'0','message'=>'添加成功'];
            return json($res);
        }
    }
    public function img(){
        $goods_id=request::post('goods_id');
        $myfile = request()->file('myfile');
//        var_dump($myfile);die;

        foreach($myfile as $file){
            // 移动到框架应用根目录/uploads/ 目录下
            $info = $file->validate(['size'=>1024*1024,'ext'=>'jpg,png,gif'])->move( './uploads');
            if($info){
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
                $img=str_replace("\\","/",$info->getSaveName());
                $name=$info->getFilename();
                $time=date('Ymd');
                $image = \think\Image::open("./uploads/$img");
                $image->thumb(150, 150)->save("./uploads/goodsimg/$time".'_big_'."$name");
                $image->thumb(100, 100)->save("./uploads/goodsimg/$time".'_middle_'."$name");
                $image->thumb(50, 50)->save("./uploads/goodsimg/$time".'_small_'."$name");
                $big_img="$time".'_big_'."$name";
                $middle_img="$time".'_middle_'."$name";
                $small_img="$time".'_small_'."$name";
                $res=db('goods_img')->insert(['big_img'=>$big_img,'middle_img'=>$middle_img,'small_img'=>$small_img,'good_id'=>$goods_id,'img'=>$img]);
            }
        }
        if($res){
            $res=['status'=>'ok','code'=>'0','message'=>'添加成功'];
            return json($res);
        }else{
            $res=['status'=>'error','code'=>'1','message'=>'添加失败'];
            return json($res);
        }
    }
    public function goods_img(){
        $goods_id=request::post('goods_id');
        $arr=db('goods_img')->where('good_id',$goods_id)->select();
        $res=['status'=>'error','code'=>'1','data'=>$arr];
        return json($res);
    }
    public function imgdelaction(){
        $id=request::post('id');
        $res1=db('goods_img')->where('img_id',$id)->find();
        $res=db('goods_img')->where('img_id',$id)->delete();
//        var_dump($res1);die;
        if ($res) {
            $big_img=$res1['big_img'];
            $middle_img=$res1['middle_img'];
            $small_img=$res1['small_img'];
            $img=$res1['img'];
            $a=$_SERVER['DOCUMENT_ROOT'];
            $big_img ="$a/backshop/tp5/public/uploads/goodsimg/$big_img";
            $middle_img ="$a/backshop/tp5/public/uploads/goodsimg/$middle_img";
            $small_img ="$a/backshop/tp5/public/uploads/goodsimg/$small_img";
            $img ="$a/backshop/tp5/public/uploads/$img";
            unlink($big_img);
            unlink($middle_img);
            unlink($small_img);
            unlink($img);
            $res2=['status'=>'ok','code'=>'1','message'=>'删除成功'];
            return json($res2);
        }
    }
    public function goodsdelaction(){
        $id=request::post('id');
        $res1=db('goods_img')->where('good_id',$id)->select();
        if (empty($res1)){
            $res=db('goods')->where('goods_id',$id)->delete();
            $res2=['status'=>'ok','code'=>'1','message'=>'删除成功1'];
            return json($res2);
        }else{
            $res=db('goods')->where('goods_id',$id)->delete();
            if ($res){
                $a=$_SERVER['DOCUMENT_ROOT'];
                $img=[];
                foreach ($res1 as $re) {
//                var_dump($re);
                    $img[]="$a/backshop/tp5/public/uploads/goodsimg/".$re['big_img'];
                    $img[]="$a/backshop/tp5/public/uploads/goodsimg/".$re['middle_img'];
                    $img[]="$a/backshop/tp5/public/uploads/goodsimg/".$re['small_img'];
                    $img[]="$a/backshop/tp5/public/uploads/".$re['img'];
                }
                foreach ($img as $img){
                    unlink($img);
                }
                $res2=['status'=>'ok','code'=>'1','message'=>'删除成功'];
                return json($res2);
            }
        }
    }
}