<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	<style type="text/css">
		body, html {width: 100%;height: 100%;margin:0;font-family:"微软雅黑";}
		#allmap{width:100%;height:100%;	}
		#r-result{width:300px;margin-top:5px;}
		p{margin:5px; font-size:14px;}
	</style>
	<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=72d96fa612dedb926c0b81c8806203b0"></script>
	<script type="text/javascript" src="http://api.map.baidu.com/library/TextIconOverlay/1.2/src/TextIconOverlay_min.js"></script>
	<script type="text/javascript" src="http://api.map.baidu.com/library/MarkerClusterer/1.2/src/MarkerClusterer_min.js"></script>
	<script type="text/javascript" src="http://api.map.baidu.com/library/Heatmap/2.0/src/Heatmap_min.js"></script>
	
	<script src="http://echarts.baidu.com/gallery/vendors/echarts/echarts-all-3.js"></script>
	<link href="http://www.bootcss.com/p/buttons/css/buttons.css" rel="stylesheet" type="text/css"/>
	
	<title>单车地图v0.4</title>
	<script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.js"></script>
</head>
<body>
<div>
	<div style="float:left;"><img style="" src="/manbike0.3/Public/myimages/nlogo.png" />
	</div>
	<select style="margin-top:30px;margin-left:10px;" id="citys">
	  <option value ="杭州市">杭州市</option>
	  <option value="北京市">北京市</option>
	  <option value="上海市">上海市</option>
	  <option value ="广州市">广州市</option>
	</select>
	<input id="keyword" type="text" placeholder="车辆名称" name="热点名称" value="" />
	<input id="chaxun" type="button" value="查询" />
	<input id="tkeyword" type="text" placeholder="车位编号" name="车位名称" value="" />
	<input id="tiaozhuan" type="button" value="跳转" />
	<input id="autoplay_key_1" class="autoplay_key" type="button" value="手动切换中" />
	<input id="shishi_5000_0" class="shishi" type="button" value="隔5S刷新中" />
	<a target="_blank" href="http://116.62.171.54:8080/manbike0.3/index.php/Home/Show/heatmap"> 
		<input type="button" value="热力图" />
	</a>
	<a target="_blank" href="http://116.62.171.54:8080/manbike0.3/index.php/Home/Show/logout"> 
		<input type="button" value="登出" />
	</a>
	<!--<input id="getlevel" type="button" value="查询等级" />
	<span>区域</span>
	<span>板块</span>
	<span>清除条件</span>-->
	<!--<input type="button"  onclick="openHeatmap();" value="打开热力图"/><input type="button"  onclick="closeHeatmap();" value="关闭热力图"/>-->
	
	<div style="display:none;" id="r-result">
		城市名: <input id="cityName" type="text" style="width:100px; margin-right:10px;" />
		<input id="cityName_click" type="button" value="查询" onclick="theLocation()" />
	</div>
	<div style="clear:both;"></div>
</div>
<div style="width:100%;height:90%;">
	<div id="stool" style="position:absolute;left:26%;width:1%;height:100px;background-color:white;z-index:100;margin-top:20%;">
		<br /><br /><
	</div>
	<div id="list_panel" style="position:absolute;width:26%;height:90%;background-color:#f0f0f0;z-index:99;overflow:auto; ">
	
		<div style="padding:15px;">为您找到以下物品</div>
		<div id="list_info"></div>
		<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div id="<?php echo ($vo["area"]); ?>" data-lon="<?php echo ($vo["location"]["lon"]); ?>" data-lat="<?php echo ($vo["location"]["lat"]); ?>" class="item" style="border-bottom:solid 1px grey ;margin-top:1px;">
			<div style="float:left;background-color:white;width:30%;height:100px;">
			<?php if(($vo["company"] == '摩拜')): ?><img style="width:100px;height:100px;" src="/manbike0.3/Public/images/dc.png" />
			<?php elseif(($vo["company"] == 'ofo')): ?>
			<img style="width:100px;height:100px;" src="/manbike0.3/Public/images/dc.png" />
			<?php elseif(($vo["company"] == 'HelloBike')): ?>
			<img style="width:100px;height:100px;" src="/manbike0.3/Public/images/dc.png" />
			<?php elseif(($vo["company"] == '酷骑')): ?>
			<img style="width:100px;height:100px;" src="/manbike0.3/Public/images/dc.png" />
			<?php elseif(($vo["company"] == '小明单车')): ?>
			<img style="width:100px;height:100px;" src="/manbike0.3/Public/images/dc.png" />
			<?php elseif(($vo["company"] == '老人')): ?>
			<img style="width:100px;height:100px;" src="/manbike0.3/Public/images/yl.png" />
			<?php elseif(($vo["company"] == '宠物')): ?>
			<img style="width:100px;height:100px;" src="/manbike0.3/Public/images/cw.png" />
			<?php else: ?> 无图片<?php endif; ?>
			</div>
			<div style="float:left;background-color:white;width:70%;height:100px;">
			<br />
			&nbsp;名称：<?php echo ($vo["name"]); ?> &nbsp;<br /> 
			<!--&nbsp;mac：<?php echo ($vo["mac"]); ?> &nbsp; rssi：<?php echo ($vo["rssi"]); ?> &nbsp; <br />-->
			&nbsp;时间：<?php echo (date('Y-m-d h:i:s',$vo["ts"])); ?><br />
			&nbsp;地点：<?php echo ($vo["province"]); echo ($vo["city"]); echo ($vo["area"]); ?>P<?php echo ($vo["dwz_info_id"]); ?>
			
			</div>
			<div style="clear:both"></div>
		</div><?php endforeach; endif; else: echo "" ;endif; ?>
	</div>
	
	<div id="stool1" style="position:absolute;left:38%;width:2%;height:100px;background-color:white;z-index:100;margin-top:20%;">
		<br /><br />&nbsp;&nbsp;<
	</div>
	<div id="list_panel1" style="position:absolute;width:38%;height:90%;background-color:#f0f0f0;z-index:88;overflow:auto;">
		<!-- 为ECharts准备一个具备大小（宽高）的Dom -->
		<br />
		<br />
		<div id="main" style="width: 600px;height:300px;"></div>
		<br />
		<!-- 为ECharts准备一个具备大小（宽高）的Dom -->
		<div id="main_2" style="width: 600px;height:300px;"></div>
		<div id="diaodu_message" style="width:100%;height:40px;bottom:0px;right:0px;text-align:center;padding-top:10px;background:#4876FF;color:white;">未初始化</div>
		<a id="setting" href="javascript:void(0);" class="button button-3d button-primary button-rounded button-small">设置</a>
	</div>
	
	<div id="allmap"></div>
</div>
	
	<input id="dwz_info_id" type="hidden" value="" />
	<input id="dlat" type="hidden" value="<?php echo ($dlat); ?>" />
	<input id="dlng" type="hidden" value="<?php echo ($dlon); ?>" />
</body>
</html>
<script type="text/javascript">

$("#tiaozhuan").click(function(){
	var tkeyword = $("#tkeyword").val();
	//alert(tkeyword);
	$.post(
			"http://116.62.171.54:8080/manbike0.3/index.php/Home/Show/getlnglat",
			{
				id:tkeyword
			},
			function(res){
				//alert(res.error_code);
				if(res.error_code == 0){
					var point = new BMap.Point(res.res.lng,res.res.lat);
					map.centerAndZoom(point, 19);
					
					//设置经纬度，并显示图表
					$("#dlng").val(res.res.lng);
					$("#dlat").val(res.res.lat);
					init_or_drag();
					left_show_after_drag();
				}
			},'json');
	
})

		$("#list_panel").css("display","none");	
		$("#stool").css("left","0%");
		$("#stool").html("<br /><br />&nbsp;>");
		/*$("#list_panel1").css("display","none");
		$("#stool1").css("left","0%");
		$("#stool1").html("<br /><br />&nbsp;&nbsp;>");*/

	function init_or_drag(){
		var lat = $("#dlat").val();
		var lng = $("#dlng").val();
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
					//$("#dwz_info_id_chewei").val(res.result);
					//$("#dwz_info_id_bike").val(res.result);
					$("#dwz_info_id").val(res.result);
					//$("#cur_dwz_info_id").html("P"+res.result);
					realtime_detail();
					history_tb();
					
				}
			},'json');
	}
	init_or_drag();
	
	// 百度地图API功能
	var map = new BMap.Map("allmap",{  
        minZoom : 11,  
        maxZoom : 19
    });
	var point = new BMap.Point(120.059071,30.276691);
	map.centerAndZoom(point, 12);
	map.enableScrollWheelZoom(true);
	map.enableInertialDragging();
	city = '杭州市';
	map.centerAndZoom(point,12);
	
	var json_str = '<?php echo ($json_str); ?>';
	function heatmap(){
		heatmapOverlay = new BMapLib.HeatmapOverlay({"radius":30});
		map.addOverlay(heatmapOverlay);
		var points = $.parseJSON(json_str);
		heatmapOverlay.setDataSet({data:points,max:1000});
	}
	
	
	//缩放
	map.addEventListener("zoomend", function () {
		//...
		var level = map.getZoom();
		var lng=this.getCenter().lng;
		var lat = this.getCenter().lat;
		if(level>12){
			show_level_15(lng,lat);
			left_show_after_drag();
		}
		if(level<=12){
		//alert(0)
			show_level_12(lng,lat);
			left_show_after_drag();
		}
		//alert(1)
	});
	
	//拖动
	map.addEventListener('dragend', function(e){
	
		is_move = true;
		var lng=this.getCenter().lng;
		var lat = this.getCenter().lat;
		var level = map.getZoom();
		//alert(level);
		if(level>12){
			show_level_15(lng,lat);
			left_show_after_drag();
		}
		if(level<=12){
			show_level_12(lng,lat);
			left_show_after_drag();
		}
		//alert(1)
		
	});

	$(".autoplay_key").click(function(){
		console.log($(this).attr('id'));
		//$(this).val("手动点击切换");
		if($(this).attr('id')=='autoplay_key_0'){
			$(this).attr('id',"autoplay_key_1");
			$(this).val("手动点击切换中");
		}else{
			$(this).attr('id',"autoplay_key_0");
			$(this).val("自动切换中");
		}
		
	})
	//根据拖动 显示左边的列表	
	function left_show_after_drag(){
	
		var bs = map.getBounds();   //获取可视区域
		var bssw = bs.getSouthWest();   //可视区域左下角
		var bsne = bs.getNorthEast();   //可视区域右上角
		//alert("当前地图可视范围是：" + bssw.lng + "," + bssw.lat + "到" + bsne.lng + "," + bsne.lat);
		//根据可是范围获取车辆
		var lng=map.getCenter().lng;
		var lat = map.getCenter().lat;
		$.post(
				"http://116.62.171.54:8080/manbike0.3/index.php/Home/Show/get_by_center",
				{
					left_bottom_lng:bssw.lng,
					left_bottom_lat:bssw.lat,
					right_top_lng:bsne.lng,
					right_top_lat:bsne.lat,
					center_lng:lng,
					center_lat:lat,
				},
				function(res){
					//alert(res.error_code);
					//显示在左侧
					var str = '';
					var lat = '';
					var lng = '';
					$(".item").remove();
					$(res.list).each(function(index,element){
						//alert(index);
						if(index==0){
							lat = element.location.lat;
							lng = element.location.lon;
						}
						var src = '/manbike0.3/Public/images/dc.png';
						if(element.company=='宠物') src="/manbike0.3/Public/images/cw.png";
						if(element.company=='老人') src="/manbike0.3/Public/images/yl.png";
						
						str += '<div id="滨江区" data-lon="'+element.location.lon+'" data-lat="'+element.location.lat+'" class="item" style="border-bottom:solid 1px grey ;margin-top:1px;">'
									+'<div style="float:left;background-color:white;width:30%;height:100px;">'
									+'<img style="width:100px;height:100px;" src="'+src+'"></div>'
									+'<div style="float:left;background-color:white;width:70%;height:100px;"><br>&nbsp;名称：'+element.name+' &nbsp;<br> <!--&nbsp;mac：C567AF397481 &nbsp; rssi：-77 &nbsp; <br />-->'
									+'&nbsp;时间：'+  new Date(parseInt(element.ts) * 1000).toLocaleString().substr(0,17)+'<br>&nbsp;地点：'+element.province+element.city+element.area+'P'+element.dwz_info_id+'</div><div style="clear:both"></div></div>';
					})
					$("#list_info").append(str);
					
					addevent();
					
					
					console.log($(".autoplay_key").attr("id"));
					//console.log($("#autoplay_value").val());
					//关闭关联
					if($(".autoplay_key").attr("id") === "autoplay_key_1"){
						console.log($(".autoplay_key").attr("id"));
						return false;
					}
					
					//设置经纬度，并显示图表
					$("#dlng").val(lng);
					$("#dlat").val(lat);
					init_or_drag();
					return ;
				
					//alert(allpoints.length);
					clear();
	
					//alert(result[0].lng);
					for (var i = 0; i < allpoints.length; i ++) {
						var label = new BMap.Label("P"+allpoints[i].id+":"+"可停"+allpoints[i].usable_num+",已停"+allpoints[i].storage_num,{offset:new BMap.Size(20,20)});
						var point = new BMap.Point(allpoints[i].lng, allpoints[i].lat);
						addMarker(point,label,allpoints[i].usable_num,allpoints[i].level);	
					}
			},'json');
		
		
	}
	
	var index = 0;
	var myGeo = new BMap.Geocoder();
	var str_area = '<?php echo ($str_area); ?>';
	var str_area_num = '<?php echo ($str_area_num); ?>';
	//alert(str_area);
	adds = $.parseJSON(str_area);
	adds_num = $.parseJSON(str_area_num);
	function bdGEO(){
		var add = adds[index];
		geocodeSearch(add);
		index++;
	}
	function geocodeSearch(add){
		if(index < adds.length){
			setTimeout(window.bdGEO,100);
		} 
		myGeo.getPoint(add, function(point){
			if (point) {
				//document.getElementById("result").innerHTML +=  index + "、" + add + ":" + point.lng + "," + point.lat + "</br>";
				var address = new BMap.Point(point.lng, point.lat);
				//alert(add);
				//ajax获取车辆数目
				
				addMarker_1(address,new BMap.Label(add+'<br />'+adds_num[add]+'辆',{offset:new BMap.Size(20,20)}));
			}
		}, "杭州市");
	}
	
	var markers = [];
	
	// 编写自定义函数,创建标注
	function addMarker(point,label,usable_num,level){
		if(usable_num == 0 ){
			var myIcon = new BMap.Icon("http://116.62.171.54:8080/manbike0.3/Public/myimages/grey.png", new BMap.Size(50,50),{ anchor: new BMap.Size(20, 30) });
			marker = new window.BMap.Marker(point,{icon:myIcon}); //按照地图点坐标生成标记  
		  }else{
			  if(level == -2){
				var myIcon = new BMap.Icon("http://116.62.171.54:8080/manbike0.3/Public/myimages/alive.png", new BMap.Size(50,50),{ anchor: new BMap.Size(20, 30) });
				marker = new window.BMap.Marker(point,{icon:myIcon}); //按照地图点坐标生成标记  
			  }
			  if(level == -1){
				var myIcon = new BMap.Icon("http://116.62.171.54:8080/manbike0.3/Public/myimages/light.png", new BMap.Size(50,50),{ anchor: new BMap.Size(20, 30) });
				marker = new window.BMap.Marker(point,{icon:myIcon}); //按照地图点坐标生成标记  
			  }
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
			  
			/*var id = $("#dwz_info_id").val();
			console.log(id);
			//alert(id);
			//获取车位的数据
			$.post(
				"http://116.62.171.54:8080/manbike0.3/index.php/Home/Tool/isalive",
				{
					id:id,
				},
				function(res){
					console.log(res.res);
					var myIcon = new BMap.Icon("http://116.62.171.54:8080/manbike0.3/Public/myimages/alive.png", new BMap.Size(50,50),{ anchor: new BMap.Size(20, 30) });
					marker = new window.BMap.Marker(point,{icon:myIcon}); //按照地图点坐标生成标记 
				},'json');*/
		  }
		
		markers.push(marker);
		//var marker = new BMap.Marker(point);
		map.addOverlay(marker);
		/*label.setStyle({ 
			color : "#fff", 
			fontSize : "16px", 
			backgroundColor :"1",
			border :"0", 
			fontWeight :"bold" 
			});*/
		marker.setLabel(label);
		marker.setAnimation(BMAP_ANIMATION_BOUNCE);
		marker.addEventListener("click",attribute);
	}
	// 编写自定义函数,创建标注
	function addMarker_(point,label){
		var myIcon = new BMap.Icon("http://116.62.171.54:8080/manbike0.3/Public/myimages/light.png", new BMap.Size(50,50));
		var marker = new BMap.Marker(point,{icon:myIcon});
		markers.push(marker);
		//var marker = new BMap.Marker(point);
		map.addOverlay(marker);
		marker.setLabel(label);
		marker.setAnimation(BMAP_ANIMATION_BOUNCE);
		marker.addEventListener("click",attribute_);
	}
	// 编写自定义函数,创建标注
	function addMarker_1(point,label){
		var myIcon = new BMap.Icon("http://116.62.171.54:8080/manbike0.3/Public/images/map-sp-mark-1.png", new BMap.Size(92,92));
		var marker = new BMap.Marker(point,{icon:myIcon});
		//var marker = new BMap.Marker(point);
		map.addOverlay(marker);
		label.setStyle({ 
			color : "#fff", 
			fontSize : "16px", 
			backgroundColor :"0.05",
			border :"0", 
			fontWeight :"bold" 
			});
		marker.setLabel(label);
		marker.addEventListener("click",attribute_1);
	}
	// 编写自定义函数,创建标注
	function addMarker_2(point,label){
		var myIcon = new BMap.Icon("http://116.62.171.54:8080/manbike0.3/Public/images/map-sp-mark-2.png", new BMap.Size(92,92));
		var marker = new BMap.Marker(point,{icon:myIcon});
		//var marker = new BMap.Marker(point);
		map.addOverlay(marker);
		marker.setLabel(label);
		marker.addEventListener("click",attribute_1);
	}
	bdGEO();
	
	//根据不同的level和城市 去获取不同的maker 并显示
	// 编写自定义函数,创建标注
	
	function attribute(e){
	
		var lng=map.getCenter().lng;
		var lat = map.getCenter().lat;
		//alert(lng);
		//alert(lat);
		
		
		var p = e.target;
		//alert("marker的位置是" + p.getPosition().lng + "," + p.getPosition().lat);    
		var point = new BMap.Point(p.getPosition().lng,p.getPosition().lat);
		//聚焦到那个点
		//map.centerAndZoom(point, 19);
		//打开下一级
		show_level_15(lng,lat);
		//将左边的收缩到最小
		$("#list_panel").css("display","none");	
		$("#stool").css("left","0%");
		$("#stool").html("<br /><br />&nbsp;>");
		
		//设置经纬度，并显示图表
		$("#dlng").val(p.getPosition().lng);
		$("#dlat").val(p.getPosition().lat);
		init_or_drag();
	}
	
	function realtime_detail(){
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
						var arr=new Array();
						var list = res.result;
						//alert(res.length);
						var i = 0;
						for(k in list)
						{
						//alert(list[k]);
						    name[i]=k+list[k];
							var item=new Object();
							item.value = list[k];
							item.name = name[i];
							arr.push(item);
							//value[i].value=list[k];
							//value[i].name=name[i];
							i++;
						}

						var value=arr;
						//alert(b);
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
		}
	
	function history_tb(){
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
	}
	
	//柱状图图标显示
		function showvolume(name,value){
			var id = $("#dwz_info_id").val();
				// 基于准备好的dom，初始化echarts实例
					var myChart = echarts.init(document.getElementById('main'));

					// 指定图表的配置项和数据
					option = {
							title : {
								text: 'P'+id+'各个单车公司单车数量统计图',
								//subtext: '纯属虚构',
								x:'center'
							},
							tooltip : {
								trigger: 'item',
								formatter: "{a} <br/>{b} : {c} ({d}%)"
							},
							legend: {
								orient: 'vertical',
								left: 'left',
								data: name
							},
							series : [
								{
									name: '公司',
									type: 'pie',
									radius : '55%',
									center: ['50%', '60%'],
									data:value,
									itemStyle: {
										emphasis: {
											shadowBlur: 10,
											shadowOffsetX: 0,
											shadowColor: 'rgba(0, 0, 0, 0.5)'
										}
									}
								}
							]
						};

					// 使用刚指定的配置项和数据显示图表。
					myChart.setOption(option);
		}
	
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
	
	
	function attribute_1(e){
	
		var lng=map.getCenter().lng;
		var lat = map.getCenter().lat;
		//alert(lng);
		//alert(lat);
		
		
		var p = e.target;
		//alert("marker的位置是" + p.getPosition().lng + "," + p.getPosition().lat);    
		var point = new BMap.Point(p.getPosition().lng,p.getPosition().lat);
		map.centerAndZoom(point, 14);
		//打开下一级
		show_level_15(lng,lat);
	}
	function attribute_(e){
	
		var lng=map.getCenter().lng;
		var lat = map.getCenter().lat;
		//alert(lng);
		//alert(lat);
		
		
		var p = e.target;
		//alert("marker的位置是" + p.getPosition().lng + "," + p.getPosition().lat);    
		var point = new BMap.Point(p.getPosition().lng,p.getPosition().lat);
		map.centerAndZoom(point, 18);
		//打开下一级
		show_level_15(lng,lat);
	}
	
	
	function show_level_15(lng,lat){
		render(lng,lat);	
	}
	function show_level_12(lng,lat){
		index=0;
		//将原来的清除掉
		clear();
		//post获取到新的点，然后显示
		bdGEO();
		heatmap();
	}
	
	var top_left_control = new BMap.ScaleControl({anchor: BMAP_ANCHOR_BOTTOM_RIGHT});// 左上角，添加比例尺
	var top_left_navigation = new BMap.NavigationControl({anchor: BMAP_ANCHOR_BOTTOM_RIGHT,enableGeolocation: true});  //左上角，添加默认缩放平移控件
	//var top_right_navigation = new BMap.NavigationControl({anchor: BMAP_ANCHOR_TOP_RIGHT, type: BMAP_NAVIGATION_CONTROL_SMALL}); //右上角，仅包含平移和缩放按钮
	/*缩放控件type有四种类型:
	BMAP_NAVIGATION_CONTROL_SMALL：仅包含平移和缩放按钮；BMAP_NAVIGATION_CONTROL_PAN:仅包含平移按钮；BMAP_NAVIGATION_CONTROL_ZOOM：仅包含缩放按钮*/
	
	//添加控件和比例尺
	function add_control(){
		       
		map.addControl(top_left_navigation); 
		map.addControl(top_left_control); 			
		//map.addControl(top_right_navigation);    
	}
	//移除控件和比例尺
	function delete_control(){
		map.removeControl(top_left_control);     
		map.removeControl(top_left_navigation);  
		map.removeControl(top_right_navigation); 
	}
	
	add_control();
	
	$("#stool").click(function(){
		//alert("xx");
		var style = $("#list_panel").css("display");
		if(style == 'block'){
			$("#list_panel").css("display","none");
			$("#stool").css("left","0%");
			$("#stool").html("<br /><br />&nbsp;>");
		}else{
			$("#list_panel").css("display","block");
			$("#stool").css("left","26%");
			$("#stool").html("<br /><br /><");
		}
		//alert(style);
		
	});
	
	$("#stool1").click(function(){
		//alert("xx");
		var style = $("#list_panel1").css("display");
		if(style == 'block'){
			$("#list_panel1").css("display","none");
			$("#stool1").css("left","0%");
			$("#stool1").html("<br /><br />&nbsp;&nbsp;>");
		}else{
			$("#list_panel1").css("display","block");
			$("#stool1").css("left","38%");
			$("#stool1").html("<br /><br />&nbsp;&nbsp;<");
		}
		//alert(style);
		
	});
	
	function theLocation(){
		var city = document.getElementById("cityName").value;
		if(city != ""){
			map.centerAndZoom(city,12);      // 用城市名设置地图中心点
		}
	}
	
	$("#citys").change(function() { 
		var city = $(this).val();
		//alert(city);
		$("#cityName").val(city);
		$("#cityName_click").trigger("click");
	}); 
	
	$("#getlevel").click(function(){
		var level = map.getZoom();
		alert(level);
	})
	
	
	function clear(){
	    var allOverlay = map.getOverlays();
        for (var i = 0; i < allOverlay.length ; i++)
        {       
            map.removeOverlay(allOverlay[i]);
        }
	}
	
	function addevent(){
		$(".item").mouseover(function(){
						var lon = $(this).attr("data-lon");	
						var lat = $(this).attr("data-lat");	
						//alert(lon);
						//alert(lat);
						var i=0;
						var label = new BMap.Label("在这里",{offset:new BMap.Size(20,20)});
						var point = new BMap.Point(lon, lat);
						addMarker_(point,label);	
			
		});
		$(".item").click(function(){
			var lon = $(this).attr("data-lon");	
			var lat = $(this).attr("data-lat");	
				
			if(map.getZoom()<=16){
				var label = new BMap.Label("在这里",{offset:new BMap.Size(20,20)});
				var point = new BMap.Point(lon,lat);
				map.centerAndZoom(point, 18);				
			}
			if(map.getZoom()>16){
				var label = new BMap.Label("在这里",{offset:new BMap.Size(20,20)});
				var point = new BMap.Point(lon, lat);
				addMarker_(point,label);				
			}
			
			
		});
	
	}
	
	$(document).ready(function(){
		
		addevent();

	});
	
	
	
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
						//var label = new BMap.Label("P"+allpoints[i].id+":"+"可停"+allpoints[i].usable_num+",已停"+allpoints[i].storage_num,{offset:new BMap.Size(20,20)});
						var label = new BMap.Label("P"+allpoints[i].id+":"+allpoints[i].storage_num+"/"+allpoints[i].usable_num,{offset:new BMap.Size(20,20)});
						var point = new BMap.Point(allpoints[i].lng, allpoints[i].lat);
						addMarker(point,label,allpoints[i].usable_num,allpoints[i].level);	
					}
					heatmap();	
			},'json');
			
		
	}
	
	function render_realtime(lng,lat){
	
		
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
						//var label = new BMap.Label("P"+allpoints[i].id+":"+"可停"+allpoints[i].usable_num+",已停"+allpoints[i].storage_num,{offset:new BMap.Size(20,20)});
						var label = new BMap.Label("P"+allpoints[i].id+":"+allpoints[i].storage_num+"/"+allpoints[i].usable_num,{offset:new BMap.Size(20,20)});
						var point = new BMap.Point(allpoints[i].lng, allpoints[i].lat);
						addMarker(point,label,allpoints[i].usable_num,allpoints[i].level);	
					}
				left_show_after_drag();	
				heatmap();	
			},'json');
			
		
	}
	
	$("#chaxun").click(function(){
		
		var keyword = $("#keyword").val();
		//alert(keyword);
		$.post(
				"http://116.62.171.54:8080/manbike0.3/index.php/Home/show/ajax_getitem",
				{
				keyword:keyword
				},
				function(res){
					
					
					var point = new BMap.Point(res.item.lng, res.item.lat);
					map.centerAndZoom(point, 18);
					alert(keyword+"的位置:P"+res.item.dwz_info_id);
					
					
					return ;
					/*clear();
	
					//alert(result[0].lng);
					for (var i = 0; i < allpoints.length; i ++) {
						var label = new BMap.Label("P"+allpoints[i].id+":"+"可停"+allpoints[i].usable_num+",已停"+allpoints[i].storage_num,{offset:new BMap.Size(20,20)});
						var point = new BMap.Point(allpoints[i].lng, allpoints[i].lat);
						addMarker(point,label,allpoints[i].usable_num,allpoints[i].level);	
					}*/
			},'json');
	})
	
	
	
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
			//console.log(str);
			$("#diaodu_message").html(str);
			
        }
        function sError(e){
            alert("error " + e);
        }
		$(".shishi").click(function(){
			
			var arr_shishi = $(".shishi").attr("id").split("_");
			//console.log(arr_shishi);
			var str = 'shishi_';
			var timestamp = Date.parse(new Date()); 
			//console.log(timestamp);
			if(arr_shishi[1]==0){
				//当为0的时候，就设置为5s
				$(".shishi").val("隔5秒刷新中");
				str=str+"5000_"+timestamp;
				$(".shishi").attr("id",str);
			}
			if(arr_shishi[1]==5000){
				//当为5的时候，就设置为0s
				$(".shishi").val("实时刷新中");
				str=str+"0_"+timestamp;
				$(".shishi").attr("id",str);
			}
		})
        function sMessage(msg){  
			//$(".realtime").attr("id");
			//var timestamp = Date.parse(new Date()); 
			var str = 'shishi_';
			var arr_shishi = $(".shishi").attr("id").split("_");
			//console.log(arr_shishi);
			//获取到时间 比较 设置
			var timestamp = Date.parse(new Date());
			//console.log(timestamp);
			//console.log(parseInt(arr_shishi[1])+parseInt(arr_shishi[2]));
			
			if(parseInt(arr_shishi[1])>0){
				if((timestamp>(parseInt(arr_shishi[1])+parseInt(arr_shishi[2])))){
					//console.log(1);
					str=str+"5000_"+timestamp;
					$(".shishi").attr("id",str);
				}else{
					return false;
				}
			}
			
			
			
            //alert('server says:' + msg.data);  
			//console.log(msg.data);
			
			var data = msg.data;
			var d = $.parseJSON(data);
			var level = map.getZoom();
			//alert(d.error_code);
			if(d.method=='render'){
				if(level <=12){
					//alert(12);
					//render(map.getCenter().lng,map.getCenter().lat);
					index = 0;
					clear();
					bdGEO();
					heatmap();
				}
				if(level >12){
					render_realtime(map.getCenter().lng,map.getCenter().lat);
					//alert(15);
					//获取车位的数据，然后显示
					if($("#dwz_info_id").val()=='') return ;
					realtime_detail();
					history_tb();
					
				}
			}
			if(d.method=='diaodu'){
				var arr = d.result.split(":");
				//$(arr).each(function(i,e){
					//alert(e);
					//str = 
				//})
				var str = '';
				if(d.result.indexOf('超出') >= 0){
					console.log(arr);
					str = arr[1]+arr[2]+arr[3];
				}else{
					str = arr[1]+':检测到:'+arr[2];
				}
				//$("#diaodu_message").html(d.result);
				$("#diaodu_message").html(str);
			}
			
			return ;
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
	
	heatmap();
    //是否显示热力图
    function openHeatmap(){
        heatmapOverlay.show();
    }
    function closeHeatmap(){
        heatmapOverlay.hide();
    }
	$("#setting").click(function(){
		var id = $("#dwz_info_id").val();
		//alert(id);
		var url = 'http://116.62.171.54:8080/manbike0.3/index.php/Admin/Bparking/index.html?no='+id+'&title=&usable_num=&storage_num=';
		window.open(url);
	})
</script>