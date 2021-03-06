<?php
namespace Admin\Controller;
use Think\Controller;
class BparkingController extends CommonController {
	public function _initialize(){
		parent::_initialize();
	}
	public function index() {
		
		//$redis = new \Redis();
		//$redis->connect('127.0.0.1', 6379);
		//$allinfo = M('info')->field('id,title,province,city,area,usable_num,storage_num,status,la,lb,lc')->select();
		//$redis->set('allinfo',json_encode($allinfo));
		//var_dump($_SESSION['auth']);
		
		$uid = $_SESSION["auth"]["id"];
		$this->assign("uid",$uid);
		
		$title="";
		$usable_num="";
		$storage_num="";
		$no="";
		$nowtime=date('Y-m-d H:i:s');
		$starttime=date('Y-m-d H:i:s',strtotime('-1 hour'));
		$this->assign("nowtime2",$nowtime);
		$this->assign("starttime2",$starttime);
		$data = M('info');
		$sql="SELECT * FROM dwz_info where 1=1 ";
		if (!empty($_GET["ntitle"]) || $_GET["ntitle"]==='0') {
			$title = $_REQUEST["ntitle"];
			$sql .= " and title like '%$title%' ";
			$where["title"] = array('like', "%$title%");
			$this->assign('title', $title);
		}
		if (!empty($_GET["usable_num"]) || $_GET["usable_num"]==='0') {
			$usable_num = $_REQUEST["usable_num"];
			$sql .= " and usable_num = '$usable_num' ";
			$where["usable_num"] = '$usable_num';
			$this->assign('usable_num', $usable_num);
		}
		if (!empty($_GET["storage_num"]) || $_GET["storage_num"]==='0') {
			$storage_num = $_REQUEST["storage_num"];
			$sql .= " and storage_num = '$storage_num' ";
			$where["storage_num"] = '$storage_num';
			$this->assign('storage_num', $storage_num);
		}
		if (!empty($_GET["nno"]) || $_GET["nno"]==='0') {
			$no = $_REQUEST["nno"];
			$sql .= " and id = '$no' ";
			$where["id"] = '$no';
			$this->assign('no', $no);
		}
		if (!empty($_GET["area"]) || $_GET["area"]==='0') {
			$area = $_REQUEST["area"];
			$sql .= " and area like '%$area%' ";
			$where["area"] = array('like', "%$area%");
			$this->assign('area', $area);
		}
		//根据行政级别，做相应的过滤
		$province = $_SESSION['auth']['province'];
		$city = $_SESSION['auth']['city'];
		$area = $_SESSION['auth']['area'];
			
		if($_SESSION['auth']['class']=='省级'){
			$sql .= " and province = '$province' ";
			$where["province"] = $_SESSION['auth']['province'];
		}
		if($_SESSION['auth']['class']=='市级'){
			$sql .= " and province = '$province' ";
			$sql .= " and city = '$city' ";
			$where["province"] = $_SESSION['auth']['province'];
			$where["city"] = $_SESSION['auth']['city'];
		}
		if($_SESSION['auth']['class']=='区级'){
			$sql .= " and province = '$province' ";
			$sql .= " and city = '$city' ";
			$sql .= " and area = '$area' ";
			$where["province"] = $_SESSION['auth']['province'];
			$where["city"] = $_SESSION['auth']['city'];
			$where["area"] = $_SESSION['auth']['area'];
		}
		//var_dump($where);
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
	
	public function test(){
		
		$redis = new \Redis();
		$redis->connect('127.0.0.1', 6379);
		$dwz_info_id = 5888;
		$la = $redis->hget('dwz_info:'.$dwz_info_id, 'la');
		$lb = $redis->hget('dwz_info:'.$dwz_info_id, 'lb');
		$lc = $redis->hget('dwz_info:'.$dwz_info_id, 'lc');
		var_dump($la);
		if($la != 0){
			echo "xxx";
		}
		echo $lb;
		echo $lc;
		echo "test";
		
		$storage_num = $redis->hget('dwz_info:'.$dwz_info_id, 'storage_num');
		$level = $redis->hget('dwz_info:'.$dwz_info_id, 'level');
		var_dump($storage_num);
		var_dump($level);
	}

	/**
	 * 添加菜单
	 */
	public function add(){
		
		$redis = new \Redis();
		$redis->connect('127.0.0.1', 6379);
		
		$data['title'] = $_POST['title'];
		$data['usable_num'] = $_POST['usable_num'];
		$data['storage_num'] = $_POST['storage_num'];
		$data['overflow_num'] = $_POST['overflow_num'];
		$data['la'] = $_POST['la'];
		$data['lb'] = $_POST['lb'];
		$data['lc'] = $_POST['lc'];
		$data['no'] = $_POST['no'];
		$data['province'] = $_POST['province'];
		$data['city'] = $_POST['city'];
		$data['area'] = $_POST['area'];
		if($_REQUEST["status"]==''){
			$data['status']=0;
		}else{
			$data['status']=$_REQUEST["status"];
		}
		$data['block_no'] = $_POST['block_no'];
		$lng_lat = $_POST['lng_lat'];
		$data['lng']=strstr($lng_lat,',',true);
		$data['lat']=ltrim(strstr($lng_lat,','), ",");
		$data['tinyurl']=time();
		$result=M('info')->add($data);
		
		$redis->hset("dwz_info:$result",'block_no',$data['block_no']);
		$redis->hset("dwz_info:$result",'id',$result);
		$redis->hset("dwz_info:$result",'lat',$data['lat']);
		$redis->hset("dwz_info:$result",'lng',$data['lng']);
		$redis->hset("dwz_info:$result",'level',0);
		$redis->hset("dwz_info:$result",'la',$data['la']);
		$redis->hset("dwz_info:$result",'lb',$data['lb']);
		$redis->hset("dwz_info:$result",'lc',$data['lc']);
		$redis->hset("dwz_info:$result",'status',0);
		$redis->hset("dwz_info:$result",'storage_num',$data['storage_num']);
		$redis->hset("dwz_info:$result",'title',$data['title']);
		$redis->hset("dwz_info:$result",'usable_num',$data['usable_num']);
		
		$redis->set('lnglatid:'.$data['lng'].'|'.$data['lat'],$result);
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
		$redis = new \Redis();
		$redis->connect('127.0.0.1', 6379);
		//var_dump($_POST);
		//exit;
		if($_POST['province']=='' || $_POST['city']=='' || $_POST['area']=='')
			$this->error('参数不正确');
		
		$data['title'] = $_POST['title'];
		$data['usable_num'] = $_POST['usable_num'];
		$data['storage_num'] = $_POST['storage_num'];
		$data['overflow_num'] = $_POST['overflow_num'];
		$data['la'] = $_POST['la'];
		$data['lb'] = $_POST['lb'];
		$data['lc'] = $_POST['lc'];
		$data['no'] = $_POST['no'];
		$data['province'] = $_POST['province'];
		$data['city'] = $_POST['city'];
		$data['area'] = $_POST['area'];
		if($_REQUEST["status"]==''){
			$data['status']=0;
		}else{
			$data['status']=$_REQUEST["status"];
		}
		$data['block_no'] = $_POST['block_no'];
		$lng_lat = $_POST['lng_lat'];
		$data['lng']=strstr($lng_lat,',',true);
		$data['lat']=ltrim(strstr($lng_lat,','), ",");
		$id = $_POST['id'];
		$result=M('info')->where("id=$id")->save($data);
		
		
		$redis->hset("dwz_info:$id",'block_no',$data['block_no']);
		$redis->hset("dwz_info:$id",'id',$id);
		$redis->hset("dwz_info:$id",'lat',$data['lat']);
		$redis->hset("dwz_info:$id",'lng',$data['lng']);
		$redis->hset("dwz_info:$id",'level',0);
		$redis->hset("dwz_info:$id",'la',$data['la']);
		$redis->hset("dwz_info:$id",'lb',$data['lb']);
		$redis->hset("dwz_info:$id",'lc',$data['lc']);
		$redis->hset("dwz_info:$id",'status',$data['status']);
		$redis->hset("dwz_info:$id",'storage_num',$data['storage_num']);
		$redis->hset("dwz_info:$id",'title',$data['title']);
		$redis->hset("dwz_info:$id",'usable_num',$data['usable_num']);
		
		$redis->set('lnglatid:'.$data['lng'].'|'.$data['lat'],$id);
		
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
		$redis = new \Redis();
		$redis->connect('127.0.0.1', 6379);
		
		$id=$_GET["id"];
		$result=M('info')->where("id=$id")->delete();
		$redis->del("dwz_info:$id");
		
		if($result){
			$this->success('删除成功',U('Admin/Bparking/index'));
		}else{
			$this->error('删除失败');
		}
	}
	public function infobikesexist(){
		$id = $_REQUEST['id'];
		
		$this->assign('id',$id);
		//根据行政级别，做相应的过滤
		$province = $_SESSION['auth']['province'];
		$city = $_SESSION['auth']['city'];
		$area = $_SESSION['auth']['area'];
		$msql='';
		$mres=array();
		if($_SESSION['auth']['class']=='省级'){
			$msql = "select * from dwz_info where id='$id' and province='$province'";
			$mres = M()->query($msql);
		}
		if($_SESSION['auth']['class']=='市级'){
			$msql = "select * from dwz_info where id='$id' and province='$province' and city='$city'";
			$mres = M()->query($msql);
		}
		if($_SESSION['auth']['class']=='区级'){
			$msql = "select * from dwz_info where id='$id' and province='$province' and city='$city' and area = '$area'";
			$mres = M()->query($msql);
		}
		
		
		$uid = $_SESSION['auth']['id'];
		if(!count($mres) and $uid !=1){
			$this->error("无权限");
		}
		
		$interval = $_REQUEST['interval'];
		$this->assign('id',$id);
		$this->assign('interval',$interval);
		if($interval == 0){
			$msg = '不刷新';
			$this->assign('msg',$msg);
		}else{
			$msg = '间隔'.$interval.'刷新';
			$this->assign('msg',$msg);
		}
		
		$redis = new \Redis();
		$redis->connect('116.62.171.54', 8085);
		//从redis获取到数据 ，然后使用图表形式展现
		
		$exist_list = $redis->smembers("infobikesexist:$id");
		
		$this->assign('exist_list',$exist_list);
		
		$arr =array();
		foreach($exist_list as $k=>$v){
			$mac = str_replace(':','-',$v);
			$exist2 = $this->getbikes_exist($id,$mac);
			$sm = $redis->smembers("infobike:$id:$mac");
			$arr_exist[$k]['name']=$redis->get('bikes:'.$mac);
			$arr_exist[$k]['mac']=$v;
			$arr_exist[$k]['lasttime']=date("Y-m-d H:i:s",max($sm));
			$arr_exist[$k]['num']=$exist2["hits"]["total"];//采集到的次数
		}
		
		$bike_company=M("bike_company")->select();
		foreach($bike_company as $k=>$v){
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
		
		$arr1 = $bnames = array_unique($bike_names);
		$bn = array_count_values($bike_names);
		$len = sizeof($bn);
		$i=0;
		foreach($bn as $k=>$v){
			$arr2[$i]['name']=$k.'('.$v.')';
			$arr2[$i]['value']=$v;
			$i++;
		}
		$this->assign('exist',sizeof($arr_exist));
		$str1 = json_encode($arr1);
		$str2 = json_encode($arr2);
		$this->assign('length',$len);
		$this->assign('str1',$str1);
		//var_dump($str2);
		$this->assign('str2',$str2);
		
		$this->assign('arr_exist',$arr_exist);
		
		$this->display();
	}
	public function infobikesexist1(){
		exit;
		$id = $_REQUEST['id'];
		
		//根据行政级别，做相应的过滤
		$province = $_SESSION['auth']['province'];
		$city = $_SESSION['auth']['city'];
		$area = $_SESSION['auth']['area'];
		$msql='';
		$mres=array();
		if($_SESSION['auth']['class']=='省级'){
			$msql = "select * from dwz_info where id='$id' and province='$province'";
			$mres = M()->query($msql);
		}
		if($_SESSION['auth']['class']=='市级'){
			$msql = "select * from dwz_info where id='$id' and province='$province' and city='$city'";
			$mres = M()->query($msql);
		}
		if($_SESSION['auth']['class']=='区级'){
			$msql = "select * from dwz_info where id='$id' and province='$province' and city='$city' and area = '$area'";
			$mres = M()->query($msql);
		}
		
		
		$uid = $_SESSION['auth']['id'];
		if(!count($mres) and $uid !=1){
			$this->error("无权限");
		}
		
		$interval = $_REQUEST['interval'];
		$this->assign('id',$id);
		$this->assign('interval',$interval);
		if($interval == 0){
			$msg = '不刷新';
			$this->assign('msg',$msg);
		}else{
			$msg = '间隔'.$interval.'刷新';
			$this->assign('msg',$msg);
		}
		
		$res = $this->get_realtime_last($id);
		$str_bikes = $res['hits']['hits'][0]['_source']['bikes'];
		$bikes = json_decode($str_bikes);
		var_dump(sizeof($bikes));
		$bike_names=array();
		$arr1=array();
		$arr2=array();
		foreach($bikes as $k=>$v){
			//根据mac获取那个公司的
			//var_dump($v->mac);
			$company = $this->get_company_by_mac($v->mac);
			//var_dump($company);
			if($company == '摩拜'){
				$bike_names[]='摩拜';
			}
			if($company == 'ofo'){
				$bike_names[]='ofo';
			}
			if($company == '其他'){
				$bike_names[]='其他';
			}
			if($company == 'NULL'){
				$bike_names[]='其他';
			}
			if($company == '酷骑'){
				$bike_names[]='酷骑';
			}
			if($company == '小明单车'){
				$bike_names[]='小明单车';
			}
			if($company == 'HelloBike'){
				$bike_names[]='HelloBike';
			}
		}
		//var_dump($bike_names);
		$arr1 = $bnames = array_unique($bike_names);
		$bn = array_count_values($bike_names);
		$len = sizeof($bn);
		$i=0;
		foreach($bn as $k=>$v){
			$arr2[$i]['name']=$k.'('.$v.')';
			$arr2[$i]['value']=$v;
			$i++;
		}
		//var_dump($bnames);
		//var_dump($bn);
		//var_dump($len);
		
		//分类统计图
		/*foreach($exist_list as $k=>$v){
			//对名称的一个计数
			//车名称
			$arr1[]=array();
			//车名称 数量
			$arr2[]['name']=array();
			$arr2[]['value']=array();
		}*/
		$str1 = json_encode($arr1);
		$str2 = json_encode($arr2);
		$this->assign('length',$len);
		$this->assign('str1',$str1);
		//var_dump($str2);
		$this->assign('str2',$str2);
		
		$this->display();
	}
	private function get_realtime_last($id){
		$lpath =  THINK_PATH.'Library/Vendor/vendor/autoload.php';
		require $lpath;
		//$hosts = [
			//	'dododo.shop:9200',         // IP + Port
		//];
		$hosts = [
		'116.62.171.54:8081',         // IP + Port
		];
		$client = \Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build();
		//获取es最后更新的时间,在更新的时候使用

		$json = '{
				  "version": true,
				  "size": 1,
				  "sort": [
					{
					  "timestamp": {
						"order": "desc",
						"unmapped_type": "boolean"
					  }
					}
				  ],
				  "query": {
					"bool": {
					  "must": [
						{
						  "query_string": {
							"query": "dwz_info_id:'.$id.'",
							"analyze_wildcard": true
						  }
						},
						{
						  "match_phrase": {
							"_type": {
							  "query": "dbs_realtime"
							}
						  }
						}
					  ],
					  "must_not": []
					}
				  }
				}';
		$params = [
				'index' => 'bike_index_v6',
				'type' => 'dbs_realtime',
				'body' => $json
		];

		$results = $client->search($params);
		//$ts = $results['hits']['hits'][0]['_source']['ts'];
		//var_dump($results);
		//var_dump($ts);
		return $results;
	}
	
	private function get_company_by_mac($mac){
		$lpath =  THINK_PATH.'Library/Vendor/vendor/autoload.php';
		require $lpath;
		//$hosts = [
			//	'dododo.shop:9200',         // IP + Port
		//];
		$hosts = [
		'116.62.171.54:8081',         // IP + Port
		];
		$client = \Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build();
		//获取es最后更新的时间,在更新的时候使用

		$json = '{
				  "version": true,
				  "size": 1,
				  "sort": [
					{
					  "timestamp": {
						"order": "desc",
						"unmapped_type": "boolean"
					  }
					}
				  ],
				  "query": {
					"bool": {
					  "must": [
						{
						  "query_string": {
							"query": "mac:'.$mac.'",
							"analyze_wildcard": true
						  }
						},
						{
						  "match_phrase": {
							"_type": {
							  "query": "dbs_realtime_last"
							}
						  }
						}
					  ],
					  "must_not": []
					}
				  }
				}';
		$params = [
				'index' => 'bike_index_v6',
				'type' => 'dbs_realtime_last',
				'body' => $json
		];

		$results = $client->search($params);
		$company = $results['hits']['hits'][0]['_source']['company'];
		//var_dump($results);
		//var_dump($ts);
		return $company;
	}
	
	

	public function onebike_liudong_new($iid=0,$mac=0,$start=0,$st=0,$judge=0){
		$redis = new \Redis();
		$redis->connect('116.62.171.54', 8085);

		$iid = 5820;
		$mac = 'F11171A635D7';
		//$jiange = 60;//间隔
		$start = "2017-09-15 00:00:00";//开始时间
		$st = 1440;//持续时间 分钟
		$judge = 120;//秒 允许的时间间隔


		//时刻转时间
		$sj = $redis->smembers("infobike:$iid:$mac");//车在车位上的时间集合
		//var_dump($sj);
		//先根据间隔判断是否连续
		foreach($sj as $k=>$v){
			if(abs($sj[$k+1] - $sj[$k])<=$judge){
				$status[$k]=1;
			}else{
				$status[$k]=0;
			}
		}

		//var_dump($status);
		$lianxu=array();
		foreach($status as $k=>$v){
			$count=0;
			for($i=$k;$i<sizeof($status);$i++){
				//当出现下一个不连续的值就退出，进行下一次
				if($status[$i]==1){
					$count++;
					$lianxu[$k]=$count;
				}else{
					$lianxu[$k]=$count;
					break;
				}
			}
		}
		var_dump(max($lianxu));
		$tjs = 0;
		$tje = 0;
		foreach($lianxu as $k=>$v){
			if($v == max($lianxu)){
				$tjs = $k;
				$tje = $k+max($lianxu);
			}
		}
		$ltime = $sj[$tje] - $sj[$tjs];
		//var_dump($ltime);
		exit;

	}

	public function infobikesall(){
		$id = $_REQUEST['id'];
		$this->assign('id',$id);
		
		
		//根据行政级别，做相应的过滤
		$province = $_SESSION['auth']['province'];
		$city = $_SESSION['auth']['city'];
		$area = $_SESSION['auth']['area'];
		$msql='';
		$mres=array();
		if($_SESSION['auth']['class']=='省级'){
			$msql = "select * from dwz_info where id='$id' and province='$province'";
			$mres = M()->query($msql);
		}
		if($_SESSION['auth']['class']=='市级'){
			$msql = "select * from dwz_info where id='$id' and province='$province' and city='$city'";
			$mres = M()->query($msql);
		}
		if($_SESSION['auth']['class']=='区级'){
			$msql = "select * from dwz_info where id='$id' and province='$province' and city='$city' and area = '$area'";
			$mres = M()->query($msql);
		}
		
		
		$uid = $_SESSION['auth']['id'];
		if(!count($mres) and $uid !=1){
			$this->error("无权限");
		}
		
		
		$redis = new \Redis();
		$redis->connect('116.62.171.54', 8085);
		//从redis获取到数据 ，然后使用图表形式展现
		$all = $redis->scard("infobikes:$id");
		$all_list = $redis->smembers("infobikes:$id");
		//var_dump($all_list);
		//$exist = $redis->scard("infobikesexist:$id");
		//$exist_list = $redis->smembers("infobikesexist:$id");
		//var_dump($exist_list);
		//var_dump($numbers);
		
		
		$this->assign('all',$all);
		$this->assign('all_list',$all_list);
		//$this->assign('exist',$exist);
		//$this->assign('exist_list',$exist_list);
		
		$arr =array();
		foreach($all_list as $k=>$v){
			$mac = str_replace(':','-',$v);
			//var_dump("infobike:$id:$mac");
			$exist = $redis->scard("infobike:$id:$mac");
			//var_dump($exist);
			$arr_all[$k]['name']=$redis->get('bikes:'.$mac);
			$arr_all[$k]['mac']=$v;
			$arr_all[$k]['lasttime']=date("Y-m-d H:i:s",max($sm));
			$arr_all[$k]['num']=$exist;
		}
		
		$bike_company=M("bike_company")->select();
		foreach($bike_company as $k=>$v){
			//var_dump($v);
			$arr[$v['title']]=explode('|',$v['keyword']);
		}
		//var_dump($arr);
		foreach($arr_all as $k=>$v){
			$flag = 'no';
			$name = $v['name'];
			foreach($arr as $kk=>$vv){
				foreach($vv as $kkk=>$vvv){
					if(strpos($name,$vvv)===0){
						$bike_names[]=$kk;
						$flag = 'yes';			
					}
				}
			}
			
			//其他归类到ofo
			if($flag=='no' and ($name == '' or $name == 'NULL')){
				//$bike_names[]='ofo';
				//$flag = 'yes';
			}
			//其他没有归类到ofo的
			if($flag=='no'){
				//$bike_names[]='其他';
			}
		}
		
		$arr1 = $bnames = array_unique($bike_names);
		$bn = array_count_values($bike_names);
		$len = sizeof($bn);
		$i=0;
		
		$all = 0;
		foreach($bn as $k=>$v){
			$arr2[$i]['name']=$k.'('.$v.')';
			$arr2[$i]['value']=$v;
			$i++;
			$all+=$v;
		}
		
		$this->assign('all',$all);
		
		$str1 = json_encode($arr1);
		$str2 = json_encode($arr2);
		$this->assign('length',$len);
		$this->assign('str1',$str1);
		$this->assign('str2',$str2);
		
		//$this->assign('arr_all',$arr_all);
		
		//通过mac地址获取到对应的bike name
		$arr = array();
		foreach($all_list as $k => $v){
			$map['mac']=array('eq',$v);
			$arr[] = M("bike")->where($map)->find();
		}
		//var_dump($arr);
		
		$this->display();
	}
	
	
	public function infobikesall1(){
		$dwz_info_id = $_REQUEST['id'];
		$this->assign('id',$dwz_info_id);
		$res_es = $this->getbikes_d($dwz_info_id);
		$buckets = $res_es["aggregations"][2]["buckets"];
		$sum = 0;
		foreach($buckets as $k=>$v){
			$arr_all[$k]["name"] = $v["key"];
			$arr_all[$k]["value"] = $v[1]["value"];
			$sum+=$v[1]["value"];
//			var_dump($v["key"]);
//			var_dump($v[1]["value"]);

		}
		$this->assign("arr_all",$arr_all);
		$this->assign("sum",$sum);
//		var_dump($arr_all);
//		var_dump($sum);
		$i=0;
		foreach($arr_all as $k=>$v){
			$arr2[$i]['name']=$v["name"].'('.$v["value"].')';
			$arr2[$i]['value']=$v["value"];
			$i++;
		}
		foreach($arr_all as $k=>$v){
			if($v["name"]== "其他"){
				$arr_color[] = 'rgba(193,35,43,1)';
			}else if($v["name"] == "小明单车"){
				$data["title"] = "小鸣单车";
				$arr_color[] = M('bike_company')->where($data)->getField('color');
			}else{
				$data["title"] = $v["name"];
				$arr_color[] = M('bike_company')->where($data)->getField('color');

			}
		}
//		var_dump($arr_color);
		$str_color = json_encode($arr_color);
		$this->assign('str_color',$str_color);
		//var_dump($arr2);
		$str1 = json_encode($arr_all);
		$str2 = json_encode($arr2);
		$this->assign('str1',$str1);
		$this->assign('str2',$str2);
		$this->display();
	}
	
	public function infobikesall2(){
		$id = $_REQUEST['id'];
		$this->assign('id',$id);
		$redis = new \Redis();
		$redis->connect('116.62.171.54', 8085);
		//从redis获取到数据 ，然后使用图表形式展现
		$all = $redis->scard("infobikes:$id");
		$all_list = $redis->smembers("infobikes:$id");
		$arr =array();
		foreach($all_list as $k=>$v){
			$mac = str_replace(':','-',$v);
			$sm = $redis->smembers("infobike:$id:$mac");
			$exist = $redis->scard("infobike:$id:$mac");
			$arr_all[$k]['name']=$redis->get('bikes:'.$mac);
			$arr_all[$k]['mac']=$v;
			$arr_all[$k]['lasttime']=date("Y-m-d H:i:s",max($sm));
			$arr_all[$k]['num']=$exist;

		}
		//分页
		$count = count($arr_all);
		$Page = new \Think\Page($count,30);//每页显示条数
		$view = array_slice($arr_all, $Page->firstRow,$Page->listRows);//分组函数
		$show = $Page->show();//显示分页
		$this->assign("show",$show);
		$this->assign('view',$view);
		$this->display();
	}

	
	public function infobikedetail(){
		$iid = $_REQUEST['id'];
		$mac = str_replace(':','-',$_REQUEST['mac']);
		$jiange = isset($_REQUEST['jiange'])?$_REQUEST['jiange']:60;
		//默认开始时间是现在
		$s = isset($_REQUEST['start'])?$_REQUEST['start']:'';
		//默认时间是一天24小时
		$st = isset($_REQUEST['still'])?$_REQUEST['still']:'';
		
		//开始时间为0，那么就默认0点
		if($s==''){
			$start = strtotime(date("Y/m/d"));
		}else{
			$start = strtotime($s);
		}
		//持续时间为0，那么就默认一天
		if($st==''){
			$end = strtotime(date("Y-m-d",strtotime("+1 day")));
			$st = 24*60;	
		}else{
			$end = $start + $st * 60;
		}
		
		$showstart = date("Y-m-d H:i:s", $start);
		
		$this->assign('showstart',$showstart);
		$this->assign('st',$st);
		
		$this->assign('id',$iid);
		$this->assign('mac',$mac);
		$this->assign('jiange',$jiange);
		
		$this->assign('iid',$iid);
		$this->assign('mac',$_REQUEST['mac']);
		
		$redis = new \Redis();
		$redis->connect('116.62.171.54', 8085);
		//哪些时间存在过，可以做筛选
		$time_list = $redis->smembers("infobike:$iid:$mac");
		
		/*$time_list2 = $this->getbikes_detail($iid,$mac);
		$time_list3 = $time_list2["hits"]["hits"];
		foreach($time_list3 as $k=>$v){
			$time_list[] = $v["_source"]["ts"];
		}*/
		
		//var_dump($time_list);
		$you =array();
		foreach($time_list as $k=>$v){
			$you[$k][]=date('Y-m-d H:i:s', $v);
			$you[$k][]=1;
			$you[$k][]=1;
		}
		//var_dump($time_list);
		//没采集到的点如何填充，间隔是10分钟 ，那么就将0点到0点分割开
		//每个间隔中如果没有，那就补充一个 0，0 
		//echo "今天是 " . date("Y/m/d");
		
		//默认时间
		//$start = strtotime(date("Y/m/d"));
		//$end = strtotime(date("Y-m-d",strtotime("+1 day")));
		$cur=$start;
		$i = 0;	
		$narr=array();
		//echo $cur;
		//间隔为1分钟
		//$jiange = 600;
		while($cur <= $end){
			/*foreach($time_list as $k=>$v){
				if($cur <= $v and $v<=$cur+$jiange){
					echo $v;
					var_dump(date('Y-m-d H:i:s', $v));
					$narr[$i][]=date('Y-m-d H:i:s', $cur);
					$narr[$i][]=10;
					$narr[$i][]=10;
					$i++;
					$cur+=$jiange;
					//$i++;
					
				}
				
				else{
					
					$narr[$i][]=date('Y-m-d H:i:s', $cur);
					$narr[$i][]=0;
					$narr[$i][]=0;
					$i++;
					$cur+=$jiange;
				}
			}*/
			/*foreach($time_list as $k=>$v){
				if($cur <= $v and $v<=$cur+$jiange){
					echo $v;
					var_dump(date('Y-m-d H:i:s', $v));
					$narr[$i][]=date('Y-m-d H:i:s', $cur);
					$narr[$i][]=1;
					$narr[$i][]=1;
					$i++;
					$cur+=$jiange;
					//$i++;
				}
				
			}*/
			
			//$narr[$i][]=date('Y-m-d H:i:s', $cur);
			$narr[$i][]=$cur;
			$narr[$i][]=0;
			$narr[$i][]=0;
			$i++;
			$cur+=$jiange;
			
		}
		//每个点再去看下是否有停的，需要设置为1
		//加入对时间列表的过滤，可以减少很多次数
		$today_time_list=array();
		foreach($time_list as $k=>$v){
			if($v>=$start and $v<=$end){
				$today_time_list[]=$v;
			}
		}
		//var_dump(sizeof($today_time_list));
		$this->assign('ttimes',sizeof($today_time_list));
		
		foreach($narr as $k=>$v){
			foreach($today_time_list as $kk=>$vv){
				if($v[0] <= $vv and $vv<=$v[0]+$jiange){
					//echo $v[0];
					//var_dump(date('Y-m-d H:i:s', $v[0]));
					$narr[$k][0]=date('Y-m-d H:i:s', $v[0]);
					$narr[$k][1]=1;
					$narr[$k][2]=1;
				}
			}
			$narr[$k][0]=date('Y-m-d H:i:s', $v[0]);
			
		}
		
		$str1 = json_encode($narr);
		//$this->assign('time_list',$time_list);
		$this->assign('str1',$str1);
		$this->assign('times',sizeof($time_list));
		$this->display();
	}
	
	
	
	public function trend(){
		exit;
		$redis = new \Redis();
		$redis->connect('116.62.171.54', 8085);
		if(IS_POST){
		
			$this->display();
		}else{
			$iid = $_REQUEST['iid'];
			//根据iid获取不同时刻的车辆数
			//根据iid获取所有
			$redis->keys("realtime:$id:*");
			
			$this->display();	
		}
		
	}
	public function getx(){
		$redis = new \Redis();
		$redis->connect('127.0.0.1', 6379);
		$dwz_info = $redis->keys("dwz_info:*");
		foreach($dwz_info as $k=>$v){
			
			var_dump($v."<br />");
			
		}
	}
	
	
	public function clear(){
		echo "clear";
		$id = $_REQUEST['id'];
		if($id=='') exit(0);
		$redis = new \Redis();
		$redis->connect('116.62.171.54', 8085);
		
		
		//$redis->del("gateway:0CEFAFCFEEC1");
		
		
		//删除集合
		$infobike = $redis->keys("infobike:$id:*");
		foreach($infobike as $k=>$v){
			$redis->del($v);
		}
		//删除集合
		$redis->del("infobikesexist:$id");
		
		//删除集合
		$realtime = $redis->keys("realtime:$id:*");
		foreach($realtime as $k=>$v){
			$redis->del($v);
		}
		
		$redis->hset("dwz_info:$id",'storage_num',0);
		$redis->hset("dwz_info:$id",'level',0);
		
		$redis->set("realtime_id_$id",0);
		$url = U("Admin/Bparking/index");
		header("location:$url");
	}
	public function realtime(){
		//所有公司
		$str = '{"mb":"\u6469\u62dc","mo":"\u6469\u62dc","HB":"HelloBike","ofo":"ofo","NokeLock":"ofo","CoolQi":"\u9177\u9a91","BL":"\u9177\u9a91","XIAOMING":"\u5c0f\u660e\u5355\u8f66"}';
		$arr = json_decode($str);
		
		
		
		//根据id 获取到realtime的最后一条记录
		$id = $_REQUEST['id'];
		$res = $this->getbikes_realtime($id);
		$exist = $res['hits']['hits'][0]['_source']['storage_num'];
		$bikes = $res['hits']['hits'][0]['_source']['bikes'];
		$all = json_decode($bikes);
		//var_dump($all);
		//没有名称的先获取名称
		
		
		//var_dump($bike_names);
		$arr1 = $bnames = array_unique($bike_names);
		$bn = array_count_values($bike_names);
		$len = sizeof($bn);
		$i=0;
		foreach($bn as $k=>$v){
			$arr2[$i]['name']=$k.'('.$v.')';
			$arr2[$i]['value']=$v;
			$i++;
		}
		//var_dump($arr1);
		//var_dump($arr2);
		$str1 = json_encode($arr1);
		$str2 = json_encode($arr2);
		$this->assign('str1',$str1);
		$this->assign('str2',$str2);
		
		$this->assign('exist',$exist);
		//根据公司名称过滤
		$this->display();
	}
	public function history(){
		$id = $_REQUEST['id'];
		$this->assign('id',$id);
		if(IS_POST){
			//$id = 5894;
			//默认开始时间是现在
			$s = isset($_REQUEST['start1'])?$_REQUEST['start1']:'';
			$e = isset($_REQUEST['start2'])?$_REQUEST['start2']:'';
			$this->assign('start',$s);
			$this->assign('end',$e);

			$s = strtotime($s).'000';
			$e = strtotime($e).'000';

			///var_dump($s);
			//var_dump($e);
			
			$nnarr = array();
			$res = $this->es_history($id,$s,$e);
			$hits = $res['hits']['hits'];
			//var_dump($res['hits']['hits']);
			foreach($hits as $k=>$v){
				$nnarr[$k][0]=date('Y-m-d H:i:s', $v['_source']['ts']);
				$nnarr[$k][1]=$v['_source']['storage_num'];
				$nnarr[$k][2]=$v['_source']['storage_num'];
			}

			$str1 = json_encode($nnarr);
			$this->assign('str1',$str1);
			$this->assign('times',sizeof($time_list));
			$this->display();
		}else{
			$this->display();
		}
	}
	
	public function cstatus(){
		
		//var_dump($_REQUEST);
		$redis = new \Redis();
		$redis->connect('127.0.0.1', 6379);
	
		$id = $_REQUEST['id'];
		if($_REQUEST['id']=='' || $_REQUEST['status']==''){
			exit(json_encode(array('error_code'=>1,'error_reason'=>'参数不正确')));
		}
		
		if($_REQUEST['status']==0){
			$status = 1;
			$text='隐藏中';
		}
		if($_REQUEST['status']==1){
			$status = 0;
			$text='显示中';
		}
		$data['status'] = $status;
		$id = $_POST['id'];
		$result=M('info')->where("id=$id")->save($data);
		$redis->hset("dwz_info:$id",'status',$status);
		$res = array('id'=>$id,'status'=>$status,'text'=>$text);
		exit(json_encode(array('error_code'=>0,'error_reason'=>'修改成功','res'=>$res)));
	}
	
	public function bstatus(){
		
		//var_dump($_REQUEST);
		$redis = new \Redis();
		$redis->connect('127.0.0.1', 6379);
	
		$id = $_REQUEST['id'];
		if($_REQUEST['id']=='' || $_REQUEST['status']==''){
			exit(json_encode(array('error_code'=>1,'error_reason'=>'参数不正确')));
		}
		
		if($_REQUEST['status']==0){
			$status = 1;
			$text='黑名单';
		}
		if($_REQUEST['status']==1){
			$status = 0;
			$text='正常';
		}
		$data['isblack'] = $status;
		$id = $_POST['id'];
		$result=M('info')->where("id=$id")->save($data);
		$redis->hset("dwz_info:$id",'isblack',$status);
		$res = array('id'=>$id,'status'=>$status,'text'=>$text);
		exit(json_encode(array('error_code'=>0,'error_reason'=>'修改成功','res'=>$res)));
	}
	
	//更新单个车位的数量
	public function update_info(){
		$id = $_REQUEST['id'];
		if($id == ''){
			$this->error("参数错误");
		}
		
		$type = 'dbs_realtime';
		$this->update_dbs_realtime($id,$type);
		
		$type = 'dbs_realtime_last';
		$this->update_dbs_realtime($id,$type);
		$type = 'dbs_realtime_first';
		$this->update_dbs_realtime($id,$type);
		
		$type = 'dbs_realtime_one_last';
		$this->update_dbs_realtime($id,$type);
		$type = 'dbs_realtime_one_first';
		$this->update_dbs_realtime($id,$type);
		
		$this->success('更新成功',U('Admin/Bparking/index'));
	}
	
	private function update_dbs_realtime($id,$type){
		//获取当天的时间
		//$start = strtotime(date('Y-m-d'));
		$start = 0;
		$end = time();
		$end = $end*1000;
		
		$map['id']=array("eq",$id);
		$info = M("info")->where($map)->find();
		//var_dump($info);
		$area = $info['area'];
		$lng = $info['lng'];
		$lat = $info['lat'];
		//var_dump($area);
		
		//根据车位修改后的状态更新
		$lpath =  THINK_PATH.'Library/Vendor/vendor/autoload.php';
		require $lpath;
		
		$hosts = [
		'116.62.171.54:8081',         // IP + Port
		];
		
		
		$client = \Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build();
		
		$json = '{
			  "version": true,
			  "size": 10000,
			  "sort": [
				{
				  "timestamp": {
					"order": "desc",
					"unmapped_type": "boolean"
				  }
				}
			  ],
			  "query": {
				"bool": {
				  "must": [
					{
					  "match_all": {}
					},
					{
					  "match_phrase": {
						"dwz_info_id": {
						  "query": "'.$id.'"
						}
					  }
					},
					{
					  "match_phrase": {
						"_type": {
						  "query": "'.$type.'"
						}
					  }
					},
					{
					  "range": {
						"timestamp": {
						  "gte": '.$start.',
						  "lte": '.$end.',
						  "format": "epoch_millis"
						}
					  }
					}
				  ],
				  "must_not": [
					{
					  "match_phrase": {
						"area": {
						  "query": "余杭区"
						}
					  }
					}
				  ]
				}
			  }
			}';

			echo $json;
			
			$params = [
				'index' => 'bike_index_v6',
				'type' => $type,
				'body' => $json
			];

			$results = $client->search($params);
			//var_dump($results);
			//exit;
			foreach($results['hits']['hits'] as $k=>$v){
				//更新dbs_realtime_last
				$id = $v['_id'];	
				
				$params = [
					'index' => 'bike_index_v6',
					'type' => $type,
					'id' => $id,
					'body' => [
						'doc' => [
							'area' => $area,
							"location"=>[
								"lat"=>$lat,
								"lon"=>$lng
							],
						]
					]
				];

				// Update doc at /my_index/my_type/my_id
				$response = $client->update($params);
				//var_dump($response);
				//var_dump($id);			
				//exit;
			}	
	}
	
	
	
	private function es_history($id,$s,$e){
		$lpath =  THINK_PATH.'Library/Vendor/vendor/autoload.php';
		require $lpath;
		//$hosts = [
		//'dododo.shop:9200',         // IP + Port
		//];
		
		$hosts = [
		'116.62.171.54:8081',         // IP + Port
		];
		
		
		$client = \Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build();
		
		$json = '{
			  "version": true,
			  "size": 500,
			  "sort": [
				{
				  "timestamp": {
					"order": "desc",
					"unmapped_type": "boolean"
				  }
				}
			  ],
			  "query": {
				"bool": {
				  "must": [
					{
					  "query_string": {
						"analyze_wildcard": true,
						"query": "'.$id.'"
					  }
					},
					{
					  "match_phrase": {
						"_type": {
						  "query": "dbs_realtime"
						}
					  }
					},
					{
					  "range": {
						"timestamp": {
						  "gte": '.$s.',
						  "lte": '.$e.',
						  "format": "epoch_millis"
						}
					  }
					}
				  ],
				  "must_not": []
				}
			  },
			  "_source": {
				"excludes": []
			  },
			  "aggs": {
				"2": {
				  "date_histogram": {
					"field": "timestamp",
					"interval": "30m",
					"time_zone": "Asia/Shanghai",
					"min_doc_count": 1
				  }
				}
			  },
			  "stored_fields": [
				"*"
			  ],
			  "script_fields": {},
			  "docvalue_fields": [
				"timestamp"
			  ]
			}';

			$params = [
				'index' => 'bike_index_v6',
				'type' => 'dbs_realtime',
				'body' => $json
			];

			$results = $client->search($params);
			//$ts = $results['hits']['hits'][0]['_source']['ts'];
			//var_dump($results);
			//var_dump($ts);
			return $results;
		
	}
	
	//车位实时记录
	private function getbikes_realtime($id){
		$lpath =  THINK_PATH.'Library/Vendor/vendor/autoload.php';
		require $lpath;
		//$hosts = [
		//'dododo.shop:9200',         // IP + Port
		//];
		
		$hosts = [
		'116.62.171.54:8081',         // IP + Port
		];
		
		
		$client = \Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build();
		//获取es最后更新的时间,在更新的时候使用
		
		/*$json = '{
				  "size": 0,
				  "query": {
					"bool": {
					  "must": [
						{
						  "match_all": {}
						},
						{
						  "match_phrase": {
							"province": {
							  "query": "'.$province.'"
							}
						  }
						},
						{
						  "match_phrase": {
							"city": {
							  "query": "'.$city.'"
							}
						  }
						},
						{
						  "match_phrase": {
							"area": {
							  "query": "'.$area.'"
							}
						  }
						},
						{
						  "range": {
							"timestamp": {
							  "gte": 0,
							  "lte": 9507697943118,
							  "format": "epoch_millis"
							}
						  }
						}
					  ],
					  "must_not": [
						{
						  "match_phrase": {
							"company": {
							  "query": "其他"
							}
						  }
						}
					  ]
					}
				  },
				  "_source": {
					"excludes": []
				  },
				  "aggs": {
					"3": {
					  "terms": {
						"field": "company",
						"size": 20,
						"order": {
						  "_count": "desc"
						}
					  }
					}
				  }
				}';

			$params = [
				'index' => 'bike_index_v2',
				'type' => 'dwz_bike_sub_realtime_first',
				'body' => $json
			];*/
			
			$json = '{
			  "size": 1,
			  "sort": [
				{
				  "timestamp": {
					"order": "desc",
					"unmapped_type": "boolean"
				  }
				}
			  ],
			  "query":{
				  "match_all":{}
			   }
			}';

			$params = [
				'index' => 'bike_index_v1',
				'type' => 'dwz_bike_sub_realtime',
				'body' => $json
			];

			$results = $client->search($params);
			//$ts = $results['hits']['hits'][0]['_source']['ts'];
			//var_dump($results);
			//var_dump($ts);
			return $results;
	}
	
	
	//车位历史总停放
	private function getbikes_history($id){
		$lpath =  THINK_PATH.'Library/Vendor/vendor/autoload.php';
		require $lpath;
		//$hosts = [
		//'dododo.shop:9200',         // IP + Port
		//];
		$hosts = [
		'116.62.171.54:8081',         // IP + Port
		];
		$client = \Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build();
		//获取es最后更新的时间,在更新的时候使用
		
		$json = '{
				  "size": 0,
				  "query": {
					"bool": {
					  "must": [
						{
						  "match_all": {}
						},
						{
						  "match_phrase": {
							"province": {
							  "query": "'.$province.'"
							}
						  }
						},
						{
						  "match_phrase": {
							"city": {
							  "query": "'.$city.'"
							}
						  }
						},
						{
						  "match_phrase": {
							"area": {
							  "query": "'.$area.'"
							}
						  }
						},
						{
						  "range": {
							"timestamp": {
							  "gte": 0,
							  "lte": 9507697943118,
							  "format": "epoch_millis"
							}
						  }
						}
					  ],
					  "must_not": [
						{
						  "match_phrase": {
							"company": {
							  "query": "其他"
							}
						  }
						}
					  ]
					}
				  },
				  "_source": {
					"excludes": []
				  },
				  "aggs": {
					"3": {
					  "terms": {
						"field": "company",
						"size": 20,
						"order": {
						  "_count": "desc"
						}
					  }
					}
				  }
				}';

			$params = [
				'index' => 'bike_index_v2',
				'type' => 'dwz_bike_sub_realtime_first',
				'body' => $json
			];

			$results = $client->search($params);
			//$ts = $results['hits']['hits'][0]['_source']['ts'];
			//var_dump($results);
			//var_dump($ts);
			return $results;
	}
	
	private function getbike($mac) {
		$lpath =  THINK_PATH.'Library/Vendor/vendor/autoload.php';
		require $lpath;
		//$hosts = [
		//'dododo.shop:9200',         // IP + Port
		//];
		
		$hosts = [
		'116.62.171.54:8081',         // IP + Port
		];
		$client = \Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build();
		//获取es最后更新的时间,在更新的时候使用
		
		$json = '{"query": {
					"bool": {
					  "must": [
						{
						  "query_string": {
							"analyze_wildcard": true,
							"query": "50F14A6A875E"
						  }
						}
					  ],
					  "must_not": []
					}
				  }
				}';

			$params = [
				'index' => 'bike_index_v2',
				'type' => 'dwz_bike_sub_realtime_first',
				'body' => $json
			];

			$results = $client->search($params);
			//$ts = $results['hits']['hits'][0]['_source']['ts'];
			//var_dump($results);
			//var_dump($ts);
			return $results;
	}
	
	private function startwith($str,$pattern) {
		if(strpos($str,$pattern) === 0)
			  return true;
		else
			  return false;
	}
	
	
	//根据车位编号获取数据
	private function getbikes_d($dwz_info_id){
		$lpath =  THINK_PATH.'Library/Vendor/vendor/autoload.php';
		require $lpath;
		//$hosts = [
	//			'dododo.shop:9200',         // IP + Port
		//];
		
		$hosts = [
		'116.62.171.54:8081',         // IP + Port
		];
		$client = \Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build();
		//获取es最后更新的时间,在更新的时候使用

			$json = '{
  "size": 0,
  "aggs": {
    "2": {
      "terms": {
        "field": "company",
        "size": 5,
        "order": {
          "1": "desc"
        }
      },
      "aggs": {
        "1": {
          "cardinality": {
            "field": "mac"
          }
        }
      }
    }
  },
  "version": true,
  "query": {
    "bool": {
      "must": [
        {
          "match_all": {}
        },
        {
          "match_phrase": {
            "_type": {
              "query": "dbs_realtime_last"
            }
          }
        },
        {
          "match_phrase": {
            "dwz_info_id": {
              "query": "'.$dwz_info_id.'"
            }
          }
        },
        {
          "range": {
            "timestamp": {
             "gte": 0,
				"lte": 9507697943118,
              "format": "epoch_millis"
            }
          }
        }
      ],
      "must_not": [
        {
          "match_phrase": {
            "company": {
              "query": "其他"
            }
          }
        }
      ]
    }
  },
  "_source": {
    "excludes": []
  },
  "highlight": {
    "pre_tags": [
      "@kibana-highlighted-field@"
    ],
    "post_tags": [
      "@/kibana-highlighted-field@"
    ],
    "fields": {
      "*": {
        "highlight_query": {
          "bool": {
            "must": [
              {
                "match_all": {}
              },
              {
                "match_phrase": {
                  "_type": {
                    "query": "dbs_realtime_last"
                  }
                }
              },
              {
                "match_phrase": {
                  "dwz_info_id": {
                    "query": "'.$dwz_info_id.'"
                  }
                }
              },
              {
                "range": {
                  "timestamp": {
                    "gte": 0,
						"lte": 9507697943118,
                    "format": "epoch_millis"
                  }
                }
              }
            ],
            "must_not": [
              {
                "match_phrase": {
                  "company": {
                    "query": "其他"
                  }
                }
              }
            ]
          }
        }
      }
    },
    "fragment_size": 2147483647
  }
}';
		$params = [
				'index' => 'bike_index_v6',
				'type' => 'dbs_realtime_last',
				'body' => $json
		];

		$results = $client->search($params);
		//$ts = $results['hits']['hits'][0]['_source']['ts'];
		//var_dump($results);
		//var_dump($ts);
		return $results;
	}
	
	
	//根据车位编号获取车辆详情数据
	private function getbikes_all3($dwz_info_id,$from,$size){
		$lpath =  THINK_PATH.'Library/Vendor/vendor/autoload.php';
		require $lpath;
		//$hosts = [
			//	'dododo.shop:9200',         // IP + Port
		//];
		$hosts = [
		'116.62.171.54:8081',         // IP + Port
		];
		$client = \Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build();
		//获取es最后更新的时间,在更新的时候使用

		$json = '{
		  "version": true,
		  "from":"'.$from.'",
		  "size": "'.$size.'",
		  "sort": [
			{
			  "timestamp": {
				"order": "desc",
				"unmapped_type": "boolean"
			  }
			}
		  ],
		  "query": {
			"bool": {
			  "must": [
				{
				  "match_all": {}
				},
				{
				  "match_phrase": {
					"_type": {
					  "query": "dbs_realtime_one_last"
					}
				  }
				},
				{
				  "match_phrase": {
					"dwz_info_id": {
					  "query": "'.$dwz_info_id.'"
					}
				  }
				},
				{
				  "range": {
					"timestamp": {
					  "gte": 0,
						"lte": 9507697943118,
					  "format": "epoch_millis"
					}
				  }
				}
			  ],
			  "must_not": [
				{
				  "match_phrase": {
					"company": {
					  "query": "其他"
					}
				  }
				}
			  ]
			}
		  },
		  "_source": {
			"excludes": []
		  },
		  "aggs": {
			"2": {
			  "date_histogram": {
				"field": "timestamp",
				"interval": "12h",
				"time_zone": "Asia/Shanghai",
				"min_doc_count": 1
			  }
			}
		  },
		  "stored_fields": [
			"*"
		  ],
		  "script_fields": {},
		  "docvalue_fields": [
			"timestamp"
		  ],
		  "highlight": {
			"pre_tags": [
			  "@kibana-highlighted-field@"
			],
			"post_tags": [
			  "@/kibana-highlighted-field@"
			],
			"fields": {
			  "*": {
				"highlight_query": {
				  "bool": {
					"must": [
					  {
						"match_all": {}
					  },
					  {
						"match_phrase": {
						  "_type": {
							"query": "dbs_realtime_one_last"
						  }
						}
					  },
					  {
						"match_phrase": {
						  "dwz_info_id": {
							"query": "'.$dwz_info_id.'"
						  }
						}
					  },
					  {
						"range": {
						  "timestamp": {
							"gte": 0,
								"lte": 9507697943118,
							"format": "epoch_millis"
						  }
						}
					  }
					],
					"must_not": [
					  {
						"match_phrase": {
						  "company": {
							"query": "其他"
						  }
						}
					  }
					]
				  }
				}
			  }
			},
			"fragment_size": 2147483647
		  }
		}';
		$params = [
				'index' => 'bike_index_v6',
				'type' => 'dbs_realtime_one_last',
				'body' => $json
		];

		$results = $client->search($params);
		//$ts = $results['hits']['hits'][0]['_source']['ts'];
		//var_dump($results);
		//var_dump($ts);
		return $results;
	}
	public function infobikesall3(){
		$dwz_info_id = $_REQUEST["id"];
		$from=0;
		$size=20;
		$res_es = $this->getbikes_all3($dwz_info_id,$from,$size);
		$count=$res_es['hits']['total'];//总条数
		$page = new \Component\Page($count, $size); //这里的分页类和Home模块的目录一致，可自行修改
		if(isset($_REQUEST["page"])){
			$page2=$_REQUEST["page"];
			$from=($page2-1)*$size;
			$res_es = $this->getbikes_all3($dwz_info_id,$from,$size);
		}
		$buckets = $res_es["hits"]["hits"];
		$sum = 0;
		foreach($buckets as $k=>$v){
			$arr_all[$k]["name"] = $v["_source"]["name"];
			$arr_all[$k]["mac"] = $v["_source"]["mac"];
			$arr_all[$k]["rssi"] = $v["_source"]["rssi"];
			$arr_all[$k]["company"] = $v["_source"]["company"];
			$arr_all[$k]["province"] = $v["_source"]["province"];
			$arr_all[$k]["city"] = $v["_source"]["city"];
			$arr_all[$k]["area"] = $v["_source"]["area"];
		}
		$pagelist = $page->fpage();
		$this->assign('show', $pagelist);
		$this->assign("arr_all",$arr_all);
		$this->assign("sum",$sum);
		$this->display();
	}
	
	public function fssj(){
		$dwz_info_id=$_REQUEST["id"];
		
				$href="http://116.62.171.54:8082/app/kibana#/discover?_g=(refreshInterval:('$$hashKey':'object:808',display:'30%20seconds',pause:!f,section:1,value:30000),time:(from:now-15m,mode:quick,to:now))&_a=(columns:!(name,mac,storage_num,area),filters:!(('$state':(store:appState),meta:(alias:!n,disabled:!f,index:AV8tUT6CT459h6-9WR96,key:dwz_info_id,negate:!f,type:phrase,value:'$dwz_info_id'),query:(match:(dwz_info_id:(query:'$dwz_info_id',type:phrase)))),('$state':(store:appState),meta:(alias:!n,disabled:!f,index:AV8tUT6CT459h6-9WR96,key:_type,negate:!f,type:phrase,value:dwz_bike_sub_realtime_last),query:(match:(_type:(query:dwz_bike_sub_realtime_last,type:phrase))))),index:AV8tUT6CT459h6-9WR96,interval:auto,query:(match_all:()),sort:!(timestamp,desc))";
			$uid = $_SESSION["auth"]["id"];
			if($uid==1){
				$this->assign("href",$href);
				$this->assign('id',$dwz_info_id);
			}
			$this->display();
	}
	public function isstop(){
		$cont=$_REQUEST["cont"];
		$all_did = M('info')->select();//查询车位表
		//获得车位编号对应查询到数据的最后时间的数组
		foreach($all_did as $k=>$v){
			$last_time[$k]["dwz_info_id"] = $v["id"];
			$getall = $this->get_ts($v["id"]);
			$last_time[$k]["time"] = $getall["hits"]["hits"][0]["_source"]["ts"];
		}
		//当前时间减去最后时间是否大于10分钟，大于10返回1
		$now_time = time();
		foreach($last_time as $k=>$v){
			$last_time2[$k]["dwz_info_id"] = $v["dwz_info_id"];
			if(($now_time-$v["time"])/60>10){
				$last_time2[$k]["sta"] = 1;//不正常
				$last_time2[$k]["icon"] = "color:red";
			}else{
				$last_time2[$k]["sta"] = 0;//正常
				$last_time2[$k]["icon"] = 'color:green';
			}
		}
//		var_dump($last_time2);
		echo  json_encode($last_time2);
	}
	
	private function get_ts($dwz_info_id){
		$lpath =  THINK_PATH.'Library/Vendor/vendor/autoload.php';
		require $lpath;
		$hosts = [
				'http://116.62.171.54:8081',         // IP + Port
		];
		$client = \Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build();
		//获取es最后更新的时间,在更新的时候使用

		$json = '{
			  "version": true,
			  "size": 1,
			  "sort": [
				{
				  "ts": {
					"order": "desc",
					"unmapped_type": "boolean"
				  }
				}
			  ],
			  "query": {
				"bool": {
				  "must": [
					{
					  "query_string": {
						"query": "dwz_info_id:'.$dwz_info_id.'",
						"analyze_wildcard": true
					  }
					}
				  ],
				  "must_not": []
				}
			  }
			}';
		$params = [
				'index' => 'bike_index_v6',
				'type' => 'dbs_realtime',
				'body' => $json
		];

		$results = $client->search($params);
		//$ts = $results['hits']['hits'][0]['_source']['ts'];
		//var_dump($results);
		//var_dump($ts);
		return $results;
	}
	
	//根据车位编号和mac地址获取采集到的次数
	private function getbikes_exist($dwz_info_id,$mac){
		$lpath =  THINK_PATH.'Library/Vendor/vendor/autoload.php';
		require $lpath;
		$hosts = [
				'http://116.62.171.54:8081',         // IP + Port
		];
		$client = \Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build();
		//获取es某mac地址在某车位上被采集到的次数
		$json = '{
		  "version": true,
		  "size": 1,
		  "query": {
			"bool": {
			  "must": [
				{
				  "query_string": {
					"analyze_wildcard": true,
					"query": "'.$mac.'"
				  }
				},
				{
				  "match_phrase": {
					"_index": {
					  "query": "bike_index_v6"
					}
				  }
				},
				{
				  "match_phrase": {
					"_type": {
					  "query": "dbs_realtime"
					}
				  }
				},
				{
				  "match_phrase": {
					"dwz_info_id": {
					  "query": "'.$dwz_info_id.'"
					}
				  }
				}
			  ]
			}
		  }
		}';
		$params = [
				'index' => 'bike_index_v6',
				'type' => 'dbs_realtime',
				'body' => $json
		];

		$results = $client->search($params);
		return $results;
	}
	
	
	public function infobikesexist_es1(){
		$id = $_REQUEST['id'];
		$this->assign('id',$id);
		$redis = new \Redis();
		$redis->connect('116.62.171.54', 8085);
		//从redis获取到数据 ，然后使用图表形式展现
		$exist = $redis->scard("infobikesexist:$id");
		$arr_exist = $this->getbikes_exist_es2($id);
		//var_dump($arr_exist["hits"]["hits"][0]["_source"]["timestamp"]);
		$exist_list2 = $arr_exist["hits"]["hits"][0]["_source"]["bikes"];
		$exist_list3 = json_decode($exist_list2,true);
		
		foreach($exist_list3 as $k=>$v){
			if($v['name']=='') $exist_list3[$k]['name']=$redis->get('bikes:'.$v['mac']);
			$exist_list3[$k]['lasttime']=date("Y-m-d H:i:s",$arr_exist["hits"]["hits"][0]["_source"]["timestamp"]/1000);
			$exist_list3[$k]['num']=$redis->scard("infobike:$id:".$v['mac']);
		}
		//var_dump($exist_list3);
		//$this->assign('arr_exist4',$exist_list3);
		//$this->assign('exist',$exist);
		$arr =array();
		$bike_company=M("bike_company")->select();
		foreach($bike_company as $k=>$v){
			$arr[$v['title']]=explode('|',$v['keyword']);
		}
		$exist_list4=array();
		foreach($exist_list3 as $k=>$v){
			$flag = 'no';
			$name = $v['name'];
			foreach($arr as $kk=>$vv){
				foreach($vv as $kkk=>$vvv){
					if($this->startwith($name,$vvv)){
						$bike_names[]=$kk;
						$flag = 'yes';
						$exist_list4[] = $v;
					}
				}
			}
			/*if($flag=='no'){
				$bike_names[]='其他';
			}*/
		}

		$this->assign('arr_exist4',$exist_list4);
		$this->assign('exist',sizeof($exist_list4));
		$arr1 = $bnames = array_unique($bike_names);
		foreach($arr1 as $k=>$v){
			if($v == "其他"){
				$arr_color[] = 'rgba(193,35,43,1)';
			}else{
				$arr_color[] = M('bike_company')->where("title='$v'")->getField('color');
			}
		}
		$bn = array_count_values($bike_names);
		$i=0;
		foreach($bn as $k=>$v){
			$arr2[$i]['name']=$k.'('.$v.')';
			$arr2[$i]['value']=$v;
			$i++;
		}
		$str_color = json_encode($arr_color);
		$this->assign('str_color',$str_color);
		$str1 = json_encode($arr1);
		$str2 = json_encode($arr2);
		$this->assign('str1',$str1);
		$this->assign('str2',$str2);
		$this->assign('arr_exist',$arr_exist);
		$this->display();
	}
	//根据车位编号和mac地址获取采集到的次数
	private function getbikes_exist_es2($dwz_info_id){
		$lpath =  THINK_PATH.'Library/Vendor/vendor/autoload.php';
		require $lpath;
		$hosts = [
				'http://116.62.171.54:8081',         // IP + Port
		];
		$client = \Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build();
		//获取es某mac地址在某车位上被采集到的次数
		$json = '{
  "version": true,
  "query": {
    "bool": {
      "must": [
        {
          "match_all": {}
        },
        {
          "match_phrase": {
            "_type": {
              "query": "dbs_realtime"
            }
          }
        },
        {
          "match_phrase": {
            "_index": {
              "query": "bike_index_v6"
            }
          }
        },
        {
          "match_phrase": {
            "dwz_info_id": {
              "query": "'.$dwz_info_id.'"
            }
          }
        },
        {
          "range": {
            "timestamp": {
              "gte": 0,
              "lte": 9507697943118,
              "format": "epoch_millis"
            }
          }
        }
      ],
      "must_not": []
    }
  },
  "size": 1,
  "sort": [
    {
      "ts": {
        "order": "desc",
        "unmapped_type": "boolean"
      }
    }
  ],
  "_source": {
    "excludes": []
  }
}';
		$params = [
				'index' => 'bike_index_v6',
				'type' => 'dbs_realtime',
				'body' => $json
		];

		$results = $client->search($params);
		return $results;
	}
	public function infobikesexist_es2(){
		$id = $_REQUEST['id'];
		$this->assign('id',$id);
		//查询到车位最后一次获取到的车辆数据，进行解析
		$arr_exist = $this->getbikes_exist_es2($id);
		$arr_exist2 = $arr_exist["hits"]["hits"][0]["_source"]["bikes"];
		$ts = $arr_exist["hits"]["hits"][0]["_source"]["ts"];
		$arr_exist3 = json_decode($arr_exist2,true);
		$arr_exist4 = array();
		$redis = new \Redis();
		$redis->connect('116.62.171.54', 8085);

		foreach($arr_exist3 as $k=>$v){
			//采集到的次数从es获取
//			$exist2 = $this->getbikes_exist($id,$v["mac"]);
			$exist = $redis->get("caijicishu:$id:".$v["mac"]);
			$arr_exist4[$k]["name"] = $v["name"];
			$arr_exist4[$k]["mac"] = $v["mac"];
			$arr_exist4[$k]["lasttime"] = date("Y-m-d H:i:s",$ts);
			$arr_exist4[$k]["num"] = $exist;
//			$arr_exist4[$k]['num']=$exist2["hits"]["total"];//采集到的次数
		}
		$this->assign('arr_exist4',$arr_exist4);
		$this->display();
	}

	
	//根据车位编号和mac地址获取车在车位上的时间
	private function getbikes_detail($dwz_info_id,$mac){
		$lpath =  THINK_PATH.'Library/Vendor/vendor/autoload.php';
		require $lpath;
		$hosts = [
				'http://116.62.171.54:8081',         // IP + Port
		];
		$client = \Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build();
		//获取es某mac地址在某车位上被采集到的次数
		$json = '{
				  "version": true,
				  "size": 500,
				  "sort": [
					{
					  "timestamp": {
						"order": "desc",
						"unmapped_type": "boolean"
					  }
					}
				  ],
				  "query": {
					"bool": {
					  "must": [
						{
						  "query_string": {
							"query": "'.$mac.'",
							"analyze_wildcard": true
						  }
						},
						{
						  "match_phrase": {
							"_index": {
							  "query": "bike_index_v6"
							}
						  }
						},
						{
						  "match_phrase": {
							"dwz_info_id": {
							  "query": "'.$dwz_info_id.'"
							}
						  }
						},
						{
						  "match_phrase": {
							"_type": {
							  "query": "dbs_realtime"
							}
						  }
						}
					  ],
					  "must_not": []
					}
				  }
				}';
		$params = [
				'index' => 'bike_index_v6',
				'type' => 'dbs_realtime',
				'body' => $json
		];

		$results = $client->search($params);
		return $results;
	}


}