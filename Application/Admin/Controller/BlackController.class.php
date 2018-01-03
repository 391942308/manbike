<?php
namespace Admin\Controller;
use Think\Controller;
class BlackController extends CommonController {
	public function _initialize(){
		parent::_initialize();
	}
	public function index() {	
		//从bike_blacklist 获取到列表 展示
		$name="";	
		$mac="";
		$info_id="";
		$data = M('blacklist');
		$sql="SELECT * FROM dwz_blacklist where 1=1 ";
		if (!empty($_GET["name"]) || $_GET["name"]==='0') {
			$name = trim($_REQUEST["name"]);
			$sql .= " and name like '$name%' ";
			$where["name"] = array('like', "%$name%");
			$this->assign('name', $name);
		}
		/*if (!empty($_GET["mac"]) || $_GET["mac"]==='0') {
			$mac = trim($_REQUEST["mac"]);
			$sql .= " and mac = '$mac' ";
			$where["mac"] = '$mac';
			$this->assign('mac', $mac);
		}*/
		if (!empty($_GET["info_id"]) || $_GET["info_id"]==='0') {
			$info_id = trim($_REQUEST["info_id"]);
			$sql .= " and dwz_info_id = '$info_id' ";
			$where["dwz_info_id"] = $info_id;
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
		
		$company_list = M("bike_company")->select();
		$this->assign("company_list",$company_list);
		$this->display();
	}
	
	public function index_analy() {
		//从bike_blacklist 获取到列表 展示
		$name="";	
		$mac="";
		$info_id="";
		$data = M('blacklist_analy');
		$sql="SELECT * FROM dwz_blacklist_analy where 1=1 ";
		if (!empty($_GET["name"]) || $_GET["name"]==='0') {
			$name = trim($_REQUEST["name"]);
			$sql .= " and name like '$name%' ";
			$where["name"] = array('like', "%$name%");
			$this->assign('name', $name);
		}
		/*if (!empty($_GET["mac"]) || $_GET["mac"]==='0') {
			$mac = trim($_REQUEST["mac"]);
			$sql .= " and mac = '$mac' ";
			$where["mac"] = '$mac';
			$this->assign('mac', $mac);
		}*/
		if (!empty($_GET["info_id"]) || $_GET["info_id"]==='0') {
			$info_id = trim($_REQUEST["info_id"]);
			$sql .= " and dwz_info_id = '$info_id' ";
			$where["dwz_info_id"] = $info_id;
			$this->assign('info_id', $info_id);
		}
		$count=$data->where($where)->count();
		//var_dump($count);exit;
		$pageSize = 20;
		$page = new \Component\Page($count, $pageSize); //这里的分页类和Home模块的目录一致，可自行修改
		$sql.=" order by times desc ";
		$sql.=$page->limit;
		$info = $data -> query($sql);
		$pagelist = $page -> fpage();
		$this->assign('show', $pagelist);
		$this->assign("bparking_list", $info);

		$this->display();
	}
	
	public function analy(){
		$id = $_REQUEST['info_id'];
		if($id == ''){
			$this->error("参数错误");
		}
		
		//根据dwz_info_id 从blacklist 
		//获取到需要解析的mac 结合时间和区域内出现的次数 
		//以及最后一次出现的dwz_info_id
		//存入数据库
		//加入一个详情按钮 用于看详细情况
		$map["dwz_info_id"]=array('eq',$id);
		$list = M("blacklist")->where($map)->select();
		//var_dump($list);
		foreach($list as $k=>$v){
			//echo $v["mac"]."<br />";
			$mac = $v["mac"];
			$end = time();
			$start = ($end - 24*60*60)*1000;
			$end=$end*1000;
			
			$time = time();
			$times = $this->getbikes_t($mac,$start,$end,$id);
			//$last_dwz_info_id = getbikes_l();
			var_dump($times);
			exit;
		}
	}
	
	//分析详情
	public function analy_detail(){
		echo "这个是分析详情页面";
	}
	
	
	/**
	 * 添加菜单
	 */
	public function add(){
		//根据id查询车位的车辆，然后依次导入到数据库表
		$id = $_REQUEST['info_id'];
		$company = $_REQUEST['company'];
		if($id == ''){
			$this->error("参数错误");
		}
		//当为全部的时候就全部导入
		//否则根据公司名称计算出条件
		if($company=="全部"){
			//$query_company = "name:*";
			$list = M("bike_company")->select();
			$arrr = array();
			foreach($list as $k=>$v){
				$v['keyword'];
				$arr = explode("|",$v['keyword']);
				foreach($arr as $kk=>$vv){
					$arrr[]="name:".$vv."*";
				}
			}
			//var_dump($arrr);
			$query_company = implode(" ",$arrr);
			//echo $query_company;
			//exit;
		}else{
			//var_dump($id);
			//var_dump($company);
			$map_company['title']=array('eq',$company);
			$c = M("bike_company")->where($map_company)->find();
			//var_dump($c['keyword']);
			$arr = explode("|",$c['keyword']);
			foreach($arr as $k=>$v){
				$arr[$k]="name:".$v."*";
			}
			$query_company = implode(" ",$arr);
		}
		
		//echo $query_company;
		
		
		$results = $this->getbikes_d($id,0,$query_company);
		//exit;
		//根据返回的记录数量，分批次插入到数据库中 ， 每页是500条记录
		$page = ceil($results["hits"]["total"]/500);
		//var_dump($page);
		$from = 0;
		for($i=0;$i<=$page;$i++){
			$from = $i*500;
			$results = $this->getbikes_d($id,$from,$query_company);
			$list = $results["hits"]["hits"];
			foreach($list as $k=>$v){
				$name = $v['_source']["name"];
				$mac = $v['_source']["mac"];
				$time=time();
				$dwz_info_id = $id;
				$Model = new \Think\Model();
				$r = $Model->query("REPLACE INTO dwz_blacklist VALUES ('', '".$name."', '".$mac."','".$time."','".$dwz_info_id."')");
			}
		}
		
		$this->success("添加成功");
		
	}
	/**
	 * 修改菜单
	 */
	public function edit(){
		$id = $_REQUEST['info_id'];
		$company = $_REQUEST['company'];
		if($id == ''){
			$this->error("参数错误");
		}
		//var_dump($company);
		
		$map_company['title']=array('eq',$company);
		$c = M("bike_company")->where($map_company)->find();
		//当为全部的时候就删除全部
		//否则结合公司删除
		if($company=="全部"){
			$Model = new \Think\Model();
			$r = $Model->query("DELETE FROM dwz_blacklist WHERE dwz_info_id = '".$id."'");
			$this->success("删除成功");		
		}else{
			$arr = explode("|",$c['keyword']);
			foreach($arr as $k=>$v){
				$like = $v."%";
				$Model = new \Think\Model();
				$sql = "DELETE FROM dwz_blacklist WHERE dwz_info_id = '".$id."' and name like $like";
				echo $sql;
				//$r = $Model->query("DELETE FROM dwz_blacklist WHERE dwz_info_id = '".$id."' and name like $like");				
			}
			$this->success("删除成功");		
		}
		
		exit;
		
		
		/*$results = $this->getbikes_d($id,0);
		//将该车位下的车辆删除
		//根据返回的记录数量，分批次插入到数据库中 ， 每页是500条记录
		$page = ceil($results["hits"]["total"]/500);
		//var_dump($page);
		$from = 0;
		for($i=0;$i<=$page;$i++){
			$from = $i*500;
			$results = $this->getbikes_d($id,$from);
			$list = $results["hits"]["hits"];
			foreach($list as $k=>$v){
				//$name = $v['_source']["name"];
				$mac = $v['_source']["mac"];
				//$time=time();
				//$dwz_info_id = $id;
				$Model = new \Think\Model();
				$r = $Model->query("DELETE FROM dwz_blacklist WHERE mac = '".$mac."'");
			}
		}*/
	}
	//获取到时间 和 点 
	//通过点获取到经纬度
	//然后显示在地图上
	public function detail(){
		$mac = $_REQUEST["mac"];
		$this->assign("mac",$mac);
		$this->display();
	}
	public function ajax_detail(){
		$mac = $_REQUEST['mac'];
		if($mac == ""){
			exit(json_encode(array("error_code"=>1,"error_reason"=>"请输入mac地址")));
		}
		//根据mac 从blacklist获取到相关的记录
		$map['mac']=array("eq",$mac);
		$item = M("blacklist")->where($map)->find();
		if($item==null){
			exit(json_encode(array("error_code"=>3,"error_reason"=>"非黑名单车辆")));
		}
		
		//从es获取到想关的记录
		//var_dump($item);
		$start=$item["time"]*1000;
		$end = time()*1000;
		$mac=$item["mac"];
		
		$redis = new \Redis();
		$redis->connect('127.0.0.1', 6379);
		
			$from = 0;
			$size = 100;
			//获取到在哪几个车位获取到的次数
			$results = $this->getdetail($mac,$start,$end,$from,$size);
			
			$last_end = time();
			$last_start = $last_end - 15*60;
			$last_start = $last_start*1000;
			$last_end = $last_end*1000;
			
			$last = $this->getlast($mac,$last_start,$last_end);
			//var_dump($results["hits"]["total"]);
			
			if($results["hits"]["total"]==0){
				exit(json_encode(array("error_code"=>2,"error_reason"=>"暂时无记录")));
			}
			
			
			$list = $results["aggregations"][3]["buckets"];
			$list_map=array();
			foreach($list as $k=>$v){
				//var_dump($v['_source']["dwz_info_id"]);
				$one["id"]=$v["key"];
				$one["times"]=$v["doc_count"];
				$one["type"]="history";
				$one["lng"]=$redis->hget("dwz_info:".$v['key'],"lng");
				$one["lat"]=$redis->hget("dwz_info:".$v['key'],"lat");
				$one["title"]=$redis->hget("dwz_info:".$v['key'],"title");
				$list_map[]=$one;
			}
			//最后活动的地点
			//var_dump($last["hits"]["hits"][0]["_source"]["dwz_info_id"]);
			//当最后15分钟还在的时候就显示 如果不在就不现实
				$one["id"]=$last["hits"]["hits"][0]["_source"]["dwz_info_id"];
				if($one["id"]!=null){				
					$one["times"]=1;
					$one["type"]="last";
					$one["lng"]=$redis->hget("dwz_info:".$last["hits"]["hits"][0]["_source"]["dwz_info_id"],"lng");
					$one["lat"]=$redis->hget("dwz_info:".$last["hits"]["hits"][0]["_source"]["dwz_info_id"],"lat");
					$one["title"]=$redis->hget("dwz_info:".$last["hits"]["hits"][0]["_source"]["dwz_info_id"],"title");
					$list_map[]=$one;	
				}
			
			//释放的地点
			//var_dump($item["id"]);
				$one["id"]=$item["dwz_info_id"];
				$one["times"]=1;
				$one["type"]="first";
				$one["lng"]=$redis->hget("dwz_info:".$item["dwz_info_id"],"lng");
				$one["lat"]=$redis->hget("dwz_info:".$item["dwz_info_id"],"lat");
				$one["title"]=$redis->hget("dwz_info:".$item["dwz_info_id"],"title");
				$list_map[]=$one;
			
			
		//var_dump($list_map);
		exit(json_encode(array("error_code"=>0,"error_reason"=>"获取成功","results"=>$list_map)));
	}
	
	//嵌入的iframe页面
	public function detail_iframe(){
		$this->display();
	}
	
	private function getdetail($mac,$start,$end,$from,$size){
		$lpath =  THINK_PATH.'Library/Vendor/vendor/autoload.php';
		require $lpath;
		//$hosts = [
	//			'dododo.shop:9200',         // IP + Port
		//];
		
		$hosts = [
		'116.62.171.54:8081',         // IP + Port
		];
		$client = \Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build();
		
		//var_dump($mac);
		//获取es最后更新的时间,在更新的时候使用

			$json = '{
				  "size": 0,
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
				  },
				  "_source": {
					"excludes": []
				  },
				  "aggs": {
					"3": {
					  "terms": {
						"field": "dwz_info_id",
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
				'type' => 'dbs_realtime',
				'body' => $json
		];
		
		$results = $client->search($params);
		//var_dump($results);
		return $results;
	}
	
	//
	private function getlast($mac,$start,$end){
		$lpath =  THINK_PATH.'Library/Vendor/vendor/autoload.php';
		require $lpath;
		//$hosts = [
	//			'dododo.shop:9200',         // IP + Port
		//];
		
		$hosts = [
		'116.62.171.54:8081',         // IP + Port
		];
		$client = \Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build();
		
		//var_dump($mac);
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
							"query": "'.$mac.'",
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
		//var_dump($results);
		return $results;
	}
	
	public function ajax_timelist(){
		
	}

	private function timelist(){
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
							"query": "dwz_info_id:5915 bikes:EAF73FD7F4AB",
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
							  "gte": 1514526792087,
							  "lte": 1514527692087,
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
		//var_dump($results);
		return $results;
	}
	
	//根据车位编号获取数据
	private function getbikes_d($dwz_info_id,$from,$company){
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
			  "version": true,
			  "from":'.$from.',
			  "size":500,
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
						"query": "'.$company.'",
						"analyze_wildcard": true
					  }
					},
					{
					  "query_string": {
						"query": "'.$dwz_info_id.'",
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
		//var_dump($results);
		return $results;
	}
	
	
	//根据mac 和 时间 地区 统计次数 dbs_realtime
	private function getbikes_t($mac,$start,$end,$dwz_info_id){
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
							"query": "'.$mac.'"
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
		//var_dump($results['hits']['total']);
		return $results;
	}
	
	//根据mac 和 时间 地区 统计次数 dbs_realtime
	private function getbikes_l($dwz_info_id,$from){
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
							"query": "B4A82804549C",
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
							  "gte": 1513756630549,
							  "lte": 1514361430549,
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
		return $results;
	}

}
