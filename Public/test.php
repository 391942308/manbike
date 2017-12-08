<?php
class test
{
    function index()
    {
        ini_set('date.timezone', 'Asia/Shanghai');
        //查询滨江区所有的车位编号
        $area = "滨江区";
        $enda = "2017-12-8 14:00:00";
        $mysqli = new mysqli();//实例化mysqli
        $mysqli->connect('localhost', 'root', 'root', 'dwz');
        if (mysqli_connect_error()) {
            exit('数据库连接错误,错误信息是.' . mysqli_connect_error());
        }
        $mysqli->set_charset("UTF8");//设置数据库编码
        $sql = "select id from dwz_info where area='$area'";//创建一句SQL语句
        $result = $mysqli->query($sql);//执行sql语句把结果集赋给$result
        //        var_dump($result->fetch_row());//将结果集的第一行输出
        //获取该行政区所有车位编号，拼接起来
        $did_str = "";
        while ($row = $result->fetch_row()) {
            foreach ($row as $v) {
                $did_str .= "dwz_info_id:" . $v . " ";
            }
        }
        //        var_dump($did_str);

        //滨江区14:00车辆总数
        $end1 = strtotime($enda) * 1000;
        $arr1 = $this->getbikes($did_str, $end1);
        $num = $arr1["hits"]["total"];
        $companys = $arr1["aggregations"][2]["buckets"];
        foreach($companys as $k=>$v){
//            var_dump($v["key"]."===".$v["doc_count"]);
            if($v["key"]=="摩拜"){
                $mb = $v["doc_count"];
            }else if($v["key"]=="ofo"){
                $ofo = $v["doc_count"];
            }else if($v["key"]=="酷骑"){
                $kq = $v["doc_count"];
            }else if($v["key"]=="小鸣单车"){
                $xm = $v["doc_count"];
            }else if($v["key"]=="HelloBike"){
                $hb = $v["doc_count"];
            }
        }
        $ts = strtotime($enda);
        $sql2 = "insert into dwz_addbike(num,kq,mb,xm,ofo,hb,ts) VALUES ('$num','$kq','$mb','$xm','$ofo','$hb',$ts)";
        $result2 = $mysqli->query($sql2);
        var_dump($result2);
        $result->free();//释放查询内存(销毁)
        $mysqli->close();//别忘了关闭你的"小资源";
    }

    //单个行政区车辆增长数据
    function getbikes($did_str,$end)
    {
        $lpath = 'C:\git\manbike\ThinkPHP\Library\Vendor\vendor\autoload.php';
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
      "terms": {
        "field": "company",
        "size": 5,
        "order": {
          "_count": "desc"
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
              "query_string": {
                "query": "'.$did_str.'",
                "analyze_wildcard": true
              }
            },
            {
              "range": {
                "timestamp": {
                  "gte": "0",
                  "lte": "' . $end . '",
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
                        "gte": "0",
                        "lte": "' . $end . '",
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
}
$biksnum = new test();
echo $biksnum->index();