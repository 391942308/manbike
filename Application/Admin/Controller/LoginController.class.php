<?php
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller {
	public function index(){
		$this->display();
	}
	public function dologin(){
		$database_admin = D('auth_user');
		$username = $condition_admin['username'] = $_POST['username'];
		$now_admin = $database_admin->field(true)->where($condition_admin)->find();
		if(empty($now_admin)){
			$this->error("登陆失败");
			exit('-2');
		}
		$password = md5($_POST['password']);
		if($password != $now_admin['password']){
			$this->error("登陆失败");
			exit('-3');
		}
		if($password == $now_admin['password']){
			session_start();
			session('auth',$now_admin);
			$this->success('登陆成功',"http://116.62.171.54:8080/manbike0.3/index.php/Admin/Index");
			exit('1');
		}else{
			$this->error("登陆失败");
			exit('-5');
		}
	}
	public function logout()
	{
		session('[destroy]');
		$this->success('退出成功！', U('Login/index'));
	}

}