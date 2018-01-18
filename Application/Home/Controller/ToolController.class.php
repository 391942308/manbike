<?php
namespace Home\Controller;
use Think\Controller;
class ToolController extends Controller {
    //首页
	public function index(){
		//根据时间和车位编号  从realtime中获取到 时间 和 数量
		
		if(!IS_POST){
			//get请求的时候 就显示form表单
			
		}else{
			//post请求的时候 就显示获取的结果
			
		}
		$id=5820;
		$start=5820;
		$end=5820;
		
		//$this->get_realtime_by_id_time($id,$start,$end);
		$this->display();
    }
	
	public function fake(){
		
		$redis = new \Redis();
		$redis->connect('116.62.171.54', 8085);
		
		//根据行政级别，做相应的过滤
		$province = "浙江省";
		$city = "杭州市";
		$area = "下沙";
		//echo $province;
		$map['province']=array("eq",$province);
		$map['city']=array("eq",$city);
		$map['area']=array("eq",$area);
		
		$list = M("info")->where($map)->select();
		//var_dump($list);
		foreach($list as $k=>$v){
			$key = "dwz_info:".$v['id'];
			$r = $redis->hget($key,'level');
			$u = $redis->hget($key,'usable_num');
			$s = $redis->hget($key,'storage_num');
			
			echo $u;
			$ur = rand(1,8)*$u/10;
			echo $ur;
			if($ur >= 1000){
				$ur = $ur/100;
			}
			echo "<br />";
			//var_dump($r);
			//if($r == -2){
				
			$redis->hset($key,'level',0);
			$redis->hset($key,'storage_num',0);
			//}
		}
		echo "设置成功";
	}
	
	//首页
	public function demo(){
		if(!IS_POST){
			//get请求的时候 就显示form表单
			
		}else{
			//post请求的时候 就显示获取的结果
			
		}
		$id=5820;
		$start=5820;
		$end=5820;
		
		$this->get_realtime_by_id_time($id,$start,$end);
		$this->display();
		exit;
		
		$province='浙江省';
        $city='杭州市';
		
        $list_area = M("info")->distinct(true)->field('area')->select();
		
		//var_dump($list_area);
		$arr_area = array();
		$arr_area_num = array();
		foreach($list_area as $k=>$v){
			//var_dump($v['area']);
			$area = $v['area'];
			$arr_area[]=$area;
			//echo $area;
			$res = $this->getbikes_pca($province,$city,$area);
			//var_dump($res);
			$arr_area_num[$area]=$res['hits']['total'];
		}
		//var_dump($arr_area);
		//var_dump($arr_area_num);
		
        $str_area = json_encode($arr_area);
        $str_area_num = json_encode($arr_area_num);
		
		$this->assign('str_area',$str_area);
		$this->assign('str_area_num',$str_area_num);
		
		$list= $this->last_20_item(0);

		foreach($list as $k=>$v){
			//echo $v['name'];
			if($v['name'] == 'mobike'){
				$list[$k]['name']='老人'.rand(1,1000);
				$list[$k]['company']='老人';
			}
			
			if($this->startwith($v['name'],'CoolQi')){
				$list[$k]['name']='宠物'.rand(1,1000);
				$list[$k]['company']='宠物';
			}
		}
		$lat = '30.276691';
		$lon = '120.059071';
		if($list != null){
			$lat = $list[0]['location']['lat'];
			$lon = $list[0]['location']['lon'];
		}
		$this->assign('dlat',$lat);
		$this->assign('dlon',$lon);
		//var_dump($list[0]['location']['lon']);
		//var_dump($list[0]['location']['lat']);
		//var_dump($list[0].location.lon);
		//var_dump($list);
		//$str_bikes = json_encode($list);
		//$this->assign('str_bikes',$str_bikes);
		
		$this->assign('list',$list);
		
		//热力图数据
		$list = M("info")->select();
		$arr =array();
		foreach($list as $k=>$v){
			$total = $this->get_num_by_id($v['id']);
			$item['lng']=$v['lng'];
			$item['lat']=$v['lat'];
			$item['count']=$total;
			$arr[]=$item;
		}
		//var_dump($arr);
		$json_str = json_encode($arr);
		$this->assign('json_str',$json_str);
		
		$this->display();
    }
	
	private function startwith($str,$pattern) {
		if(strpos($str,$pattern) === 0)
          return true;
		else
          return false;
	}

	
	//车位历史总停放
	/*private function get_realtime_by_id_time($id,$start,$end){
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
				  "version": true,
				  "size": 500,
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
							"query": "5893",
							"analyze_wildcard": true
						  }
						},
						{
						  "match_phrase": {
							"_type": {
							  "query": "dwz_bike_sub_realtime"
							}
						  }
						},
						{
						  "range": {
							"timestamp": {
							  "gte": 1508947200000,
							  "lte": 1509033599999,
							  "format": "epoch_millis"
							}
						  }
						}
					  ],
					  "must_not": []
					}
				  }
				}';

			$params = [
				'index' => 'bike_index_v5',
				'type' => 'dwz_bike_sub_realtime',
				'body' => $json
			];

			$results = $client->search($params);
			//$ts = $results['hits']['hits'][0]['_source']['ts'];
			var_dump($results);
			//var_dump($ts);
			return $results;
	}*/
	
	
	

}