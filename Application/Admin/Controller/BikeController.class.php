<?php
namespace Admin\Controller;
use Think\Controller;
class BikeController extends CommonController {
	public function index()
	{
		$uid = $_SESSION['auth']['id'];
		$this->assign('uid',$uid);
		//获取每家公司的单车数量
		$list = M("bike_company")->select();
		$this->assign('list1', $list);
		$company="";
		if(!empty($_GET["company"]) || $_GET["company"] === '0'){
			$company=trim($_REQUEST["company"]);
			$this->assign("company",$company);
		}
		$from=0;
		$size=20;
		if($uid!=1){
			//非管理员，根据行政级别进行过滤
			//echo "x";
			//根据行政级别，做相应的过滤
			$area = $_SESSION['auth']['area'];
			$data66["province"] = $_SESSION['auth']['province'];
			$data66["city"] = $_SESSION['auth']['city'];
			$data66["area"] = $area;
			$did_arr = M('info')->where($data66)->select();
		}else {
			$did_arr = M('info')->select();
		}
		$did_str = '';
		foreach($did_arr as $k=>$v){
			$did_str .="dwz_info_id:".$v["id"]." ";
		}
		$res_es = $this->getarea($company,$from,$size,$did_str);
		$count=$res_es['hits']['total'];
		$page = new \Component\Page($count, $size); //这里的分页类和Home模块的目录一致，可自行修改
		if(isset($_REQUEST["page"])){
			$page2=$_REQUEST["page"];
			$from2=($page2-1)*$size;
			$res_es = $this->getarea($company,$from2,$size,$did_str);
		}
//		var_dump($res_es);
		$pagelist = $page->fpage();
		$this->assign('show', $pagelist);
		$buckets = $res_es['hits']['hits'];
		foreach($buckets as $k=>$v){
			$allbikes[$k]["name"]=$v["_source"]["name"];
			$allbikes[$k]["mac"]=$v["_source"]["mac"];
			$allbikes[$k]["rssi"]=$v["_source"]["rssi"];
			$allbikes[$k]["dwz_info_id"]=$v["_source"]["dwz_info_id"];
			$allbikes[$k]["company"]=$v["_source"]["company"];
			$allbikes[$k]["province"]=M('info')->where("id=".$v["_source"]["dwz_info_id"])->getField('province');
			$allbikes[$k]["city"]=M('info')->where("id=".$v["_source"]["dwz_info_id"])->getField('city');
			$allbikes[$k]["area"]=M('info')->where("id=".$v["_source"]["dwz_info_id"])->getField('area');
		}
		//var_dump($allbikes);
		$this->assign("a_menu_list",$allbikes);
		$this->display();
	}
	//获取区域车辆数据
	private function getarea($company,$from,$size,$did_str){
		$lpath =  THINK_PATH.'Library/Vendor/vendor/autoload.php';
		require $lpath;
		//$hosts = [
		//	'dododo.shop:9200',         // IP + Port
		//];
		$hosts = [
				'116.62.171.54:8081',         // IP + Port
		];
		$client = \Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build();
		if($company==''){
			$json = '
			{
  "version": true,
  "from":"'.$from.'",
  "size":"'.$size.'",
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
                  "query": "'.$did_str.'",
                  "analyze_wildcard": true,
                  "all_fields": true
                }
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
        "interval": "1w",
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
                    "query": "dbs_realtime_last"
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
}';}else{

			$json = '{
  "version": true,
  "from":"'.$from.'",
  "size":"'.$size.'",
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
                  "query": "'.$did_str.'",
                  "analyze_wildcard": true,
                  "all_fields": true
                }
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
            "company": {
              "query": "'.$company.'"
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
  "_source": {
    "excludes": []
  },
  "aggs": {
    "2": {
      "date_histogram": {
        "field": "timestamp",
        "interval": "1w",
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
                    "query": "dbs_realtime_last"
                  }
                }
              },
              {
                "match_phrase": {
                  "company": {
                    "query": "'.$company.'"
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
        }
      }
    },
    "fragment_size": 2147483647
  }
}';}

		$params = [
				"scroll" => "1m",
				'index' => 'bike_index_v6',
				'type' => 'dbs_realtime_last',
				'body' => $json
		];
		$results = $client->search($params);
		return $results;
	}
	public function add(){
		$data['name'] = $_POST['name'];
		$data['mac'] = $_POST['mac'];
		$data['rssi'] = $_POST['rssi'];
		$data['dwz_info_id'] = $_POST['dwz_info_id'];
		$lng_lat = $_POST['lng_lat'];
		$data['lng']=strstr($lng_lat,',',true);
		$data['lat']=ltrim(strstr($lng_lat,','), ",");
		$data['time']=time();
		$result=M('bike')->add($data);
		if ($result) {
			$this->success('添加成功',U('Admin/Bike/index'));
		}else{
			$this->error('添加失败');
		}
	}
	/**
	 * 修改菜单
	 */
	public function edit(){
		$data['name'] = $_POST['name'];
		$data['rssi'] = $_POST['rssi'];
		$data['dwz_info_id'] = $_POST['dwz_info_id'];
		$lng_lat = $_POST['lng_lat'];
		$data['lng']=strstr($lng_lat,',',true);
		$data['lat']=ltrim(strstr($lng_lat,','), ",");
		$data['time']=time();
		$mac = $_POST['mac'];
		$result=M('bike')->where("mac=$mac")->save($data);
		if ($result) {
			$this->success('修改成功',U('Admin/Bike/index'));
		}else{
			$this->error('修改失败');
		}
	}

	/**
	 * 删除菜单
	 */
	public function delete(){
		$mac=$_GET["mac"];
		$result=M('bike')->where("mac=$mac")->delete();
		if($result){
			$this->success('删除成功',U('Admin/Bike/index'));
		}else{
			$this->error('删除失败');
		}
	}
	
	//车辆曾经停靠过的车位
	public function allcw(){
		echo "allcw";
		$mac = str_replace(':','-',$_REQUEST['mac']);
		var_dump($mac);
		$redis = new \Redis();
		$redis->connect('127.0.0.1', 6379);
		$cw = $redis->smembers("bikeinfo:$mac");
		var_dump($cw);
		$this->assign('cw',$cw);
		$this->assign("a_menu_list", $cw);
		$this->display();
	}
	
	public function bikedetails(){
		$uid = $_SESSION["auth"]["id"];
		$mac = $_REQUEST["mac"];
		$href = "http://116.62.171.54:8082/app/kibana#/discover?_g=(refreshInterval:('$$hashKey':'object:807',display:'30%20seconds',pause:!f,section:1,value:30000),time:(from:now-7d,mode:quick,to:now))&_a=(columns:!(mac,name,dwz_info_id,area,company),filters:!(('$state':(store:appState),meta:(alias:!n,disabled:!f,index:AV8tUT6CT459h6-9WR96,key:company,negate:!t,type:phrase,value:%E5%85%B6%E4%BB%96),query:(match:(company:(query:%E5%85%B6%E4%BB%96,type:phrase)))),('$state':(store:appState),meta:(alias:!n,disabled:!f,index:AV8tUT6CT459h6-9WR96,key:mac,negate:!f,type:phrase,value:'$mac'),query:(match:(mac:(query:'$mac',type:phrase))))),index:AV8tUT6CT459h6-9WR96,interval:auto,query:(match_all:()),sort:!(timestamp,desc))";
		if($uid==1){
			$this->assign("href",$href);
		}
		$this->display();
	}


}