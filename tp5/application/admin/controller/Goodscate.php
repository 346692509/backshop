<?php
namespace app\admin\controller;
use gmars\rbac\Rbac;
use Request;

class Goodscate extends Common{
    public function index(){
        return $this->fetch('index');
    }
    public function aa(){
        return $this->fetch('aa');
    }
//    private function getTree($array, $pid =0, $level = 0){
//
//        $f_name=__FUNCTION__; // 定义当前函数名
//        //声明静态数组,避免递归调用时,多次声明导致数组覆盖
//        static $list = [];
//        echo "<ul >";
//        foreach ($array as $key => $value){
//            //第一次遍历,找到父节点为根节点的节点 也就是pid=0的节点
//            if ($value['pid'] == $pid){
////                echo $value['pid']."--p的id";
//                //父节点为根节点的节点,级别为0，也就是第一级
////                $flg = str_repeat('|--',$level);
//                // 更新 名称值
////                $value['name'] = $value['name'];
//                // 输出 名称
//                echo "<li class='' ".$value['id'].">".$value['name']."</h4></li>";
//                //把数组放到list中
//                $list[] = $value;
////                var_dump($list);
//                //把这个节点从数组中移除,减少后续递归消耗
//                unset($array[$key]);
//                //开始递归,查找父ID为该节点ID的节点,级别则为原级别+1
////                echo $value['id']."--value的id";
//                $this->getTree($array, $value['id'], $level+1);
//            }
//        }
//        echo "</ul>";
//        return $list;
//    }
    public function show() {
        $data = db('goods_cate')->select();
        $res=['status'=>'error','code'=>'1','data'=>$data];
        return json($res);
    }
    public function addAction(){
        $data=Request::post();
        $validate = new \app\admin\validate\Category;
        if (!$validate->check($data)) {
            $res=['status'=>'error','code'=>'2','message'=>$validate->getError()];
            return json($res);
        }
        $arr=db('goods_cate')->where('name',$data['name'])->find();
        if (empty($arr)){
            $arr1=db('goods_cate')->insert(['name'=>$data['name'],'pid'=>$data['id']]);
            if ($arr1){
                $res=['status'=>'ok','code'=>'0','message'=>'添加成功'];
                return json($res);
            }
        }else{
            $res=['status'=>'ok','code'=>'1','message'=>'添加失败'];
            return json($res);
        }
    }
    public function updateAction(){
        $data=Request::post();
        $validate = new \app\admin\validate\Category;
        if (!$validate->check($data)) {
            $res=['status'=>'error','code'=>'2','message'=>$validate->getError()];
            return json($res);
        }
        $arr=db('goods_cate')->where('name',$data['name'])->find();
        if (empty($arr)||empty($arr)&&$arr['id']==$data['id']){
            $arr1=db('goods_cate')->where('id',$data['id'])->update(['name'=>$data['name']]);
            if ($arr1){
                $res=['status'=>'ok','code'=>'0','message'=>'修改成功'];
                return json($res);
            }
        }else{
            $res=['status'=>'ok','code'=>'1','message'=>'修改失败'];
            return json($res);
        }
    }
    public function delAction(){
        $id=Request::post('id');
//        $validate = new \app\admin\validate\del;
//        if (!$validate->check($data)) {
//            $res=['status'=>'error','code'=>'2','message'=>$validate->getError()];
//            return json($res);
//        }
        $a=$this->getSon($id);
        $count=count($a);
        if ($count<=1){
            db('goods_cate')->where('id',$id)->delete();
            $res=['status'=>'ok','code'=>'1','message'=>'删除成功'];
            return json($res);
        }else{
            db('goods_cate')->where('id',$id)->delete();
            foreach ($a as$k=>$v) {
                $res=db('goods_cate')->where('id',$v)->delete();
            }
            $res=['status'=>'ok','code'=>'1','message'=>'删除成功'];
            return json($res);
        }



    }
    private  function getSon($id,$array=array(),$level=1)
    {
        $f_name=__FUNCTION__; // 定义当前函数名
        static $list;
        $array=db('goods_cate')->where('pid',$id)->select();
        foreach ($array as $k => $v)
        {
            if($v['pid'] == $id)
            {
//                $flg = str_repeat('|--',$level);
                // 更新 名称值
//                $v['id'] = $v['id'];
                // 输出 名称
                $v['id']."<br/>";
                //存放数组中
                $list[] = $v['id'];
                // 删除查询过的数组
                 unset($array[$k]);
                $this->getSon($v['id'],$array,$level+1);
        }
        }
        return($list) ;
    }
}