<?php
namespace Admin\Controller;
use Think\Controller;
class FenxiController extends CommonController {
	public function _initialize(){
		parent::_initialize();
	}
    public function index(){
		if($_POST){
			$redis = new \Redis();
			$redis->connect('116.62.171.54', 8085);
			M("bike_sub_realtime")->where
			echo "xx";
			//根据原始数据，	
		}else{
			
		}
		
	}
}