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
	
	<title>单车地图v0.1</title>
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
	<input type="text" name="热点名称" value="" />
	<input type="button" value="查询" />
	<input id="getlevel" type="button" value="查询等级" />
	
	<span>区域</span>
	<span>板块</span>
	<span>清除条件</span>
	
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
	
		<div style="padding:15px;">为您找到以下单车</div>
		<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div id="<?php echo ($vo["area"]); ?>" data-lon="<?php echo ($vo["location"]["lon"]); ?>" data-lat="<?php echo ($vo["location"]["lat"]); ?>" class="item" style="border-bottom:solid 1px grey ;margin-top:1px;">
			<div style="float:left;background-color:white;width:30%;height:100px;">
			<?php if(($vo["company"] == '摩拜')): ?><img style="width:135px;height:100px;" src="/manbike0.3/Public/images/mobike.jpg" />
			<?php elseif(($vo["company"] == 'ofo')): ?>
			<img style="width:135px;height:100px;" src="/manbike0.3/Public/images/ofo.jpg" />
			<?php elseif(($vo["company"] == 'HelloBike')): ?>
			<img style="width:135px;height:100px;" src="/manbike0.3/Public/images/hello.jpg" />
			<?php elseif(($vo["company"] == '酷骑')): ?>
			<img style="width:135px;height:100px;" src="/manbike0.3/Public/images/kq.jpeg" />
			<?php elseif(($vo["company"] == '小明单车')): ?>
			<img style="width:135px;height:100px;" src="/manbike0.3/Public/images/xm.jpg" />
			<?php else: ?> 无图片<?php endif; ?>
			</div>
			<div style="float:left;background-color:white;width:70%;height:100px;">
			&nbsp;名称：<?php echo ($vo["name"]); ?> &nbsp;<br /> 
			&nbsp;mac：<?php echo ($vo["mac"]); ?> &nbsp; rssi：<?php echo ($vo["rssi"]); ?> &nbsp; <br />
			&nbsp;时间：<?php echo (date('Y-m-d h:i:s',$vo["ts"])); ?><br />
			&nbsp;地点：<?php echo ($vo["province"]); ?> &nbsp; <?php echo ($vo["city"]); ?> &nbsp; <?php echo ($vo["area"]); ?>
			
			</div>
			<div style="clear:both"></div>
		</div><?php endforeach; endif; else: echo "" ;endif; ?>
	</div>
	<div id="allmap"></div>
</div>
	
</body>
</html>
<script type="text/javascript">

	// 百度地图API功能
	var map = new BMap.Map("allmap",{  
        minZoom : 11,  
        maxZoom : 19
    });
	var point = new BMap.Point(120.19, 30.26);
	map.centerAndZoom(point, 12);
	map.enableScrollWheelZoom(true);
	map.enableInertialDragging();
	city = '杭州市';
	map.centerAndZoom(city,12);
	
	//缩放
	map.addEventListener("zoomend", function () {
		//...
		var level = map.getZoom();
		var lng=this.getCenter().lng;
		var lat = this.getCenter().lat;
		if(level>12){
			show_level_15(lng,lat);
		}
		if(level<=12){
		//alert(0)
			show_level_12(lng,lat);
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
		}
		if(level<=12){
			show_level_12(lng,lat);
		}
		//alert(1)
		
	});	
	
	
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
		markers.push(marker);
		//var marker = new BMap.Marker(point);
		map.addOverlay(marker);
		marker.setLabel(label);
		marker.setAnimation(BMAP_ANIMATION_BOUNCE);
		marker.addEventListener("click",attribute);
	}
	// 编写自定义函数,创建标注
	function addMarker_(point,label){
		var myIcon = new BMap.Icon("http://116.62.171.54:8080/manbike0.3/Public/myimages/pin.png", new BMap.Size(50,50));
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
		map.centerAndZoom(point, 19);
		//打开下一级
		show_level_15(lng,lat);
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
	
	$(document).ready(function(){
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
						var label = new BMap.Label("P"+allpoints[i].id+":"+"可停"+allpoints[i].usable_num+",已停"+allpoints[i].storage_num,{offset:new BMap.Size(20,20)});
						var point = new BMap.Point(allpoints[i].lng, allpoints[i].lat);
						addMarker(point,label,allpoints[i].usable_num,allpoints[i].level);	
					}
			},'json');
			
		
	}
	

	
</script>