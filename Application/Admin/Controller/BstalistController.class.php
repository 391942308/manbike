<?php
namespace Admin\Controller;
use Think\Controller;
class BstalistController extends CommonController {
	public function _initialize(){
		parent::_initialize();
	}
    public function index()
    {
        $block_list=M("info_block")->select();
        $this->assign("block_list",$block_list);
        if($_POST){
            $redis = new \Redis();
            $redis->connect('116.62.171.54', 8085);
            $id="";
            $start="";
            $end="";
            $data = M('bike_sub_realtime');
            $sql = "SELECT * FROM dwz_bike_sub_realtime where 1=1 ";
            if (!empty($_REQUEST["id"])) {
                $id = $_REQUEST["id"];
                $info_list=M('info')->where("block_no=$id")->select();
               //var_dump($info_list);
                if(!empty($info_list)){
                    foreach($info_list as $k=>$v){
                        $ids[]=$v["id"];
                    }
                    $ids2=implode(",",$ids);
                    //var_dump($ids2);
                    $sql .= " and dwz_info_id in ($ids2) ";
                }else{
                    $sql .= " and dwz_info_id = '' ";
                }
                $this->assign('id', $id);
            }
            if (!empty($_REQUEST["start"]) || $_REQUEST["start"]==='0') {
                $start = $_REQUEST["start"];
                $start2 = strtotime($_REQUEST["start"]);
                $sql .= " and time >= $start2 ";
                $this->assign('start', $start);
            }
            if (!empty($_REQUEST["end"]) || $_REQUEST["end"]==='0') {
                $end = $_REQUEST["end"];
                $end2 = strtotime($_REQUEST["end"]);
                $sql .= " and time <= $end2 ";
                $this->assign('end', $end);
            }
            $info = $data->query($sql);
           // var_dump($info);
            $arr2=array();
            foreach($info as $k=>$v){
                $arr2[]=json_decode($v["bikes"],true);
            }
            //var_dump($arr2);
            foreach($arr2 as $v1){
                foreach($v1 as $v2){
                    //var_dump($v2["mac"]);
                    $redis->sadd("statistics:".$id.$start2.$end2,$v2["mac"]);
                }
            }

            $stanum=$redis->scard("statistics:".$id.$start2.$end2);
            $stalist2=$redis->smembers("statistics:".$id.$start2.$end2);
            foreach($stalist2 as $k=>$v){
                $mac = str_replace(':','-',$v);
                //var_dump($mac);
                //echo "<br>";
                $stalist3[$k]['name']=$redis->get('bikes:'.$mac);
                $stalist3[$k]['mac']=$v;
            }
            //var_dump($stalist3);
            //运算结束以后删除
            $redis->del("statistics:".$id.$start2.$end2);

            $this->assign('stanum', $stanum);
            $this->assign('stalist3', $stalist3);
        }
        $this->display();
    }
}