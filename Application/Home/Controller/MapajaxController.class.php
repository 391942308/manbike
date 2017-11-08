<?php
namespace Home\Controller;
use Think\Controller;
class MapajaxController extends Controller {
    //首页
	public function index(){
        $redis = new \Redis();
		$redis->connect('127.0.0.1', 6379);
    }
	public function getid(){
		//根据经纬度获取到车位的id ，然后再获取到实时情况
        $redis = new \Redis();
		$redis->connect('127.0.0.1', 6379);
		$lng = $_REQUEST['lng'];
		$lat = $_REQUEST['lat'];
        if($lng == '' || $lat==''){
			exit(json_encode(array('error_code'=>1,'error_reason'=>'参数不正确','result'=>'')));
		}
		$id = $redis->get("lnglatid:".$lng.'|'.$lat);
		exit(json_encode(array('error_code'=>0,'error_reason'=>'正确','result'=>$id)));
    }
	//实时图表数据
	public function realtime_detail(){
        $id = $_REQUEST['id'];
        if($id == ''){
			exit(json_encode(array('error_code'=>1,'error_reason'=>'参数不正确','result'=>'')));
		}
		$redis = new \Redis();
		$redis->connect('127.0.0.1', 6379);
		//获取到最后的那个id
		$exist = $redis->scard("infobikesexist:$id");
		$exist_list = $redis->smembers("infobikesexist:$id");
		$arr =array();
		foreach($exist_list as $k=>$v){
			$mac = str_replace(':','-',$v);
			$arr_exist[$k]['name']=$redis->get('bikes:'.$mac);
			$arr_exist[$k]['mac']=$v;
		}
		
		//var_dump($bikes);
		$bike_company=M("bike_company")->select();
		foreach($bike_company as $k=>$v){
			//var_dump($v);
			$arr[$v['title']]=explode('|',$v['keyword']);
		}
		
		foreach($arr_exist as $k=>$v){
			$flag = 'no';
			$name = $v['name'];
			foreach($arr as $kk=>$vv){
				foreach($vv as $kkk=>$vvv){
					if($this->startwith($name,$vvv)){
						$bike_names[]=$kk;
						$flag = 'yes';			
					}
				}
			}
			
			if($flag=='no'){
				$bike_names[]='其他';
			}
		}
		
		$bn = array_count_values($bike_names);
		$len = sizeof($bn);
		exit(json_encode(array('error_code'=>0,'error_reason'=>'正确','length'=>$len,'result'=>$bn)));
    }
	
	private function startwith($str,$pattern) {
		if(strpos($str,$pattern) === 0)
			  return true;
		else
			  return false;
	}
	//实时
	/*public function realtime_detail_rssi(){
        $id = $_REQUEST['id'];
        if($id == ''){
			exit(json_encode(array('error_code'=>1,'error_reason'=>'参数不正确','result'=>'')));
		}
		$redis = new \Redis();
		$redis->connect('127.0.0.1', 6379);
		//获取到最后的那个id
		$iid = $redis->get("realtime_id_".$id);
		$item['id'] = $redis->hget("realtime:$id:$iid",'id');
		$item['dwz_info_id'] = $redis->hget("realtime:$id:$iid",'id');
		$item['storage_num'] = $redis->hget("realtime:$id:$iid",'id');
		$item['time'] = $redis->hget("realtime:$id:$iid",'id');
		$item['bikes'] = $redis->hget("realtime:$id:$iid",'bikes');
		//var_dump(json_decode($item['bikes']));
		//var_dump($item);
		$bikes = json_decode($item['bikes']);
		//var_dump($bikes);
		foreach($bikes as $k=>$v){
			$bike_rssi[]=$v->rssi;
		}
		$br = array_count_values($bike_rssi);
		$len = sizeof($br);
		//var_dump($bn);
		
		//var_dump($statistic);
		exit(json_encode(array('error_code'=>0,'error_reason'=>'正确','length'=>$len,'result'=>$br)));
    }*/
	
	public function sendmsg(){
		$usr = $_REQUEST['usr'];
		$msg = $_REQUEST['msg'];
		var_dump($usr);
		$this->wssend($usr);
		exit(json_encode(array('error_code'=>0,'error_reason'=>'发送成功','result'=>'')));
	}
	public function luru(){
		//var_dump($_REQUEST);
		$data['title']=$luru_panel_title = $_REQUEST['luru_panel_title'];
		$data['lng']=$luru_panel_lng = $_REQUEST['luru_panel_lng'];
		$data['lat']=$luru_panel_lat = $_REQUEST['luru_panel_lat'];
		$data['usebal_num']=$luru_panel_no = $_REQUEST['luru_panel_no'];
		if($luru_panel_title == '' || $luru_panel_lng == '' || $luru_panel_lat == '' || $luru_panel_no == ''){
			exit(json_encode(array('error_code'=>1,'error_reason'=>'参数不正确','result'=>'')));
		}
		$data['longurl']='http://'.time();
		$data['tinyurl']=time();
		$data['addtime']=time();
		$data['beizhu']='bz';
		$data['scenetype']=99;
		
		$res = M("info")->add($data);
		exit(json_encode(array('error_code'=>1,'error_reason'=>'参数不正确','result'=>$res)));
	}
	
	public function history_list(){
		$redis = new \Redis();
		$redis->connect('127.0.0.1', 6379);
		$lastid = $redis->get("dwz_message_log_diaodu1");
		//var_dump($lastid);
		$arr = array();
		for($i=$lastid;$i>=$lastid-10;$i--){
			$item['id']=$redis->hget("dwz_message_log:diaodu1:".$i,'id');
			$item['content']=$redis->hget("dwz_message_log:diaodu1:".$i,'content');
			$item['time']=$redis->hget("dwz_message_log:diaodu1:".$i,'time');
			$arr[] = $item;
		}
		//var_dump($arr);
		exit(json_encode(array('error_code'=>0,'reason'=>'获取成功','result'=>$arr)));
	}
	
	public function export(){
		$redis = new \Redis();
		$redis->connect('127.0.0.1', 6379);
		$keys = $redis->KEYS("realtime:5820:?*");
		$list = array();
		foreach($keys as $k=>$v){
			//var_dump($v);
			$item['id'] = $redis->hget($v, 'id');
			$item['dwz_info_id'] = $redis->hget($v, 'dwz_info_id');
			$item['storage_num'] = $redis->hget($v, 'storage_num');
			$item['time'] = $redis->hget($v, 'time');
			$item['bikes'] = $redis->hget($v, 'bikes');
			//$item['backup'] = $redis->hget($v, 'backup');
			$list[]=$item;
		}
		var_dump($list[0]);
		//存入数据库
	}
	public function exx(){
		$str='[{"bind":"10","mac":"CC:65:46:78:5F:23","name":"mb_I194RmXM","rssi":"-91"},{"bind":"10","mac":"18:93:D7:4A:25:40","name":"BL-2A","rssi":"-78"},{"bind":"10","mac":"C7:F7:87:38:F5:69","name":"mb_afU4h/fH","rssi":"-65"},{"bind":"10","mac":"C3:DF:FA:D1:59:3D","name":"ofo","rssi":"-75"},{"bind":"10","mac":"9C:1D:58:17:1D:9D","name":"BL-2A","rssi":"-79"},{"bind":"10","mac":"D4:36:39:B4:C6:E1","name":"CoolQi","rssi":"-85"},{"bind":"10","mac":"D4:36:39:A5:29:70","name":"CoolQi","rssi":"-75"},{"bind":"10","mac":"3C:A3:08:B5:98:05","name":"BL-3A","rssi":"-92"},{"bind":"10","mac":"50:33:8B:12:CD:03","name":"BL-2A","rssi":"-88"},{"bind":"10","mac":"D4:36:39:6E:4E:42","name":"CoolQi","rssi":"-78"},{"bind":"10","mac":"50:F1:4A:4C:13:4A","name":"CoolQi","rssi":"-98"},{"bind":"10","mac":"D8:08:EC:40:9F:9F","name":"ofo","rssi":"-88"},{"bind":"10","mac":"CD:5E:86:E8:16:77","name":"mb_dxbohl7N","rssi":"-83"},{"bind":"10","mac":"CB:31:03:88:D4:16","name":"mb_FtSIAzHL","rssi":"-76"},{"bind":"10","mac":"D5:55:3E:D1:2A:E2","name":"mb_4irRPlXV","rssi":"-93"},{"bind":"10","mac":"18:93:D7:49:AB:79","name":"BL-2A","rssi":"-92"},{"bind":"10","mac":"D7:DE:E4:F2:F7:17","name":"ofo","rssi":"-64"},{"bind":"10","mac":"C5:D5:3E:53:C5:3A","name":"mobike","rssi":"-88"},{"bind":"10","mac":"D4:36:39:68:AF:64","name":"CoolQi","rssi":"-84"},{"bind":"10","mac":"F8:A8:CD:EB:C1:A6","name":"mb_psHrzaj4","rssi":"-93"},{"bind":"10","mac":"9C:1D:58:8A:C5:34","name":"BL-2A","rssi":"-95"},{"bind":"10","mac":"18:93:D7:49:E0:D3","name":"BL-2A","rssi":"-94"},{"bind":"10","mac":"50:F1:4A:53:4A:B4","name":"BL-2","rssi":"-84"},{"bind":"10","mac":"C3:9F:DE:B7:DA:89","name":"mb_idq33p/D","rssi":"-92"},{"bind":"10","mac":"50:F1:4A:4B:87:DD","name":"CoolQi","rssi":"-96"},{"bind":"10","mac":"C5:BB:B2:61:80:2C","name":"ofo_adv_tes","rssi":"-89"},{"bind":"10","mac":"9C:1D:58:8A:A7:C9","name":"BL-2A","rssi":"-95"},{"bind":"10","mac":"E6:2F:18:4D:5E:98","name":"mb_mF5NGC/m","rssi":"-96"},{"bind":"10","mac":"F4:5E:AB:1B:E5:97","name":"BL-2A","rssi":"-99"},{"bind":"10","mac":"FC:A4:C8:94:C3:28","name":"mb_KMOUyKT8","rssi":"-95"},{"bind":"10","mac":"D4:36:39:A2:D3:31","name":"CoolQi","rssi":"-92"},{"bind":"10","mac":"FE:60:A6:33:95:FA","name":"ofo","rssi":"-92"}]';
		$arr = json_decode($str);
		//var_dump($arr);
		foreach($arr as $k => $v){
			//插入到数据库 ignore模式
			//插入到redis bike:$mac dwz_info_id
			var_dump($v);
			$dwz_info_id = 5820;
			$data['bind']=$v->bind;
			$data['mac']=$v->mac;
			$data['name']=$v->name;
			$data['rssi']=$v->rssi;
			$data['dwz_info_id']=$dwz_info_id;
			M("bike")->add($data,$options=array(),$replace=true);
			//M()->add
			/*$bind = $v->bind;
			$mac = $v->mac;
			$name = $v->name;
			$rssi = $v->rssi;
			$sql = "INSERT IGNORE INTO dwz_bike(id,name,mac,rssi,bind) VALUES ('','$name','$mac','$rssi','$bind')";
			$Model = M();
			$result = $Model->query($sql);
			foreach ($result as $k=>$val){
				$goods_id = $val["goods_id"];
			}*/
		}
	}
	public function history_tb(){
		//
		$id = $_REQUEST['id'];
		//$id = 5819;
		$redis = new \Redis();
		$redis->connect('127.0.0.1', 6379);
		$kid = 'realtime_id_'.$id;
		$rid = $redis->get($kid);
		//var_dump($rid);
		$list = array();
		for($i=$rid;$i>$rid-10;$i--){
			$list[$i]['time'] = $redis->hget("realtime:$id:$i","time");
			$list[$i]['time'] = date("Y-m-d H:i:s",$list[$i]['time']);
			$list[$i]['storage_num'] = $redis->hget("realtime:$id:$i","storage_num");
		}
		//var_dump($list);
		exit(json_encode(array('error_code'=>0,'error_reason'=>'正确','length'=>$len,'result'=>$list)));
	}
	//某个车位曾经停过的车辆
	/*public function infobike(){
		$redis = new \Redis();
		$redis->connect('127.0.0.1', 6379);
		$iid=5820;
		$time = time();
		echo "infobike";
		$str='[{"bind":"10","mac":"CC:65:46:78:5F:23","name":"mb_I194RmXM","rssi":"-91"},{"bind":"10","mac":"18:93:D7:4A:25:40","name":"BL-2A","rssi":"-78"},{"bind":"10","mac":"C7:F7:87:38:F5:69","name":"mb_afU4h/fH","rssi":"-65"},{"bind":"10","mac":"C3:DF:FA:D1:59:3D","name":"ofo","rssi":"-75"},{"bind":"10","mac":"9C:1D:58:17:1D:9D","name":"BL-2A","rssi":"-79"},{"bind":"10","mac":"D4:36:39:B4:C6:E1","name":"CoolQi","rssi":"-85"},{"bind":"10","mac":"D4:36:39:A5:29:70","name":"CoolQi","rssi":"-75"},{"bind":"10","mac":"3C:A3:08:B5:98:05","name":"BL-3A","rssi":"-92"},{"bind":"10","mac":"50:33:8B:12:CD:03","name":"BL-2A","rssi":"-88"},{"bind":"10","mac":"D4:36:39:6E:4E:42","name":"CoolQi","rssi":"-78"},{"bind":"10","mac":"50:F1:4A:4C:13:4A","name":"CoolQi","rssi":"-98"},{"bind":"10","mac":"D8:08:EC:40:9F:9F","name":"ofo","rssi":"-88"},{"bind":"10","mac":"CD:5E:86:E8:16:77","name":"mb_dxbohl7N","rssi":"-83"},{"bind":"10","mac":"CB:31:03:88:D4:16","name":"mb_FtSIAzHL","rssi":"-76"},{"bind":"10","mac":"D5:55:3E:D1:2A:E2","name":"mb_4irRPlXV","rssi":"-93"},{"bind":"10","mac":"18:93:D7:49:AB:79","name":"BL-2A","rssi":"-92"},{"bind":"10","mac":"D7:DE:E4:F2:F7:17","name":"ofo","rssi":"-64"},{"bind":"10","mac":"C5:D5:3E:53:C5:3A","name":"mobike","rssi":"-88"},{"bind":"10","mac":"D4:36:39:68:AF:64","name":"CoolQi","rssi":"-84"},{"bind":"10","mac":"F8:A8:CD:EB:C1:A6","name":"mb_psHrzaj4","rssi":"-93"},{"bind":"10","mac":"9C:1D:58:8A:C5:34","name":"BL-2A","rssi":"-95"},{"bind":"10","mac":"18:93:D7:49:E0:D3","name":"BL-2A","rssi":"-94"},{"bind":"10","mac":"50:F1:4A:53:4A:B4","name":"BL-2","rssi":"-84"},{"bind":"10","mac":"C3:9F:DE:B7:DA:89","name":"mb_idq33p/D","rssi":"-92"},{"bind":"10","mac":"50:F1:4A:4B:87:DD","name":"CoolQi","rssi":"-96"},{"bind":"10","mac":"C5:BB:B2:61:80:2C","name":"ofo_adv_tes","rssi":"-89"},{"bind":"10","mac":"9C:1D:58:8A:A7:C9","name":"BL-2A","rssi":"-95"},{"bind":"10","mac":"E6:2F:18:4D:5E:98","name":"mb_mF5NGC/m","rssi":"-96"},{"bind":"10","mac":"F4:5E:AB:1B:E5:97","name":"BL-2A","rssi":"-99"},{"bind":"10","mac":"FC:A4:C8:94:C3:28","name":"mb_KMOUyKT8","rssi":"-95"},{"bind":"10","mac":"D4:36:39:A2:D3:31","name":"CoolQi","rssi":"-92"},{"bind":"10","mac":"FE:60:A6:33:95:FA","name":"ofo","rssi":"-92"}]';
		$arr = json_decode($str);
		$redis->delete("infobikesexist:".$iid);
		foreach($arr as $k=>$v){
			$mac = str_replace(':','-',$v->mac);
			$redis->sadd("infobikes:".$iid,$v->mac);	
			$redis->sadd("infobikesexist:".$iid,$v->mac);	
			$redis->sadd("infobike:$iid:".$mac,$time);	
			$redis->sadd("bikeinfo:".$mac,$iid);	
		}
		
	}*/
	public function tt(){
		$bike_company=M("bike_company")->select();
		foreach($bike_company as $k=>$v){
			//var_dump($v);
			$arr[$v['title']]=explode('|',$v['keyword']);
		}
		foreach($arr as $k => $v){
			var_dump($k);
			foreach($v as $kk => $vv){
				var_dump($vv);
			}
		}
		//var_dump($arr);
	}
	//websocket
	private function wssend($message){
		//exec("/opt/lampp/bin/php /root/ws/client.php '$message'");
		exec("/opt/lampp/bin/php /root/ws/wsclient.php wssend:'$message'");
	}
}