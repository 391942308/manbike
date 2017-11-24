<?php
namespace Admin\Controller;
use Think\Controller;
class ChangebikesController extends CommonController {
	public function _initialize(){
		parent::_initialize();
	}
	public function index() {
		$uid = $_SESSION['auth']['id'];
		$this->assign("uid",$uid);
		if($uid!=1){
			$province2 = $_SESSION['auth']['province'];
			$data["province"]=$province2;
			$city2 = $_SESSION['auth']['city'];
			$data["city"]=$city2;
			$area2 = $_SESSION['auth']['area'];
			$data["area"]=$area2;
			$this->assign("province2",$province2);
			$this->assign("city2",$city2);
			$this->assign("area2",$area2);
			$arr_id = M('info')->where($data)->select();
		}else{
			$arr_id = M('info')->select();
		}
		$this->assign("arr_id",$arr_id);
		if($_GET){
			$tj = $_GET["tj"];
			$this->assign("tj",$tj);
			if($uid!=1){
				if($_REQUEST["area"]!=$area2 && $_REQUEST["area"]!=''){
					echo "<script>alert('选择的行政区不正确，您只能看".$area2."的数据！');window.history.back();</script>";
				}
				if(isset($_REQUEST["dwz_info_id"]) && $_REQUEST["dwz_info_id"]!='' && $_REQUEST["dwz_info_id"]!='请选择车位'){
					$dwz_info_id = $_REQUEST["dwz_info_id"];
					$arr = array();
					foreach ($arr_id as $k=>$v) {
						if($dwz_info_id==$v["id"]){
							$arr[]=1;
						}
					}
					if(count($arr)==0){
						echo "<script>alert('选择的车位不正确，您只能看".$area2."内车位的数据！');window.history.back();</script>";
					}
				}
			}
			if($tj==1){
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
					if($_REQUEST["dwz_info_id"] == '请选择车位'){
						if($uid==1){
							$allbikes_trend = $this->getbikes_all($start,$end);
						}else{
							$allbikes_trend = $this->getbikes_one_all_area($area2,$start,$end);
						}
					}else{
						$allbikes_trend = $this->getbikes_one_all($dwz_info_id,$start,$end);
					}
				}else if($_REQUEST["dwz_info_id"]!=''&& $_REQUEST["start"]=='' && $_REQUEST["end"]!=''){
					$start = 0;
					if($_REQUEST["dwz_info_id"]=='请选择车位'){
						$allbikes_trend = $this->getbikes_all($start,$end);
					}else{
						$allbikes_trend = $this->getbikes_one_all($dwz_info_id,$start,$end);
					}
				}else if($_REQUEST["dwz_info_id"]!=''&& $_REQUEST["start"]!='' && $_REQUEST["end"]==''){
					$end = 9507697943118;
					if($_REQUEST["dwz_info_id"]=='请选择车位'){
						$allbikes_trend = $this->getbikes_all($start,$end);
					}else{
						$allbikes_trend = $this->getbikes_one_all($dwz_info_id,$start,$end);
					}
				}else if($_REQUEST["dwz_info_id"]==''&& $_REQUEST["start"]!='' && $_REQUEST["end"]!=''){
					$start = 0;
					$end = 9507697943118;
					$allbikes_trend = $this->getbikes_all($start,$end);
				}else if($_REQUEST["dwz_info_id"]==''&& $_REQUEST["start"]=='' && $_REQUEST["end"]!=''){
					$start = 0;
					$allbikes_trend = $this->getbikes_all($start,$end);
				}else if($_REQUEST["dwz_info_id"]==''&& $_REQUEST["start"]!='' && $_REQUEST["end"]==''){
					$end = 9507697943118;
					$allbikes_trend = $this->getbikes_all($start,$end);
				}else if($_REQUEST["dwz_info_id"]!=''&& $_REQUEST["start"]!='' && $_REQUEST["end"]!=''){
					if($_REQUEST["dwz_info_id"]=='请选择车位'){
						$allbikes_trend = $this->getbikes_all($start,$end);
					}else{
						$allbikes_trend = $this->getbikes_one_all($dwz_info_id,$start,$end);
					}
				}else if($_REQUEST["dwz_info_id"]==''&& $_REQUEST["start"]=='' && $_REQUEST["end"]==''){
					$start = 0;
					$end = 9507697943118;
					if($uid==1){
						$allbikes_trend = $this->getbikes_all($start,$end);
					}else{
						$allbikes_trend = $this->getbikes_one_all_area($area2,$start,$end);
					}
				}
			}else if($tj==2){
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
					if($uid==1){
						$allbikes_trend = $this->getbikes_all($start,$end);
					}else{
						$allbikes_trend = $this->getbikes_one_all_area($area2,$start,$end);
					}
				}
			}
		}else{
			$start = 0;
			$end = 9507697943118;
			if($uid==1){
				$allbikes_trend = $this->getbikes_all($start,$end);
			}else{
				$allbikes_trend = $this->getbikes_one_all_area($area2,$start,$end);
			}

		}
		$buckets = $allbikes_trend["aggregations"][2]["buckets"];
		$kq = array();
		$mb = array();
		$xm = array();
		$ofo = array();
		$hb = array();
		$size = sizeof($buckets);
		//var_dump($buckets);
		//exit;

		foreach ($buckets as $k=>$v) {
			if(sizeof($v[3]['buckets'])==5){
				$init=$v[3]['buckets'];
				break;
			}
		}
		foreach ($init as $k=>$v) {
			$init[$k]['doc_count']=0;
		}
		//var_dump($init);
		//exit;

		foreach($buckets as $k=>$v){
			//var_dump($v[3]['buckets']);
			if(sizeof($v[3]['buckets'])<5){
				//将有的赋值上去
				foreach($v[3]['buckets'] as $kk=>$vv){
//					var_dump($vv);
					//if($should[$k][]){
					//根据key获取到对应的数组下标
					//}
					foreach($init as $kkk=>$vvv){
						if($vvv['key']==$vv['key']){
							$init[$kkk]=$vv;
						}
					}
				}
				//var_dump($init);
				$buckets[$k][3]['buckets']=$init;
			}
		}
//		var_dump($buckets);
		//exit;

		foreach($buckets as $k=>$v){
			//要追加的数组
			$arr1["key"] = "酷骑";
			$arr1["doc_count"] = 0;
			$arr2["key"] = "摩拜";
			$arr2["doc_count"] = 0;
			$arr3["key"] = "小鸣单车";
			$arr3["doc_count"] = 0;
			$arr4["key"] = "ofo";
			$arr4["doc_count"] = 0;
			$arr5["key"] = "HelloBike";
			$arr5["doc_count"] = 0;
//			var_dump($v[3]["buckets"]);
//			var_dump("<br>");
//			var_dump("<br>");
//			var_dump("<br>");
			foreach($v[3]["buckets"] as $k2=>$v2){
				if($v2["key"]=='酷骑'){
					$kq[] = $v2["doc_count"];
					$kq_color = M('bike_company')->where("title='酷骑'")->getField('color');
				}else if($v2["key"]=='摩拜'){
					$mb[] = $v2["doc_count"];
					$mb_color = M('bike_company')->where("title='摩拜'")->getField('color');
				}else if($v2["key"]=='小鸣单车'){
					$xm[] = $v2["doc_count"];
					$xm_color = M('bike_company')->where("title='小鸣单车'")->getField('color');
				}else if($v2["key"]=='ofo'){
					$ofo[] = $v2["doc_count"];
					$ofo_color = M('bike_company')->where("title='ofo'")->getField('color');
				}else if($v2["key"]=='HelloBike'){
					$hb[] = $v2["doc_count"];
					$hb_color = M('bike_company')->where("title='HelloBike'")->getField('color');
				}
				$ts[$k] = date('Y-m-d H:i:s',strtotime($v["key_as_string"]));
			}
		}
		for($i=0;$i<sizeof($mb);$i++){
			$sum[] = $kq[$i] + $mb[$i] + $xm[$i]+ $ofo[$i]+ $hb[$i];
		}
		$j_kq = json_encode($kq);
		$j_kq_color = json_encode($kq_color);
		$j_mb = json_encode($mb);
		$j_mb_color = json_encode($mb_color);
		$j_xm = json_encode($xm);
		$j_xm_color = json_encode($xm_color);
		$j_ofo = json_encode($ofo);
		$j_ofo_color = json_encode($ofo_color);
		$j_hb = json_encode($hb);
		$j_hb_color = json_encode($hb_color);
		$j_ts = json_encode($ts);
		$j_sum = json_encode($sum);
		$this->assign("j_kq",$j_kq);
		$this->assign("j_kq_color",$j_kq_color);
		$this->assign("j_mb",$j_mb);
		$this->assign("j_mb_color",$j_mb_color);
		$this->assign("j_xm",$j_xm);
		$this->assign("j_xm_color",$j_xm_color);
		$this->assign("j_ofo",$j_ofo);
		$this->assign("j_ofo_color",$j_ofo_color);
		$this->assign("j_hb",$j_hb);
		$this->assign("j_hb_color",$j_hb_color);
		$this->assign("j_ts",$j_ts);
		$this->assign("j_sum",$j_sum);

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
        "interval": "1d",
        "time_zone": "Asia/Shanghai",
        "min_doc_count": 1
      },
      "aggs": {
        "3": {
          "terms": {
            "field": "company",
            "size": 10,
            "order": {
              "_term": "desc"
            }
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
          "range": {
            "timestamp": {
              "gte": "'.$start.'",
              "lte": "'.$end.'",
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
                "range": {
                  "timestamp": {
                    "gte": "'.$start.'",
                    "lte": "'.$end.'",
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
        "interval": "1d",
        "time_zone": "Asia/Shanghai",
        "min_doc_count": 1
      },
      "aggs": {
        "3": {
          "terms": {
            "field": "company",
            "size": 10,
            "order": {
              "_term": "desc"
            }
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
              "gte": "'.$start.'",
              "lte": "'.$end.'",
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
                    "gte": "'.$start.'",
                    "lte": "'.$end.'",
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
        "interval": "1d",
        "time_zone": "Asia/Shanghai",
        "min_doc_count": 1
      },
      "aggs": {
        "3": {
          "terms": {
            "field": "company",
            "size": 10,
            "order": {
              "_term": "desc"
            }
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
              "query": "dbs_realtime_one_last"
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
              "lte":"'.$end.'" ,
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
                    "query": "dbs_realtime_one_last"
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
}