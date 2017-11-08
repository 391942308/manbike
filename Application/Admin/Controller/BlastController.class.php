<?php
namespace Admin\Controller;
use Think\Controller;
//流动非流动统计
class BlastController extends CommonController {
	public function _initialize(){
		parent::_initialize();
	}
	public function onebike(){
		$str = '["5819",1505787468,1505791560,"100","1200"]';
		$arr = json_decode($str);
			$iid = $arr[0];
			$start = $arr[1];
			$end = $arr[2];
			$judge = $arr[3];
			$rule = $arr[4];
		    onebike_liudong_new($iid,$mac='C6DE96097BA3',$start,$end,$judge,$rule);
	}
	
	public function oneinfo()
	{
		$redis = new \Redis();
		$redis->connect('127.0.0.1', 6379);	
		if(IS_POST){
			
			//var_dump($_POST);
			$iid = isset($_POST['iid'])?$_POST['iid']:'';
			//var_dump($iid);
			$this->assign('iid',$iid);
			$jiange = isset($_POST['jiange'])?$_POST['jiange']:'';//间隔
			$start = isset($_POST['start'])?$_POST['start']:'';//开始时间
			$start = strtotime($start);
			$st = isset($_POST['st'])?$_POST['st']:'';//持续时间 分钟
			$end = isset($_POST['end'])?$_POST['end']:'';//持续时间 分钟
			$end = strtotime($end);
			$judge = isset($_POST['judge'])?$_POST['judge']:'';//秒 允许的时间间隔
			$this->assign('judge',$judge);
			$rule = isset($_POST['rule'])?$_POST['rule']:'';
			$this->assign('rule',$rule);
			
			$key_arr[]=$iid;
			$key_arr[]=$start;
			$key_arr[]=$end;
			$key_arr[]=$judge;
			$key_arr[]=$rule;
			//echo json_encode($key_arr);
			//exit;
			$key=implode('_',$key_arr);
			
			if($iid=='' || $start=='' || $end=='' || $judge=='' || $rule==''){
				$url = U("Admin/Blast/oneinfo");
				header("location:$url");
				exit;
			}
			oneinfo_liudong($iid,$start,$end,$judge,$rule);
			//计算完了 获取到数据 然后直接删除
			$liudongno = $redis->smembers("liudongno:$key");
			$liudong = $redis->smembers("liudong:$key");
			
			$redis->del("liudongno:$key");
			$redis->del("liudong:$key");
			//var_dump($feiliudong);
			//var_dump($liudong);
			$all = array_merge($liudongno,$liudong);
			$all2 = array();
			foreach($all as $k=>$v){
				if(in_array($v,$liudongno)){
					$all2[$k]['mac']=$v;
					$all2[$k]['flag']=1;//非流动
					$all2[$k]['name']=$redis->get("bikes:$v");
					$all2[$k]['rssi']=$redis->get("bikesrssi:$v");
					$all2[$k]['iid']=$iid;
				}
				if(in_array($v,$liudong)){
					$all2[$k]['mac']=$v;
					$all2[$k]['flag']=2;//流动
					$all2[$k]['name']=$redis->get("bikes:$v");
					$all2[$k]['rssi']=$redis->get("bikesrssi:$v");
					$all2[$k]['iid']=$iid;
				}
			}
			//var_dump($all2);
			$this->assign("iid",$iid);
			$this->assign('start',$_POST['start']);
			$this->assign('end',$_POST['end']);
			//$this->assign('',);
			//$this->assign();
			
			$this->assign('all',$all2);
			$this->display();
		}else{
			//默认获取的数据为当天的数据
			$iid = $_REQUEST['iid'];
			if($iid == NULL){
				$this->display();
			}
			//var_dump($iid);
			$judge = 100;
			$rule = 1200;
			$this->assign('iid',$iid);
			$this->assign('judge',$judge);
			$this->assign('rule',$rule);
			//var_dump($_REQUEST);
			$start=date("Y-m-d 00:00:00");
			$this->assign('start',$start);
			//var_dump($start);
			$start = strtotime($start);
			$end = date("Y-m-d 00:00:00",strtotime("+1 day"));
			$this->assign('end',$end);
			//var_dump($end);
			$end = strtotime($end);
			$this->assign("iid",$iid);
			
			$key_arr[]=$iid;
			$key_arr[]=$start;
			$key_arr[]=$end;
			$key_arr[]=$judge;
			$key_arr[]=$rule;
			//echo json_encode($key_arr);
			//exit;
			$key=implode('_',$key_arr);
			
			if($iid=='' || $start=='' || $end=='' || $judge=='' || $rule==''){
				$url = U("Admin/Blast/oneinfo");
				header("location:$url");
				exit;
			}
			oneinfo_liudong($iid,$start,$end,$judge,$rule);
			//计算完了 获取到数据 然后直接删除
			$liudongno = $redis->smembers("liudongno:$key");
			$liudong = $redis->smembers("liudong:$key");
			
			$redis->del("liudongno:$key");
			$redis->del("liudong:$key");
			//var_dump($feiliudong);
			//var_dump($liudong);
			$all = array_merge($liudongno,$liudong);
			$all2 = array();
			foreach($all as $k=>$v){
				if(in_array($v,$liudongno)){
					$all2[$k]['mac']=$v;
					$all2[$k]['flag']=1;//非流动
					$all2[$k]['name']=$redis->get("bikes:$v");
					$all2[$k]['rssi']=$redis->get("bikesrssi:$v");
					$all2[$k]['iid']=$iid;
				}
				if(in_array($v,$liudong)){
					$all2[$k]['mac']=$v;
					$all2[$k]['flag']=2;//流动
					$all2[$k]['name']=$redis->get("bikes:$v");
					$all2[$k]['rssi']=$redis->get("bikesrssi:$v");
					$all2[$k]['iid']=$iid;
				}
			}
			
			//var_dump($all2);
			$this->assign("iid",$iid);
			//$this->assign('',);
			//$this->assign();
			
			$this->assign('all',$all2);
			$this->display();	
		}
		//
		
		//$redis->
		//echo "持续时间统计";
		
	}
	public function oneblock()
	{
		if(IS_POST){
			$redis = new \Redis();
			$redis->connect('127.0.0.1', 6379);	
			//var_dump($_POST);
			$bid = isset($_POST['bid'])?$_POST['bid']:'';
			$jiange = isset($_POST['jiange'])?$_POST['jiange']:'';//间隔
			$start = isset($_POST['start'])?$_POST['start']:'';//开始时间
			$start = strtotime($start);
			$st = isset($_POST['st'])?$_POST['st']:'';//持续时间 分钟
			$end = isset($_POST['end'])?$_POST['end']:'';//持续时间 分钟
			$end = strtotime($end);
			$judge = isset($_POST['judge'])?$_POST['judge']:'';//秒 允许的时间间隔
			$rule = isset($_POST['rule'])?$_POST['rule']:'';
			
			/*$key_arr[]=$bid;
			$key_arr[]=$start;
			$key_arr[]=$end;
			$key_arr[]=$judge;
			$key_arr[]=$rule;
			//echo json_encode($key_arr);
			//exit;
			$key=implode('_',$key_arr);*/
			
			if($bid=='' || $start=='' || $end=='' || $judge=='' || $rule==''){
				$url = U("Admin/Blast/oneblock");
				header("location:$url");
				exit;
			}
			
			$all_info = $redis->smembers("block:$bid");
			
			$liudongno=array();
			$liudong=array();
			foreach($all_info as $k=>$v){
				$key_arr[]=$v;
				$key_arr[]=$start;
				$key_arr[]=$end;
				$key_arr[]=$judge;
				$key_arr[]=$rule;
				//echo json_encode($key_arr);
				//exit;
				$key=implode('_',$key_arr);
				
				oneinfo_liudong($v,$start,$end,$judge,$rule);
				
				$liudongno =array_merge($liudongno,$redis->smembers("liudongno:$key"));
				$liudong = array_merge($liudong,$redis->smembers("liudong:$key"));
				
				$redis->del("liudongno:$key");
				$redis->del("liudong:$key");
				//var_dump($feiliudong);
				//var_dump($liudong);
			}
			
				$all = array_merge($liudongno,$liudong);
				$all2 = array();
				foreach($all as $k=>$v){
					if(in_array($v,$liudongno)){
						$all2[$k]['mac']=$v;
						$all2[$k]['flag']=1;//非流动
						$all2[$k]['name']=$redis->get("bikes:$v");
						$all2[$k]['rssi']=$redis->get("bikesrssi:$v");
						$all2[$k]['iid']=$iid;
					}
					if(in_array($v,$liudong)){
						$all2[$k]['mac']=$v;
						$all2[$k]['flag']=2;//流动
						$all2[$k]['name']=$redis->get("bikes:$v");
						$all2[$k]['rssi']=$redis->get("bikesrssi:$v");
						$all2[$k]['iid']=$iid;
					}
				}
				
			
			//计算完了 获取到数据 然后直接删除
			
			
			$this->assign('all',$all2);
			$this->display();
		}else{
			$this->display();	
		}
		//
		
		//$redis->
		//echo "持续时间统计";
		
	}


}