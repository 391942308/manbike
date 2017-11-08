<?php
namespace Admin\Controller;
use Think\Controller;
class PublicController extends CommonController {
    public function _initialize(){
		parent::_initialize();
		//session id
		//check auth
        $Auth = new \Think\Auth();
		$ruleName = MODULE_NAME . '/' . ACTION_NAME; //规则唯一标识
		//var_dump($ruleName);
		$userId = 1; //用户ID
		$type = 1; //分类-具体是什么没搞懂，默认为1
		$mode='url'; //执行check的模式
		$relation = 'and'; //'or' 表示满足任一条规则即通过验证; 'and'则表示需满足所有规则才能通过验证
		if($Auth->check($ruleName,$userId,$type,$mode,$relation)){
		$dietxt = '认证：通过';
		}else{
		$dietxt = '认证：失败';
		}
		//var_dump($dietxt);
   }
	
	public function db(){
        $list = M("info")->select();
		//var_dump($list);
    }
	public function index(){
		//$this->assign('dietxt',$dietxt);
		$this->display();
    }
	public function auth(){
		$this->assign('dietxt',$dietxt);
		//$this->display();
    }
	public function left(){
		$this->display();
	}
}