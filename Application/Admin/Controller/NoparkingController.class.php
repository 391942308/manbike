<?php
namespace Admin\Controller;
use Think\Controller;
class NoparkingController extends CommonController {
	public function _initialize(){
		parent::_initialize();
	}
	public function index() {
		
		//var_dump($_REQUEST);
		$start = $_REQUEST['start'];
		$end = $_REQUEST['end'];
		if($start=='' and $end == ''){
			$start=mktime(0,0,0,date('m'),date('d')-7,date('Y'));
			$end=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;	
		}else{
			$start = strtotime($start);
			$end = strtotime($end);
		}
		
		$this->assign('start',date('Y-m-d H:i:s', $start));
		$this->assign('end',date('Y-m-d H:i:s', $end));
		//连接本地的 Redis 服务
		$redis = new\ Redis();
		$redis->connect('116.62.171.54', 8085);
		//查询出所有违停车位的信息
		$info = M('info')->field("id,title,province,city,area,usable_num,la,lb,lc")->select();
		
		//$start=mktime(0,0,0,date('m'),date('d')-7,date('Y'));
		//$end=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
		
		//echo $start;
		//echo $end;
		
		$start = $start *1000;
		$end = $end *1000;
		//echo $start;
		//echo $end;
		//exit;
		$list = M("info_block")->select();
		$infolist = array();
		$i=0;
		foreach($list as $k=>$v){
			$arr = explode('|',$v['content']);
			foreach($arr as $kk=>$vv){
				$min=$this->chewei_sort_min($vv,$start,$end);
				$max=$this->chewei_sort($vv,$start,$end,'desc');
				$map['id']=array('eq',$vv);
				$one = M("info")->where($map)->find();
				if($one != null){
					//$list[$k][$vv]['title']=$one['title'];
					$infolist[$i]['id'] = $v['id'];
					$infolist[$i]['btitle'] = $v['title'];
					$infolist[$i]['title'] = $one['id'].'&nbsp;&nbsp;'.$one['title'];
					$infolist[$i]['max'] = $max;
					$infolist[$i]['min'] = $min;
					$i++;
				}
				
			}
		}
		//var_dump($infolist);
		$this->assign('infolist',$infolist);
		
		//$this->assign('info',$info);
		$this->display();
	}
	
	
	private function chewei_sort_min($id,$start,$end){
		$lpath =  THINK_PATH.'Library/Vendor/vendor/autoload.php';
		require $lpath;
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
				  "storage_num": {
					"order": "asc",
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
						"storage_num": {
						  "query": 0
						}
					  }
					},
					{
					  "match_phrase": {
						"storage_num": {
						  "query": 1
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
		if($results['hits']['total'] == 0) return 0;
		
		$bikes  = $results['hits']['hits'][0]['_source']['bikes'];
		//获取到的记录做解析
		$bikes = json_decode($bikes);
		
		
		return $results['hits']['hits'][0]['_source']['storage_num'];
	}
	
	
	private function chewei_sort($id,$start,$end,$sort){
		$lpath =  THINK_PATH.'Library/Vendor/vendor/autoload.php';
		require $lpath;
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
				  "storage_num": {
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
						"query": "dwz_info_id:'.$id.'"
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
					  "range": {
						"storage_num": {
						  "lte": 1000
						}
					  }
					}
				  ],
				  "must_not": [
					{
					  "match_phrase": {
						"storage_num": {
						  "query": 0
						}
					  }
					},
					{
					  "match_phrase": {
						"storage_num": {
						  "query": 1
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
		
		if($results['hits']['total'] == 0) return 0;
		
		$bikes  = $results['hits']['hits'][0]['_source']['bikes'];
		//获取到的记录做解析
		$bikes = json_decode($bikes);
		
		
		return $results['hits']['hits'][0]['_source']['storage_num'];
	}


}