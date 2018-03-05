<?php
namespace Home\Controller;
use Think\Controller;
class ShownnController extends Controller {
    
	public function getlnglat(){
		$id = $_REQUEST['id'];
		//echo $id;
		if($id == ''){
			exit(json_encode(array('error_code'=>1,'error_reason'=>'参数不正确')));
		}
		
		$res = M("info")->where(array('id'=>$id))->field("id,lng,lat")->find();
		exit(json_encode(array('error_code'=>0,'error_reason'=>'正确','res'=>$res)));
	}
	
	public function login(){
		
		if(IS_POST){
			//var_dump($_POST);
			$u = $_POST['u'];
			$p = $_POST['p'];
			
			$database_admin = D('auth_user');
			$username = $condition_admin['username'] = $_POST['u'];
			$now_admin = $database_admin->field(true)->where($condition_admin)->find();
			//var_dump($now_admin);
			
			if(empty($now_admin)){
				$this->error("用户不存在");
				//exit('-2');
			}
			$password = md5($_POST['p']);
			if($password != $now_admin['password']){
				$this->error("密码错误");
				//exit('-3');
			}
			if($password == $now_admin['password']){
				session_start();
				session('is_login',$now_admin);
				session('auth',$now_admin);
				$this->success('登陆成功',"http://baohe.toalls.com:8080/manbike0.3/index.php/Home/Shown/map");
				//exit('1');
			}else{
				$this->error("登陆失败");
				//exit('-5');
			}
		}else{
			//echo "login";
			$this->display();
		}
	}
	public function logout(){
		//echo "logout";
		unset($_SESSION['is_login']);
		//var_dump($_SESSION);
		$this->success('退出成功！', U('/Home/Shown/login'));
	}
	
	public function ajax_heatmap(){
		//热力图数据
		$list = M("info")->select();
		$arr =array();
		foreach($list as $k=>$v){
			$total = $this->get_num_by_id($v['id']);
			$item['lng']=$v['lng'];
			$item['lat']=$v['lat'];
			$item['count']=$total;
			$arr[]=$item;
			
			//设置level
			//echo $v['id'];
			$this->setalive($v['id']);
		}
		exit(json_encode($arr));
		
	}
	
	//首页
	public function map(){
		if($_SESSION['is_login'] == null){
		$this->error('请登录后访问', U('/Home/Shown/login'));
		}
		$this->assign('username',$_SESSION['is_login']['username']);
		$this->display();
    }
	
	//获取区级别的
	public function ajax_area(){
		$province='浙江省';
        $city='杭州市';
		$list_area = M("info")->distinct(true)->field('area')->select();
		$arr_area = array();
		$arr_area_num = array();
		foreach($list_area as $k=>$v){
			//var_dump($v['area']);
			$area = $v['area'];
			$arr_area[]=$area;
			//echo $area;
			$res = $this->getbikes_ids($province,$city,$area);
			//var_dump($res);
			$arr_area_num[$area]=$res['hits']['total'];
		}
		///var_dump($arr_area_num);
		exit(json_encode(array('error_code'=>0,'error_reason'=>'获取成功','info'=>$arr_area_num)));
	}
	
	//获取网格级别的
	public function ajax_block(){
		$list = M("info_block")->select();
		//var_dump($list);
		//循环获取到相关的数据
		foreach($list as $k=>$v){
			//echo $v['content'];
			$arr = explode('|',$v['content']);
			//获取其中的一个经纬度，然后将他作为这个区块的经纬度
			$id=$arr[0];
			$map['id']=array('eq',$arr[0]);
			$item = M("info")->where($map)->find();
			$list[$k]['lat']=$item['lat'];
			$list[$k]['lng']=$item['lng'];
			//$list[$k]['block']=$this->realtime($v['id']);
			$list[$k]['block']=$this->realtime_new($v['id']);
		}
		//var_dump($list);
		exit(json_encode(array('error_code'=>0,'error_msg'=>'获取成功','info'=>$list)));
	}
	public function ajax_block_show(){
		$title = $_REQUEST['title'];
		if($title == ''){
			$this->error('参数不正确');
		}
		$map['title']=array('eq',$title);
		//$map['lng']=array('eq',$lng);
		$block = M("info_block")->where($map)->find();
		$id = $block['id'];
		$pie = $this->block_pie($id);
		//var_dump($pie);
		$trend = $this->block_trend_new($id);
		//var_dump($trend);
		exit(json_encode(array('id'=>$title,'pie'=>$pie,'trend'=>$trend)));
	}
	public function ajax_info_show(){
		$id = $_REQUEST['id'];
		if($id == ''){
			$this->error('参数不正确');
		}
		$id = str_replace("P","",$id);
		//$info = M("info")->where(array('id'=>$id))->find();
		//var_dump($info);
		//$id = $block['id'];
		$pie = $this->info_pie($id);
		//var_dump($pie);
		$trend = $this->info_trend($id);
		//var_dump($trend);
		exit(json_encode(array('id'=>'P'.$id,'pie'=>$pie,'trend'=>$trend)));
	}
	public function block_pie_record(){
		$redis = new \Redis();
		$redis->connect('116.62.171.54', 8085);
		$time=time();
		//echo $time;
		$redis = new \Redis();
		$redis->connect('116.62.171.54', 8085);
		$list = M("info_block")->select();
		var_dump($list[0]);
		foreach($list as $k=>$v){
			//var_dump();
			$id = $v['id'];
			//var_dump($id);
			$res =$this->block_pie_total_num($id);
			//var_dump($res);	
			$redis->lpush("blockhis20:$id",$time.'_'.$res);
			$data['bid']=$id;
			$data['time']=$time;
			$data['res']=$res;
			M("block_history")->add($data);
			
			if($redis->llen("blockhis20:$id")>2000){
				$redis->rpop("blockhis20:$id");
			}
			
		}
		
	}
	//获取网格的饼图和历史信息
	private function block_pie_total_num($id){
		$redis = new \Redis();
		$redis->connect('116.62.171.54', 8085);
		$map['id']=array('eq',$id);
		$res = M("info_block")->where($map)->find();
		$this->assign('title',$res['title']);
		//var_dump($res['content']);
		$arr = explode('|',$res['content']);
		//var_dump($arr);
		//获取在3分钟内的最后一条！！！
		$arr_mac=array();
		foreach($arr as $k=>$v){
			//每一个车位的查看下 ，然后如果是已经过期了 ，那么就设置为0
			//$redis->hset('dwz_info:'.$v, 'storage_num',$storage_num);
			
			/*$arr_exist = $this->getbikes_exist_es2($v);
			$arr_exist2 = $arr_exist["hits"]["hits"][0]["_source"]["bikes"];
			//echo $arr_exist2;
			$arr_exist3 = json_decode($arr_exist2,true);
			//var_dump($arr_exist3);
			$mac = array_column($arr_exist3,'mac');*/
			
			if($redis->ttl("infobikesexist:$v")==-2){
				$redis->hset('dwz_info:'.$v, 'level',-2);
				$redis->hset('dwz_info:'.$v, 'storage_num',0);
			}
			//这边获取mac直接从redis获取，redis那边设置过期时间为2分钟
			$mac = $redis->smembers("infobikesexist:$v");
			
			if(is_array($mac)){
				$arr_mac = array_merge($arr_mac,$mac);	
			}
			
			//$arr_bikes[]=$arr_exist3;
			//var_dump($arr_exist2);
		}
		
		$arr_mac = array_unique($arr_mac);
		//$num = sizeof($arr_mac);
		
		
		$bike = array();
		foreach($arr_mac as $k=>$v){
			$exist_list3[$k]['name']= $redis->get('bikes:'.$v);
			$exist_list3[$k]['mac']= $v;
		}
		
		
		$arr =array();
		$bike_company=M("bike_company")->select();
		foreach($bike_company as $k=>$v){
			$arr[$v['title']]=explode('|',$v['keyword']);
		}
		foreach($exist_list3 as $k=>$v){
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
	
	public function showx(){
		$redis = new \Redis();
		$redis->connect('116.62.171.54', 8085);
		
		//$vv=5923;
		//echo $redis->ttl("infobikesexist:$vv");
		
		//$redis->hset('dwz_info:'.$vv, 'level',-2);
		//$redis->hset('dwz_info:'.$vv, 'storage_num',0);
		//$redis->expire("infobikesexist:".$vv,60*5);
		exit;
		//当过期时间为-1和-2的时候都不处理
		$blist = M("info_block")->select();
		foreach($blist as $k=>$v){
			$arr = explode('|',$v['content']);
			foreach($arr as $kk=>$vv){
				//echo $vv;
				echo "<br />";
				echo $redis->ttl("infobikesexist:$vv");
				//$redis->expire("infobikesexist:".$vv,60*5);
			}
		}
		
		exit;
		//循环设置下过期时间，然后如果没有了，那么就基本上就是应该设置为0了
		$v=$_REQUEST['id'];
		echo $v;
		$redis = new \Redis();
		$redis->connect('116.62.171.54', 8085);
		
		$mac = $redis->smembers("infobikesexist:$v");
		echo $redis->ttl("infobikesexist:$v");
		var_dump($mac);
		//$redis->delete("infobikesexist:$v");
		//$redis->hset('dwz_info:'.$v, 'level',-2);
		//$redis->hset('dwz_info:'.$v, 'storage_num',0);
		//var_dump($mac);*/
		//$list = M("info")->select();
		//var_dump($list);
		//foreach($list as $k=>$v){
			//$redis->expire("infobikesexist:".$v['id'],60*5);
			//echo $redis->ttl("infobikesexist:".$v['id']);
		//	exit;
		//}
	}	
	
	//获取网格的饼图和历史信息
	private function block_pie($id){
		$redis = new \Redis();
		$redis->connect('116.62.171.54', 8085);
		$map['id']=array('eq',$id);
		$res = M("info_block")->where($map)->find();
		$this->assign('title',$res['title']);
		//var_dump($res['content']);
		$arr = explode('|',$res['content']);
		//var_dump($arr);
		//获取在3分钟内的最后一条！！！
		$arr_mac=array();
		foreach($arr as $k=>$v){
			$arr_exist = $this->getbikes_exist_es2($v);
			$arr_exist2 = $arr_exist["hits"]["hits"][0]["_source"]["bikes"];
			//echo $arr_exist2;
			$arr_exist3 = json_decode($arr_exist2,true);
			//var_dump($arr_exist3);
			$mac = array_column($arr_exist3,'mac');
			if(is_array($mac)){
				$arr_mac = array_merge($arr_mac,$mac);	
			}
			
			//$arr_bikes[]=$arr_exist3;
			//var_dump($arr_exist2);
		}
		
		$arr_mac = array_unique($arr_mac);
		//var_dump($arr_mac);
		$bike = array();
		foreach($arr_mac as $k=>$v){
			$exist_list3[$k]['name']= $redis->get('bikes:'.$v);
			$exist_list3[$k]['mac']= $v;
		}
		
		
		$arr =array();
		$bike_company=M("bike_company")->select();
		foreach($bike_company as $k=>$v){
			$arr[$v['title']]=explode('|',$v['keyword']);
		}
		foreach($exist_list3 as $k=>$v){
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

		$arr1 = $bnames = array_unique($bike_names);
		$bn = array_count_values($bike_names);
		$i=0;
		foreach($bn as $k=>$v){
			$arr2[$i]['name']=$k.'('.$v.')';
			$arr2[$i]['value']=$v;
			foreach($bike_company as $kk=>$vv){
				if($vv['title']==$k){
					$arr2[$i]['color']=$vv['color'];
				}
			}
			$i++;
		}
		return $arr2;
		//var_dump($arr2);
		//exit;
	}
	
	public function block_trend_new_p(){
		$id = $_REQUEST['id'];
		//$rrr = $this->block_trend_new($id);
		//var_dump($rrr);
		$rrr = $this->realtime_new($id);
		var_dump($rrr);
	}
	
	private function realtime_new($id){
		$redis = new \Redis();
		$redis->connect('127.0.0.1', 6379);
		$one = $redis->lrange("blockhis20:$id","0","0");
		if($one==null){
			return 0;
		}else{
			$arr = explode('_',$one[0]);
			return $arr[1];	
		}
		
	}
	
	private function block_trend_new($id){
		$redis = new \Redis();
		$redis->connect('127.0.0.1', 6379);
		
		$list = $redis->lrange("blockhis20:$id","0","20");
		$list = array_reverse($list);
		//var_dump($list);
		$nnarr = array();
		
		foreach($list as $k=>$v){
			$arr = explode('_',$v);
			$nnarr[$k][0]=date('Y-m-d H:i:s', $arr[0]);
			$nnarr[$k][1]=$arr[1];
			$nnarr[$k][2]=$arr[1];
		}
		return $nnarr;
	}
	
	private function block_trend($id){
		$end=time();
		$start = $end-60*20;
		$start = $start*1000;
		$end = $end*1000;
		$map['id']=array('eq',$id);
		$res = M("info_block")->where($map)->find();
		$arr = explode('|',$res['content']);
		foreach($arr as $k=>$v){
			$arr[$k]='dwz_info_id:'.$v;
		}
		$query = implode(" ",$arr);

		$lpath =  THINK_PATH.'Library/Vendor/vendor/autoload.php';
		require $lpath;
		$hosts = [
				'http://116.62.171.54:8081',         // IP + Port
		];
		$client = \Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build();
		//获取es最后更新的时间,在更新的时候使用
		//echo $query;
		$json ='{
				  "size": 20,
				  "aggs": {
					"2": {
					  "date_histogram": {
						"field": "timestamp",
						"interval": "1m",
						"time_zone": "Asia/Shanghai",
						"min_doc_count": 1
					  },
					  "aggs": {
						"1": {
						  "sum": {
							"field": "storage_num"
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
						  "query_string": {
							"query": "'.$query.'",
							"analyze_wildcard": true
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
							  "gte": '.$start.',
							  "lte": '.$end.',
							  "format": "epoch_millis"
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
		//var_dump($results['aggregations'][2]['buckets']);
		
		$nnarr = array();
		$res = $results['aggregations'][2]['buckets'];
		//var_dump($res);
		
		foreach($res as $k=>$v){
			//var_dump($v['1']);
			//exit;
			$nnarr[$k][0]=date('Y-m-d H:i:s', $v['key']/1000);
			$nnarr[$k][1]=$v['1']['value'];
			$nnarr[$k][2]=$v['1']['value'];
			
		}
		//var_dump($nnarr);
		return $nnarr;
	}
	
	private function info_pie($id){
		$redis = new \Redis();
		$redis->connect('116.62.171.54',8085);
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
			
			/*if($flag=='no'){
				$bike_names[]='其他';
			}*/
		}
		
		$bn = array_count_values($bike_names);
		$len = sizeof($bn);
		$i=0;
		foreach($bn as $k=>$v){
			$arr2[$i]['name']=$k.'('.$v.')';
			$arr2[$i]['value']=$v;
			foreach($bike_company as $kk=>$vv){
				if($vv['title']==$k){
					$arr2[$i]['color']=$vv['color'];
				}
			}
			$i++;
		}
		return $arr2;
    }
	
	private function info_trend($id){
		
		$end=time();
		$start = $end-60*20;
		$start = $start*1000;
		$end = $end*1000;
		$lpath =  THINK_PATH.'Library/Vendor/vendor/autoload.php';
		require $lpath;
	  $hosts = [['host' =>'47.97.218.117','port' =>'9200','user' =>'elastic','pass' =>'Admin===']];
		$client = \Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build();
		$json='{
				  "version": true,
				  "size": 20,
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
					  "must_not": []
					}
				  }
				}';
			$params = [
					'index' => 'dwz_v1',
					'type' => 'dbs_realtime',
					'body' => $json
			];
			$results = $client->search($params);
			$nnarr = array();
			foreach($results['hits']['hits'] as $k=>$v){ 
				$nnarr[$k][0]=str_replace('T',' ',$v['_source']['ts']);
				$nnarr[$k][1]=$v['_source']['storage_num'];
				$nnarr[$k][2]=$v['_source']['storage_num'];
			}
			
			$nnarr = array_reverse($nnarr);
			return $nnarr;
	}
	
	
	//获取车位级别的
	public function ajax_info(){
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
		$redis->connect('116.62.171.54',8085);
		
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
	
	
	//获取最新的那个数量
	private function realtime($id){
		
		$redis = new \Redis();
		$redis->connect('116.62.171.54', 8085);
		
		if($id == ''){
			$this->error('参数不正确');
		}
		$map['id']=array('eq',$id);
		$res = M("info_block")->where($map)->find();
		$this->assign('title',$res['title']);
		//var_dump($res['content']);
		$arr = explode('|',$res['content']);
		//var_dump($arr);
		//获取在3分钟内的最后一条！！！
		$arr_mac=array();
		foreach($arr as $k=>$v){
			$arr_exist = $this->getbikes_exist_es2($v);
			$arr_exist2 = $arr_exist["hits"]["hits"][0]["_source"]["bikes"];
			//echo $arr_exist2;
			$arr_exist3 = json_decode($arr_exist2,true);
			//var_dump($arr_exist3);
			$mac = array_column($arr_exist3,'mac');
			if(is_array($mac)){
				$arr_mac = array_merge($arr_mac,$mac);	
			}
			
			//$arr_bikes[]=$arr_exist3;
			//var_dump($arr_exist2);
		}
		
		$arr_mac = array_unique($arr_mac);
		//var_dump($arr_mac);
		$bike = array();
		foreach($arr_mac as $k=>$v){
			$exist_list3[$k]['name']= $redis->get('bikes:'.$v);
			$exist_list3[$k]['mac']= $v;
		}
		
		
		$arr =array();
		$bike_company=M("bike_company")->select();
		foreach($bike_company as $k=>$v){
			$arr[$v['title']]=explode('|',$v['keyword']);
		}
		foreach($exist_list3 as $k=>$v){
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
		$i=0;
		foreach($bn as $k=>$v){
			$arr2[$i]['name']=$k.'('.$v.')';
			$arr2[$i]['value']=$v;
			$i++;
		}
		
		//var_dump($arr2);
		if($arr2 == NULL){
			return 0;
		}
		
		$str1 = json_encode($arr1);
		$str2 = json_encode($arr2);
		//var_dump($str1);
		//var_dump($str2);
		$this->assign('str1',$str1);
		$this->assign('str2',$str2);
		$exist = 0;
		foreach($arr2 as $k=>$v){
			$exist +=$v['value'];
		}
		//$this->assign('exist',$exist);
		return $exist;
		//$this->display();
	}
	
	
	//根据车位编号和mac地址获取采集到的次数
	private function getbikes_exist_es2($dwz_info_id){
		//最后3分钟
		$end = time();
		$start = $end - 60*10;
		$end = $end*1000 ;
		$start = $start*1000 ;
		
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
              "gte": '.$start.',
              "lte": '.$end.',
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

	
	
	//根据是否存活，设置level
	private function setalive($id){
		$redis = new \Redis();
		$redis->connect('127.0.0.1', 6379);
		$lasttime = $redis->get("lasttime:$id");
		$time = time();
		if($time - $lasttime > 10*60 ){
			$redis->hset('dwz_info:'.$id, 'level',-2);
		}
	}
	
	private function startwith($str,$pattern) {
		if(strpos($str,$pattern) === 0)
          return true;
		else
          return false;
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
					and a.type=1 and a.show_type=1
				 LIMIT 3
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
						and a.lng is not null and a.lat is not null and a.lng <> '' and a.lat <> ''
					 " . $where_sql ."
					 ORDER BY
						distance
					 LIMIT 10
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
					if(count($data) >= 20){
						break;
					}
				}else{
					if(count($data) >= 10){
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
	
	
	//根据经纬度获取到所有车位
	public function getredian_by_area(){
		$area = $_REQUEST['area'];
		$res = array();
		if($area == ''){
			exit(json_encode(array('code'=>1,'reason'=>'参数不正确','res'=>$res)));
		}
		
		$map_area['area']=array('eq',$area);
		$list = M("info")->field('id')->where($map_area)->select();
		var_dump($list);
		foreach($list as $k=>$v){
			
		}
		
		
	}
	
	/*public function last_20_item_p(){
		$res = $this->last_20_item(0);
		$list = $res['hits']['hits'];
		var_dump($list);
	}
	
	//所在市的最后20条数据
	private function last_20_item($from){
		$size = 20;
		$from = $from*$size;
		
		
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
				  "version": true,
				  "from":'.$from.',
				  "size": '.$size.',
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
							"province": {
							  "query": "浙江省"
							}
						  }
						},
						{
						  "match_phrase": {
							"city": {
							  "query": "杭州市"
							}
						  }
						},
						{
						  "match_phrase": {
							"_type": {
							  "query": "dwz_bike_sub_realtime_last"
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
				
				  "stored_fields": [
					"*"
				  ],
				  "script_fields": {},
				  "docvalue_fields": [
					"timestamp"
				  ]
				}';
				
		$params = [
				'index' => 'bike_index_v5',
				'type' => 'dwz_bike_sub_realtime_last',
				'body' => $json
			];

		$results = $client->search($params);
		//var_dump($results['hits']['hits'][0]);
		//$list = $results['hits']['hits'];
		//var_dump($list);
		//foreach($list as $k=>$v){
			//echo $k;
			//var_dump($v);
		//}
			//$ts = $results['hits']['hits'][0]['_source']['ts'];
			//var_dump($results);
			//var_dump($ts);
		$list = array();
		foreach($results['hits']['hits'] as $k=>$v){
			$list[]=$v['_source'];
		}
		//var_dump($list);
		return $list;
	}*/
	//根据mac 或名称获取到经纬度 然后显示
	/*public function ajax_getitem(){
		
		$lpath =  THINK_PATH.'Library/Vendor/vendor/autoload.php';
		require $lpath;
		//$hosts = [
		//'dododo.shop:9200',         // IP + Port
		//];
		
		$hosts = [
		'116.62.171.54:8081',         // IP + Port
		];
		
		$client = \Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build();
		
		$keyword = $_REQUEST['keyword'];
		if($keyword== ''){
			exit(json_encode(array('error_code'=>2,'error_reason'=>'参数错误')));
		}

		//echo $keyword;
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
								"query": "bikes:'.$keyword.'",
								"analyze_wildcard": true
							  }
							}
						  ],
						  "must_not": [
							{
							  "match_phrase": {
								"_type": {
								  "query": "dwz_bike_sub_realtime_last"
								}
							  }
							},
							{
							  "match_phrase": {
								"_type": {
								  "query": "dwz_bike_sub_realtime_history"
								}
							  }
							}
						  ]
						}
					  }
					}';
			$params = [
				'index' => 'bike_index_v5',
				'type' => 'dwz_bike_sub_realtime',
				'body' => $json
			];

			$results = $client->search($params);
			//$ts = $results['hits']['hits'][0]['_source']['ts'];
			//var_dump($results['hits']['total']);
			if($results['hits']['total']>0){
				//存在，
				$bikes = $results['hits']['hits'][0]['_source']['bikes'];
				$item['dwz_info_id']=$results['hits']['hits'][0]['_source']['dwz_info_id'];
				$r = M("info")->where(array('id'=>$item['dwz_info_id']))->find();
				//var_dump($r);
				$item['ts']=$results['hits']['hits'][0]['_source']['ts'];
				$bikes = json_decode($bikes);
				foreach($bikes as $k=>$v){
					//var_dump($v);
					if($this->startwith($v->name,$keyword)){
						//var_dump($v);
						$item['bind']=$v->bind;
						$item['mac']=$v->mac;
						$item['name']=$v->name;
						$item['rssi']=$v->rssi;
						$item['lng']=$r['lng'];
						$item['lat']=$r['lat'];
					}
				}
				//var_dump($item);
				exit(json_encode(array('error_code'=>0,'error_reason'=>'获取成功','item'=>$item)));
				//var_dump($results['hits']['hits'][0]['_source']);
			}else{
				//不存在，返回错误信息
				exit(json_encode(array('error_code'=>1,'error_reason'=>'不存在')));
			}		
			
	}*/
	/*
	public function get_by_center(){
		$left_bottom_lng = $_REQUEST['left_bottom_lng'];
		$left_bottom_lat = $_REQUEST['left_bottom_lat'];
		$right_top_lng = $_REQUEST['right_top_lng'];
		$right_top_lat = $_REQUEST['right_top_lat'];
		$center_lat = $_REQUEST['center_lat'];
		$center_lng = $_REQUEST['center_lng'];
		
		//var_dump($_REQUEST);
		
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
				  "size": 20,
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
					  "filter": {
						"geo_bounding_box": {
						  "type":       "indexed",
						  "location": {
							"bottom_left": {
							  "lat":  '.$left_bottom_lat.',
							  "lon": '.$left_bottom_lng.'
							},
							"top_right": {
							  "lat":  '.$right_top_lat.',
							  "lon": '.$right_top_lng.'
							}
						  }
						}
					  },
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
				  "sort": [
					{
					  "_geo_distance": {
						"location": { 
						  "lat":  '.$center_lat.',
						  "lon": '.$center_lng.'
						},
						"order":         "asc",
						"unit":          "km", 
						"distance_type": "plane" 
					  }
					}
				  ],
				  "sort": [
					{
					  "timestamp": {
						"order": "desc",
						"unmapped_type": "boolean"
					  }
					}
				  ]
				}';
		$params = [
				'index' => 'bike_index_v5',
				'type' => 'dwz_bike_sub_realtime_last',
				'body' => $json
			];

		$results = $client->search($params);
		//var_dump($results['hits']['hits']);
		$list = array();
		foreach($results['hits']['hits'] as $k=>$v){
			$list[] = $v['_source'];
		}
		foreach($list as $k=>$v){
			//echo $v['name'];
			if($v['name'] == 'mobike'){
				$list[$k]['name']='老人'.rand(1,1000);
				$list[$k]['company']='老人';
			}
			
			if($this->startwith($v['name'],'CoolQi')){
				$list[$k]['name']='宠物'.rand(1,1000);
				$list[$k]['company']='宠物';
			}
		}
		//var_dump($list);
		exit(json_encode(array('error_code'=>0,'error_reason'=>'成功','list'=>$list)));
			//$ts = $results['hits']['hits'][0]['_source']['ts'];
			//var_dump($results);
			//var_dump($ts);
		//return $results;
	}*/
	
	public function heatmap(){
		//获取每个车位的一个数量
		$list = M("info")->select();
		//var_dump(sizeof($list));
		$arr =array();
		foreach($list as $k=>$v){
			$total = $this->get_num_by_id($v['id']);
			$item['lng']=$v['lng'];
			$item['lat']=$v['lat'];
			$item['count']=$total;
			$arr[]=$item;
		}
		//var_dump($arr);
		$json_str = json_encode($arr);
		$this->assign('json_str',$json_str);
		//var_dump($arr);
		//var_dump($list);
		$this->display();
	}
	private function get_num_by_id($id){
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
				  "version": true,
				  "size": 20,
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
			
			return $results['hits']['total'];
	}
	
	
	
	//根据省市区获取
	private function getbikes_pca($province,$city,$area){
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
	
	//根据省市区获取到对应的id  然后使用id
	private function getbikes_ids($province='',$city='',$area=''){
		//$province = '浙江省';
		//$city = '杭州市';
		//$area = '滨江区';
		
		//根据省市区获取到ids 字符串 ，然后查es 获取到数量
		if($province!='') $map['province']=array('eq',$province);
		if($city!='') $map['city']=array('eq',$city);
		if($area!='') $map['area']=array('eq',$area);
		$list = M("info")->where($map)->select();
		//var_dump($list);
		$arr_ids = array();
		foreach($list as $k=>$v){
			$arr_ids[]='dwz_info_id:'.$v['id'];
		}
		$ids = implode(" ",$arr_ids);
		//var_dump($ids);
		//exit;
		
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
					  "query_string": {
						"analyze_wildcard": true,
						"query": "'.$ids.'"
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
		$bike_company=M("bike_company")->select();
		foreach($bike_company as $k=>$v){
			//var_dump($v);
			$arr[$v['title']]=explode('|',$v['keyword']);
		}
		foreach($bikes as $k=>$v){
			$flag = 'no';
			$name = $v->name;
			foreach($arr as $kk=>$vv){
				foreach($vv as $kkk=>$vvv){
					if(strpos($name,$vvv)!==false){
						$bike_names[]=$kk;
						$flag = 'yes';						
					}
				}
			}
			if($flag=='no'){
				$bike_names[]='其他';
			}
		}
		
		/*foreach($bikes as $k=>$v){
			$name = $v->name;
			if(strpos($name,'mb')!==false || strpos($name,'mo')!==false){
				$bike_names[]='摩拜';	
			}elseif(strpos($name,'BLXXX')!==false){
				$bike_names[]='骑乐无比';
			}elseif($name=='NULL'){
				$bike_names[]='无名称';	
			}elseif(strpos($name,'ofo')!==false){
				$bike_names[]='ofo';
			}elseif(strpos($name,'CoolQi')!==false || strpos($name,'BL')!==false){
				$bike_names[]='酷骑';
			}elseif(strpos($name,'XIAOMING')!==false){
			    $bike_names[]='小明单车';
			}else{
				$bike_names[]='其他';
			}
		}*/
		$bnames = array_unique($bike_names);
		$bn = array_count_values($bike_names);
		$len = sizeof($bn);
		//var_dump($bn);
		
		//var_dump($statistic);
		exit(json_encode(array('error_code'=>0,'error_reason'=>'正确','length'=>$len,'result'=>$bn)));
    }
	//实时
	public function realtime_detail_rssi(){
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
    }
	
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
	public function tools(){
		//echo "tools";
		$bikes1='[{"bind":10,"mac":"C73FAD993CAF","name":"","rssi":-91},{"bind":10,"mac":"F5924B9DB3B2","name":"ofo","rssi":-87},{"bind":10,"mac":"F9B1F9DF8C8A","name":"mobike","rssi":-87},{"bind":10,"mac":"D4A88FDDFDD8","name":"mobike","rssi":-87},{"bind":10,"mac":"C015C843F0E8","name":"","rssi":-76},{"bind":10,"mac":"C614743FFE15","name":"mb_Ff4\/dBTG","rssi":-88},{"bind":10,"mac":"B4A8280458DF","name":"NokeLock","rssi":-93},{"bind":10,"mac":"04FEA188D453","name":"JBL Flip 4","rssi":-92},{"bind":10,"mac":"D280916A5D63","name":"","rssi":-85},{"bind":10,"mac":"C4A2454BE8C8","name":"","rssi":-91},{"bind":10,"mac":"ED800B40BCC9","name":"mobike","rssi":-91},{"bind":10,"mac":"DAA20A82F875","name":"mobike","rssi":-83},{"bind":10,"mac":"E75C21C4289D","name":"mobike","rssi":-88},{"bind":10,"mac":"EEA86B54D74B","name":"","rssi":-81},{"bind":10,"mac":"FF87A0B72C84","name":"","rssi":-91},{"bind":10,"mac":"D56348A24032","name":"","rssi":-89},{"bind":10,"mac":"FA4EC8B469E7","name":"mb_52m0yE76","rssi":-87},{"bind":10,"mac":"C26BD86E4B98","name":"mobike","rssi":-92},{"bind":10,"mac":"D32E7259ECE5","name":"","rssi":-85},{"bind":10,"mac":"50F14A4C223B","name":"","rssi":-90},{"bind":10,"mac":"D43639A1CE9F","name":"XIAOMING","rssi":-75},{"bind":10,"mac":"D43639B613F2","name":"","rssi":-86},{"bind":10,"mac":"1893D749B0CF","name":"","rssi":-83},{"bind":10,"mac":"EE0B9B1105C2","name":"","rssi":-86},{"bind":10,"mac":"1893D746B7B9","name":"","rssi":-83},{"bind":10,"mac":"CED908750C15","name":"mobike","rssi":-80},{"bind":10,"mac":"F17BC54EAC4C","name":"mobike","rssi":-83},{"bind":10,"mac":"1893D7315456","name":"","rssi":-87},{"bind":10,"mac":"E12ACA0E8282","name":"","rssi":-75},{"bind":10,"mac":"E11B02CED8E3","name":"mobike","rssi":-89},{"bind":10,"mac":"EA674F62718F","name":"mobike","rssi":-89},{"bind":10,"mac":"F7DB4DD6C206","name":"mobike","rssi":-89},{"bind":10,"mac":"EEEAADDBFF8F","name":"mb_j\/\/breru","rssi":-88},{"bind":10,"mac":"D315967C506D","name":"mb_bVB8lhXT","rssi":-95},{"bind":10,"mac":"D7328725F7C3","name":"mobike","rssi":-71},{"bind":10,"mac":"D6583070AD77","name":"mb_d61wMFjW","rssi":-93},{"bind":10,"mac":"D1CE062B9A5D","name":"mb_XZorBs7R","rssi":-93},{"bind":10,"mac":"C1888E6558EE","name":"mobike","rssi":-86},{"bind":10,"mac":"E7FFC43A36CF","name":"mobike","rssi":-81},{"bind":10,"mac":"CBD23BDFA8F0","name":"ofo","rssi":-90},{"bind":10,"mac":"C888DE26C429","name":"mobike","rssi":-87},{"bind":10,"mac":"9C1D581734A3","name":"","rssi":-85},{"bind":10,"mac":"E57CB556E049","name":"mb_SeBWtXzl","rssi":-87},{"bind":10,"mac":"D436399F57DA","name":"CoolQi","rssi":-83},{"bind":10,"mac":"C92E88C954C0","name":"mobike","rssi":-79},{"bind":10,"mac":"F2F054C7E72E","name":"mobike","rssi":-62},{"bind":10,"mac":"001583384272","name":"HM-A300","rssi":-89},{"bind":10,"mac":"D8F8C6C9112D","name":"mobike","rssi":-92},{"bind":10,"mac":"F2AFD41308BE","name":"mb_vggT1K\/y","rssi":-92},{"bind":10,"mac":"D436396E8E69","name":"","rssi":-92},{"bind":10,"mac":"508CB15A3A51","name":"XIAOMING","rssi":-69},{"bind":10,"mac":"EEE590E6C92F","name":"mobike","rssi":-71},{"bind":10,"mac":"B4A8280454DB","name":"NokeLock","rssi":-87},{"bind":10,"mac":"E0670FCDF705","name":"mb_BffND2fg","rssi":-75},{"bind":10,"mac":"D7D8D491A481","name":"mobike","rssi":-81},{"bind":10,"mac":"C4DA08F94BCB","name":"mb_y0v5CNrE","rssi":-81},{"bind":10,"mac":"DADCC96A1B96","name":"mb_lhtqydza","rssi":-84},{"bind":10,"mac":"EDD0F595AEC4","name":"ofo","rssi":-93},{"bind":10,"mac":"CB7D43C1038A","name":"mobike","rssi":-87},{"bind":10,"mac":"50F14A4C1785","name":"CoolQi","rssi":-93},{"bind":10,"mac":"D12E219A7051","name":"mb_UXCaIS7R","rssi":-85},{"bind":10,"mac":"DAEBBFDADDA5","name":"mb_pd3av+va","rssi":-93},{"bind":10,"mac":"DBDE0B59650F","name":"mobike","rssi":-89},{"bind":10,"mac":"EEF4A7892F3B","name":"mb_Oy+Jp\/Tu","rssi":-83},{"bind":10,"mac":"C5B02AD28C68","name":"mobike","rssi":-69},{"bind":10,"mac":"C8B39D573ABB","name":"","rssi":-86},{"bind":10,"mac":"DBD567778699","name":"mobike","rssi":-83},{"bind":10,"mac":"D4C880D9B8C6","name":"mb_xrjZgMjU","rssi":-90},{"bind":10,"mac":"F68EF54EDB94","name":"","rssi":-88},{"bind":10,"mac":"C01055C9450E","name":"mobike","rssi":-89},{"bind":10,"mac":"C19E6EAF6492","name":"mobike","rssi":-80},{"bind":10,"mac":"FDAD50305272","name":"mobike","rssi":-73},{"bind":10,"mac":"FC312B4CDAD6","name":"mb_1tpMKzH8","rssi":-83},{"bind":10,"mac":"C32F706566E1","name":"mobike","rssi":-87},{"bind":10,"mac":"EE21CDC11C0B","name":"mobike","rssi":-82},{"bind":10,"mac":"E5FCF1BF36C6","name":"mobike","rssi":-91},{"bind":10,"mac":"D436396E3936","name":"","rssi":-84},{"bind":10,"mac":"1893D736B5D9","name":"","rssi":-84},{"bind":10,"mac":"FAF2CA709FA0","name":"mb_oJ9wyvL6","rssi":-75},{"bind":10,"mac":"9C1D58170AB5","name":"","rssi":-85},{"bind":10,"mac":"B4A8280454F7","name":"NokeLock","rssi":-89},{"bind":10,"mac":"50338B1212DB","name":"BL-2A","rssi":-93},{"bind":10,"mac":"F736B167D6D0","name":"","rssi":-92},{"bind":10,"mac":"F38A02108D4E","name":"mobike","rssi":-90},{"bind":10,"mac":"C76BB7A68DEE","name":"mobike","rssi":-85},{"bind":10,"mac":"FECFB9DBDCA6","name":"mb_ptzbuc\/+","rssi":-89}]';
		$bikes2='[{"bind":10,"mac":"C73FAD993CAF","name":"ofo","rssi":-92},{"bind":10,"mac":"F5924B9DB3B2","name":"","rssi":-90},{"bind":10,"mac":"F9B1F9DF8C8A","name":"mobike","rssi":-85},{"bind":10,"mac":"D4A88FDDFDD8","name":"mobike","rssi":-88},{"bind":10,"mac":"C015C843F0E8","name":"","rssi":-76},{"bind":10,"mac":"6BE48671AD04","name":"","rssi":-85},{"bind":10,"mac":"C614743FFE15","name":"mb_Ff4\/dBTG","rssi":-88},{"bind":10,"mac":"04FEA188D453","name":"JBL Flip 4","rssi":-90},{"bind":10,"mac":"D280916A5D63","name":"","rssi":-81},{"bind":10,"mac":"C4A2454BE8C8","name":"ofo_adv_tes","rssi":-93},{"bind":10,"mac":"ED800B40BCC9","name":"mobike","rssi":-91},{"bind":10,"mac":"DAA20A82F875","name":"mobike","rssi":-82},{"bind":10,"mac":"E75C21C4289D","name":"mobike","rssi":-86},{"bind":10,"mac":"EEA86B54D74B","name":"","rssi":-87},{"bind":10,"mac":"C5221D351ECF","name":"","rssi":-86},{"bind":10,"mac":"D56348A24032","name":"","rssi":-86},{"bind":10,"mac":"FA4EC8B469E7","name":"mb_52m0yE76","rssi":-88},{"bind":10,"mac":"C26BD86E4B98","name":"mobike","rssi":-94},{"bind":10,"mac":"D32E7259ECE5","name":"ofo_adv_tes","rssi":-85},{"bind":10,"mac":"F6D83D58FEE1","name":"mobike","rssi":-93},{"bind":10,"mac":"DFA91C17C7D1","name":"mobike","rssi":-92},{"bind":10,"mac":"50F14A4C223B","name":"","rssi":-90},{"bind":10,"mac":"D43639A1CE9F","name":"XIAOMING","rssi":-74},{"bind":10,"mac":"D43639B613F2","name":"","rssi":-87},{"bind":10,"mac":"1893D749B0CF","name":"","rssi":-78},{"bind":10,"mac":"EE0B9B1105C2","name":"","rssi":-85},{"bind":10,"mac":"1893D746B7B9","name":"","rssi":-81},{"bind":10,"mac":"CED908750C15","name":"mobike","rssi":-82},{"bind":10,"mac":"F17BC54EAC4C","name":"mobike","rssi":-80},{"bind":10,"mac":"1893D7315456","name":"","rssi":-89},{"bind":10,"mac":"E12ACA0E8282","name":"","rssi":-85},{"bind":10,"mac":"E11B02CED8E3","name":"mobike","rssi":-80},{"bind":10,"mac":"EA674F62718F","name":"mobike","rssi":-87},{"bind":10,"mac":"EEEAADDBFF8F","name":"mb_j\/\/breru","rssi":-86},{"bind":10,"mac":"F2AFD41308BE","name":"mb_vggT1K\/y","rssi":-88},{"bind":10,"mac":"D7328725F7C3","name":"mobike","rssi":-70},{"bind":10,"mac":"D6583070AD77","name":"mb_d61wMFjW","rssi":-89},{"bind":10,"mac":"C1888E6558EE","name":"mobike","rssi":-85},{"bind":10,"mac":"E7FFC43A36CF","name":"mobike","rssi":-78},{"bind":10,"mac":"CBD23BDFA8F0","name":"","rssi":-90},{"bind":10,"mac":"CF544B2FE251","name":"mobike","rssi":-92},{"bind":10,"mac":"C888DE26C429","name":"mobike","rssi":-81},{"bind":10,"mac":"9C1D581734A3","name":"BL-2A","rssi":-90},{"bind":10,"mac":"E57CB556E049","name":"mb_SeBWtXzl","rssi":-89},{"bind":10,"mac":"F1500D776AE4","name":"mobike","rssi":-89},{"bind":10,"mac":"D436399F57DA","name":"","rssi":-90},{"bind":10,"mac":"C92E88C954C0","name":"mobike","rssi":-81},{"bind":10,"mac":"F2F054C7E72E","name":"mobike","rssi":-64},{"bind":10,"mac":"D8F8C6C9112D","name":"mobike","rssi":-91},{"bind":10,"mac":"D315967C506D","name":"mb_bVB8lhXT","rssi":-93},{"bind":10,"mac":"D436396E8E69","name":"CoolQi","rssi":-90},{"bind":10,"mac":"508CB15A3A51","name":"XIAOMING","rssi":-65},{"bind":10,"mac":"EEE590E6C92F","name":"mobike","rssi":-73},{"bind":10,"mac":"B4A8280454DB","name":"NokeLock","rssi":-75},{"bind":10,"mac":"E0670FCDF705","name":"mb_BffND2fg","rssi":-76},{"bind":10,"mac":"D7D8D491A481","name":"mobike","rssi":-83},{"bind":10,"mac":"C4DA08F94BCB","name":"mb_y0v5CNrE","rssi":-84},{"bind":10,"mac":"DADCC96A1B96","name":"mb_lhtqydza","rssi":-87},{"bind":10,"mac":"CB7D43C1038A","name":"mobike","rssi":-85},{"bind":10,"mac":"D12E219A7051","name":"mb_UXCaIS7R","rssi":-93},{"bind":10,"mac":"DBDE0B59650F","name":"mobike","rssi":-88},{"bind":10,"mac":"EEF4A7892F3B","name":"mb_Oy+Jp\/Tu","rssi":-85},{"bind":10,"mac":"C5B02AD28C68","name":"mobike","rssi":-69},{"bind":10,"mac":"D43639A0DE7C","name":"","rssi":-92},{"bind":10,"mac":"D112B61DE91B","name":"mobike","rssi":-91},{"bind":10,"mac":"C8B39D573ABB","name":"","rssi":-94},{"bind":10,"mac":"DBD567778699","name":"mobike","rssi":-91},{"bind":10,"mac":"D4C880D9B8C6","name":"mb_xrjZgMjU","rssi":-90},{"bind":10,"mac":"F68EF54EDB94","name":"","rssi":-85},{"bind":10,"mac":"C01055C9450E","name":"mobike","rssi":-91},{"bind":10,"mac":"C19E6EAF6492","name":"mobike","rssi":-80},{"bind":10,"mac":"FDAD50305272","name":"mobike","rssi":-76},{"bind":10,"mac":"FC312B4CDAD6","name":"mb_1tpMKzH8","rssi":-82},{"bind":10,"mac":"C32F706566E1","name":"mobike","rssi":-87},{"bind":10,"mac":"EE21CDC11C0B","name":"mobike","rssi":-90},{"bind":10,"mac":"E5FCF1BF36C6","name":"mobike","rssi":-85},{"bind":10,"mac":"D436396E3936","name":"","rssi":-86},{"bind":10,"mac":"C93B6C61A210","name":"mobike","rssi":-90},{"bind":10,"mac":"1893D736B5D9","name":"","rssi":-89},{"bind":10,"mac":"FAF2CA709FA0","name":"mb_oJ9wyvL6","rssi":-81},{"bind":10,"mac":"9C1D58170AB5","name":"BL-2A","rssi":-91},{"bind":10,"mac":"B4A8280454F7","name":"NokeLock","rssi":-89},{"bind":10,"mac":"F736B167D6D0","name":"","rssi":-86},{"bind":10,"mac":"F38A02108D4E","name":"mobike","rssi":-91},{"bind":10,"mac":"C76BB7A68DEE","name":"mobike","rssi":-85},{"bind":10,"mac":"FECFB9DBDCA6","name":"mb_ptzbuc\/+","rssi":-90},{"bind":10,"mac":"D83753E365DA","name":"mobike","rssi":-94}]';
		$arr1 = json_decode($bikes1);
		$arr2 = json_decode($bikes2);
		$arr_mac1=array();
		$arr_mac2=array();
		foreach($arr1 as $k=>$v){
			$arr_mac1[]=$v->mac;
		}
		foreach($arr2 as $k=>$v){
			$arr_mac2[]=$v->mac;
		}
		$res = array_diff($arr_mac1,$arr_mac2);
		echo sizeof($res);
		var_dump($res);
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
	public function infobike(){
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
		
	}
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
