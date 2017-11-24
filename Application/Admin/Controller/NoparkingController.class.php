<?php
namespace Admin\Controller;
use Think\Controller;
class NoparkingController extends CommonController {
	public function index() {
		//连接本地的 Redis 服务
		$redis = new\ Redis();
		$redis->connect('116.62.171.54', 8085);
		//查询出所有违停车位的信息
		$npinfo = M('info')->where("usable_num=0")->select();
		$arr =array();
		foreach($npinfo as $k=>$v){
			$map_ag['dwz_info_id']=array( $v['id']);//获取违停车位id的值
			//从redis获取到数据 ，然后使用图表形式展现
			$id=$map_ag['dwz_info_id'][0];//违停车位ID
			//从redis获取到数据 ，然后使用图表形式展现
			$all = $redis->scard("infobikes:$id");
			$all_list = $redis->smembers("infobikes:$id");
			//var_dump($all_list);
			foreach($all_list as $keys=>$vals){
				$mac = str_replace(':','-',$vals);
				//var_dump("infobike:$id:$mac");
				$exist = $redis->scard("infobike:$id:$mac");
				//var_dump($exist);
				$arr_all[$keys]['mac']=$vals;
				$arr_all[$keys]['num']=$exist;
				$arr_all[$keys]['dwz_info_id']=$id;
				//var_dump($arr_all);
			}
		}
		$this->assign('all',$all);
		$this->assign('all_list',$all_list);
		$this->assign('arr_all',$arr_all);
		//循环判断
//		foreach($ags as $k=>$v){
//			if(!$v){//判断是否为空（false）
//				unset($ags[$k]);//删除
//			}
//		}
//		foreach($ags as $value){
//			foreach($value as $v){
//				$arr2[]=$v;
//			}
//		}
////销毁
//		unset($ags,$value,$v);
//		foreach($arr2 as $k=>&$v){
//			$v["bikes"]=json_decode($v["bikes"],true);
//			//var_dump($v["bikes"][0]["mac"]);
//		}
		//unset($v["bikes"]); // 最后取消掉引用
		//var_dump($arr2);

		//用车位编号iid_mac作为键
		//mac相同的就叠加



		//$this->assign("arr2",$arr2);
		$this->display();
	}
	/**
	 * 添加菜单
	 */
	public function add(){
		$data['title'] = $_POST['title'];
		$data['usable_num'] = $_POST['usable_num'];
		$data['storage_num'] = $_POST['storage_num'];
		$data['overflow_num'] = $_POST['overflow_num'];
		$data['no'] = $_POST['no'];
		$data['block_no'] = $_POST['block_no'];
		$lng_lat = $_POST['lng_lat'];
		$data['lng']=strstr($lng_lat,',',true);
		$data['lat']=ltrim(strstr($lng_lat,','), ",");
		$data['tinyurl']=time();
		$result=M('info')->add($data);
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
		$data['title'] = $_POST['title'];
		$data['usable_num'] = $_POST['usable_num'];
		$data['storage_num'] = $_POST['storage_num'];
		$data['overflow_num'] = $_POST['overflow_num'];
		$data['no'] = $_POST['no'];
		$data['block_no'] = $_POST['block_no'];
		$lng_lat = $_POST['lng_lat'];
		$data['lng']=strstr($lng_lat,',',true);
		$data['lat']=ltrim(strstr($lng_lat,','), ",");
		$id = $_POST['id'];
		$result=M('info')->where("id=$id")->save($data);
		if ($result) {
			$this->success('修改成功',U('Admin/Bparking/index'));
		}else{
			$this->error('修改失败');
		}
	}

	/**
	 * 删除菜单
	 */
	public function delete(){
		$id=$_GET["id"];
		$result=M('info')->where("id=$id")->delete();
		if($result){
			$this->success('删除成功',U('Admin/Bparking/index'));
		}else{
			$this->error('删除失败');
		}
	}


}