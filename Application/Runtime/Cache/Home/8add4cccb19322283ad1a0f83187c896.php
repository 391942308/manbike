<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	<style type="text/css">
		body, html,#allmap {width: 100%;height: 100%;overflow: hidden;margin:0;font-family:"微软雅黑";}
		#l-map{height:100%;width:78%;float:left;border-right:2px solid #bcbcbc;}
		#r-result{height:100%;width:20%;float:left;}
	</style>
	<script src="https://cdn.bootcss.com/jquery/1.12.1/jquery.js"></script>
	<script src="http://echarts.baidu.com/gallery/vendors/echarts/echarts-all-3.js"></script>
	<script src="/manbike0.3/Public/myjslib/mui.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=72d96fa612dedb926c0b81c8806203b0"></script>
	<link href="http://www.bootcss.com/p/buttons/css/buttons.css" rel="stylesheet" type="text/css"/>
	<title>显示界面--车辆实时调度系统v0.3</title>
</head>
<body>
	<div id="allmap"></div>
	<div id="log_panel" class="" style="display:block;">
		<div id="diaodu_message" style="width:100%;height:40px;position:fixed;bottom:0px;right:0px;text-align:center;padding-top:10px;background:#4876FF;color:white;">
		未初始化
		</div>
		<a id="show_log_panel" style="z-index:99;position:fixed;background:#4876FF;right:0px;bottom:0px;height:40px;color:white;padding-top:10px;border-left:1px solid white;padding-left:10px;padding-right:10px;" href="javascript:void(0);">关闭</a>
	</div>
	<div id="console" style="position:fixed;left:0px;top:0px;display:none;">
	 <a id="cur_dwz_info_id" href="javascript:void(0);" class="button button-3d button-primary button-rounded button-small">未选择</a>
	 <a id="show_history_panel" href="javascript:void(0);" class="button button-3d button-primary button-rounded button-small">历史消息</a>
	 <!--<a target="_blank" id="show_realtime_form" href="javascript:void(0);" class="button button-primary button-rounded button-small">提交实时车辆</a>-->
	 <a id="show_realtime_panel" href="javascript:void(0);" class="button button-3d button-primary button-rounded button-small">数量详情</a>
	 <a id="show_realtime_panel_2" href="javascript:void(0);" class="button button-3d button-primary button-rounded button-small">历史变化</a>
	 <a id="show_login_panel" href="javascript:void(0);" class="button button-3d button-primary button-rounded button-small">登陆界面</a>
	 <a id="show_diaodu_panel" href="javascript:void(0);" class="button button-3d button-primary button-rounded button-small">人工调度</a>
	 <a id="show_chewei_panel" href="javascript:void(0);" class="button button-3d button-primary button-rounded button-small">信息修改</a>
	 <a id="show_inout_panel" href="javascript:void(0);" class="button button-3d button-primary button-rounded button-small">出入修改</a>
	 <!--<a id="enable_input_mode" href="javascript:void(0);" class="button button-3d button-primary button-rounded button-small">录入模式</a>-->
	 <!--<a id="enable_chewei_mode" href="javascript:void(0);" class="button button-3d button-primary button-rounded button-small">启用实时车位模式</a>-->
	 <!--<a id="show_log_panel" href="javascript:void(0);" class="button button-3d button-primary button-rounded button-small">关闭操作</a>-->
	</div>
	<div id="sub_form" style="position:fixed;right:0px;top:100px;background:yellow;width:200px;height:300px;display:none;">
	<h1>提交实时车辆</h1>
	<form>
	<input name="bike_company" type="text" value="ofo" id="bike_company"  required="required" class="form-control col-md-7 col-xs-12">
	<input name="dwz_info_id" type="text" value="5820" id="dwz_info_id" required="required" class="form-control col-md-7 col-xs-12">
	<input name="dwz_info_title" type="text" value="云站" id="dwz_info_title" required="required" class="form-control col-md-7 col-xs-12">
	</form>
	</div>
	<div id="diaodu_panel" class="panel close_panel" style="position:fixed;left:0px;bottom:50px;background:white;width:350px;height:360px;display:none;border:1px solid #4876FF">
	<div style="text-align:center;"><h3>人工调度</h3></div>
		<div style="margin-left:0px;">
		
		<table style="border:0px"	>
		  <tr>
			<th>调度组名称:</th>
			<th><input id="send_group" name="group" type="text" value="" placeholder="张三组" id="bike_company"  required="required" class="form-control col-md-7 col-xs-12"></th>
			<th></th>	
		  </tr>
		  <tr>
			<th>调度员姓名:</th>
			<td><input id="send_usr" name="name" type="text" value="" placeholder="张三" id="bike_company"  required="required" class="form-control col-md-7 col-xs-12"></td>
			<td>注：多个调度员用英文逗号分隔</td>
		  </tr>
		  <tr>
			<th>调度信息:</th>
			<td colspan="2"><textarea rows="5" cols="30" id="send_content" class="form-control" rows="3" placeholder="5820车位超出10辆，调派到5819车位"></textarea></td>
		  </tr>
		  <tr >
		  
		  <td colspan="3" style="text-align:right;" ><a id="send" href="javascript:void(0);" class="button button-3d button-primary button-rounded button-small">发送</a></td>
		  
		  </tr>
		  
		</table>
		
		</div>
	
	</div>
	<div id="realtime_panel" class="panel close_panel" style="position:fixed;left:0px;bottom:50px;background:white;width:400px;height:350px;display:none;border:1px solid #4876FF">
	<!-- 为ECharts准备一个具备大小（宽高）的Dom -->
    <div id="main" style="width: 350px;height:300px;"></div>
	</div>
	<div id="realtime_panel_2" class="panel close_panel" style="position:fixed;left:0px;bottom:50px;background:white;width:600px;height:300px;display:none;border:1px solid #4876FF">
	 <!-- 为ECharts准备一个具备大小（宽高）的Dom -->
    <div id="main_2" style="width: 600px;height:300px;"></div>
	</div>
	<div id="history_panel" class="panel close_panel" style="position:fixed;left:0px;bottom:50px;background:white;width:800px;height:500px;display:none;border:1px solid #4876FF">
		<div style="text-align:center"><h2>历史消息记录</h2></div>
		<div style="text-align:right;"><a target="_blank" href="http://116.62.171.54:8080/manbike0.3/index.php/Home/Index/message_list">更多记录...</a></div>
		<div id="history_panel_content"></div>
	</div>
	<div id="chewei_panel" class="panel close_panel" style="position:fixed;left:0px;bottom:50px;background:white;width:300px;height:300px;display:none;border:1px solid #4876FF">
		<div style="text-align:center"><h3>车位信息输入界面</h3></div>
		<table border="0px">
		  <tr>
			<th>车位编号</th>
			<th><input name="dwz_info_id" type="text" value="5820" id="dwz_info_id_chewei"  required="required" class="form-control col-md-7 col-xs-12"></th>
		  </tr>
		  <tr>
			<th>提交时间</th>
			<td><input name="time" type="text" value="123456789" id="time" required="required" class="form-control col-md-7 col-xs-12"></td>
		  </tr>
		  <tr>
			<th>车位当前数量</th>
			<th><input name="nums" type="text" value="20" id="nums" required="required" class="form-control col-md-7 col-xs-12"></th>
		  </tr>
		  <tr>
			<th>备注</th>
			<th><input name="backup" type="text" value="备注信息" id="backup" required="required" class="form-control col-md-7 col-xs-12"></th>
		  </tr>
		  <tr>
			<th>所有车辆信息</th>
			<th><input type="text" name="bikes" type="text" value='[{"bind":"10","mac":"C9:F0:28:FD:89:48","name":"mb_SIn9KPDJ","rssi":"-79"},{"bind":"10","mac":"08:46:CD:DD:1B:75","name":"NULL","rssi":"-71"},{"bind":"10","mac":"88:C2:55:C6:9E:44","name":"BlueTooth Printer","rssi":"-76"},{"bind":"10","mac":"88:C2:55:A3:9F:E7","name":"ziJiang printer","rssi":"-78"},{"bind":"10","mac":"19:18:FC:04:55:9C","name":"NULL","rssi":"-86"},{"bind":"10","mac":"19:18:FC:03:BA:C5","name":"NULL","rssi":"-95"},{"bind":"10","mac":"19:18:FC:03:BA:B6","name":"NULL","rssi":"-82"}]' id="bikes"  required="required" class="form-control col-md-7 col-xs-12"></th>
		  </tr>
		  <tr>
		  <td colspan="3" style="text-align:right;" ><a id="send_chewei" href="javascript:void(0);" class="button button-3d button-primary button-rounded button-small">发送</a></td>
		  </tr>
		</table>
	</div>
	<div id="inout_panel" class="panel close_panel" style="position:fixed;left:0px;bottom:50px;background:white;width:300px;height:300px;display:none;border:1px solid #4876FF">
		<div style="text-align:center;"><h3>车辆信息输入界面</h3></div>
		<table border="0px">
		  <tr>
			<th>车辆所属公司</th>
			<th><input name="bike_company" type="text" value="ofo" id="bike_company"  required="required" class="form-control col-md-7 col-xs-12"></th>
		  </tr>
		  <tr>
			<th>车位编号</th>
			<td><input name="dwz_info_id" type="text" value="5820" id="dwz_info_id_bike" required="required" class="form-control col-md-7 col-xs-12"></td>
		  </tr>
		  <tr>
			<th>车位标题</th>
			<th><input name="dwz_info_title" type="text" value="云站" id="dwz_info_title" required="required" class="form-control col-md-7 col-xs-12"></th>
		  </tr>
		  <tr>
			<th>车位坐标</th>
			<th><input name="gps" type="text" value="123,22" id="gps" required="required" class="form-control col-md-7 col-xs-12"></th>
		  </tr>
		  <tr>
			<th>ibeacon编号</th>
			<th><input type="text" name="ibeacon_id" type="text" value="11-22-xx-tt" id="ibeacon_id"  required="required" class="form-control col-md-7 col-xs-12"></th>
		  </tr>
		  <tr>
			<th>进出数量</th>
			<th><input type="text" name="inout" id="inout" value="1" required="required" class="form-control col-md-7 col-xs-12"></th>
		  </tr>
		  <tr>
		  <td colspan="3" style="text-align:right;" ><a id="send_bike" href="javascript:void(0);" class="button button-3d button-primary button-rounded button-small">发送</a></td>
		  </tr>
		</table>
	</div>
	<div id="login_panel" class="panel close_panel" style="position:fixed;left:0px;bottom:50px;background:white;width:250px;height:200px;display:none;border:1px solid #4876FF">
		<div style="text-align:center;"><h2>登陆</h2></div>
		<div style="margin:0px auto;">
		<table border="0px">
		  <tr>
			<th>用户名：</th>
			<th><input id="usr" type="text" name="usr" value="" /></th>
		  </tr>
		  <tr>
			<th>密码：</th>
			<td><input id="pwd" type="text" name="pwd" value="" /></td>
		  </tr>
		  <td colspan="2" style="text-align:right;"><a id="login" href="javascript:void(0);" class="button button-3d button-primary button-rounded button-small">登陆</a></td>
		</table>
		</div>
	</div>
	
	<div id="luru_panel" class="panel close_panel" style="position:fixed;left:0px;bottom:50px;background:white;width:250px;height:300px;display:none;border:1px solid #4876FF">
		<div style="text-align:center;"><h2>车位信息录入</h2></div>
		<div style="margin:0px auto;">
		<table border="0px">
		  <tr>
			<th>标题：</th>
			<th><input id="luru_panel_title" type="text" name="tt" value="" /></th>
		  </tr>
		  <tr>
			<th>经度：</th>
			<td><input id="luru_panel_lng" type="text" name="lng" value="" /></td>
		  </tr>
		  <tr>
			<th>纬度：</th>
			<td><input id="luru_panel_lat" type="text" name="lat" value=""  /></td>
		  </tr>
		  <tr>
			<th>可用车位数量：</th>
			<td><input id="luru_panel_no" type="text" name="luru_panel_no" value="" /></td>
		  </tr>
		  <td colspan="2" style="text-align:right;"><a id="baocun" href="javascript:void(0);" class="button button-3d button-primary button-rounded button-small">保存</a></td>
		</table>
		</div>
	</div>
	<!-------------------------------------------->
	<input type="hidden" id="dwz_info_id" value="" />
</body>
</html>
<script type="text/javascript">
	// 百度地图API功能
	var markerArr = new Array();
	var center=null;
	var enable_input_mode=false;
	var enable_chewei_mode=false;
	//界面打开的控制值,当界面打开以后就可以使用websocket触发
	var kg_history_panel=false;
	
	var map = new BMap.Map("allmap");    
	//路口 120.180029, 30.184971
	//东边路 120.18103, 30.1844
	//map.centerAndZoom(new BMap.Point(120.18046, 30.184333), 19);  
	city = '杭州市';
	map.centerAndZoom(city,12); 
	//setTimeout(function(){
		//map.setZoom(14);   
	//}, 2000);  //2秒后放大到14级
	map.enableScrollWheelZoom(true);
	
	var is_move = false;
	//前台拖动显示不同的点
	map.addEventListener('dragend', function(e){
		is_move = true;
		var lng=this.getCenter().lng;
		var lat = this.getCenter().lat;
		//alert(lng);
		//alert(lat);
		render(lng,lat);
	});	
	map.addEventListener("click", function(e){  
      if(enable_input_mode == true){
		//alert(e.point.lng + ", " + e.point.lat);     
			$(".panel").css("display","none");
			var display = $("#luru_panel").css("display");
			if(display == 'block'){
				$("#luru_panel").css("display","none");
			}else{
				$("#luru_panel").css("display","block");
			}
			$("#luru_panel_lng").val(e.point.lng);
			$("#luru_panel_lat").val(e.point.lat);
	  }	
	});

// 编写自定义函数,创建标注
	function addMarker(point,id,level,usable_num,storage_num){
	  var marker = new BMap.Marker(point);
	  if(usable_num == 0 ){
		var myIcon = new BMap.Icon("http://116.62.171.54:8080/manbike0.3/Public/myimages/grey.png", new BMap.Size(50,50),{ anchor: new BMap.Size(20, 30) });
		marker = new window.BMap.Marker(point,{icon:myIcon}); //按照地图点坐标生成标记  
	  }else{
		  if(level == 1 || level == 0){
			var myIcon = new BMap.Icon("http://116.62.171.54:8080/manbike0.3/Public/myimages/green.png", new BMap.Size(50,50),{ anchor: new BMap.Size(20, 30) });
			marker = new window.BMap.Marker(point,{icon:myIcon}); //按照地图点坐标生成标记  
		  }
		  if(level == 2){
			var myIcon = new BMap.Icon("http://116.62.171.54:8080/manbike0.3/Public/myimages/yellow.png", new BMap.Size(50,50),{ anchor: new BMap.Size(20, 30) });
			marker = new window.BMap.Marker(point,{icon:myIcon}); //按照地图点坐标生成标记  
		  }
		  if(level == 3){
			var myIcon = new BMap.Icon("http://116.62.171.54:8080/manbike0.3/Public/myimages/red.png", new BMap.Size(50,50),{ anchor: new BMap.Size(20, 30) });
			marker = new window.BMap.Marker(point,{icon:myIcon}); //按照地图点坐标生成标记  
		  }
	  }
	  
	  
	  map.addOverlay(marker);
	  marker.setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画
	  marker.addEventListener("click",attribute);
	  
	  var opts = {
		position : point,    // 指定文本标注所在的地理位置
		offset   : new BMap.Size(-10, -20)    //设置文本偏移量
	}
		var label = new BMap.Label("P"+id+"：可停"+usable_num+"，已停"+storage_num+"", opts);  // 创建文本标注对象
			label.setStyle({
				 color : "red",
				 fontSize : "12px",
				 height : "20px",
				 lineHeight : "20px",
				 fontFamily:"微软雅黑"
			 });
		map.addOverlay(label);  
	  
	}
	
	 
	// 向地图添加25个标注
	/*var allpoints = new Array()
	p0=new Object();
	p0.lng = 120.180029;
	p0.lat = 30.184971;
	p0.level = 3;
	
	p1=new Object();
	p1.lng = 120.18103;
	p1.lat = 30.1844;
	p1.level = 1;
	
	allpoints[0]=p0;
	allpoints[1]=p1;
	
	for (var i = 0; i < allpoints.length; i ++) {
		var point = new BMap.Point(allpoints[i].lng, allpoints[i].lat);
		addMarker(point,allpoints[i].level);
	}*/
	/*function render(){
		$.post(
			"http://116.62.171.54:8080/manbike0.3/index.php/Home/Index/ajax_data",
			{suggest:'suggest'},
			function(allpoints){
			clear();
				//alert(result[0].lng);
				for (var i = 0; i < allpoints.length; i ++) {
					var point = new BMap.Point(allpoints[i].lng, allpoints[i].lat);
					addMarker(point,allpoints[i].id,allpoints[i].level,allpoints[i].usable_num,allpoints[i].storage_num);	
				}
		},'json');
	}
	render();*/
	
	function render(lng,lat){
		//alert(lng);
		//alert(lat);
		$.post(
			"http://116.62.171.54:8080/manbike0.3/index.php/Home/Index/app_findby_location",
			{
			lng:lng,
			lat:lat
			},
			function(allpoints){
		//alert(allpoints.length);
			clear();
				//alert(result[0].lng);
				for (var i = 0; i < allpoints.length; i ++) {
					var point = new BMap.Point(allpoints[i].lng, allpoints[i].lat);
					addMarker(point,allpoints[i].id,allpoints[i].level,allpoints[i].usable_num,allpoints[i].storage_num);	
				}
		},'json');
	}
	render(map.getCenter().lng,map.getCenter().lat);
	
	function clear(){
	    var allOverlay = map.getOverlays();
        for (var i = 0; i < allOverlay.length ; i++)
        {       
            map.removeOverlay(allOverlay[i]);
        }
	}
	
	///////////////////////////////////////////////////////////////////////////////////////////////////////////
//定义控制变量
//当websocket通知到的时候 根据控制变量 实时刷新
var show_realtime_panel_2 = false;

	//使用websocket
		var socket;  
        function Connect(){  
            try{  
                socket=new WebSocket('ws://116.62.171.54:8083');  
            }catch(e){  
                alert('error');  
                return;  
            }  
            socket.onopen = sOpen;  
            socket.onerror = sError;
            socket.onmessage= sMessage;
            socket.onclose= sClose;
        }  
        function sOpen(){
            //alert('connect success!');
			var str = '初始化成功!';
			$("#diaodu_message").html(str);
			
        }
        function sError(e){
            alert("error " + e);
        }
        function sMessage(msg){  
            //alert('server says:' + msg.data);  
			console.log(msg.data);
			var data = msg.data;
			var d = $.parseJSON(data);
			//alert(d.error_code);
			if(d.method=='render') render(map.getCenter().lng,map.getCenter().lat);
			if(d.method=='diaodu') diaodu(d.result);
			if(d.method=='wssend') {
				$("#diaodu_message").html(d.result);
			}
			if(d.method=='wslogin') {
				$("#diaodu_message").html(d.result);
			}
			if(enable_chewei_mode == true) $("#show_realtime_panel").trigger("click");
			if(show_realtime_panel_2 == true) $("#show_realtime_panel_2").trigger("click");
        }
        function sClose(e){
            alert("connect closed:" + e.code);
        }  
        function Send(){
            socket.send(document.getElementById("msg").value);
        } 
        function Close(){
            socket.close();
        }  
	
		Connect();
	
		function diaodu(str){
			$("#diaodu_message").html(str);
		}
//////////////////////////////////////////////////////////////
		$("#baocun").click(function(){
			var luru_panel_title = $("#luru_panel_title").val();
		    var luru_panel_lng = $("#luru_panel_lng").val();
		    var luru_panel_lat = $("#luru_panel_lat").val();
		    var luru_panel_no = $("#luru_panel_no").val();
		    /*alert(luru_panel_title);
		    alert(luru_panel_lng);
		    alert(luru_panel_lat);
		    alert(luru_panel_no);*/
			
			$.post(
			"http://116.62.171.54:8080/manbike0.3/index.php/Home/Mapajax/luru",
			{
			luru_panel_title:luru_panel_title,
			luru_panel_lng:luru_panel_lng,
			luru_panel_lat:luru_panel_lat,
			luru_panel_no:luru_panel_no,
			},
			function(res){
				alert(res.error_code);
				/*if(res.error_code == 0){
					var list = res.result;
					for(var i = 0;i < list.length; i++) {
						var item = list[i];	
						temp = '<tr><th>'+item.id+'</th><th>'+item.content+'</th><th>'+item.time+'</th></tr>';
						str +=temp;
					}
					str +='</table>';
					$("#history_panel_content").append(str);
					//alert(item.id);
					
				}*/
			},'json');
		})
		//登陆方法
		$("#login").click(function(){
			//alert("xx");
			var usr = $("#usr").val();
			var pwd = $("#pwd").val();
			var msg = 'wslogin:'+usr+':'+pwd;
			//alert(usr);
			//var str = '["aaa","wslogin:\u5f20\u4e09"]';
			//alert(str);
			var arr = ['',msg];
			var toStr = JSON.stringify( arr );
			//alert(toStr);
			//return ;
			socket.send(toStr);
		});
		
		//人工调度方法
		$("#send").click(function(){
		var send_group = $("#send_group").val();
		var send_usr = $("#send_usr").val();
		var send_content ='人工调度指令--'+ $("#send_content").val();
		var msg = send_group+":"+send_usr+":"+send_content;
		//alert(send_group);
		//alert(send_usr);
		//alert(send_content);
		msg='wssend:'+msg;
			var arr = ['',msg];
			var toStr = JSON.stringify(arr);
			//alert(toStr);
			//return ;
			socket.send(toStr);
		});
		//发送进出车辆
		$("#send_bike").click(function(){

		    var bike_company = $("#bike_company").val();
		    var dwz_info_id = $("#dwz_info_id_bike").val();
		    var dwz_info_title = $("#dwz_info_title").val();
		    var gps = $("#gps").val();
		    var ibeacon_id = $("#ibeacon_id").val();
		    var inout = $("#inout").val();
			$.post(
			"http://116.62.171.54:8080/manbike0.3/index.php/Home/Index/sub",
			{
			bike_company:bike_company,
			dwz_info_id:dwz_info_id,
			dwz_info_title:dwz_info_title,
			gps:gps,
			ibeacon_id:ibeacon_id,
			inout:inout,
			},
			function(res){
				//alert(res.error_code);
				/*if(res.error_code == 0){
					var list = res.result;
					for(var i = 0;i < list.length; i++) {
						var item = list[i];	
						temp = '<tr><th>'+item.id+'</th><th>'+item.content+'</th><th>'+item.time+'</th></tr>';
						str +=temp;
					}
					str +='</table>';
					$("#history_panel_content").append(str);
					//alert(item.id);
					
				}*/
			},'json');
		});
		//发送车位
		$("#send_chewei").click(function(){
			var dwz_info_id = $("#dwz_info_id_chewei").val();
		    var time = $("#time").val();
		    var nums = $("#nums").val();
		    var gps = $("#gps").val();
		    var backup = $("#backup").val();
		    var bikes = $("#bikes").val();
			$.post(
			"http://116.62.171.54:8080/manbike0.3/index.php/Home/Index/sub_realtime",
			{
				dwz_info_id:dwz_info_id,
				time:time,
				nums:nums,
				gps:gps,
				backup:backup,
				bikes:bikes,
			},
			function(res){
				//alert(res.error_code);
				/*if(res.error_code == 0){
					var list = res.result;
					for(var i = 0;i < list.length; i++) {
						var item = list[i];	
						temp = '<tr><th>'+item.id+'</th><th>'+item.content+'</th><th>'+item.time+'</th></tr>';
						str +=temp;
					}
					str +='</table>';
					$("#history_panel_content").append(str);
					//alert(item.id);
					
				}*/
			},'json');
		});
		
		function attribute(e){
			var p = e.target;
			var lng = p.getPosition().lng;
			var lat = p.getPosition().lat;

		//alert("marker的位置是" + p.getPosition().lng + "," + p.getPosition().lat);    
		var point = new BMap.Point(p.getPosition().lng,p.getPosition().lat);
		map.centerAndZoom(point, 19);
			
			//alert("marker的位置是" + p.getPosition().lng + "," + p.getPosition().lat);    
			//根据经纬度获取到车位编号,并根据控制台的情况，显示隐藏控制台
			//经纬度作为唯一标识
			$("#console").css("display","block");
			/*
			var display = $("#console").css("display");
			if(display == 'block'){
				$("#console").css("display","none");
				$(".panel").css("display","none");
			}else{
				$("#console").css("display","block");
				$(".panel").css("display","none");
			}*/
			
			
			$.post(
			"http://116.62.171.54:8080/manbike0.3/index.php/Home/Mapajax/getid",
			{
				lng:lng,
				lat:lat,
			},
			function(res){
				//alert(res.error_code);
				if(res.error_code == 0){
					//alert(res.result);
					$("#dwz_info_id_chewei").val(res.result);
					$("#dwz_info_id_bike").val(res.result);
					$("#dwz_info_id").val(res.result);
					$("#cur_dwz_info_id").html("P"+res.result);
					
				}
			},'json');	
			
		}
		//显示各家公司的车数量1
		$("#show_realtime_panel").click(function(){
			var id = $("#dwz_info_id").val();
			var name = new Array();
			var value = new Array();
			//alert(id);
			//获取车位的数据
			$.post(
				"http://116.62.171.54:8080/manbike0.3/index.php/Home/Mapajax/realtime_detail",
				{
					id:id,
				},
				function(res){
					//alert(res.error_code);
					if(res.error_code == 0){
						var list = res.result;
						//alert(res.length);
						var i = 0;
						for(k in list)
						{
						//alert(list[k]);
						    name[i]=k;
							value[i]=list[k];
							i++;
						}

						showvolume(name,value)
						//$("#dwz_info_id_chewei").val(res.result);
						//$("#dwz_info_id_bike").val(res.result);
						//$("#dwz_info_id").val(res.result);
					}
				},'json');	
					
		
		
			//alert("show_realtime_form");
			$(".panel").css("display","none");
			var display = $("#realtime_panel").css("display");
			if(display == 'block'){
				$("#realtime_panel").css("display","none");
			}else{
				$("#realtime_panel").css("display","block");
			}
		})
		
		//柱状图图标显示
		function showvolume(name,value){
		//alert(name);
		var id = $("#dwz_info_id").val();
				// 基于准备好的dom，初始化echarts实例
					var myChart = echarts.init(document.getElementById('main'));

					// 指定图表的配置项和数据
					var option = {
						 // 图表标题
						title: {
							text: id + '车位数量详情'
						},
						tooltip: {},
						legend: {
							x: 'right',
							data:['数量']
						},
						xAxis: {
							type: 'category',  
							boundaryGap: [0, 0.01],  
							axisLabel:{
								interval:0,
								rotate:-30
							},
							name: '车企', 
							data:name
						},
						yAxis: {},
						series: [{
							barMaxWidth:10,//最大宽度
							name: '数量',
							type: 'bar',
							data: value
						}],
						color:['#4876FF', 'green','yellow','blueviolet']
					};

					// 使用刚指定的配置项和数据显示图表。
					myChart.setOption(option);
		}
		
		//显示各家公司的车数量2
		$("#show_realtime_panel_2").click(function(){
			
			
			var id = $("#dwz_info_id").val();
			var name = new Array();
			var value = new Array();
			//alert(id);
			//获取车位的数据
			$.post(
				"http://116.62.171.54:8080/manbike0.3/index.php/Home/Mapajax/history_tb",
				{
					id:id,
				},
				function(res){
					//alert(res.error_code);
					if(res.error_code == 0){
						var list = res.result;
						//alert(res.length);
						var i = 0;
						for(k in list)
						{
						//alert(k);
						//alert(list[k].time);
						    name[i]=list[k].time;
							value[i]=list[k].storage_num;
							i++;
						}

						showvolume_2(name,value)
						//$("#dwz_info_id_chewei").val(res.result);
						//$("#dwz_info_id_bike").val(res.result);
						//$("#dwz_info_id").val(res.result);
					}
				},'json');	
					
		
		
			//alert("show_realtime_form");
			$(".panel").css("display","none");
			var display = $("#realtime_panel_2").css("display");
			if(display == 'block'){
				$("#realtime_panel_2").css("display","none");
				show_realtime_panel_2 = false;
			}else{
				$("#realtime_panel_2").css("display","block");
				show_realtime_panel_2 = true;
			}
		})
		
		//柱状图图标显示
		function showvolume_2(name,value){

		//alert(name);
		var id = $("#dwz_info_id").val();
		
				// 基于准备好的dom，初始化echarts实例
					var myChart = echarts.init(document.getElementById('main_2'));

					// 指定图表的配置项和数据
					option = {
						grid : {
							top : 100,    //距离容器上边界40像素
							bottom: 100,   //距离容器下边界30像素
							right:100,
							tooltip:{
								position: [100, 100]
							}
						},
						title : {
							text: id+'车位车辆变化情况',
							//subtext: '纯属虚构'
						},
						tooltip : {
							trigger: 'axis'
						},
						legend: {
							data:['停车数量']
						},
						toolbox: {
							show : true,
							//left:500,
							feature : {
								mark : {show: true},
								dataView : {show: true, readOnly: false},
								magicType : {show: true, type: ['line', 'bar']},
								restore : {show: true},
								saveAsImage : {show: true}
							}
						},
						calculable : true,
						xAxis : [
							{
								type : 'category',
								boundaryGap: [0, 0.01],  
								axisLabel:{
									interval:0,
									rotate:-30
								},
								data : name
							}
						],
						yAxis : [
							{
								type : 'value',
								axisLabel : {
									formatter: '{value} 辆'
								}
							}
						],
						series : [
							{
								name:'停车数量',
								type:'line',
								data:value,
								markPoint : {
									data : [
										{type : 'max', name: '最大值'},
										{type : 'min', name: '最小值'}
									]
								},
								markLine : {
									data : [
										{type : 'average', name: '平均值'}
									]
								}
							}
						],
						color:['#4876FF', 'green','yellow','blueviolet']
					};

					// 使用刚指定的配置项和数据显示图表。
					myChart.setOption(option);

		}
		
		
		
		$("#show_diaodu_panel").click(function(){
			//alert("show_diaodu_panel");
			$(".panel").css("display","none");
			var display = $("#diaodu_panel").css("display");
			if(display == 'block'){
				$("#diaodu_panel").css("display","none");
			}else{
				$("#diaodu_panel").css("display","block");
			}
		})
		$("#show_history_panel").click(function(){
			//alert("show_history_form");
			$("#history_panel_content").html("");
			var str = '<table border="0px">';
			var temp = '';
			$.post(
			"http://116.62.171.54:8080/manbike0.3/index.php/Home/Mapajax/history_list",
			{suggest:'suggest'},
			function(res){
				if(res.error_code == 0){
					var list = res.result;
					for(var i = 0;i < list.length; i++) {
						var item = list[i];	
						temp = '<tr><th>'+item.id+'</th><th>'+item.content+'</th><th>'+item.time+'</th></tr>';
						str +=temp;
					}
					str +='</table>';
					$("#history_panel_content").append(str);
					//alert(item.id);
					
				}
			},'json');
			
			
			$(".panel").css("display","none");
			var display = $("#history_panel").css("display");
			if(display == 'block'){
				$("#history_panel").css("display","none");
			}else{
				$("#history_panel").css("display","block");
			}
			
		})
		$("#show_log_panel").click(function(){
			//alert("show_log_panel");
			$(".panel").css("display","none");
			$("#console").css("display","none");
			show_realtime_panel_2 = false;
			return;
			var display = $("#log_panel").css("display");
			if(display == 'block'){
				$("#log_panel").css("display","none");
			}else{
				$("#log_panel").css("display","block");
			}
		})
		$("#show_login_panel").click(function(){
			//alert("show_log_panel");
			$(".panel").css("display","none");
			var display = $("#login_panel").css("display");
			if(display == 'block'){
				$("#login_panel").css("display","none");
			}else{
				$("#login_panel").css("display","block");
			}
		})
		$("#show_chewei_panel").click(function(){
			//alert("show_log_panel");
			$(".panel").css("display","none");
			var display = $("#chewei_panel").css("display");
			if(display == 'block'){
				$("#chewei_panel").css("display","none");
			}else{
				$("#chewei_panel").css("display","block");
			}
		})
		$("#show_inout_panel").click(function(){
			//alert("show_log_panel");
			$(".panel").css("display","none");
			var display = $("#inout_panel").css("display");
			if(display == 'block'){
				$("#inout_panel").css("display","none");
			}else{
				$("#inout_panel").css("display","block");
			}
		})
		
		
		$("#enable_input_mode").click(function(){
			$(".panel").css("display","none");
			if(enable_input_mode == false){
				enable_input_mode=true;
				$("#enable_input_mode").text("关闭点击录入模式(启用中)");
				//alert(enable_input_mode);
			}else{
				enable_input_mode=false;
				$("#enable_input_mode").text("启用点击录入模式(关闭中)");
				//alert(enable_input_mode);
			}
			//alert(enable_input_mode);
		})
		$("#enable_chewei_mode").click(function(){
			$(".panel").css("display","none");
			if(enable_chewei_mode == false){
				enable_chewei_mode=true;
				$("#enable_chewei_mode").text("关闭实时车位模式(启用中)");
				//alert(enable_input_mode);
			}else{
				enable_chewei_mode=false;
				$("#enable_chewei_mode").text("启用实时车位模式(关闭中)");
				//alert(enable_input_mode);
			}
			//alert(enable_input_mode);
		})
    </script>  
	<script type="text/javascript">
        $(".close_panel").dblclick(function(){
			$(".close_panel").css("display","none");
		});
    </script>