<?php
namespace Admin\Controller;
use Think\Controller;
class IblockController extends CommonController {
	public function _initialize(){
		parent::_initialize();
	}
	public function index()
	{
		$title = "";
		$data = M('info_block');
		$sql = "SELECT * FROM dwz_info_block where 1=1 ";
		if (!empty($_GET["title"]) || $_GET["title"]==='0') {
			$title = $_REQUEST["title"];
			$sql .= " and title like '%$title%' ";
			$where["title"] = array('like', "%$title%");
			$this->assign('title', $title);
		}
		$count = $data->where($where)->count();
		//var_dump($count);exit;
		$pageSize = 20;
		$page = new \Component\Page($count, $pageSize); //这里的分页类和Home模块的目录一致，可自行修改
		$sql.=$page->limit;
		$info = $data->query($sql);
		$pagelist = $page->fpage();
		$this->assign('show', $pagelist);
		$this->assign("a_menu_list", $info);
		$this->display();
	}
	/**
	 * 添加菜单
	 */
	public function add(){
		$data['title'] = $_POST['title'];
		$data['content'] = $_POST['content'];
		$result=M('info_block')->add($data);
		if ($result) {
			$this->success('添加成功',U('Admin/Iblock/index'));
		}else{
			$this->error('添加失败');
		}
	}
	/**
	 * 修改菜单
	 */
	public function edit(){
		$data['title'] = $_POST['title'];
		$data['content'] = $_POST['content'];
		$id = $_POST['id'];
		$result=M('info_block')->where("id=$id")->save($data);
		if ($result) {
			$this->success('修改成功',U('Admin/Iblock/index'));
		}else{
			$this->error('修改失败');
		}
	}

	/**
	 * 删除菜单
	 */
	public function delete(){
		$block_no=$_GET["id"];
		$result=M('info_block')->where("id=$block_no")->delete();
		if($result){
			$this->success('删除成功',U('Admin/Iblock/index'));
		}else{
			$this->error('删除失败');
		}
	}
	public function exist(){
		$id=$_GET["id"];
		$infos=M('info')->where("block_no=$id")->select();
		$sum=0;
		foreach($infos as $k=>$v){
			//echo $v["id"];
			$id=$v["id"];
			$redis = new \Redis();
			$redis->connect('116.62.171.54', 8085);
			//从redis获取到数据 ，然后使用图表形式展现
			$all = $redis->scard("infobikes:$id");
			$all_list = $redis->smembers("infobikes:$id");
			//var_dump($all_list);
			$exist2 = $redis->scard("infobikesexist:$id");
			$sum+=$exist2;
			$exist_list = $redis->smembers("infobikesexist:$id");
			//$exist+=$exist;
			//var_dump($numbers);
			$arr =array();
			foreach($exist_list as $k=>$v){
				$mac = str_replace(':','-',$v);
				//var_dump("infobike:$id:$mac");
				$exist = $redis->scard("infobike:$id:$mac");
				$sm = $redis->smembers("infobike:$id:$mac");
				//var_dump($sm);
				$arr_exist[$k]['mac']=$v;
				$arr_exist[$k]['num']=$exist;
				$arr_exist[$k]['id']=$id;
				$arr_exist[$k]['lasttime']=date('Y-m-d:H:i:s',max($sm));
			}

		}
		//var_dump($sum);
		$this->assign('all',$all);
		$this->assign('all_list',$all_list);
		$this->assign('sum',$sum);
		$this->assign('exist_list',$exist_list);
		$this->assign('arr_exist',$arr_exist);
		$this->display();
	}
	public function sall(){
		$block_no=$_GET["id"];
		$infos=M('info')->where("block_no=$block_no")->select();
		$sum=0;
		foreach($infos as $k=>$v){
			$id=$v["id"];
			$redis = new \Redis();
			$redis->connect('116.62.171.54', 8085);
			//从redis获取到数据 ，然后使用图表形式展现
			$all = $redis->scard("infobikes:$id");
			$all_list = $redis->smembers("infobikes:$id");
			$sum+=$all;
			//$exist = $redis->scard("infobikesexist:$id");
			//$exist_list = $redis->smembers("infobikesexist:$id");
			//var_dump($exist_list);
			//var_dump($numbers);


			//$this->assign('exist',$exist);
			//$this->assign('exist_list',$exist_list);

			$arr =array();
			foreach($all_list as $k=>$v){
				$mac = str_replace(':','-',$v);
				//var_dump("infobike:$id:$mac");
				$exist = $redis->scard("infobike:$id:$mac");
				//var_dump($exist);
				$arr_all[$k]['mac']=$v;
				$arr_all[$k]['num']=$exist;
				$arr_all[$k]['id']=$id;
			}
		}
		$this->assign('sum',$sum);
		$this->assign('all',$all);
		$this->assign('all_list',$all_list);
		$this->assign('arr_all',$arr_all);
		$this->display();
	}
	public function infobikesall(){
		if($_REQUEST['id'] == ''){
			$this->error('参数不正确');
		}
		$map['id']=array('eq',$_REQUEST['id']);
		$res = M("info_block")->where($map)->find();
		$this->assign('title',$res['title']);
		//var_dump($res['content']);
		$arr = explode('|',$res['content']);
		foreach($arr as $k=>$v){
			$arr[$k]='dwz_info_id:'.$v;
		}
		$query = implode(" ",$arr);
		//var_dump($query);
		//exit;
		$lpath =  THINK_PATH.'Library/Vendor/vendor/autoload.php';
		require $lpath;
		$hosts = [
				'http://116.62.171.54:8081',         // IP + Port
		];
		$client = \Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build();
		//获取es最后更新的时间,在更新的时候使用
		
		$json ='{
			  "size": 0,
			  "query": {
				"bool": {
				  "must": [
					{
					  "query_string": {
						"analyze_wildcard": true,
						"query": "'.$query.'"
					  }
					},
					{
					  "match_phrase": {
						"_type": {
						  "query": "dwz_bike_sub_realtime_last"
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
				'index' => 'bike_index_v5',
				'type' => 'dwz_bike_sub_realtime_last',
				'body' => $json
		];

		$results = $client->search($params);
		//var_dump($results['hits']['total']);
		//var_dump();
		$this->assign('all',$results['hits']['total']);
		$buckets = $results['aggregations'][3]['buckets'];
		$arr1 = array();
		$arr2 = array();
		$i=0;
		foreach($buckets as $k=>$v){
			$arr1[]=$v['key'];
			$arr2[$i]['name']=$v['key'].'('.$v['doc_count'].')';
			$arr2[$i]['value']=$v['doc_count'];
			$i++;
		}
		
		/*$arr1 = $bnames = array_unique($bike_names);
		$bn = array_count_values($bike_names);
		$len = sizeof($bn);
		$i=0;
		
		$all = 0;
		foreach($bn as $k=>$v){
			$arr2[$i]['name']=$k.'('.$v.')';
			$arr2[$i]['value']=$v;
			$i++;
			$all+=$v;
		}*/
		//var_dump($arr1);
	//	var_dump($arr2);
		$str1 = json_encode($arr1);
		$str2 = json_encode($arr2);
		$this->assign('str1',$str1);
		$this->assign('str2',$str2);
		//$ts = $results['hits']['hits'][0]['_source']['ts'];
		//var_dump($results);
		//var_dump($ts);
		
		$this->display();
	}
	
	public function clear_realtime(){
		$redis = new \Redis();
		$redis->connect('127.0.0.1', 6379);
		$list = M("info")->select();
		
		foreach($list as $k=>$v){
			$id=$v['id'];
			//$iid = $redis->get("realtime_id_".$id);
			$arr = $redis->keys("realtime:$id:*");
			foreach($arr as $k=>$v){
				$redis->set("realtime_id_".$id,0);
				$redis->del($v);
			 }
		}
		
		foreach($list as $kk=>$vv){
			$id = $vv['id'];
			$arr = $redis->keys("infobike:$id:*");
			foreach($arr as $k=>$v){
				$redis->delete($v);
			}
		}
		
		foreach($list as $kk=>$vv){
			$id = $vv['id'];
			$arr = $redis->keys("caijicishu:$id:*");
			foreach($arr as $k=>$v){
				$redis->delete($v);
			}
		}
		$this->success('缓存清除成功',U('Admin/Iblock/index'));
		//echo "缓存清除成功";
	}
	public function clear_infobike(){
		$redis = new \Redis();
		$redis->connect('127.0.0.1', 6379);
		$list = M("info")->select();
		
		foreach($list as $kk=>$vv){
			$id = $vv['id'];
			$arr = $redis->keys("infobike:$id:*");
			foreach($arr as $k=>$v){
				$redis->delete($v);
			}
		}
		/*foreach($list as $k=>$v){
			$id=$v['id'];
			//获取到最后的那个id
			$iid = $redis->get("realtime_id_".$id);
			//$redis->set('test','test');
			//$redis->expire('test',60*30);
			//echo $redis->get('test');
			 $arr = $redis->keys("realtime:$id:*");
			 //var_dump($arr);
			 foreach($arr as $k=>$v){
				$redis->set("realtime_id_".$id,0);
				$redis->del($v);
			 }
		}*/
		
		 exit;
		 
		//exit;
		var_dump($iid);
		$item['id'] = $redis->hget("realtime:$id:$iid",'id');
		$item['dwz_info_id'] = $redis->hget("realtime:$id:$iid",'id');
		$item['storage_num'] = $redis->hget("realtime:$id:$iid",'id');
		$item['time'] = $redis->hget("realtime:$id:$iid",'id');
		$item['bikes'] = $redis->hget("realtime:$id:$iid",'bikes');
		var_dump(json_decode($item['bikes']));
		//var_dump($item);
		$exist = $redis->scard("infobikesexist:$id");
		$exist_list = $redis->smembers("infobikesexist:$id");
		
		//$redis->set("realtime_id_".$id,0);
		//$redis->del("realtime:$id:$iid");
		
		
	}

}