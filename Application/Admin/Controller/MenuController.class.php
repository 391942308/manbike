<?php
namespace Admin\Controller;
use Think\Controller;
class MenuController extends CommonController {
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

	public function index() {

		 $data = M('menu');
		 $count = $data->count();
		 $pageSize = 10;
		 $page = new \Component\Page($count, $pageSize); //这里的分页类和Home模块的目录一致，可自行修改
		//3. 拼装sql语句获得每页信息
		$sql = "SELECT * FROM dwz_menu  order by sort DESC ".$page->limit;
		$info = $data -> query($sql);
		$pagelist = $page -> fpage();
		$this->assign('show', $pagelist);
		$this->assign("a_menu_list", $info);
		$this->display();




//		$a_menu = M('menu')->select();
//		$a_menu_count=M('menu')->count();
//		$p = new \Think\Page($a_menu_count, 5);
//		$this->assign('a_menu_list',$a_menu);
//		$pagebar = $p->show();
//		$this->assign('pagebar', $pagebar);
//		$this->display();
	}
	/**
	 * 添加菜单
	 */
	public function add(){
		$data['title'] = $_POST['title'];
		$data['pid'] = $_POST['pid'];
		$data['name'] = $_POST['name'];
		$data['icon'] = $_POST['icon'];
		$data['sort'] = $_POST['sort'];
		$data['status'] = $_POST['status'];
		$result=M('Menu')->add($data);
		$result2=M('Auth_rule')->add($data);
		if ($result && $result2) {
			$this->success('添加成功',U('Admin/Menu/index'));
		}else{
			$this->error('添加失败');
		}
	}
	/**
	 * 修改菜单
	 */
	public function edit(){
		$data['title'] = $_POST['title'];
		$data['pid'] = $_POST['pid'];
		$data['name'] = $_POST['name'];
		$data['icon'] = $_POST['icon'];
		$data['sort'] = $_POST['sort'];
		$data['status'] = $_POST['status'];
		$id = $_POST['id'];
		$result=M('Menu')->where("id=$id")->save($data);
		$result2=M('Auth_rule')->where("id=$id")->save($data);
		if ($result && $result2) {
			$this->success('修改成功',U('Admin/Menu/index'));
		}else{
			$this->error('修改失败');
		}
	}

	/**
	 * 删除菜单
	 */
	public function delete(){
		$id=$_GET["id"];
		$result=M('menu')->where("id=$id")->delete();
		$result2=M('Auth_rule')->where("id=$id")->delete();
		if($result && $result2){
			$this->success('删除成功',U('Admin/Menu/index'));
		}else{
			$this->error('请先删除子菜单');
		}
	}


}