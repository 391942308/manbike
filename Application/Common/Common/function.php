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
		
		//����ʱ��
		$sk = $redis->smembers("infobike:$iid:$mac");
		
		$skk=array();
		//��������ɸѡʱ��
		foreach($sk as $k=>$v){
			if($v>$start and $v<$end){
				$skk[]=$v;
			}
		}
		//var_dump($skk);
		//var_dump($sj);
		//exit;
		//ʱ��תʱ��
		
		//var_dump($sj);
		//�ȸ��ݼ���ж��Ƿ�����
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
				//��������һ����������ֵ���˳���������һ��
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
		$tjs = 0;//ͳ�ƿ�ʼ�±�
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
		//����ĳ����λ��������1800S��ʱ���Ϊ����������
		//�ѳ����ŵ���Ӧ�ĳ�λ
		//ͳ�Ƶ�ʱ���Ƚ��Լ����,Ȼ���ڷ��뵽��Ӧ�ĵط�
		$redis->SREM("liudongno:$key",$mac);
		$redis->SREM("liudong:$key",$mac);
		if($ltime >= $rule){
			$redis->sadd("liudongno:$key",$mac);
		}else{
			$redis->sadd("liudong:$key",$mac);
		}
		
	}
	//������λ���������
	function oneinfo_liudong($iid=0,$start=0,$end=0,$judge=0,$rule=0){
		//��ȡ���ó�λ�����г���
		$redis = new \Redis();
		$redis->connect('127.0.0.1', 6379);
		$all_list = $redis->smembers("infobikes:$iid");
		foreach($all_list as $k=>$v){
			//var_dump($v);
			onebike_liudong_new($iid,$v,$start,$end,$judge,$rule);
		}
		
	}
	//����С�����������
	function oneblock_liudong($bid=0,$jiange=0,$start=0,$st=0,$judge=0,$rule=0){
		$redis = new \Redis();
		$redis->connect('127.0.0.1', 6379);
		//$bid=2;
		$all_info = $redis->smembers("block:$bid");
		foreach($all_info as $k=>$v){
			oneinfo_liudong($v,$jiange,$start,$st,$judge,$rule);	
		}
		
	}