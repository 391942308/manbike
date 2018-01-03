<?php
namespace Admin\Controller;
use Think\Controller;
class RuleController extends CommonController {
	public function _initialize(){
		parent::_initialize();
	}
	function list_to_tree($list, $pk='id',$pid = 'pid',$child = '_child',$root=0) {
		// 创建Tree
		$tree = array();
		if(is_array($list)) {
			// 创建基于主键的数组引用
			$refer = array();
			foreach ($list as $key => $data) {
				$refer[$data[$pk]] =& $list[$key];
			}
			foreach ($list as $key => $data) {
				// 判断是否存在parent
				$parentId = $data[$pid];
				if ($root == $parentId) {
					$tree[] =& $list[$key];
				}else{
					if (isset($refer[$parentId])) {
						$parent =& $refer[$parentId];
						$parent[$child][] =& $list[$key];
					}
				}
			}
		}
		return $tree;
	}


	public function index() {

		 $data = M('auth_rule');
		 $auth_rule=$data->select();
//		 $count = $data->count();
//		 $pageSize = 10;
//		 $page = new \Component\Page($count, $pageSize); //这里的分页类和Home模块的目录一致，可自行修改
//		//3. 拼装sql语句获得每页信息
//		$sql = "SELECT * FROM dwz_auth_rule ".$page->limit;
//		$auth_rule = $data -> query($sql);
//		$pagelist = $page -> fpage();
//		$this->assign('show', $pagelist);
		$auth_rule=$this->list_to_tree($auth_rule); //详细参数见手册
		$this->assign("auth_rule",$auth_rule);
		$this->display();
	}
	/**
	 * 添加菜单
	 */
	public function add(){
		$data['pid'] = $_POST['pid'];
		$data['title'] = $_POST['title'];
		$data['name'] = $_POST['name'];
		$result=M('auth_rule')->add($data);
		if ($result) {
			$this->success('添加成功',U('Admin/Rule/index'));
		}else{
			$this->error('添加失败');
		}
	}
	/**
	 * 修改菜单
	 */
	public function edit(){
		$data['title'] = $_POST['title'];
		$data['name'] = $_POST['name'];
		$data['pid'] = $_POST['pid'];
		$id = $_POST['id'];
		$result=M('auth_rule')->where("id=$id")->save($data);
		if ($result) {
			$this->success('修改成功',U('Admin/Rule/index'));
		}else{
			$this->error('修改失败');
		}
	}

	/**
	 * 删除菜单
	 */
	public function delete(){
		$id=$_GET["id"];
		$result=M('auth_rule')->where("id=$id")->delete();
		if($result){
			$this->success('删除成功',U('Admin/Rule/index'));
		}else{
			$this->error('删除失败');
		}
	}
	//*******************用户组**********************
	/**
	 * 用户组列表
	 */
	public function group(){
		$data = M('auth_group');
		$count = $data->count();
		$pageSize = 10;
		$page = new \Component\Page($count, $pageSize); //这里的分页类和Home模块的目录一致，可自行修改
		//3. 拼装sql语句获得每页信息
		$sql = "SELECT * FROM dwz_auth_group ".$page->limit;
		$auth_group = $data -> query($sql);
		$pagelist = $page -> fpage();
		$this->assign('show', $pagelist);
		$this->assign("auth_group", $auth_group);
		$this->display();
	}

	/**
	 * 添加用户组
	 */
	public function add_group(){
		$data['title'] = $_POST['title'];
		$data['province'] = $_POST['province'];
		$data['city'] = $_POST['city'];
		$data['area'] = $_POST['area'];
		$data['class'] = $_POST['class'];
		$result=M('auth_group')->add($data);
		if ($result) {
			$this->success('添加成功',U('Admin/Rule/group'));
		}else{
			$this->error('添加失败');
		}
	}

	/**
	 * 修改用户组
	 */
	public function edit_group(){
		$id = $_POST['id'];
		$data['title'] = $_POST['title'];
		$data['province'] = $_POST['province'];
		$data['city'] = $_POST['city'];
		$data['area'] = $_POST['area'];
		$data['class'] = $_POST['class'];
		$result=M('auth_group')->where("id=$id")->save($data);
		if ($result) {
			$this->success('修改成功',U('Admin/Rule/group'));
		}else{
			$this->error('修改失败');
		}
	}

	/**
	 * 删除用户组
	 */
	public function delete_group(){
		$id=$_GET['id'];
		$result=M('auth_group')->where("id=$id")->delete();
		if ($result) {
			$this->success('删除成功',U('Admin/Rule/group'));
		}else{
			$this->error('删除失败');
		}
	}
	//*****************权限-用户组*****************
	/**
	 * 分配权限
	 */
	public function rule_group(){
		if(IS_POST){
			$id=$_GET['id'];
			$data['rule_ids']=$_POST['rule_ids'];
			$data['rules']=implode(',', $data['rule_ids']);
			$result=M('auth_group')->where("id=$id")->save($data);
			if ($result) {
				$this->success('操作成功',U('Admin/Rule/group'));
			}else{
				$this->error('操作失败');
			}
		}else{
			$id=$_GET['id'];
			// 获取用户组数据
			$group_data=M('auth_group')->where(array('id'=>$id))->find();
			$group_data['rules']=explode(',', $group_data['rules']);
			// 获取规则数据
			$rule_data1=M('auth_rule')->select();
			$rule_data=$this->list_to_tree($rule_data1);
			$this->assign('group_data',$group_data);
			$this->assign('rule_data',$rule_data);
			$this->display();
		}

	}
	//******************用户-用户组*******************
	/**
	 * 添加成员
	 */
	public function check_user(){
		$username=$_GET["username"];
		$group_id=$_GET["group_id"];
		$group_name=M('auth_group')->where("id=$group_id")->getField('title');
		$auth_group_access=M('auth_group_access')->where("group_id=$group_id")->select();
		$arr_uid=array();
		foreach($auth_group_access as $a){
			$arr_uid[]=$a;
		}
		$uids=array();
		foreach($arr_uid as $k=>$v){
			$uids[]=$v['uid'];
		}
		// 判断用户名是否为空
		if(empty($username)){
			$user_data='';
		}else{
			$user_data=M('auth_user')->where(array('username'=>$username))->select();
		}
		$this->assign('group_name',$group_name);
		$this->assign('uids',$uids);
		$this->assign('user_data',$user_data);
		$this->display();
	}
	/**
	 * 添加用户到用户组
	 */
	public function add_user_to_group(){
		$data["uid"]=$_GET["uid"];
		$data["group_id"]=$_GET["group_id"];
		$data["username"]=$_GET["username"];
		$map=array(
				'uid'=>$data['uid'],
				'group_id'=>$data['group_id']
		);
		$count=M('auth_group_access')->where($map)->count();
		if($count==0){
			M('auth_group_access')->add($data);
		}
		$this->success('操作成功',U('Admin/Rule/check_user',array('group_id'=>$data['group_id'],'username'=>$data['username'])));
	}
	/**
	 * 管理员列表
	 */
	public function admin_user_list(){
		$auth_user=M('auth_user')->select();
		$this->assign('auth_user',$auth_user);
		$this->display();
	}

	/**
	 * 添加管理员
	 */
	public function add_admin(){
		if(IS_POST){
			$data['group_ids']=$_POST['group_ids'];
			$data['username']=$_POST['username'];
			$data['password']=md5($_POST['password']);
			$result=M('auth_user')->add($data);
			if($result){
				if (!empty($data['group_ids'])) {
					foreach ($data['group_ids'] as $k => $v) {
						$group=array(
								'uid'=>$result,
								'group_id'=>$v
						);
						M('auth_group_access')->add($group);
					}
				}
				// 操作成功
				$this->success('添加成功',U('Admin/Rule/admin_user_list'));
			}else{
				// 操作失败
				$this->error('添加失败');
			}
		}else{
			$auth_group=M('auth_group')->select();
			$this->assign('auth_group',$auth_group);
			$this->display();
		}
	}

	/**
	 * 修改管理员
	 */
	public function edit_admin(){
		if(IS_POST){
			$data['group_ids']=$_POST['group_ids'];
			$data['username']=$_POST['username'];
			$data['password']=$_POST['password'];
			// 组合where数组条件
			$uid=$_GET['id'];
			// 修改权限
			M('auth_group_access')->where("uid=$uid")->delete();
			foreach ($data['group_ids'] as $k => $v) {
				$group=array(
						'uid'=>$uid,
						'group_id'=>$v
				);
				M('auth_group_access')->add($group);
			}
			$data=array_filter($data);
			// 如果修改密码则md5
			if (!empty($data['password'])) {
				$data['password']=md5($data['password']);
			}
			// p($data);die;
			$result=M('auth_user')->where("id=$uid")->save($data);
			if($result!==false){
				// 操作成功
				$this->success('编辑成功',U('Admin/Rule/edit_admin',array('id'=>$uid)));
			}else{
					// 操作失败
					$this->error('编辑失败');

			}
		}else{
			$id=$_GET['id'];
			// 获取用户数据
			$user_data=M('auth_user')->find($id);
			// 获取已加入用户组
			$group_data=M('auth_group_access')
					->where(array('uid'=>$id))
					->getField('group_id',true);
			// 全部用户组
			$data=M('auth_group')->select();
			$assign=array(
					'data'=>$data,
					'user_data'=>$user_data,
					'group_data'=>$group_data
			);
			$this->assign($assign);
			$this->display();
		}
	}
	public function check_block()
	{
		if (IS_POST) {
			$ids = $_POST["ids"];
			$ids = rtrim($ids, ',');
			//var_dump($ids);
			$id=$_POST["group_id"];
			$data["blocks"]=$ids;
			$result=M('auth_group')->where("id=$id")->save($data);
			if($result){
				$this->success('修改成功');
			}else{
				$this->error('修改失败');
			}
		} else {
			$block_list = M('info_block')->select();
			$this->assign('block_list', $block_list);
			$this->display();
		}
	}
}