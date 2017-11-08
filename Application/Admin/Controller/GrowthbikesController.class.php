<?php
namespace Admin\Controller;
use Think\Controller;
class GrowthbikesController extends CommonController {
	public function _initialize(){
		parent::_initialize();
	}
	public function index() {
		if($_GET){
			$tj = $_GET["tj"];
			if($tj==1){
				$this->assign("tj",$tj);
				if(isset($_REQUEST["dwz_info_id"]) && $_REQUEST["dwz_info_id"]!=''){
					$dwz_info_id = $_REQUEST["dwz_info_id"];
					$this->assign("dwz_info_id",$dwz_info_id);
				}
				if(isset($_REQUEST["start"]) && $_REQUEST["start"]!=''){
					$start = strtotime($_REQUEST["start"]) * 1000;
					$this->assign("start",$_REQUEST["start"]);
				}
				if(isset($_REQUEST["end"]) && $_REQUEST["end"]!=''){
					$end = strtotime($_REQUEST["end"]) * 1000;
					$this->assign("end",$_REQUEST["end"]);
				}
				if($_REQUEST["dwz_info_id"]!=''&& $_REQUEST["start"]=='' && $_REQUEST["end"]==''){
					$start = 0;
					$end = 9507697943118;
					$allbikes_trend = $this->getbikes_one_all($dwz_info_id,$start,$end);
				}else if($_REQUEST["dwz_info_id"]!=''&& $_REQUEST["start"]=='' && $_REQUEST["end"]!=''){
					$start = 0;
					$allbikes_trend = $this->getbikes_one_all($dwz_info_id,$start,$end);
				}else if($_REQUEST["dwz_info_id"]!=''&& $_REQUEST["start"]!='' && $_REQUEST["end"]==''){
					$end = 9507697943118;
					$allbikes_trend = $this->getbikes_one_all($dwz_info_id,$start,$end);
				}else if($_REQUEST["dwz_info_id"]==''&& $_REQUEST["start"]!='' && $_REQUEST["end"]!=''){
					$allbikes_trend = $this->getbikes_all($start,$end);
				}else if($_REQUEST["dwz_info_id"]==''&& $_REQUEST["start"]=='' && $_REQUEST["end"]!=''){
					$start = 0;
					$allbikes_trend = $this->getbikes_all($start,$end);
				}else if($_REQUEST["dwz_info_id"]==''&& $_REQUEST["start"]!='' && $_REQUEST["end"]==''){
					$end = 9507697943118;
					$allbikes_trend = $this->getbikes_all($start,$end);
				}else if($_REQUEST["dwz_info_id"]!=''&& $_REQUEST["start"]!='' && $_REQUEST["end"]!=''){
					$allbikes_trend = $this->getbikes_one_all($dwz_info_id,$start,$end);
				}else if($_REQUEST["dwz_info_id"]==''&& $_REQUEST["start"]=='' && $_REQUEST["end"]==''){
					$start = 0;
					$end = 9507697943118;
					$allbikes_trend = $this->getbikes_all($start,$end);
				}

				$buckets = $allbikes_trend["aggregations"][2]["buckets"];
				$arr_bikes_num = array();
				$arr_bikes_ts = array();
				foreach($buckets as $k=>$v){
					$arr_bikes_num[$k] = $v["doc_count"];
					$arr_bikes_ts[$k] = date('Y-m-d H:i:s',strtotime($v["key_as_string"]));
				}
			}else if($tj==2){
				$this->assign("tj",$tj);
				if(isset($_REQUEST["area"]) && $_REQUEST["area"]!=''){
					$area = $_REQUEST["area"];
					$this->assign("area",$area);
				}
				if(isset($_REQUEST["start"]) && $_REQUEST["start"]!=''){
					$start = strtotime($_REQUEST["start"]) * 1000;
					$this->assign("start",$_REQUEST["start"]);
				}
				if(isset($_REQUEST["end"]) && $_REQUEST["end"]!=''){
					$end = strtotime($_REQUEST["end"]) * 1000;
					$this->assign("end",$_REQUEST["end"]);
				}
				if($_REQUEST["area"]!=''&& $_REQUEST["start"]=='' && $_REQUEST["end"]==''){
					$start = 0;
					$end = 9507697943118;
					$allbikes_trend = $this->getbikes_one_all_area($area,$start,$end);
				}else if($_REQUEST["area"]!=''&& $_REQUEST["start"]=='' && $_REQUEST["end"]!=''){
					$start = 0;
					$allbikes_trend = $this->getbikes_one_all_area($area,$start,$end);
				}else if($_REQUEST["area"]!=''&& $_REQUEST["start"]!='' && $_REQUEST["end"]==''){
					$end = 9507697943118;
					$allbikes_trend = $this->getbikes_one_all_area($area,$start,$end);
				}else if($_REQUEST["area"]==''&& $_REQUEST["start"]!='' && $_REQUEST["end"]!=''){
					$allbikes_trend = $this->getbikes_all($start,$end);
				}else if($_REQUEST["area"]==''&& $_REQUEST["start"]=='' && $_REQUEST["end"]!=''){
					$start = 0;
					$allbikes_trend = $this->getbikes_all($start,$end);
				}else if($_REQUEST["area"]==''&& $_REQUEST["start"]!='' && $_REQUEST["end"]==''){
					$end = 9507697943118;
					$allbikes_trend = $this->getbikes_all($start,$end);
				}else if($_REQUEST["area"]!=''&& $_REQUEST["start"]!='' && $_REQUEST["end"]!=''){
					$allbikes_trend = $this->getbikes_one_all_area($area,$start,$end);
				}else if($_REQUEST["area"]==''&& $_REQUEST["start"]=='' && $_REQUEST["end"]==''){
					$start = 0;
					$end = 9507697943118;
					$allbikes_trend = $this->getbikes_all($start,$end);
				}

				$buckets = $allbikes_trend["aggregations"][2]["buckets"];
				$arr_bikes_num = array();
				$arr_bikes_ts = array();
				foreach($buckets as $k=>$v){
					$arr_bikes_num[$k] = $v["doc_count"];
					$arr_bikes_ts[$k] = date('Y-m-d H:i:s',strtotime($v["key_as_string"]));
				}
			}
		}else{
			$start = 0;
			$end = 9507697943118;
			$allbikes_trend = $this->getbikes_all($start,$end);
			$buckets = $allbikes_trend["aggregations"][2]["buckets"];
			$arr_bikes_num = array();
			$arr_bikes_ts = array();
			foreach($buckets as $k=>$v){
				$arr_bikes_num[$k] = $v["doc_count"];
				$arr_bikes_ts[$k] = date('Y-m-d H:i:s',strtotime($v["key_as_string"]));
			}
		}


		//查询所有区块
		$blocklist = M('info_block')->select();
		$this->assign("blocklist",$blocklist);
		$str1 = json_encode($arr_bikes_num);
		$str2 = json_encode($arr_bikes_ts);
		$this->assign("str1",$str1);
		$this->assign("str2",$str2);
		$this->display();
	}
	//总的车辆增长数据
	private function getbikes_all($start,$end){
		$lpath =  THINK_PATH.'Library/Vendor/vendor/autoload.php';
		require $lpath;
		$hosts = [
				'http://116.62.171.54:8081',         // IP + Port
		];
		$client = \Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build();
		//获取es最后更新的时间,在更新的时候使用

		$json = '{
  "size": 0,
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
              "query": "dbs_realtime_first"
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
          "range": {
            "timestamp": {
              "gte": "'.$start.'",
              "lte": "'.$end.'",
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
                    "query": "dbs_realtime_first"
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
                "range": {
                  "timestamp": {
                    "gte": "'.$start.'",
                    "lte": "'.$end.'",
                    "format": "epoch_millis"
                  }
                }
              }
            ],
            "must_not": []
          }
        }
      }
    },
    "fragment_size": 2147483647
  }
}';
		$params = [
				'index' => 'bike_index_v6',
				'type' => 'dbs_realtime_first',
				'body' => $json
		];

		$results = $client->search($params);
		//$ts = $results['hits']['hits'][0]['_source']['ts'];
		//var_dump($results);
		//var_dump($ts);
		return $results;
	}
	//单个车位车辆增长数据
	private function getbikes_one_all($dwz_info_id,$start,$end){
		$lpath =  THINK_PATH.'Library/Vendor/vendor/autoload.php';
		require $lpath;
		$hosts = [
				'http://116.62.171.54:8081',         // IP + Port
		];
		$client = \Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build();
		//获取es最后更新的时间,在更新的时候使用

		$json = '{
  "size": 0,
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
              "query": "dbs_realtime_one_first"
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
              "gte": "'.$start.'",
              "lte": "'.$end.'",
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
                    "query": "dbs_realtime_one_first"
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
                     "gte": "'.$start.'",
              		"lte": "'.$end.'",
                    "format": "epoch_millis"
                  }
                }
              }
            ],
            "must_not": []
          }
        }
      }
    },
    "fragment_size": 2147483647
  }
}';
		$params = [
				'index' => 'bike_index_v6',
				'type' => 'dbs_realtime_one_first',
				'body' => $json
		];

		$results = $client->search($params);
		//$ts = $results['hits']['hits'][0]['_source']['ts'];
		//var_dump($results);
		//var_dump($ts);
		return $results;
	}
	//单个行政区车辆增长数据
	private function getbikes_one_all_area($area,$start,$end){
		$lpath =  THINK_PATH.'Library/Vendor/vendor/autoload.php';
		require $lpath;
		$hosts = [
				'http://116.62.171.54:8081',         // IP + Port
		];
		$client = \Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build();
		//获取es最后更新的时间,在更新的时候使用

		$json = '{
  "size": 0,
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
              "query": "dbs_realtime_one_first"
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
              "gte": "'.$start.'",
              "lte": "'.$end.'",
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
                    "query": "dbs_realtime_one_first"
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
                    "gte": "'.$start.'",
                    "lte": "'.$end.'",
                    "format": "epoch_millis"
                  }
                }
              }
            ],
            "must_not": []
          }
        }
      }
    },
    "fragment_size": 2147483647
  }
}';
		$params = [
				'index' => 'bike_index_v6',
				'type' => 'dbs_realtime_one_first',
				'body' => $json
		];

		$results = $client->search($params);
		//$ts = $results['hits']['hits'][0]['_source']['ts'];
		//var_dump($results);
		//var_dump($ts);
		return $results;
	}
}