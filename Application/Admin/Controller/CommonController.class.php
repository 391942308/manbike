<?php
namespace Admin\Controller;
use Think\Controller;
class CommonController extends BaseController {
    // 自动登录
    public function _initialize(){
        //判断用户是否已经登录
        if (!isset($_SESSION['auth'])) {
            $this->error('对不起,您还没有登录!请先登录再进行浏览', U('Login/index'), 1);
        }
        session_start();
        $uid = $_SESSION['auth']['id'];
        if($uid==1){
            $list=M('menu')->order('sort desc')->select();
            $blocklist=M('info_block')->select();
        }else{
            parent::_initialize();
            $auth=new \Think\Auth();
            session('uid',$uid);
            $this->assign('uid',$uid);
            if($uid==C('ADMIN_AUTH_KEY')){
                return true;
            };
            $ma=MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
            $result=$auth->check($ma,$uid);
            if(!$result){
                $this->error('您没有权限访问');
            }
            //在数组里面的不需要加入权限判断的页面
            //消息 个人中心 登陆 找回密码 朋友分享
            $arr = array(
                'Admin/Login/index',
                'Admin/Login/dologin',
                'Admin/Login/logout'
            );
            $map_aga['uid']=array('eq',$uid);
            $aga = M("auth_group_access")->where($map_aga)->select();//查询出该uid对应的组的信息
			
			//将群组信息放到session里面，方便后面根据区域进行统计和显示
			//var_dump($aga[0]['group_id']);
			$map_pri['id']=$aga[0]['group_id'];
			$mag = M("auth_group")->where($map_pri)->find();
			//var_dump($mag);
			$_SESSION['auth']['class']=$mag['class'];
			$_SESSION['auth']['province']=$mag['province'];
			$_SESSION['auth']['city']=$mag['city'];
			$_SESSION['auth']['area']=$mag['area'];
			
			//var_dump($_SESSION['auth']);
			
            $ags = array();
            //获取用户对应的所有用户组
            foreach($aga as $k=>$v){
                $map_ag['id']=array('eq', $v['group_id']);//获取group_id的值
                $ags[]= M("auth_group")->where($map_ag)->find();//根据id查询出对应组的信息
            }
            //对一个用户属于多个组进行处理，得到所有的菜单id
            $menuss=array();
            foreach($ags as $k=>$v){
                $str = $v['rules'];
                //var_dump($str);
                $arr = explode(',',$str);
                $menuss[]=$arr;
                //var_dump($arr);
            }
            //将所有的菜单id数组合并
            $all_arr=array();
            foreach($menuss as $k=>$v){
                $all_arr = array_merge($all_arr,$v);
            }
            //去掉数组中重复的id
            $arr2=array_unique($all_arr);
            $list2 = array();
            foreach($arr2 as $k=>$v){
                $map_m['id']=array('eq',$v);
                $list2[]=M('menu')->where($map_m)->find();
            }
            //var_dump($list2);
            $list = $this->my_sort($list2,'sort',SORT_DESC,SORT_STRING);
            $blockss=array();
            foreach($ags as $k=>$v){
                $str3 = $v['blocks'];
                //var_dump($str);
                $arr3 = explode(',',$str3);
                $blockss[]=$arr3;
                //var_dump($arr);
            }
            //将所有的菜单id数组合并
            $all_arr3=array();
            foreach($blockss as $k=>$v){
                $all_arr3 = array_merge($all_arr3,$v);
            }
            //去掉数组中重复的id
            $arr4=array_unique($all_arr3);
            $blocklist = array();
            foreach($arr4 as $k=>$v){
                $map_m2['id']=array('eq',$v);
                $blocklist[]=M('info_block')->where($map_m2)->find();
               // var_dump($blocklist);
            }
        }
        $this->assign("blocklist",$blocklist);
        $list=$this->list_to_tree($list); //详细参数见手册
		//var_dump($list);
        $this->assign("list",$list);
    }
	
	//$list排序
    function my_sort($arrays,$sort_key,$sort_order=SORT_DESC,$sort_type=SORT_NUMERIC ){
        foreach( $arrays as $k=>$v){
            if( !$v )
                unset( $arrays[$k] );
        }
        if(is_array($arrays)){
            $key_arrays=array();
            foreach ($arrays as $array){
                if(is_array($array)){
                    $key_arrays[] = $array[$sort_key];

                }else{
                    return false;
                }
            }
        }else{
            return false;
        }

        array_multisort($key_arrays,$sort_order,$sort_type,$arrays);
        return $arrays;
    }
	
    function list_to_tree($list, $pk='id',$pid = 'pid',$child = '_child',$root=0) {
        // 创建Tree
        $tree = array();
        if(is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId = $data[$pid];
                if ($root == $parentId) {
                    $tree[] =& $list[$key];
                }else{
                    if (isset($refer[$parentId])) {
                        $parent =& $refer[$parentId];
                        $parent[$child][] =& $list[$key];
                    }
                }
            }
        }
        return $tree;
    }
}