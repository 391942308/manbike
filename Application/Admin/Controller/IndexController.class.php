<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends CommonController {
    public function _initialize(){
		parent::_initialize();
   }
	
	public function db(){
        $list = M("info")->select();
		//var_dump($list);
    }
	
	public function index(){
		date_default_timezone_set('Asia/Shanghai'); 
		//var_dump($res_es);
		
		$gno = M("auth_group")->count();
		$this->assign('gno',$gno);
		
		$uno = M("auth_user")->count();
		$this->assign('uno',$uno);
		
		$ino = M("info")->count();
		$this->assign('ino',$ino);
		
		$bcno = M("bike_company")->count();
		$this->assign('bcno',$bcno);
		
		
		$uid = $_SESSION['auth']['id'];
			//获取每家公司的单车数量
			$list = M("bike_company")->select();
			$this->assign('list1',$list);
			
			//非管理员，根据行政级别进行过滤
            //echo "x";
			
			//根据行政级别，做相应的过滤
			$province = $_SESSION['auth']['province'];
			$city = $_SESSION['auth']['city'];
			$area = $_SESSION['auth']['area'];
			
			$sql_area_count = 'SELECT COUNT(DISTINCT area) as count FROM dwz_info';
				$count = M()->query($sql_area_count);
				$this->assign('ibno',$count[0]['count']);
				
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
				$this->assign('ibno',1);
				$this->assign('uno',1);
				$this->assign('gno',1);
				
				$sql_area_count = "SELECT COUNT(*) as count FROM dwz_info where area = '$area' and status=0 ";
				$count = M()->query($sql_area_count);
				$this->assign('ino',$count[0]['count']);

				$sql .= " and province = '$province' ";
				$sql .= " and city = '$city' ";
				$sql .= " and area = '$area' ";
				$where["province"] = $_SESSION['auth']['province'];
				$where["city"] = $_SESSION['auth']['city'];
				$where["area"] = $_SESSION['auth']['area'];
			}
			
			$res_es = $this->getbikes_ids($province,$city,$area);
			$buckets = $res_es['aggregations'][3]['buckets'];
			
			//var_dump($res_es['hits']['total']);
			foreach($buckets as $k => $v){
				$arr1[]=$v['key'].'('.$v['doc_count'].')';
				$temp['name']=$v['key'].'('.$v['doc_count'].')';
				$temp['value']=$v['doc_count'];
				$arr2[]=(object)$temp;
				if($v["key"] == "其他"){
					$arr_color[] = 'rgba(193,35,43,1)';
				}else{
					$data["title"] = $v["key"];
					$arr_color[] = M('bike_company')->where($data)->getField('color');
				}
			}
			
//			var_dump($arr1);
//			var_dump($arr2);
			$str_color = json_encode($arr_color);
			$str1 = json_encode($arr1);
			$str2 = json_encode($arr2);
			$this->assign('str_color',$str_color);
			$this->assign('length',$length);
			$this->assign('str1',$str1);
			//var_dump($str2);
			$this->assign('str2',$str2);
			
			$this->assign('bno',$res_es['hits']['total']);
			
			
			//一个星期 新增单车数量 默认为7天
			$day=$_REQUEST['day'];
			
			if($day==''){
				$day=7;
			}
			$this->assign('day',$day);
			//var_dump($day);
			//$end = $t0 = strtotime(date('Y-m-d'));
			$end = $t0 = time();
			//var_dump($end);
			$seconds=3600*24*$day;
			$start=$t0 - $seconds;
			$start=$start*1000;
			$end=$end*1000;
			
			$buckets = $this->oneweek_2($start,$end,$province,$city,$area);	
			//$buckets = $this->new_add($start,$end,$province,$city,$area);
			
			//var_dump($buckets);
			$arr11=array();
			$arr22=array();
			$arr33=array();
			$j = 0;
			foreach($buckets as $k=>$v){
				$arr11[$j]=$v['key'].'('.$v['doc_count'].')';
				//$arr22[$j]['name']=$v['key'].'('.$v['doc_count'].')';
				//$arr22[$j]['value']=$v['doc_count'];
				$arr22[$j]=$v['doc_count'];
				$arr33[]=$v['key'];
				$j++;
			}
			$arr_color33=array();
			//var_dump($arr33);
			foreach($arr33 as $k=>$v){
				//var_dump($v);
				if(trim($v) == "其他"){
					$arr_color33[] = 'rgba(193,35,43,1)';
				}else{
					$data33["title"] = trim($v);
					$arr_color33[] = M('bike_company')->where($data33)->getField('color');

				}
			}
			//var_dump($arr_color33);
			$str_color33 = json_encode($arr_color33);
			$this->assign('str_color33',$str_color33);
			$str11 = json_encode($arr11);
			$str22 = json_encode($arr22);
			$str33 = json_encode($arr33);
			//$this->assign('length',$length);
			$this->assign('str11',$str11);
			$this->assign('str22',$str22);
			
			
			$this->display();
    }

	
	//将区域改成 使用id查询
	private function oneweek_2($start,$end,$province="",$city="",$area=""){
		
		//根据省市区查询ids，然后es获取相关数据
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
		
		
		$lpath =  THINK_PATH.'Library/Vendor/vendor/autoload.php';
		require $lpath;
		//$hosts = [
		//'dododo.shop:9200',         // IP + Port
		//];
		$hosts = [
		'116.62.171.54:8081',      // IP + Port
		];
		
		$client = \Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build();
		//获取es最后更新的时间,在更新的时候使用
		
		//获取当前一星期的时间
		//$start = strtotime("last Monday")*1000;
		//$end = strtotime("Monday")*1000;
		//var_dump($start);
		//var_dump($end);
	
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
			'type' => 'dbs_realtime_first',
			'body' => $json
		];

		$results = $client->search($params);
		$buckets = $results['aggregations'][3]['buckets'];
		//var_dump($buckets);
		return $buckets;
	}
	private function new_add($start,$end,$province="",$city="",$area=""){
		
		//到前一天
		$buckets_1 = $this->oneweek_2(0,$start,$province,$city,$area);
		
		//全部
		$start_2 = 0;
		$end_2 = $start;
		$buckets_2 = $this->oneweek_2(0,9512114950000,$province,$city,$area);
		
		//var_dump($buckets_1);
		//var_dump($buckets_2);
		foreach($buckets_1 as $k=>$v){
			//var_dump($v);
			foreach($buckets_2 as $kk=>$vv){
				if($v['key']==$vv['key']){
					$buckets_1[$k]['doc_count']=$vv['doc_count']-$v['doc_count'];
				}
			}
		}
		return $buckets_1;
		
	}
	
	public function clear(){
		$redis = new \Redis();
		$redis->connect('116.62.171.54', 8085);
		echo "clear";
		
		$list = M("bike")->select();
		$str2 = ':';
		//echo sizeof($list);
		foreach($list as $k=>$v){
			if(strpos($v['mac'],$str2) === false){     //使用绝对等于
				//不包含
				;
			}else{
				//echo "x";
				//包含
				//$data['name']=$v['mac'];
				//M("error_bike")->add($data,array(),true);
				$redis->sadd('error_bike',$v['mac']);
			}
		}
	}
	public function clear1(){
		$redis = new \Redis();
		$redis->connect('116.62.171.54', 8085);
		$list = $redis->smembers("error_bike");
		//删除数据库bikes
		foreach($list as $k => $v){
			$map['mac']=array('eq',$v);
			//M("bike")->where($map)->delete();
		}
		//删除redis错误数据
	}
	public function clearx(){
		$redis = new \Redis();
		$redis->connect('116.62.171.54', 8085);
		//删除键
		$list = $redis->keys("infobikes:5858:*");
		foreach($list as $k => $v){
			if(strpos($v,'-') !== false || strpos($v,':') !== false){
				echo $v;
				//$redis->del($v);
			}
		}
		echo sizeof($list);
		//删除集合
		$list = $redis->smembers("infobikes:5860");
		foreach($list as $k=>$v){
			if(strpos($v,'-') !== false || strpos($v,':') !== false){
				echo $v;
				$redis->srem("infobikes:5860",$v);
			}
		}
		
		
		//$list = $redis->smembers("error_bike");
		//删除数据库bikes
		//foreach($list as $k => $v){
			//$map['mac']=array('eq',$v);
			//M("bike")->where($map)->delete();
		//}
		//删除redis错误数据
		
		
	}
	public function analysis(){
		//echo 'analysis';
		
		$cno = 3333;
		$map['dkey']=array('eq','ruku_nu');
		$item = M("temp_value")->where($map)->find();
		$bno = $item['dvalue'];
		$ano = M("bike_sub_realtime")->count();
		$cno = $ano - $bno;
		$this->assign('ano',$ano);
		$this->assign('bno',$bno);
		$this->assign('cno',$cno);
		$this->display();
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
	
}