<?php
namespace app\admin\controller;
use think\Controller;
use Request;
use think\facade\Session;
use gmars\rbac\Rbac;
class Login extends Controller
{
    public function login() {
        return $this->fetch('login');
    }
    public function loginAction() {
        $user=Request::post('user');
        $pwd=Request::post('pwd');
        $res=db('user')->where('user_name',$user)->find();
        $code=Request::post('code');
        if(!captcha_check($code)){
            $arr=['status'=>'error','code'=>'1','message'=>'验证码错误'];
        }elseif($res['user_name']!=$user || $res['password']!=md5($pwd)){
            $arr=['status'=>'error','code'=>'2','message'=>'用户名或密码错误'];
        }else{
            $arr=['status'=>'seccuss','code'=>'0','message'=>'登录成功'];
            Session::set('name',$user);
             $rbac= new Rbac();
             $rbac->cachePermission($res['id']);
        }
        $json=json_encode($arr);
        echo $json;
    } 
    public function loginOut() {
        Session::clear();
        $this->redirect('login/login');
    }
}
