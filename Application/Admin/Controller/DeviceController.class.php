<?php
namespace Admin\Controller;
use Think\Controller;
class DeviceController extends CommonController {
	public function _initialize(){
		parent::_initialize();
	}
	public function index() {
		$type="";
		$mac="";
		$info_id="";
		$data = M('device_manage');
		$sql="SELECT * FROM dwz_device_manage where 1=1 ";
		if (!empty($_GET["type"]) || $_GET["type"]==='0') {
			$type = trim($_REQUEST["type"]);
			$sql .= " and type like '%$type%' ";
			$where["type"] = array('like', "%$type%");
			$this->assign('type', $type);
		}
		if (!empty($_GET["mac"]) || $_GET["mac"]==='0') {
			$mac = trim($_REQUEST["mac"]);
			$sql .= " and mac = '$mac' ";
			$where["mac"] = '$mac';
			$this->assign('mac', $mac);
		}
		if (!empty($_GET["info_id"]) || $_GET["info_id"]==='0') {
			$info_id = trim($_REQUEST["info_id"]);
			$sql .= " and info_id = '$info_id' ";
			$where["info_id"] = '$info_id';
			$this->assign('info_id', $info_id);
		}
		$count=$data->where($where)->count();
		//var_dump($count);exit;
		$pageSize = 20;
		$page = new \Component\Page($count, $pageSize); //这里的分页类和Home模块的目录一致，可自行修改
		$sql.=$page->limit;
		$info = $data -> query($sql);
		$pagelist = $page -> fpage();
		$this->assign('show', $pagelist);
		$this->assign("bparking_list", $info);

		$this->display();
	}
	/**
	 * 添加菜单
	 */
	public function add(){
		$data['timestamp'] = $_POST['timestamp'];
		$type = $_POST['type'];
		$data['type'] = $type;
		$mac = $_POST['mac'];
		$data['mac'] = $mac;
		$data['gatewayFree'] = $_POST['gatewayFree'];
		$data['gatewayLoad'] = $_POST['gatewayLoad'];
		$info_id = $_POST['info_id'];
		$data['info_id'] = $info_id;
		$redis = new \Redis();
		$redis->connect('116.62.171.54', 8085);
		
		if($type=="Gateway"){
			//$redis->delete("gatewayiid:".$mac);
			$redis->set("gatewayiid:".$mac,$info_id);   //结果：bool(true)
			echo $redis->get("gatewayiid:".$mac);   //结果：bool(true)
		}
		$result=M('device_manage')->add($data);
		if ($result) {
			$this->success('添加成功');
		}else{
			$this->error('添加失败');
		}
	}
	/**
	 * 修改菜单
	 */
	public function edit(){
		$data['timestamp'] = $_POST['timestamp'];
		$type = $data['type'] = $_POST['type'];
		$mac = $data['mac'] = $_POST['mac'];
		$data['gatewayFree'] = $_POST['gatewayFree'];
		$data['gatewayLoad'] = $_POST['gatewayLoad'];
		$info_id = $data['info_id'] = $_POST['info_id'];
		$id = $_POST['id'];
		$result=M('device_manage')->where("id=$id")->save($data);
		
		$redis = new \Redis();
		$redis->connect('116.62.171.54', 8085);
		if($type=="Gateway"){
			//$redis->delete("gatewayiid:".$mac);
			$redis->set("gatewayiid:".$mac,$info_id);   //结果：bool(true)
			//echo $redis->get("gatewayiid:".$mac); 
		}
		if ($result) {
			$this->success('修改成功',U('Admin/Device/index'));
		}else{
			$this->error('修改失败');
		}
	}

	/**
	 * 删除菜单
	 */
	public function delete(){
		$id=$_GET["id"];
		$result=M('device_manage')->where("id=$id")->find();
		M('device_manage')->where("id=$id")->delete();
		if($result['type']=="Gateway"){
			$redis = new \Redis();
			$redis->connect('116.62.171.54', 8085);
			$redis->delete("gatewayiid:".$result['mac']);
		}
		
		if($result){
			$this->success('删除成功',U('Admin/Device/index'));
		}else{
			$this->error('删除失败');
		}
	}

	public function get(){
		$redis = new \Redis();
		$redis->connect('116.62.171.54', 8085);
		$list = $redis->keys("gatewayiid:*");   //结果：bool(true)
		var_dump($list);
		
		foreach($list as $k=>$v){
			echo $v.":".$redis->get($v);
			echo "<br />";
		}
		//$redis->set("gatewayiid:".$mac,$info_id);   //结果：bool(true)
	}

}