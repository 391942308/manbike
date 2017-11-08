<?php
namespace Admin\Controller;
use Think\Controller;
class StalistController extends CommonController {
	public function _initialize(){
		parent::_initialize();
	}
    public function index()
    {
		$dwz_info_id="";
		$start="";
		$end="";
		$nowtime=date('Y-m-d H:i:s');
		$starttime=date('Y-m-d H:i:s',strtotime('-1 hour'));
		$this->assign("nowtime2",$nowtime);
		$this->assign("starttime2",$starttime);
		if($_GET){
			$redis = new \Redis();
			$redis->connect('116.62.171.54', 8085);
			$all_real=$redis->KEYS("realtime:?*");
			$all_dwzinfo2=array();
			foreach($all_real as $k=>$v){
				//var_dump($v);
				//var_dump($redis->hget($v,'time'));
				$all_dwzinfo["bikes"]=$redis->hget($v,'bikes');
				$all_dwzinfo["dwz_info_id"]=$redis->hget($v,'dwz_info_id');
				$all_dwzinfo["time"]=$redis->hget($v,'time');
				$all_dwzinfo2[]=$all_dwzinfo;
			}
			//var_dump($all_dwzinfo2);
			$dwz_info_id = $_REQUEST["dwz_info_id"];
			$this->assign('dwz_info_id', $dwz_info_id);
			$start = strtr($_REQUEST["start"],"+"," ");
			if($start==""){
				$start=$starttime;
			}
			$end = strtr($_REQUEST["end"],"+"," ");
			if($end==""){
				$end=$nowtime;
			}
			if($dwz_info_id!=''&& $start=='' && $end==''){
				$stanum=$redis->scard("infobikes:$dwz_info_id");
				$stalist2=$redis->smembers("infobikes:$dwz_info_id");
			}else{
				foreach ($all_dwzinfo2 as $k2 => $v2) {
					if ($v2["dwz_info_id"] == $dwz_info_id) {
						$all_dwzinfo3[] = $v2;
					};
				}
				//var_dump($all_dwzinfo3);

				$start2 = strtotime($start);
				foreach ($all_dwzinfo3 as $k => $v) {
					if ($v["time"] >= $start2) {
						$all_dwzinfo4[] = $v;
					}
				}

				$end2 = strtotime($end);
				foreach ($all_dwzinfo4 as $k4 => $v4) {
					if ($v["time"] <= $end2) {
						$all_dwzinfo5[] = $v4;
					}
				}
				//var_dump($all_dwzinfo5);
				$arr2 = array();
				foreach ($all_dwzinfo5 as $k => $v) {
					$arr2[] = json_decode($v["bikes"], true);
				}
				//var_dump($arr2);
				foreach($arr2 as $v1){
					foreach($v1 as $v2){
						$redis->sadd("statistics:".$dwz_info_id.$start2.$end2,$v2["mac"]);
					}
				}
				$stanum=$redis->scard("statistics:".$dwz_info_id.$start2.$end2);
				$stalist2=$redis->smembers("statistics:".$dwz_info_id.$start2.$end2);
				//运算结束以后删除
				$redis->del("statistics:".$dwz_info_id.$start2.$end2);
			}
			$this->assign('start', $start);
			$this->assign('end', $end);
			foreach($stalist2 as $k=>$v){
				//获取名称和最后停放时间
				$mac = str_replace(':','',$v);
				$exist = $redis->scard("infobike:$dwz_info_id:$mac");
				$sm = $redis->smembers("infobike:$dwz_info_id:$mac");
				//var_dump($mac);
				$arr_all[$k]['mac']=$mac;
				$arr_all[$k]['name']=$redis->get('bikes:'.$mac);;
				$arr_all[$k]['rssi']=$redis->get('bikesrssi:'.$mac);
				$arr_all[$k]['num']=$exist;
				$arr_all[$k]['lasttime']=date('Y-m-d H:i:s',max($sm));
			}
			//var_dump($stalist2);
			$this->assign('stanum', $stanum);
			$this->assign('arr_all', $arr_all);
			}

			$bike_company=M("bike_company")->select();
			foreach($bike_company as $k=>$v){
				//var_dump($v);
				$arr[$v['title']]=explode('|',$v['keyword']);
			}
			//var_dump($arr);
			foreach($arr_all as $k=>$v){
				$flag = 'no';
				$name = $v['name'];
				foreach($arr as $kk=>$vv){
					foreach($vv as $kkk=>$vvv){
						if(strpos($name,$vvv)!==false){
							$bike_names[]=$kk;
							$flag = 'yes';
						}
					}
				}
				if($flag=='no'){
					$bike_names[]='其他';
				}
			}

			$arr1 = $bnames = array_unique($bike_names);
			$bn = array_count_values($bike_names);
			$len = sizeof($bn);
			$i=0;
			foreach($bn as $k=>$v){
				$arr2[$i]['name']=$k;
				$arr2[$i]['value']=$v;
				$i++;
			}
			//var_dump($bnames);
			//var_dump($bn);
			//var_dump($len);

			//分类统计图
			/*foreach($exist_list as $k=>$v){
                //对名称的一个计数
                //车名称
                $arr1[]=array();
                //车名称 数量
                $arr2[]['name']=array();
                $arr2[]['value']=array();
            }*/
			$str1 = json_encode($arr1);
			$str2 = json_encode($arr2);
			$this->assign('length',$len);
			$this->assign('str1',$str1);
			//var_dump($str2);
			$this->assign('str2',$str2);

			$this->display();

    }

}