<?php
namespace Admin\Controller;
use Think\Controller;
class AddbikeController extends CommonController {
	public function _initialize(){
		parent::_initialize();
	}
	public function index()
	{
		if(IS_POST){
			$redis = new \Redis();
			$redis->connect('116.62.171.54', 8085);
			$start = "";
			$end = "";
			if (isset($_POST["start"])) {
				$start = $_REQUEST["start"];
				$start2 = strtotime($start);
				$map["time"] = array("LT", "$start2");
				$map2["time"] = array("EGT", "$start2");
				$this->assign("start", $start);
			}
			if (isset($_POST["end"])) {
				$end = $_REQUEST["end"];
				$end2 = strtotime($end);
				$map2["time"] = array("ELT", "$end2");
				$this->assign("end", $end);
			}
			$ball = M('bike_sub_realtime')->where($map)->select();//查询出开始时间之前总的车辆数据
			foreach ($ball as $k => $v) {
				$arr_ball[] = json_decode($v["bikes"], true);//把bikes字段的json数据转换成数组
			}
			foreach ($arr_ball as $k => $v) {
				if (!$v)
					unset($arr_ball[$k]);//去除空元素
			}
			//多维数组转一维
			$arr_mac=array();
			foreach($arr_ball as $v1){
				foreach($v1 as $v2){
					$arr_mac[]=$v2["mac"];
				}
			}
			//var_dump($arr_mac);
			$between = M('bike_sub_realtime')->where($map2)->select();
			foreach ($between as $k => $v) {
				$arr_between[] = json_decode($v["bikes"], true);//把bikes字段的json数据转换成数组
			}
			foreach ($arr_between as $k => $v) {
				if (!$v)
					unset($arr_between[$k]);//去除空元素
			}
			//多维数组转一维
			$arr_mac2=array();
			foreach($arr_between as $v1){
				foreach($v1 as $v2){
					$arr_mac2[]=$v2["mac"];
				}
			}
			//var_dump($arr_mac2);
			$result=array_diff($arr_mac2,$arr_mac);
			//var_dump($result);
			foreach($result as $k=>$v){
				//var_dump($v);
				$mac = str_replace(':','',$v);
				$arr_all[$k]['mac']=$mac;
				$arr_all[$k]['name']=$redis->get('bikes:'.$mac);;
				$arr_all[$k]['rssi']=$redis->get('bikesrssi:'.$mac);
			}
			$this->assign("arr_all",$arr_all);
			//var_dump($arr_all);
			$this->display();
		}else{

			$this->display();
		}


	}
}