<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    //首页
	public function index(){
		$url = "http://116.62.171.54:8080/manbike0.3/index.php/Home/Show/demo";
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
					if(count($data) >= 30){
						break;
					}
				}else{
					if(count($data) >= 30){
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
		
		$redis = new \Redis();
		$redis->connect('127.0.0.1', 6379);
		
		$list = array();
		foreach($data as $k=>$v){
			$item['id'] = $redis->hget('dwz_info:'.$v['id'], 'id');
			$item['lng'] = $redis->hget('dwz_info:'.$v['id'], 'lng');
			$item['lat'] = $redis->hget('dwz_info:'.$v['id'], 'lat');
			$item['usable_num'] = $redis->hget('dwz_info:'.$v['id'], 'usable_num');
			$item['storage_num'] = $redis->hget('dwz_info:'.$v['id'], 'storage_num');
			$item['level'] = $redis->hget('dwz_info:'.$v['id'], 'level');
			$list[]=$item;
		}
		
		echo json_encode($list);
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
		$data['storage_num']=$storage_num=$_POST['nums'];
		$data['backup']=$backup=$_POST['backup'];
		$data['bikes']=$bikes=$_POST['bikes'];
		
		if($dwz_info_id=='' || $time=='' || $storage_num=='' ||$bikes==''){
			exit(json_encode(array('error_code'=>1,'reason'=>'参数不正确','result'=>'')));
		}
		//流水插入到数据库
		M("bike_sub_realtime")->add($data);
		
		$redis = new \Redis();
		$redis->connect('127.0.0.1', 6379);
		
		$redis->set('lasttime:'.$iid,$time);
		
		$lng = $redis->hget('dwz_info:'.$dwz_info_id, 'lng');
		$lat = $redis->hget('dwz_info:'.$dwz_info_id, 'lat');
		//
		$arr = json_decode($bikes);
		$map_bike['dwz_info_id']=array('eq',$dwz_info_id);
		$data['status']=0;
		M("bike")->where($map_bike)->save($data);
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
			$data['bind']=$v->bind;
			if($v->mac == null or $v->mac == ''){
				$data['mac']=' ';	
			}else{
				$data['mac']=$v->mac;
			}
			$data['name']=$v->name;
			$data['rssi']=$v->rssi;
			$data['dwz_info_id']=$dwz_info_id;
			$data['lng']=$lng;
			$data['lat']=$lat;
			$data['time']=time();
			$data['status']=1;
			//M("bike")->add($data,$options=array(),$replace=true);
			$map['mac']=array('eq',$v->mac);
			
			$item = M("bike")->where($map)->find();
			if($item!=null){
				//车在了  名称在了 就不需要更新
				if($v->name !='' and $v->name !=null)
					M("bike")->add($data,$options=array(),$replace=true);
			}else{
				M("bike")->add($data,$options=array(),$replace=true);
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
			$redis->sadd("infobike:$iid:".$mac,$time); //车辆在某个车位采集到的所以时间
			$redis->expire("infobike:$iid:".$mac,60*60*24*3);
			$redis->incr("caijicishu:$dwz_info_id:".$mac);		//车辆在某个车位采集到的次数
			//$redis->sadd("bikeinfo:".$mac,$iid);	
		}
		
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
		
		$this->getws($message);
		
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
			M("new_ibeacon")->add($data);
			file_put_contents('/root/logxxx.txt', var_export($postText, true) . "\n", FILE_APPEND);
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