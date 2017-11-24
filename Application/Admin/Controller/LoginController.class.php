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
			exit('-2');
		}
		$password = md5($_POST['password']);
		if($password != $now_admin['password']){
			exit('-3');
		}
		if($password == $now_admin['password']){
			session_start();
			session('auth',$now_admin);
			session('username',$username);
			exit('1');
		}else{
			exit('-5');
		}
	}
	public function logout()
	{
		session('[destroy]');
		$this->success('退出成功！', U('Login/index'));
	}

}