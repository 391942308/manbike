<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    //首页
	public function index(){
		$url = "http://baohe.toalls.com:8080/manbike0.3/index.php/Home/Shown/map";
		header("Location:$url");
        $this->display();
    }
	//初始化
	public function init(){
		echo "开始初始化...<br />";
		$redis = new \Redis();
		$redis->connect('127.0.0.1', 6379);
		//echo "Connection to server sucessfully";
		//查看服务是否运行
		//echo "Server is running: " . $redis->ping();
		$list = M("info")->select();
		foreach($list as $k=>$v){
			$row = $v;
			//数据初始化
			$redis->hset('dwz_info:'.$row['id'], 'id', $row['id']);  
			$redis->hset('dwz_info:'.$row['id'], 'title', $row['title']);  
			$redis->hset('dwz_info:'.$row['id'], 'lng', $row['lng']);  
			$redis->hset('dwz_info:'.$row['id'], 'lat', $row['lat']);  
			$redis->hset('dwz_info:'.$row['id'], 'usable_num', $row['usable_num']);  
			//$redis->hset('dwz_info:'.$row['id'], 'storage_num', $row['storage_num']);  
			$redis->hset('dwz_info:'.$row['id'], 'storage_num', 0);  
			$redis->hset('dwz_info:'.$row['id'], 'status', $row['status']);  
			//$redis->hset('dwz_info:'.$row['id'], 'level', $row['level']);  
			$redis->hset('dwz_info:'.$row['id'], 'level', 0);  
			$redis->hset('dwz_info:'.$row['id'], 'no', $row['no']);  
			$redis->hset('dwz_info:'.$row['id'], 'block_no', $row['block_no']);  
			//echo 'dwz_info:'.$row['id'];
			
			//区块关系初始化
			$redis->sadd('block:'.$row['block_no'], $row['id']);  
			echo "...<br />";
			//经纬度做key 然后车位id 做value ajax获取车位实时详情使用
		    
			$redis->set('lnglatid:'.$v['lng'].'|'.$v['lat'],$v['id']);
		}
		echo "完成初始化...";
		
		
    }
	//ajax获取数据
	public function ajax_data(){
		//从redis动态获取数据
		//地图根据获取的数据动态显示
		$redis = new \Redis();
		$redis->connect('127.0.0.1', 6379);
		$keys = $redis->KEYS("dwz_info:?*");
		$list = array();
		foreach($keys as $k=>$v){
			$item['id'] = $redis->hget($v, 'id');
			$item['lng'] = $redis->hget($v, 'lng');
			$item['lat'] = $redis->hget($v, 'lat');
			$item['usable_num'] = $redis->hget($v, 'usable_num');
			$item['storage_num'] = $redis->hget($v, 'storage_num');
			$item['level'] = $redis->hget($v, 'level');
			$list[]=$item;
		}
		echo json_encode($list);
	}
	//历史消息记录
	public function message_list(){
		//从redis动态获取数据
		//地图根据获取的数据动态显示
		$redis = new \Redis();
		$redis->connect('127.0.0.1', 6379);
		$keys = $redis->KEYS("dwz_message_log:update1:?*");
		//var_dump($keys);
		$list = array();
		foreach($keys as $k=>$v){
			$item['id'] = $redis->hget($v, 'id');
			$item['content'] = $redis->hget($v, 'content');
			$item['time'] = $redis->hget($v, 'time');
			$list[]=$item;
		}
		//var_dump($list);
		//echo json_encode($list);
		$this->assign('list',$list);
		$this->display();
	}
	//
	public function show(){
		$this->display();
	}
	public function app_findby_location(){	
		
		$lng = trim($_REQUEST['lng']);
		$lat = trim($_REQUEST['lat']);
		//$lng = '120.18046';
		//$lat = '30.184333';
		
		if(isset($lng) && isset($lat)){
			$model= D('info');
			
			$top = Array();
			
			$where_sql = "";
			
			//雷达图要置顶， 地图显示不用置顶
			if(!isset($_GET['ismap'])){
				//置顶3条
				$sql = " SELECT 
					id,
					title, 
					longurl,
					tinyurl,
					scenetype,
					sceneicon,
					lng,
					lat,
					beizhu,
					usable_num,
					storage_num,
					level
				 FROM 
					dwz_info a 
				 WHERE
					a.istop=1 
					and a.type=1
					and a.type=1 and a.show_type=1 and a.status=0
				 LIMIT 30
				";
				$top = $model->query($sql);
				
				
				
			//地图有可能会搜索关键字和分类
			}else{
				$keyword = $_GET['keyword'];
				
				if(!empty($keyword)){
					//$keyword = base64_decode($keyword);
					$where_sql = $where_sql . " and a.title like '%$keyword%' ";
				}
				
				$scenetype = $_GET['scenetype'];
				if(!empty($scenetype)){
					$where_sql = $where_sql . " and a.scenetype = '$scenetype' ";
				}
			}
			
			//距离最近的7条
			$sql = " SELECT 
						id,
						title, 
						longurl,
						tinyurl,
						scenetype,
						sceneicon,
						lng,
						lat,
						(POWER(MOD(ABS(a.lng - $lng),360),2) + POWER(ABS(a.lat - $lat),2)) AS distance,
						beizhu,
						usable_num,
						storage_num,
						level
					 FROM 
						dwz_info a 
					   WHERE
						a.type=1
						and a.type=1 and a.show_type=1
						and a.lng is not null and a.lat is not null and a.lng <> '' and a.lat <> '' and a.status=0
					 " . $where_sql ."
					 ORDER BY
						distance
					 LIMIT 30
					 ";
			$tmpData = $model->query($sql);
			
			$data = Array();
			$map = Array();	
		
			foreach($top as $key=>$v){
				$title = $v['title'];
				if(!isset($map[$title])){
					$map[$title] = $v;
					$v['longurl'] = "http://url.97farm.com/" . $v['tinyurl'];
					$data[] = $v;
				}
			}
			
			for($idx=0; $idx<count($tmpData); $idx++){
				//地图显示30条
				if(isset($_GET['lng']) && isset($_GET['lat'])){
					if(count($data) >= 35){
						break;
					}
				}else{
					if(count($data) >= 35){
						break;
					}					
				}
				
				$val = $tmpData[$idx];
				$title = $val['title'];
				if(!isset($map[$title])){
					$map[$title] = $val;
					$val['longurl'] = "http://url.97farm.com/" . $val['tinyurl'];
					$data[] = $val;
				}
			}
		}
		
		
		header("Content-type: application/json;");
		if($error != null && $status == 0){
			$status = 1;
		}
		
		$result = Array(
			"status"=>$status,
			"error"=>$error,
			"data" => $data
		);
		
		
		echo json_encode($data);
	}
	public function map(){
		$this->display();
	}
	public function map_list(){
		$this->display();
	}
	public function sub_form(){
		$this->display();
	}
	public function sub_realtime_form(){
		$this->display();
	}
	public function sub_realtime(){
		//$postText = trim(file_get_contents('php://input'));
		//file_put_contents('/root/log11111.txt', var_export($_POST, true) . "\n", FILE_APPEND);
		
		$iid = $data['dwz_info_id']=$dwz_info_id=$_POST['dwz_info_id'];
		//$data['time']=$time=$_POST['time'];
		$data['time']=$time=time();
		//$data['storage_num']=$storage_num=$_POST['nums'];
		$data['backup']=$backup=$_POST['backup'];
		$data['bikes']=$bikes=$_POST['bikes'];
		$data['storage_num']=$storage_num=$this->cal_nums($bikes);
		
		if($dwz_info_id=='' || $time=='' || $storage_num=='' ||$bikes==''){
			exit(json_encode(array('error_code'=>1,'reason'=>'未发现有效车辆','result'=>'')));
		}
		//流水插入到数据库
		$bsrid = M("bike_sub_realtime")->add($data);
		
		$redis = new \Redis();
		$redis->connect('127.0.0.1', 6379);
		
		$redis->lpush("bsrid",$bsrid);
		
		$redis->set('lasttime:'.$iid,$time);
		
		$lng = $redis->hget('dwz_info:'.$dwz_info_id, 'lng');
		$lat = $redis->hget('dwz_info:'.$dwz_info_id, 'lat');
		//
		$arr = json_decode($bikes);
		$map_bike['dwz_info_id']=array('eq',$dwz_info_id);
		//$data_bike['status']=0;
		//M("bike")->where($map_bike)->save($data);
		foreach($arr as $k => $v){
			$mac = str_replace(':','-',$v->mac);
			$flag = $redis->exists('bikes:'.$mac);
			if($flag==1){
				if($v->name != '' and $v->name!=null)
					$redis->set('bikes:'.$mac,$v->name);
			}else{
				$redis->set('bikes:'.$mac,$v->name);	
			}
			
			//插入到数据库 ignore模式
			//插入到redis bike:$mac dwz_info_id
			//var_dump($v);
			//$dwz_info_id = 5820;
			$data_bike['bind']=$v->bind;
			if($v->mac == null or $v->mac == ''){
				$data_bike['mac']=' ';	
			}else{
				$data_bike['mac']=$v->mac;
			}
			$data_bike['name']=$v->name;
			$data_bike['rssi']=$v->rssi;
			$data_bike['dwz_info_id']=$dwz_info_id;
			$data_bike['lng']=$lng;
			$data_bike['lat']=$lat;
			$data_bike['time']=time();
			$data_bike['status']=1;
			//M("bike")->add($data,$options=array(),$replace=true);
			$map['mac']=array('eq',$v->mac);
			
			$item = M("bike")->where($map)->find();
			if($item!=null){
				//车在了  名称在了 就不需要更新
				if($v->name !='' and $v->name !=null)
					M("bike")->add($data_bike,$options=array(),$replace=true);
			}else{
				M("bike")->add($data_bike,$options=array(),$replace=true);
			}
		}
		//车位信息更新
		
		
		//记录实时情况流水
		$id = $redis->incr("realtime_id_$dwz_info_id");//设置自增id，相当于主键
		$time = time();
		$redis->hset("realtime:$dwz_info_id:".$id, 'id', $id);  
		$redis->hset("realtime:$dwz_info_id:".$id, 'dwz_info_id', $dwz_info_id);  
		$redis->hset("realtime:$dwz_info_id:".$id, 'time', $time);  
		$redis->hset("realtime:$dwz_info_id:".$id, 'storage_num', $storage_num);
		$redis->hset("realtime:$dwz_info_id:".$id, 'backup', $backup);  
		$redis->hset("realtime:$dwz_info_id:".$id, 'bikes', $bikes);
		$redis->expire("realtime:$dwz_info_id:".$id,60*30);
//////////////////////////////////////////////////
//将消息日志插入到数据库		
//设置过期时间
///////////////////////////////////////////////////

		/////////////////统计禁停区的记录////////////////////
		//$iid=5820;
		//$time = time();
		//echo "infobike";
		$str=$bikes;
		$arr = json_decode($str);
		$redis->delete("infobikesexist:".$iid);
		foreach($arr as $k=>$v){
			$mac = str_replace(':','-',$v->mac);
			//$redis->sadd("infobikes:".$iid,$v->mac);	
			$redis->sadd("infobikesexist:".$iid,$v->mac);	//当前的车辆
			//$redis->sadd("infobike:$iid:".$mac,$time); //车辆在某个车位采集到的所以时间	
			//$redis->expire("infobike:$iid:".$mac,60*60*24*3);
			$redis->incr("caijicishu:$dwz_info_id:".$mac);		//车辆在某个车位采集到的次数
			//$redis->sadd("bikeinfo:".$mac,$iid);	
		}
		$redis->expire("infobikesexist:".$iid,60*5);	//有效时间5分钟
		
		////////////////////////////////////

		
		//设置车位实时的数量
		$usable_num = $redis->hget('dwz_info:'.$dwz_info_id, 'usable_num');
		$la = $redis->hget('dwz_info:'.$dwz_info_id, 'la');
		$lb = $redis->hget('dwz_info:'.$dwz_info_id, 'lb');
		$lc = $redis->hget('dwz_info:'.$dwz_info_id, 'lc');
		
		
		$redis->hset('dwz_info:'.$dwz_info_id, 'storage_num',$storage_num);

		//根据比例 ，设置 level
		$redis->hset('dwz_info:'.$dwz_info_id, 'level',1);
		if($la!= 0){
			if($storage_num<=$la){
				$redis->hset('dwz_info:'.$dwz_info_id, 'level',-1);
			}
		}
		if($lb!=0){
			if($storage_num>=$lb){
				$redis->hset('dwz_info:'.$dwz_info_id, 'level',2);
			}
		}
		if($lc!=0){
			if($storage_num>=$lc){
				$redis->hset('dwz_info:'.$dwz_info_id, 'level',3);
			}
		}
		if($la==0 and $lb==0 and $lc==0){
			if($storage_num/$usable_num <= 0.8){
				$redis->hset('dwz_info:'.$dwz_info_id, 'level',1);
			}
			if($storage_num/$usable_num > 0.8 and $storage_num/$usable_num <= 1){
				$redis->hset('dwz_info:'.$dwz_info_id, 'level',2);
			}
			if($storage_num/$usable_num > 1){
				$redis->hset('dwz_info:'.$dwz_info_id, 'level',3);
			}	
		}
		
		$message = '';
		if($storage_num<0) {
			//echo "提交数量错误";
			$message="提交数量错误";
			//$this->message_log("update1","update1: $dwz_info_id 提交数量错误:$storage_num! at ".date("Y-m-d h:i:s"),1,$redis);
		}
		if($storage_num>$usable_num) {
			$num = $storage_num - $usable_num;
			//echo "超出存储数量";
			$message="P".$dwz_info_id.":超出存储数量:".$num;
			//$this->message_log("diaodu1","diaodu1:$dwz_info_id 车位 可以调度到 $cno 车位! at ".date("Y-m-d h:i:s"),1,$redis);
			$cno = $this->diaodu($redis,$dwz_info_id,$num);
			//var_dump($cno);
			if($cno != null){
				//var_dump($cno);
				$message="P".$dwz_info_id."超出存储数量:".$num.",可以调度到($cno)车位";
				//$this->message_log("diaodu1","diaodu1:$dwz_info_id 车位 可以调度到 $cno 车位! at ".date("Y-m-d h:i:s"),1,$redis);
			}
		}
		if($storage_num<=$usable_num and $storage_num >=0) {
			//echo "数量";
			$message="P".$dwz_info_id.":$storage_num";
			//$this->message_log("update1","update1:$dwz_info_id 数量:$storage_num! at ".date("Y-m-d h:i:s"),1,$redis);
		}
		
		
		//file_get_contents("http://116.62.171.54:8083/?method=sub_realtime");
		$url = "http://116.62.171.54:8083/?method=sub_realtime";
		
		//$this->getws($message);
		
		exit(json_encode(array('error_code'=>0,'reason'=>'提交成功','result'=>'')));
		
		//$b = json_decode($bikes);
		//var_dump($dwz_info_id);
		//var_dump($b);
		//var_dump($nums);
		//将接收到的数据提交到redis
		//或者进过解析提交到redis
		//同时提交
		
		//["xm_11-aa-33-44","xm_11-aa-33-44","xm_11-aa-33-44","xm_11-aa-33-44","xm_11-aa-33-44","xm_11-aa-33-44","xm_11-aa-33-44","xm_11-aa-33-44","xm_11-aa-33-44","xm_11-aa-33-44","xm_11-aa-33-44","xm_11-aa-33-44"]
		//echo "real";
		//echo json_encode(array('xm_11-aa-33-44','xm_11-aa-33-44','xm_11-aa-33-44','xm_11-aa-33-44','xm_11-aa-33-44','xm_11-aa-33-44','xm_11-aa-33-44','xm_11-aa-33-44','xm_11-aa-33-44','xm_11-aa-33-44','xm_11-aa-33-44','xm_11-aa-33-44'));
	}
	
	public function test(){
		$bikes='[{"bind":10,"mac":"FB01D36D8447","name":"mobike","rssi":-79},{"bind":10,"mac":"C37B483E3AED","name":"mobike","rssi":-70},{"bind":10,"mac":"D809CFC50618","name":"mb_GAbFzwnY","rssi":-65},{"bind":10,"mac":"E2D9A1F1D2C4","name":"mobike","rssi":-76},{"bind":10,"mac":"F57B77379335","name":"mb_NZM3d3v1","rssi":-63},{"bind":10,"mac":"CE993F8658B5","name":"67A0518","rssi":-80},{"bind":10,"mac":"C8E947BDED47","name":"67C2757","rssi":-79},{"bind":10,"mac":"D7EE512FCE73","name":"mb_c84vUe7X","rssi":-73},{"bind":10,"mac":"E3CA580737CC","name":"67A1898","rssi":-85},{"bind":10,"mac":"EC0E1EDC5BCD","name":"mobike","rssi":-76},{"bind":10,"mac":"CE3A64289156","name":"mb_VpEoZDrO","rssi":-68},{"bind":10,"mac":"E70259F6D572","name":"mb_ctX2WQLn","rssi":-49},{"bind":10,"mac":"CFB4AB7F1301","name":"mobike","rssi":-64},{"bind":10,"mac":"E95DA9017230","name":"ofo","rssi":-76},{"bind":10,"mac":"D97A04CCD584","name":"mb_hNXMBHrZ","rssi":-69},{"bind":10,"mac":"D0D3F6137BDB","name":"mobike","rssi":-81},{"bind":10,"mac":"E39FA2F1F44D","name":"67A1796","rssi":-79},{"bind":10,"mac":"CE9C43AE60BB","name":"mb_u2CuQ5zO","rssi":-77},{"bind":10,"mac":"C14F04667CED","name":"mobike","rssi":-71},{"bind":10,"mac":"EF803E9719D4","name":"mb_1BmXPoDv","rssi":-68},{"bind":10,"mac":"C12418365DAF","name":"ofo","rssi":-75},{"bind":10,"mac":"D436396AF3E1","name":"CoolQi","rssi":-80},{"bind":10,"mac":"D2667A8CACD2","name":"","rssi":-67},{"bind":10,"mac":"C63E9C95AA80","name":"mobike","rssi":-82},{"bind":10,"mac":"FBE86CBF6722","name":"67A1943","rssi":-74},{"bind":10,"mac":"C3DADB98361B","name":"mobike","rssi":-77},{"bind":10,"mac":"C7E13362FF70","name":"mobike","rssi":-71},{"bind":10,"mac":"EFF0C016B3DE","name":"ofo","rssi":-81},{"bind":10,"mac":"DB75F0A7C5AF","name":"mb_r8Wn8HXb","rssi":-68},{"bind":10,"mac":"F227FBC70EAF","name":"mobike","rssi":-71},{"bind":10,"mac":"74BAB6AEB9C0","name":"","rssi":-80},{"bind":10,"mac":"E6D33A776685","name":"mb_hWZ3OtPm","rssi":-62},{"bind":10,"mac":"C2794EA09592","name":"mb_kpWgTnnC","rssi":-67},{"bind":10,"mac":"E9975B98906A","name":"ofo","rssi":-75},{"bind":10,"mac":"DDCC85BD2261","name":"","rssi":-70},{"bind":10,"mac":"FDED10E73576","name":"mb_djXnEO39","rssi":-81},{"bind":10,"mac":"C1663D3B8D9E","name":"mobike","rssi":-77},{"bind":10,"mac":"FE9BF6F81FAC","name":"mb_rB\/49pv+","rssi":-80},{"bind":10,"mac":"AC233F205F5D","name":"","rssi":-77},{"bind":10,"mac":"E79F44E0730B","name":"mobike","rssi":-69},{"bind":10,"mac":"EBFBD90A9065","name":"6740012","rssi":-78},{"bind":10,"mac":"DA1230E3705D","name":"mobike","rssi":-71},{"bind":10,"mac":"DFB59B681423","name":"mb_IxRom7Xf","rssi":-71},{"bind":10,"mac":"EE047FFEC953","name":"mobike","rssi":-74},{"bind":10,"mac":"D7C901DF23F7","name":"mobike","rssi":-68},{"bind":10,"mac":"C2DEA3B093EC","name":"mobike","rssi":-76},{"bind":10,"mac":"E235DF852585","name":"mobike","rssi":-78},{"bind":10,"mac":"D6ACD7CF229C","name":"mobike","rssi":-69},{"bind":10,"mac":"C0085F372C53","name":"mobike","rssi":-79},{"bind":10,"mac":"DD4E48A34866","name":"mb_ZkijSE7d","rssi":-64},{"bind":10,"mac":"DDED4CD2E3AE","name":"67A1450","rssi":-81},{"bind":10,"mac":"DB7954D7267A","name":"mb_eibXVHnb","rssi":-80},{"bind":10,"mac":"E3DC117BFCC2","name":"mobike","rssi":-76},{"bind":10,"mac":"D3B9E880F560","name":"mobike","rssi":-76},{"bind":10,"mac":"C3ECE55C7041","name":"mb_QXBc5ezD","rssi":-76},{"bind":10,"mac":"50F14A4250F7","name":"XIAOMING","rssi":-68},{"bind":10,"mac":"D86287A360AC","name":"mobike","rssi":-66},{"bind":10,"mac":"C5C57722800E","name":"mobike","rssi":-79},{"bind":10,"mac":"F29C054EA904","name":"mobike","rssi":-66},{"bind":10,"mac":"EDEAF1B9AE67","name":"mobike","rssi":-73},{"bind":10,"mac":"E0BBE970589A","name":"mb_mlhw6bvg","rssi":-50},{"bind":10,"mac":"D432FFA91BD8","name":"mobike","rssi":-68},{"bind":10,"mac":"DA8E4D2D8FD0","name":"mobike","rssi":-68},{"bind":10,"mac":"D9236F98BAB2","name":"mobike","rssi":-76},{"bind":10,"mac":"CE91AFBD52CD","name":"6740295","rssi":-72},{"bind":10,"mac":"CF428A5A79E8","name":"ofo","rssi":-82},{"bind":10,"mac":"DD203F94B4A6","name":"mobike","rssi":-75},{"bind":10,"mac":"E95D3EAD8C1B","name":"mobike","rssi":-76},{"bind":10,"mac":"DFF99BFDA750","name":"mb_UKf9m\/nf","rssi":-68},{"bind":10,"mac":"C1C3BC8A497A","name":"mb_ekmKvMPB","rssi":-69},{"bind":10,"mac":"CB148A6D7F92","name":"mobike","rssi":-70},{"bind":10,"mac":"F3164BD2AF38","name":"mb_OK\/SSxbz","rssi":-63},{"bind":10,"mac":"CD2ED9AB716D","name":"mobike","rssi":-70},{"bind":10,"mac":"F668ED71DA6B","name":"mb_a9px7Wj2","rssi":-76},{"bind":10,"mac":"C610BC9994B6","name":"mobike","rssi":-74},{"bind":10,"mac":"D90F50CFA1E7","name":"mobike","rssi":-79},{"bind":10,"mac":"F696914CA143","name":"mobike","rssi":-61},{"bind":10,"mac":"FCD10964AFC5","name":"mb_xa9kCdH8","rssi":-79},{"bind":10,"mac":"F2EC07CC3FFE","name":"","rssi":-80},{"bind":10,"mac":"C5C48A551584","name":"mb_hBVVisTF","rssi":-74},{"bind":10,"mac":"50338B1238B5","name":"BL-3","rssi":-75},{"bind":10,"mac":"E21537CDE435","name":"mb_NeTNNxXi","rssi":-65},{"bind":10,"mac":"E1E42B5D6432","name":"mobike","rssi":-67},{"bind":10,"mac":"C68E5ED426DD","name":"mobike","rssi":-70},{"bind":10,"mac":"C867B984E040","name":"mb_QOCEuWfI","rssi":-80},{"bind":10,"mac":"FBCE124CAC4E","name":"mobike","rssi":-75},{"bind":10,"mac":"DE29F973A59C","name":"mobike","rssi":-67},{"bind":10,"mac":"C640257AE621","name":"mobike","rssi":-80},{"bind":10,"mac":"E6AB8EBF1F3D","name":"","rssi":-65},{"bind":10,"mac":"EBE8042CA2BF","name":"ofo","rssi":-72},{"bind":10,"mac":"E13F3FABCB48","name":"mobike","rssi":-66},{"bind":10,"mac":"CB966285FBD9","name":"mb_2fuFYpbL","rssi":-81},{"bind":10,"mac":"C6457E7B390A","name":"mb_Cjl7fkXG","rssi":-76},{"bind":10,"mac":"D9C605E65F3B","name":"mb_O1\/mBcbZ","rssi":-74},{"bind":10,"mac":"C435C791DA94","name":"mobike","rssi":-68},{"bind":10,"mac":"E4DB47AD322D","name":"mobike","rssi":-67},{"bind":10,"mac":"F07605F12916","name":"mb_FinxBXbw","rssi":-67},{"bind":10,"mac":"FC88C2DB09D9","name":"mb_2Qnbwoj8","rssi":-79},{"bind":10,"mac":"DA5087ECCBB9","name":"mb_ucvsh1Da","rssi":-63},{"bind":10,"mac":"FA85C117B46B","name":"mobike","rssi":-76},{"bind":10,"mac":"F253C47AB86C","name":"mobike","rssi":-69},{"bind":10,"mac":"9C1D5816FE70","name":"BL-2A","rssi":-76},{"bind":10,"mac":"F686B31AA9DC","name":"mobike","rssi":-61},{"bind":10,"mac":"FF38BB4DBBB2","name":"mobike","rssi":-76},{"bind":10,"mac":"C00FAC87180A","name":"mb_ChiHrA\/A","rssi":-78},{"bind":10,"mac":"C74AEAF35295","name":"mb_lVLz6krH","rssi":-74},{"bind":10,"mac":"FB2D35B43DA4","name":"mobike","rssi":-79},{"bind":10,"mac":"F16A5B458FC5","name":"mobike","rssi":-65},{"bind":10,"mac":"6299233461EF","name":"","rssi":-78},{"bind":10,"mac":"A0E6F8013404","name":"ISE000","rssi":-70},{"bind":10,"mac":"D1541D611FFC","name":"mobike","rssi":-62},{"bind":10,"mac":"C8FD1994CEFE","name":"XIAOMING","rssi":-72},{"bind":10,"mac":"FA0B6F10CD5C","name":"\u5409\u5229\u4e1c\u7ad9","rssi":-70},{"bind":10,"mac":"D8E8C78EE75B","name":"mobike","rssi":-70},{"bind":10,"mac":"AC233F205F72","name":"BrtBeacon505","rssi":-69},{"bind":10,"mac":"E1B6490DDBB9","name":"mobike","rssi":-44},{"bind":10,"mac":"C16A070DC9DC","name":"6740293","rssi":-80},{"bind":10,"mac":"DDA5791CAB80","name":"ofo","rssi":-76},{"bind":10,"mac":"F273F4026CF0","name":"67B3438","rssi":-79},{"bind":10,"mac":"F5396DC5F613","name":"mb_E\/bFbTn1","rssi":-68},{"bind":10,"mac":"CFE6FD7CAF0A","name":"mb_Cq98\/ebP","rssi":-78},{"bind":10,"mac":"FE175232E40A","name":"mobike","rssi":-75},{"bind":10,"mac":"EF3E728BE2DC","name":"mb_3OKLcj7v","rssi":-75},{"bind":10,"mac":"EC783828FCE0","name":"67A1792","rssi":-78},{"bind":10,"mac":"E3B9580B3336","name":"ofo_adv_tes","rssi":-81},{"bind":10,"mac":"C94D4A2867C2","name":"mobike","rssi":-67},{"bind":10,"mac":"CBE19386DAFB","name":"mb_+9qGk+HL","rssi":-69},{"bind":10,"mac":"FE12D2D25548","name":"mb_SFXS0hL+","rssi":-69},{"bind":10,"mac":"F8E2E5E42C7E","name":"mobike","rssi":-66},{"bind":10,"mac":"EC18B0843ED7","name":"mb_1z6EsBjs","rssi":-73},{"bind":10,"mac":"C5D061455EBF","name":"67A1436","rssi":-82}]';
		$num = $this->cal_nums($bikes);
		var_dump($num);
	}
	
	private function cal_nums($bikes){
		$redis = new \Redis();
		$redis->connect('127.0.0.1', 6379);
		
		$exist_list = json_decode($bikes,true);
		$arr =array();
		foreach($exist_list as $k=>$v){
			//$mac = str_replace(':','-',$v);
			$mac=$v['mac'];
			$arr_exist[$k]['name']=$redis->get('bikes:'.$mac);
			$arr_exist[$k]['mac']=$v['mac'];
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
			
			/*if($flag=='no'){
				$bike_names[]='其他';
			}*/
		}
		$num = sizeof($bike_names);
		return $num;
	}
	
	private function startwith($str,$pattern) {
		if(strpos($str,$pattern) === 0)
          return true;
		else
          return false;
	}
	
	public function sub(){
		//require("mylibs/phpMQTT.php");
		//$mqtt = new \phpMQTT("116.62.171.54", 8081, "test"); //Change client name to something unique
		$redis = new \Redis();
		$redis->connect('127.0.0.1', 6379);
		//echo "Server is running: " . $redis->ping();
		
		$data['bike_company']=$bike_company = isset($_POST['bike_company'])?$_POST['bike_company']:'';
		$data['dwz_info_id']=$dwz_info_id = isset($_POST['dwz_info_id'])?$_POST['dwz_info_id']:'';
		$data['dwz_info_title']=$dwz_info_title = isset($_POST['dwz_info_title'])?$_POST['dwz_info_title']:'';
		$data['gps']=$gps = isset($_POST['gps'])?$_POST['gps']:'';
		$data['lng']=$lng = isset($_POST['lng'])?$_POST['lng']:'';
		$data['lat']=$lat = isset($_POST['lat'])?$_POST['lat']:'';
		$data['ibeacon_id']=$ibeacon_id = isset($_POST['ibeacon_id'])?$_POST['ibeacon_id']:'';
		$data['binout']=$inout = isset($_POST['inout'])?$_POST['inout']:'';
		if($bike_company=='' || $dwz_info_id=='' || $dwz_info_title=='' || $ibeacon_id=='' || $inout==''){
			exit(json_encode(array('error_code'=>1,'reason'=>'参数不正确','result'=>'')));
			exit;
		}
		M("bike_sub")->add($data);
		
		//记录流水
		$id = $redis->incr("dwz_bike_flow_".$dwz_info_id);//设置自增id，相当于主键
		$time = time();
		$redis->hset('dwz_bike_flow:'.$dwz_info_id.':'.$id, 'id', $id);  
		$redis->hset('dwz_bike_flow:'.$dwz_info_id.':'.$id, 'create_time', $time);  
		$redis->hset('dwz_bike_flow:'.$dwz_info_id.':'.$id, 'bike_company', $bike_company);  
		$redis->hset('dwz_bike_flow:'.$dwz_info_id.':'.$id, 'dwz_info_id', $dwz_info_id);  
		$redis->hset('dwz_bike_flow:'.$dwz_info_id.':'.$id, 'dwz_info_title', $dwz_info_title);  
		$redis->hset('dwz_bike_flow:'.$dwz_info_id.':'.$id, 'gps', $gps);  
		$redis->hset('dwz_bike_flow:'.$dwz_info_id.':'.$id, 'lng', $lng);  
		$redis->hset('dwz_bike_flow:'.$dwz_info_id.':'.$id, 'lat', $lat);  
		$redis->hset('dwz_bike_flow:'.$dwz_info_id.':'.$id, 'ibeacon_id', $ibeacon_id);  
		$redis->hset('dwz_bike_flow:'.$dwz_info_id.':'.$id, 'inout', $inout); 
//////////////////////////////////////////////////
//将消息日志插入到数据库		
//设置过期时间
///////////////////////////////////////////////////

		
		//设置bike_flow的同时，计算统计值，并更新
		//当编号不存在的情况下，那么增长
		$usable_num = $redis->hget('dwz_info:'.$dwz_info_id, 'usable_num');
		//echo $usable_num;
		$storage_num = $redis->hget('dwz_info:'.$dwz_info_id, 'storage_num');
		//echo $storage_num;
		$storage_num = $storage_num + $inout;
		$message = "";
		if($storage_num<0) {
			//echo "提交数量错误";
			$message="提交数量错误";
			//$this->message_log("update1","update1: $dwz_info_id 提交数量错误:$storage_num! at ".date("Y-m-d h:i:s"),1,$redis);
		}
		if($storage_num>$usable_num) {
			$num = $storage_num - $usable_num;
			//echo "超出存储数量";
			$message="超出存储数量:$num";
			//$this->message_log("diaodu1","diaodu1:$dwz_info_id 车位 可以调度到 $cno 车位! at ".date("Y-m-d h:i:s"),1,$redis);
			$cno = $this->diaodu($redis,$dwz_info_id,$num);
			//var_dump($cno);
			if($cno != null){
				//var_dump($cno);
				$message="超出存储数量:($num),($dwz_info_id)车位可以调度到($cno)车位";
				//$this->message_log("diaodu1","diaodu1:$dwz_info_id 车位 可以调度到 $cno 车位! at ".date("Y-m-d h:i:s"),1,$redis);
			}
		}
		if($storage_num<=$usable_num and $storage_num >=0) {
			//echo "数量";
			$message="数量:$storage_num";
			//$this->message_log("update1","update1:$dwz_info_id 数量:$storage_num! at ".date("Y-m-d h:i:s"),1,$redis);
		}
		$redis->hset('dwz_info:'.$dwz_info_id, 'storage_num',$storage_num);
		//根据比例 ，设置 level
		if($storage_num/$usable_num <= 0.8){
			$redis->hset('dwz_info:'.$dwz_info_id, 'level',1);
		}
		if($storage_num/$usable_num > 0.8 and $storage_num/$usable_num <= 1){
			$redis->hset('dwz_info:'.$dwz_info_id, 'level',2);
		}
		if($storage_num/$usable_num > 1){
			$redis->hset('dwz_info:'.$dwz_info_id, 'level',3);
		}
		
		//file_get_contents("http://116.62.171.54:8083/?method=sub&message=$message");
		//$url = "http://116.62.171.54:8083/?method=sub&message=$message";
		
		$this->getws($message);
		
		exit(json_encode(array('error_code'=>0,'reason'=>'提交成功','result'=>'')));
		//var_dump($bike_count);
		//$this->success("http://116.62.171.54:8080/manbike0.2/index.php/Home/Index/sub_form");
		$url = "http://116.62.171.54:8080/manbike0.2/index.php/Home/Index/sub_form";
		header("Location:$url");
	}
	
	//车位列表接口
	public function site_list(){
		$block_id = isset($_POST['block_id'])?$_POST['block_id']:'';
		$title = isset($_POST['title'])?$_POST['title']:'';
		$lng = isset($_POST['lng'])?$_POST['lng']:'';
		$lat = isset($_POST['lat'])?$_POST['lat']:'';
		$page = isset($_POST['page'])?$_POST['page']:'';
		$num = isset($_POST['num'])?$_POST['num']:'';
		if($lng=='' || $lat==''){
			exit(json_encode(array('error_code'=>1,'reason'=>'参数不正确','result'=>'')));
			exit;
		}
		$list = M("info")->field("id,lng,lat,title,usable_num")->select();
		exit(json_encode(array('error_code'=>0,'reason'=>'获取列表成功','result'=>$list)));
	}
	//车位列表接口
	public function site_detail(){
		$site_id = isset($_POST['site_id'])?$_POST['site_id']:'';
		if($site_id==''){
			exit(json_encode(array('error_code'=>1,'reason'=>'参数不正确','result'=>'')));
			exit;
		}
		$map['id']=array('eq',$site_id);
		$info = M("info")->where($map)->select();
		if($info==null) exit(json_encode(array('error_code'=>0,'reason'=>'车位不存在','result'=>$info)));
		else exit(json_encode(array('error_code'=>0,'reason'=>'获取车位详情成功','result'=>$info)));
	}
	public function bike_company1(){
		//$ip = $_SERVER['REMOTE_ADDR'];
		$postText = trim(file_get_contents('php://input'));
		//file_put_contents('/root/log.txt', var_export($ip, true) . "\n", FILE_APPEND);
		//file_put_contents('/root/log.txt', var_export($postText, true) . "\n", FILE_APPEND);
		
		$data['bikes']=$postText;
		M("new_ibeacon")->add($data);
		exit;
		//$postText = '[{"timestamp":"2017-09-06T09:37:45Z","type":"Unknown","mac":"1918FC051F60","bleName":"","rssi":-72,"rawData":"1E168643BDBBBADEBABAA3A246BFA5DA813C95B29DA8AEE27FBA88454545BA"},{"timestamp":"2017-09-06T09:37:45Z","type":"Unknown","mac":"1918FC051F66","bleName":"","rssi":-70,"rawData":"1E168643BDBBBADEBABAA3A246BFA5DC813C95B29DA8AEE27FBA88454545BA"},{"timestamp":"2017-09-06T09:37:46Z","type":"iBeacon","mac":"1918FC051F5A","bleName":"","ibeaconUuid":"FDA50693A4E24FB1AFCFC6EB07647825","ibeaconMajor":10002,"ibeaconMinor":5208,"rssi":-67,"ibeaconTxPower":-59,"battery":0},{"timestamp":"2017-09-06T09:37:45Z","type":"Unknown","mac":"3535EDC935EE","bleName":"","rssi":-61,"rawData":"1EFF060001092000351A0F7A6A0A330B5CC7A2C1BE09A1C536C259B583CA30"},{"timestamp":"2017-09-06T09:37:45Z","type":"Unknown","mac":"1918FC03BAC5","bleName":"","rssi":-76,"rawData":"1E168643292F2E4A2E2E3736D22D94EB15A801260996D28FEB2E1CD1D1D12E"},{"timestamp":"2017-09-06T09:37:45Z","type":"Unknown","mac":"1918FC051F6C","bleName":"","rssi":-68,"rawData":"1E168643BDBBBADEBABAA3A246BFA5D6813C95B29DA8AEE27FBA88454545BA"},{"timestamp":"2017-09-06T09:37:45Z","type":"Unknown","mac":"1918FC051F6B","bleName":"","rssi":-74,"rawData":"1E168643BDBBBADEBABAA3A246BFA5D1813C95B29DA8AEE27FBA88454545BA"},{"timestamp":"2017-09-06T09:37:45Z","type":"iBeacon","mac":"1918FC051F5B","bleName":"","ibeaconUuid":"FDA50693A4E24FB1AFCFC6EB07647825","ibeaconMajor":10002,"ibeaconMinor":5208,"rssi":-74,"ibeaconTxPower":-59,"battery":0},{"timestamp":"2017-09-06T09:37:45Z","type":"iBeacon","mac":"1918FC051F63","bleName":"","ibeaconUuid":"FDA50693A4E24FB1AFCFC6EB07647825","ibeaconMajor":10002,"ibeaconMinor":5208,"rssi":-74,"ibeaconTxPower":-59,"battery":0},{"timestamp":"2017-09-06T09:37:45Z","type":"Unknown","mac":"C9F028FD8948","bleName":"mb_SIn9KPDJ","rssi":-53,"rawData":"0201060C096D625F53496E394B50444A"},{"timestamp":"2017-09-06T09:37:44Z","type":"Unknown","mac":"1918FC051F6D","bleName":"","rssi":-67,"rawData":"1E168643BDBBBADEBABAA3A246BFA5D7813C95B29DA8AEE27FBA88454545BA"},{"timestamp":"2017-09-06T09:37:45Z","type":"Unknown","mac":"88C255A39FE7","bleName":"ziJiang printer","rssi":-61,"rawData":"10097A694A69616E67207072696E746572"},{"timestamp":"2017-09-06T09:37:45Z","type":"Unknown","mac":"1918FC04559C","bleName":"","rssi":-74,"rawData":"1E168643DDDBDABEDADAC3C226DE8F46E15CF5D2FDC8CE821FDAE8252525DA"},{"timestamp":"2017-09-06T09:37:45Z","type":"Unknown","mac":"1918FC051F6F","bleName":"","rssi":-73,"rawData":"1E168643BDBBBADEBABAA3A246BFA5D5813C95B29DA8AEE27FBA88454545BA"}]';
		$post = json_decode($postText);
		foreach($post as $k=>$v){
			$data['timestamp']=$v->timestamp;
			$data['type']=$v->type;
			$data['mac']=$v->mac;
			$data['bleName']=$v->bleName;
			$data['rssi']=$v->rssi;
			if($v->rawData == null)
				$data['rawData']='';
			M("ibeacon")->add($data);
		}
		//file_put_contents('/root/log.txt', var_export($postText, true) . "\n", FILE_APPEND);
	}
	public function sub_gateway_form(){
		$this->display();
	}
	public function gateway(){
		
		$redis = new \Redis();
		$redis->connect('127.0.0.1', 6379);
		
		$flag = isset($_POST['flag'])?$_POST['flag']:0;
		//file_put_contents('/root/logsss.txt', var_export($postText, true) . "\n", FILE_APPEND);
		if($flag == 1){
			//当标志为1的时候是模拟数据，不需要入库
			$postText = $_POST['inout'];
			file_put_contents('/root/moni.txt', var_export($postText, true) . "\n", FILE_APPEND);
			//var_dump($_POST);
		}else{
			//实际提交的数据，存入mysql数据库
			$postText = trim(file_get_contents('php://input'));			
			$data['bikes']=$postText;
			$id = M("new_ibeacon")->add($data);
			
			//file_put_contents('/root/logxxx.txt', var_export($postText, true) . "\n", FILE_APPEND);
		}
		
		
		//数据存入数据库 
		
		//最新一条数据存入redis
		//以gateway的mac作为键
		$key = '';
		$arr = array();
		$post = json_decode($postText);
		//var_dump(sizeof($post));
		//var_dump($post);
		
		$i = 0;
		$arr=array();
		foreach($post as $k=>$v){
			
			if($v->type=='Gateway'){
				$key = $v->mac;
			}else{
				$temp['bind']=10;
				$temp['mac']=$v->mac;
				$temp['name']=$v->bleName;
				$temp['rssi']=$v->rssi;
				$arr[]=$temp;
			}
			$i++;
			/*$data['timestamp']=$v->timestamp;
			$data['type']=$v->type;
			$data['mac']=$v->mac;
			$data['bleName']=$v->bleName;
			$data['rssi']=$v->rssi;
			if($v->rawData == null)
				$data['rawData']='';
			M("ibeacon")->add($data);*/
		}
		//将数据存入redis中
		//echo $key;
		//var_dump(sizeof($arr));
		//echo json_encode($arr);
		$value = json_encode($arr);
		$redis->set('gateway:'.$key,$value);
		exec("/opt/lampp/bin/php /root/ws/gateway.php $key",$info);
	}
	public function gateway1(){
		file_put_contents('/root/geteway111.txt', var_export($_POST, true) . "\n", FILE_APPEND);
	}
	
	//当超出存储数量的时候，需要计算出该车位所在区块如何调度
	//查询本区块的车位
	//比较数量
	//比较距离
	//发出调度指令
	private function diaodu($redis,$dwz_info_id,$num){
		//根据id查询所在区块
		$block_no = $redis->hget('dwz_info:'.$dwz_info_id, 'block_no');
		//var_dump($block_no);
		//使用区块编号查询所有车位
		$mb = $redis->smembers("block:$block_no");
		//然后计算哪几个车位可以存放
		$can_use_chewei=array();
		foreach($mb as $k=>$v){
			$usable_num = $redis->hget('dwz_info:'.$v, 'usable_num');
			$storage_num = $redis->hget('dwz_info:'.$v, 'storage_num');
			$leave_num = $usable_num-$storage_num;
			if($leave_num >= $num){
				$can_use_chewei[]=$v;
			}
		}
		//var_dump($can_use_chewei);
		//然后再根据距离得到最近的那个车位
		$distance = array();
		foreach($can_use_chewei as $k=>$v){
			//var_dump($v);
			$olng = $redis->hget('dwz_info:'.$dwz_info_id, 'lng');
			$olat = $redis->hget('dwz_info:'.$dwz_info_id, 'lat');
			
			$lng = $redis->hget('dwz_info:'.$v, 'lng');
			$lat = $redis->hget('dwz_info:'.$v, 'lat');
			$dis = ($lng-$olng)*($lng-$olng)+($lat-$olat)*($lat-$olat);
			$distance[$dis]=$v;
		}
		sort($distance);
		$cno =  reset($distance);
		return $cno;
	}
	//记录历史消息
	private function message_log($topic,$content,$block=1,$redis){
		$id = $redis->incr("dwz_message_log_".$topic);//设置自增id，相当于主键
		$time = date("Y-m-d h:i:s");
		$redis->hset('dwz_message_log:'.$topic.':'.$id, 'id', $id);  
		$redis->hset('dwz_message_log:'.$topic.':'.$id, 'time', $time);  
		$redis->hset('dwz_message_log:'.$topic.':'.$id, 'content', $content);  
	}
	//curl 方法
	private function getws($message){
		//exec("/opt/lampp/bin/php /root/ws/client.php '$message'");
		$data['content']=$message;
		M("message_log")->add($data);
		exec("/opt/lampp/bin/php /root/ws/wsclient.php getws:'$message'");
	}
	
	
	
}