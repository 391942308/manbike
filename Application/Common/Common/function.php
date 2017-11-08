<?php
	function onebike_liudong_new($iid=0,$mac=0,$start=0,$end=0,$judge=0,$rule=0){
		$redis = new \Redis();
		$redis->connect('127.0.0.1', 6379);
		$key_arr[]=$iid;
		$key_arr[]=$start;
		$key_arr[]=$end;
		$key_arr[]=$judge;
		$key_arr[]=$rule;
		$key=implode('_',$key_arr);
		
		//所有时刻
		$sk = $redis->smembers("infobike:$iid:$mac");
		
		$skk=array();
		//根据条件筛选时刻
		foreach($sk as $k=>$v){
			if($v>$start and $v<$end){
				$skk[]=$v;
			}
		}
		//var_dump($skk);
		//var_dump($sj);
		//exit;
		//时刻转时间
		
		//var_dump($sj);
		//先根据间隔判断是否连续
		foreach($skk as $k=>$v){
			if(abs($skk[$k+1] - $skk[$k])<=$judge){
				$status[$k]=1;
			}else{
				$status[$k]=0;
			}
		}
		
			//var_dump($status);
		$lianxu=array();
		foreach($status as $k=>$v){
			$count=0;
			for($i=$k;$i<sizeof($status);$i++){
				//当出现下一个不连续的值就退出，进行下一次
				if($status[$i]==1){
					$count++;
					$lianxu[$k]=$count;
				}else{
					$lianxu[$k]=$count;
					break;	
				}
			}
		}
		//var_dump(max($lianxu));
		$tjs = 0;//统计开始下标
		$tje = 0;
		foreach($lianxu as $k=>$v){
			if($v == max($lianxu)){
				$tjs = $k;
				$tje = $k+max($lianxu);
			}
		}
		$ltime = $skk[$tje] - $skk[$tjs];
		//var_dump($ltime);
		//var_dump(date("Y-m-d h:i:sa", $sj[$tjs]));
		//var_dump(date("Y-m-d h:i:sa", $sj[$tje]));
		//var_dump($ltime);
		//当在某个车位连续放了1800S的时候就为非流动车辆
		//把车辆放到对应的车位
		//统计的时候先将自己清除,然后在放入到相应的地方
		$redis->SREM("liudongno:$key",$mac);
		$redis->SREM("liudong:$key",$mac);
		if($ltime >= $rule){
			$redis->sadd("liudongno:$key",$mac);
		}else{
			$redis->sadd("liudong:$key",$mac);
		}
		
	}
	//单个车位的流动情况
	function oneinfo_liudong($iid=0,$start=0,$end=0,$judge=0,$rule=0){
		//获取到该车位的所有车辆
		$redis = new \Redis();
		$redis->connect('127.0.0.1', 6379);
		$all_list = $redis->smembers("infobikes:$iid");
		foreach($all_list as $k=>$v){
			//var_dump($v);
			onebike_liudong_new($iid,$v,$start,$end,$judge,$rule);
		}
		
	}
	//单个小区的流动情况
	function oneblock_liudong($bid=0,$jiange=0,$start=0,$st=0,$judge=0,$rule=0){
		$redis = new \Redis();
		$redis->connect('127.0.0.1', 6379);
		//$bid=2;
		$all_info = $redis->smembers("block:$bid");
		foreach($all_info as $k=>$v){
			oneinfo_liudong($v,$jiange,$start,$st,$judge,$rule);	
		}
		
	}