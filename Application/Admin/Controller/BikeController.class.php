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
		if($uid!=1){
			//非管理员，根据行政级别进行过滤
			//echo "x";
			$company="";
			if(!empty($_GET["company"]) || $_GET["company"] === '0'){
				$company=trim($_REQUEST["company"]);
				if($company=="小鸣单车"){
					$company="小明单车";
					$this->assign("company","小鸣单车");
				}else{
					$this->assign("company",$company);	
				}
				
			}
			//根据行政级别，做相应的过滤
			$province = $_SESSION['auth']['province'];
			$city = $_SESSION['auth']['city'];
			$area = $_SESSION['auth']['area'];
			$from=0;
			$size=15;
			if($_SESSION['auth']['class']=='省级'){
				$res_es = $this->getbikes_p($province,$company,$from,$size);
				$count=$res_es['hits']['total'];
				$page = new \Component\Page($count, $size); //这里的分页类和Home模块的目录一致，可自行修改
				if(isset($_REQUEST["page"])){
					$page2=$_REQUEST["page"];
					$from=($page2-1)*$size;
					$res_es = $this->getbikes_p($province,$company,$from,$size);
				}
			}
			if($_SESSION['auth']['class']=='市级'){
				$res_es = $this->getbikes_pc($province,$city,$company,$from,$size);
				$count=$res_es['hits']['total'];
				$page = new \Component\Page($count, $size); //这里的分页类和Home模块的目录一致，可自行修改
				if(isset($_REQUEST["page"])){
					$page2=$_REQUEST["page"];
					$from=($page2-1)*$size;
					$res_es = $this->getbikes_pc($province,$city,$company,$from,$size);
				}
			}
			if($_SESSION['auth']['class']=='区级'){
				$res_es = $this->getbikes_pca($province,$city,$area,$company,$from,$size);
				$count=$res_es['hits']['total'];
				$page = new \Component\Page($count, $size); //这里的分页类和Home模块的目录一致，可自行修改
				if(isset($_REQUEST["page"])){
					$page2=$_REQUEST["page"];
					$from=($page2-1)*$size;
					$res_es = $this->getbikes_pca($province,$city,$area,$company,$from,$size);
				}
			}
			$pagelist = $page->fpage();
			$this->assign('show', $pagelist);
			$buckets = $res_es['hits']['hits'];
//				var_dump($buckets);
			foreach($buckets as $k=>$v){
				$allbikes[$k]["name"]=$v["_source"]["name"];;
				$allbikes[$k]["mac"]=$v["_source"]["mac"];;
				$allbikes[$k]["rssi"]=$v["_source"]["rssi"];;
				$allbikes[$k]["dwz_info_id"]=$v["_source"]["dwz_info_id"];;
				$allbikes[$k]["company"]=$v["_source"]["company"];;
				$allbikes[$k]["province"]=$v["_source"]["province"];;
				$allbikes[$k]["city"]=$v["_source"]["city"];;
				$allbikes[$k]["area"]=$v["_source"]["area"];;
			}
			//var_dump($allbikes);
			$this->assign("a_menu_list",$allbikes);
		}else {
			$name = "";
			$mac = "";
			$dwz_info_id = "";
			$data = M('bike');
			$sql = "SELECT * FROM dwz_bike where 1=1 and name!='0' and name is not null and name<>'' ";
			$wherecount = "SELECT count(*) wc FROM dwz_bike where 1=1 and name!='0' and name is not null and name<>'' ";
			
			$company_list = M('bike_company')->select();
			foreach($company_list as $k=>$v){
				$arr_com[] = $v["keyword"];
			}
			$str4 = "";
			foreach($arr_com as $k=>$v){
				$str4=$v."|".$str4;
			}
			$str5 = trim($str4,"|");
			$arr_str5 = explode("|",$str5);
			$str6="";
			foreach($arr_str5 as $k=>$v){
				$str6.= " name like '$v%' or";
			}
			$str7 = rtrim($str6,"or");
			$sql.=" and ($str7) ";
			$wherecount.=" and ($str7) ";
//			var_dump($sql);

			
			if (!empty($_GET["name"]) || $_GET["name"] === '0') {
				if ($_GET["name"] == 'NULL' || $_GET["name"] == 'null' || $_GET["name"] == 'Null') {
					//var_dump("aa");exit;
					$name = trim($_REQUEST["name"]);
					$sql .= " and name = ' '";
					$wherecount .= " and name = ' '";
					//$where['name']='';
					$this->assign("name", $name);
				} else {
					$name = trim($_REQUEST["name"]);
					$pos = strpos($name, '|');
					if ($pos === false) {
						$sql .= " and name like '$name%' ";
						$wherecount .= " and name like '$name%' ";
						//$where["name"] = array('like', "$name%");
						$this->assign('name', $name);
					} else {
						//存在
						$arr = explode('|', $name);
						$str = "";
						foreach ($arr as $k => $v) {
							if ($v == 'NULL' || $v == 'null' || $v == 'Null') {
								$str .= " name = ' ' or ";
							} else if ($v == '0') {
								$str .= " name = '0' or ";
							} else {
								$str .= " name like '$v%' or ";
							}
						}
						$str2 = rtrim($str, 'or ');
						$str3 = " and " . "(" . $str2 . ")";
						$sql .= $str3;
						$wherecount .= $str3;
						//var_dump($sql);exit;
						$this->assign('name', $name);
					}


				}
			}
			if (!empty($_GET["mac"]) || $_GET["mac"] === '0') {
				$mac = trim($_REQUEST["mac"]);
				$sql .= " and mac like '%$mac%' ";
				$wherecount .= " and mac like '%$mac%' ";
				//$where["mac"] = array('like', "%$mac%");
				$this->assign('mac', $mac);
			}
			if (!empty($_GET["dwz_info_id"]) || $_GET["dwz_info_id"] === '0') {
				$dwz_info_id = trim($_REQUEST["dwz_info_id"]);
				$sql .= " and dwz_info_id = '$dwz_info_id' ";
				$wherecount .= " and dwz_info_id = '$dwz_info_id' ";
				//$where["dwz_info_id"] = $dwz_info_id;
				$this->assign('dwz_info_id', $dwz_info_id);
			}
			$res = $data->query($wherecount);
			$count = $res[0]["wc"];
			$pageSize = 20;
			$page = new \Component\Page($count, $pageSize); //这里的分页类和Home模块的目录一致，可自行修改
			$sql .= $page->limit;
			$info = $data->query($sql);
			$pagelist = $page->fpage();
			$this->assign('show', $pagelist);
			$this->assign("a_menu_list", $info);
		}
		$this->display();
	}
	//根据省获取
	private function getbikes_p($province,$company,$from,$size){
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
		if($company!=""){
			$json = '
				{
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
            "province": {
              "query": "'.$province.'"
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
                  "province": {
                    "query": "'.$province.'"
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
}
			';
		}else{
			$json = '
			{
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
              "query": "dbs_realtime_last"
            }
          }
        },
        {
          "match_phrase": {
            "province": {
              "query": "'.$province.'"
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
                    "query": "dbs_realtime_last"
                  }
                }
              },
              {
                "match_phrase": {
                  "province": {
                    "query": "'.$province.'"
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
		}

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

	//根据省市获取
	private function getbikes_pc($province,$city,$company,$from,$size){
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
		if($company!=""){
			$json = '
				{
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
}
			';
		}else{
			$json = '
			{
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
              "query": "dbs_realtime_last"
            }
          }
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
                    "query": "dbs_realtime_last"
                  }
                }
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
		}

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
	//根据省市区获取
	private function getbikes_pca($province,$city,$area,$company,$from,$size){
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
		if($company!=""){
			$json = '
				{
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
}
			';
		}else{
			$json = '
			{
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
              "query": "dbs_realtime_last"
            }
          }
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
                    "query": "dbs_realtime_last"
                  }
                }
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
        }
      }
    },
    "fragment_size": 2147483647
  }
}';
		}

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
//	public function index() {
//		$data = M('bike');
//		if(IS_POST){
//			$name=$_REQUEST["name"];
//			$mac=$_REQUEST["mac"];
//			$dwz_info_id=$_REQUEST["dwz_info_id"];
//			$sql="SELECT * FROM dwz_bike where 1=1";
//			if($name!=""){
//				$sql.=" and name like '%$name%' ";
//				$where["name"]=array('like',"%$name%");
//				$this->assign('name', $name);
//			}
//			if($mac!=""){
//				$sql.=" and mac like '%$mac%' ";
//				$where["mac"]=array('like',"%$mac%");
//				$this->assign('mac', $mac);
//			}
//			if($dwz_info_id!=""){
//				$sql.=" and dwz_info_id = '$dwz_info_id' ";
//				$where["dwz_info_id"]=$dwz_info_id;
//				$this->assign('dwz_info_id', $dwz_info_id);
//			}
//			$count=$data->where($where)->count();
//			//var_dump($count);exit;
//			$pageSize = 20;
//			$page = new \Component\Page($count, $pageSize); //这里的分页类和Home模块的目录一致，可自行修改
//			$info = $data -> query($sql);
//			$pagelist = $page -> fpage();
//			$this->assign('show', $pagelist);
//			$this->assign("a_menu_list", $info);
//		}else {
//			$count = $data->count();
//			$pageSize = 20;
//			$page = new \Component\Page($count, $pageSize); //这里的分页类和Home模块的目录一致，可自行修改
//			//3. 拼装sql语句获得每页信息
//			$sql = "SELECT * FROM dwz_bike " . $page->limit;
//			$info = $data->query($sql);
//			$pagelist = $page->fpage();
//			$this->assign('show', $pagelist);
//			$this->assign("a_menu_list", $info);
//		}
//		$this->display();
//	}
	/**
	 * 添加菜单
	 */
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