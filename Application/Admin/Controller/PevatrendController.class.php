<?php
namespace Admin\Controller;
use Think\Controller;
class PevatrendController extends CommonController {
	public function _initialize(){
		parent::_initialize();
	}
	public function index(){
		$uid = $_SESSION['auth']['id'];
		$this->assign("uid", $uid);
//		var_dump($this->chewei_sort('5893','0','9507697943118'));
		$did_arr = M('info')->select();
		$this->assign("did_arr",$did_arr);
		if($_REQUEST){
			$start = strtotime($_REQUEST["start"]);
			$end = strtotime($_REQUEST["end"]);
			$start2 = $start*1000;
			$end2 = $end*1000;
			$this->assign("start",$_REQUEST["start"]);
			$this->assign("end",$_REQUEST["end"]);
			$id = $_REQUEST["dwz_info_id"];
			$this->assign("dwz_info_id",$id);
			$j = 24*60*60; //一天的秒数
//			M('peva')->where('1')->delete();
			$ts = array();
			$max = array();
			$min = array();
			for ($i=$start; $i < $end ; $i+=$j) {
				$time1 = date('Y-m-d',$i);//格式化
				$time2 = date('Y-m-d',$i+$j);
				$ts1 = $i*1000;
				$ts2 = ($i+$j)*1000;
//				var_dump($time1);
//				var_dump($time2);
//				var_dump("<br>");
				$sort1 =  'desc';
				$sort2 =  'asc';
//				$data["ts"] = $time2;
//				$data["maxnum"] = $this->chewei_sort($id,$ts1,$ts2,$sort1);
//				$data["minnum"] = $this->chewei_sort($id,$ts1,$ts2,$sort2);
//				$res = M("peva")->add($data);
//				var_dump($maxnum);
//				var_dump($minnum);
//				var_dump("<br>");
				$ts[] = $time2;
				$max[] = $this->chewei_sort($id,$ts1,$ts2,$sort1);
				$min[] = $this->chewei_sort($id,$ts1,$ts2,$sort2);
			}
			$j_ts = json_encode($ts);
			$j_max = json_encode($max);
			$j_min = json_encode($min);
//			var_dump($ts);
//			var_dump($max);
//			var_dump($min);
			$this->assign("j_ts",$j_ts);
			$this->assign("j_max",$j_max);
			$this->assign("j_min",$j_min);
		}
		$this->display();
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
					"order": "'.$sort.'",
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