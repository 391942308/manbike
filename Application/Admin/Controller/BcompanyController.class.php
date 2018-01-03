<?php
namespace Admin\Controller;
use Think\Controller;
class BcompanyController extends CommonController {
	public function index()
	{
		$redis = new \Redis();
		$redis->connect('127.0.0.1', 6379);
		$allcompany = M('bike_company')->select();
		$redis->set('allcompany',json_encode($allcompany));
		
		$title = "";
		$data = M('bike_company');
		$sql = "SELECT  *  FROM dwz_bike_company where 1=1 ";
		$where=array();
		if (!empty($_GET["title"]) || $_GET["title"]==='0') {
			$title = trim ($_REQUEST["title"]);
			$sql .= " and title like '%$title%' ";
			$where["title"] = array('like', "%$title%");
			$this->assign('title', $title);
		}
		
		$count = $data->where($where)->count();

		//var_dump($count);exit;
		$pageSize = 20;
		$page = new \Component\Page($count, $pageSize); //这里的分页类和Home模块的目录一致，可自行修改
		$sql.=$page->limit;
		$info = $data->query($sql);
		$pagelist = $page->fpage();
		$this->assign('show', $pagelist);
		$this->assign("a_menu_list", $info);
		$this->display();
	}
	/**
	 * 添加菜单
	 */
	public function add(){
		$data['title'] = $_POST['title'];
		$data['keyword'] = $_POST['keyword'];
		$data['color'] = $_POST['color'];
		$result=M('bike_company')->add($data);
		if ($result) {
			$this->success('添加成功',U('Admin/Bcompany/index'));
		}else{
			$this->error('添加失败');
		}
	}
	/**
	 * 修改菜单
	 */
	public function edit(){
		$data['title'] = $_POST['title'];
		$data['keyword'] = $_POST['keyword'];
		$data['color'] = $_POST['color'];
		$id = $_POST['id'];
		$result=M('bike_company')->where("id=$id")->save($data);
		if ($result) {
			$this->success('修改成功',U('Admin/Bcompany/index'));
		}else{
			$this->error('修改失败');
		}
	}

	/**
	 * 删除菜单
	 */
	public function delete(){
		$id=$_GET["id"];
		$result=M('bike_company')->where("id=$id")->delete();
		if($result){
			$this->success('删除成功',U('Admin/Bcompany/index'));
		}else{
			$this->error('删除失败');
		}
	}


}