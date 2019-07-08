<?php
namespace app\admin\controller;
use think\Controller;
use Request;
use think\facade\Session;
use gmars\rbac\Rbac;
class Common extends Controller{
    public function __construct(){
        parent::__construct();
		$a=Session::get('name');
        if(empty($a)){
            $this->redirect('login/login');
        }else{
        	$this->assign('name',$a); 
        }
        $module=Request::module();
        $controller=Request::controller();
        $action=Request::action();
        $arr_controller=['Permission','Permissioncate','Role','User'];
        $arr_action=['list','delaction','updateaction','addaction','updataat'];
        if (in_array($controller, $arr_controller)){
            if (in_array($action,$arr_action)){
                $str="$module/$controller/$action";
                $str-strtolower($str);
                $rbac= new Rbac();
                $bool=$rbac->can($str);
                if (!$bool){
                    header("Content-Type:application/json");
                    $res=['status'=>'error','code'=>'1001','message'=>'没有权限'];
                    echo json_encode($res);
                    die;
                }
            }
        }
    }
    public function token(){
    	$token = $this->request->token('__token__', 'sha1');
    	$arr= ['token'=>$token];
        echo json_encode($arr); 
    }
}