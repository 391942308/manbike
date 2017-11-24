<?php
namespace Admin\Controller;
use Think\Controller;
class IblockController extends CommonController {
	public function index()
	{
		$title = "";
		$data = M('info_block');
		$sql = "SELECT * FROM dwz_info_block where 1=1 ";
		if (!empty($_GET["title"]) || $_GET["title"]==='0') {
			$title = $_REQUEST["title"];
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
		$data['content'] = $_POST['content'];
		$result=M('info_block')->add($data);
		if ($result) {
			$this->success('添加成功',U('Admin/Iblock/index'));
		}else{
			$this->error('添加失败');
		}
	}
	/**
	 * 修改菜单
	 */
	public function edit(){
		$data['title'] = $_POST['title'];
		$data['content'] = $_POST['content'];
		$id = $_POST['id'];
		$result=M('info_block')->where("id=$id")->save($data);
		if ($result) {
			$this->success('修改成功',U('Admin/Iblock/index'));
		}else{
			$this->error('修改失败');
		}
	}

	/**
	 * 删除菜单
	 */
	public function delete(){
		$block_no=$_GET["id"];
		$result=M('info_block')->where("id=$block_no")->delete();
		if($result){
			$this->success('删除成功',U('Admin/Iblock/index'));
		}else{
			$this->error('删除失败');
		}
	}
	public function exist(){
		$id=$_GET["id"];
		$infos=M('info')->where("block_no=$id")->select();
		$sum=0;
		foreach($infos as $k=>$v){
			//echo $v["id"];
			$id=$v["id"];
			$redis = new \Redis();
			$redis->connect('116.62.171.54', 8085);
			//从redis获取到数据 ，然后使用图表形式展现
			$all = $redis->scard("infobikes:$id");
			$all_list = $redis->smembers("infobikes:$id");
			//var_dump($all_list);
			$exist2 = $redis->scard("infobikesexist:$id");
			$sum+=$exist2;
			$exist_list = $redis->smembers("infobikesexist:$id");
			//$exist+=$exist;
			//var_dump($numbers);
			$arr =array();
			foreach($exist_list as $k=>$v){
				$mac = str_replace(':','-',$v);
				//var_dump("infobike:$id:$mac");
				$exist = $redis->scard("infobike:$id:$mac");
				$sm = $redis->smembers("infobike:$id:$mac");
				//var_dump($sm);
				$arr_exist[$k]['mac']=$v;
				$arr_exist[$k]['num']=$exist;
				$arr_exist[$k]['id']=$id;
				$arr_exist[$k]['lasttime']=date('Y-m-d:H:i:s',max($sm));
			}

		}
		//var_dump($sum);
		$this->assign('all',$all);
		$this->assign('all_list',$all_list);
		$this->assign('sum',$sum);
		$this->assign('exist_list',$exist_list);
		$this->assign('arr_exist',$arr_exist);
		$this->display();
	}
	public function sall(){
		$block_no=$_GET["id"];
		$infos=M('info')->where("block_no=$block_no")->select();
		$sum=0;
		foreach($infos as $k=>$v){
			$id=$v["id"];
			$redis = new \Redis();
			$redis->connect('116.62.171.54', 8085);
			//从redis获取到数据 ，然后使用图表形式展现
			$all = $redis->scard("infobikes:$id");
			$all_list = $redis->smembers("infobikes:$id");
			$sum+=$all;
			//$exist = $redis->scard("infobikesexist:$id");
			//$exist_list = $redis->smembers("infobikesexist:$id");
			//var_dump($exist_list);
			//var_dump($numbers);


			//$this->assign('exist',$exist);
			//$this->assign('exist_list',$exist_list);

			$arr =array();
			foreach($all_list as $k=>$v){
				$mac = str_replace(':','-',$v);
				//var_dump("infobike:$id:$mac");
				$exist = $redis->scard("infobike:$id:$mac");
				//var_dump($exist);
				$arr_all[$k]['mac']=$v;
				$arr_all[$k]['num']=$exist;
				$arr_all[$k]['id']=$id;
			}
		}
		$this->assign('sum',$sum);
		$this->assign('all',$all);
		$this->assign('all_list',$all_list);
		$this->assign('arr_all',$arr_all);
		$this->display();
	}

}