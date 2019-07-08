<?php
namespace app\admin\controller;
use think\Controller;
use think\facade\Session;
use gmars\rbac\Rbac;
class Index extends Common{

    public function index(){
    
        return $this->fetch('index');      
    }
    public function noControl(){
    	
    	return $this->fetch('nocontrol'); 
    }
}
