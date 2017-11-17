<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends CommonController {
    public function _initialize(){
		parent::_initialize();
		//session id
		//check auth
        /*$Auth = new \Think\Auth();
		$ruleName = MODULE_NAME . '/' . ACTION_NAME; //规则唯一标识
		//var_dump($ruleName);
		$userId = 1; //用户ID
		$type = 1; //分类-具体是什么没搞懂，默认为1
		$mode='url'; //执行check的模式
		$relation = 'and'; //'or' 表示满足任一条规则即通过验证; 'and'则表示需满足所有规则才能通过验证
		if($Auth->check($ruleName,$userId,$type,$mode,$relation)){
		$dietxt = '认证：通过';
		}else{
		$dietxt = '认证：失败';
		}*/
		//var_dump($dietxt);
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
		//暂时先将超级管理员区别开
		if($uid!=1){
			//获取每家公司的单车数量
			$list = M("bike_company")->select();
			$this->assign('list1',$list);
			
			//非管理员，根据行政级别进行过滤
            //echo "x";
			
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
			
			$res_es = $this->getbikes_pca($province,$city,$area);
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
			$end = $t0 = strtotime(date('Y-m-d'));
			$seconds=3600*24*$day;
			$start=$t0 - $seconds;
			$start=$start*1000;
			$end=$end*1000;
			
			$buckets = $this->oneweek_1($start,$end,$area);
			//var_dump($buckets);
			$arr11=array();
			$arr22=array();
			$j = 0;
			foreach($buckets as $k=>$v){
				$arr11[$j]=$v['key'].'('.$v['doc_count'].')';
				//$arr22[$j]['name']=$v['key'].'('.$v['doc_count'].')';
				//$arr22[$j]['value']=$v['doc_count'];
				$arr22[$j]=$v['doc_count'];
				$j++;
			}
			
			$str11 = json_encode($arr11);
			$str22 = json_encode($arr22);
			//$this->assign('length',$length);
			$this->assign('str11',$str11);
			$this->assign('str22',$str22);
			
			$this->display();
        }else{
			
			$sql_area_count = 'SELECT COUNT(DISTINCT area) as count FROM dwz_info';
			$count = M()->query($sql_area_count);
			$this->assign('ibno',$count[0]['count']);
			//超级管理员暂时去数据库获取
			//echo "xx";
			
			$data=array();
			//获取每家公司的单车数量
			$list = M("bike_company")->select();
			$this->assign('list1',$list);
			//var_dump($list);
			foreach($list as $k=>$v){
				$nm = $v['title'];
				//var_dump($nm);
				//模糊查询相关的记录情况
				//查询的同时，将当前时刻的数据记录到数据库表
				$arr = explode('|',$v['keyword']);
				//var_dump($arr);
				//echo sizeof($arr);
				$condition = '' ;
				foreach($arr as $k=>$v){
					if($k < sizeof($arr)-1)
						{
							
							if($v=='ofo'){
								$condition .= " name like '$v%' or ";	
							}else{
								$condition .= " name like '$v%' or ";
							}
							
							/*if($v=='' or $v=='NULL' or $v=='null'){
								$condition .= " name like '' or ";
							}else{
								$condition .= " name like '$v%' or ";	
							}*/
							
						}
					else
						{
							if($v=='ofo'){
								$condition .= " name like '$v%' ";	
							}else{
								$condition .= " name like '$v%' ";	
							}
							/*
							if($v=='' or $v=='NULL' or $v=='null'){
								$condition .= " name like '' ";
							}else{
								$condition .= " name like '$v%' ";	
							}*/
							
						}
				}
				//var_dump($condition);
				//$name = $v['title'];
				$sql="select count(*) as numbers ,' $nm ' AS name  from dwz_bike where ".$condition;
				$Model = M();
				$res = $Model->query($sql);
				//var_dump($res);
				$data[]=$res;
			}	
			//一定的时间记录一次,做数量变化统计图表
			
			
			//var_dump($data);
			$arr1=array();
			$arr2=array();
			$length=sizeof($data);
			$bnoo=0;
			foreach($data as $k => $v){
//				var_dump($v[0]['name']);
				if(trim($v[0]['name']) == "其他"){
					$arr_color[] = 'rgba(193,35,43,1)';
				}else{
					$map["title"] = trim($v[0]['name']);
					$arr_color[] = M('bike_company')->where($map)->getField('color');
				}
				$arr1[]=$v[0]['name'].'('.$v[0]['numbers'].')';
				$temp['name']=$v[0]['name'].'('.$v[0]['numbers'].')';
				$temp['value']=$v[0]['numbers'];
				$arr2[]=(object)$temp;
				$bnoo+=$v[0]['numbers'];
			}
			//$arr1[]='其他';
			
			//$temp['value']=$bno - $bnoo;
			//$temp['name']='其他'.'('.$temp['value'].')';
			//$arr2[]=(object)$temp;
			$bno = 0;
			//var_dump($arr2);
			foreach($arr2 as $k=>$v){
				$bno = $bno + $v->value;
			}
			$this->assign('bno',$bno);
			//var_dump($arr1);
			//var_dump($arr2);
			$str_color = json_encode($arr_color);
			$this->assign('str_color',$str_color);
			$str1 = json_encode($arr1);
			$str2 = json_encode($arr2);
			$this->assign('length',$length);
			$this->assign('str1',$str1);
			$this->assign('str2',$str2);
		//	var_dump($str1);
			//var_dump($str2);
			$this->assign('title','首页');
			
			
			//一个星期 新增单车数量 默认为7天
			$day=$_REQUEST['day'];
			
			if($day==''){
				$day=7;
			}
			$this->assign('day',$day);
			//var_dump($day);
			$end = $t0 = strtotime(date('Y-m-d'));
			$seconds=3600*24*$day;
			$start=$t0 - $seconds;
			$start=$start*1000;
			$end=$end*1000;
			//var_dump($start);
			//var_dump($end);
			//$start = strtotime("last Monday")*1000;
			//$end = strtotime("Monday")*1000;
			//$this->assign('start',date("Y/m/d",strtotime("last Monday")));
			//$this->assign('end',date("Y/m/d",strtotime("Monday")));
			//var_dump(date("Y-m-d H:i:s",strtotime("last Monday")));
			//var_dump(date("Y-m-d H:i:s",strtotime("Monday")));
			
			$buckets = $this->oneweek($start,$end);
			//var_dump($buckets);
			$arr11=array();
			$arr22=array();
			$j = 0;
			foreach($buckets as $k=>$v){
				$arr11[$j]=$v['key'].'('.$v['doc_count'].')';
				//$arr22[$j]['name']=$v['key'].'('.$v['doc_count'].')';
				//$arr22[$j]['value']=$v['doc_count'];
				$arr22[$j]=$v['doc_count'];
				$j++;
			}

			$str11 = json_encode($arr11);
			$str22 = json_encode($arr22);
			//$this->assign('length',$length);
			$this->assign('str11',$str11);
			$this->assign('str22',$str22);
			
			$this->display();
		}
    }
	private function oneweek($start,$end){
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
				  "match_all": {}
				},
				{
				  "match_phrase": {
					"_type": {
					  "query": "dbs_realtime_first"
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
					"_type": {
					  "query": "dbs_realtime_last"
					}
				  }
				},
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
	private function oneweek_1($start,$end,$area){
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
				  "match_all": {}
				},
				{
				  "match_phrase": {
					"_type": {
					  "query": "dbs_realtime_first"
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
				},
				{
				  "match_phrase": {
					"area": {
					  "query": "'.$area.'"
					}
				  }
				}
			  ],
			  "must_not": [
				{
				  "match_phrase": {
					"_type": {
					  "query": "dbs_realtime_last"
					}
				  }
				},
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
}