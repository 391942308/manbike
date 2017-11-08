<?php
namespace Admin\Controller;
use Think\Controller;
class BikesubController extends CommonController {
	public function _initialize(){
		parent::_initialize();
	}
	public function index() {
		 $data = M('bike_sub');
		 $count = $data->count();
		 $pageSize = 20;
		 $page = new \Component\Page($count, $pageSize); //这里的分页类和Home模块的目录一致，可自行修改
		//3. 拼装sql语句获得每页信息
		$sql = "SELECT * FROM dwz_bike_sub ".$page->limit;
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
		$data['mca'] = $_POST['mca'];
		$data['icon'] = $_POST['icon'];
		$result=M('Menu')->add($data);
		if ($result) {
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
		$data['mca'] = $_POST['mca'];
		$data['icon'] = $_POST['icon'];
		$id = $_POST['id'];
		$result=M('Menu')->where("id=$id")->save($data);
		if ($result) {
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
		$result=M('bike_sub')->where("id=$id")->delete();
		if($result){
			$this->success('删除成功',U('Admin/Bikesub/index'));
		}else{
			$this->error('删除失败');
		}
	}


}