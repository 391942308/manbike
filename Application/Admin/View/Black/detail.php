<!DOCTYPE html>
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
	<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=YeWyudDrXPyGtndIQlMztw7cIjI7si1t"></script>
	<script type="text/javascript" src="http://api.map.baidu.com/library/TextIconOverlay/1.2/src/TextIconOverlay_min.js"></script>
	<script type="text/javascript" src="http://api.map.baidu.com/library/MarkerClusterer/1.2/src/MarkerClusterer_min.js"></script>
	<script type="text/javascript" src="http://api.map.baidu.com/library/Heatmap/2.0/src/Heatmap_min.js"></script>
	
	<script src="http://echarts.baidu.com/gallery/vendors/echarts/echarts-all-3.js"></script>
	<link href="http://www.bootcss.com/p/buttons/css/buttons.css" rel="stylesheet" type="text/css"/>
	
	<title>运动轨迹</title>
	<script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.js"></script>
</head>
<body>
<div>
	
	<div style="height:30px;padding:10px;">
	<form action="http://baohe.toalls.com:8080/manbike0.3/index.php/Admin/Black/detail">
		<input id="tkeyword" type="text" placeholder="mac地址" name="mac" value="{$mac}" />
		<input id="chaxun" type="button" value="查询" />
	</form>
	</div>
	
	<div style="clear:both;"></div>
</div>
<div style="width:100%;height:90%;">

	
	<div id="allmap"></div>
</div>
	
	
</body>
</html>


<script>
	
		//加入一个记录数据，记录所有前面的post ， 
		//然后如果最新的一个post来了 就把前面那几个都清除掉
		var inter ;
		
		//初始化百度地图
		var map = new BMap.Map("allmap",{  
			minZoom : 10,  
			maxZoom : 19
		});
		//var point = new BMap.Point(120.059071,30.276691);
		var point = new BMap.Point(120.175707,30.196643);
		map.centerAndZoom(point, 15);
		map.enableScrollWheelZoom(true);
		map.enableInertialDragging();
		var control = new BMap.ScaleControl({anchor: BMAP_ANCHOR_TOP_RIGHT});// 左上角，添加比例尺
		var navigation = new BMap.NavigationControl({anchor: BMAP_ANCHOR_TOP_RIGHT,enableGeolocation: true});  //左上角，添加默认缩放平移控件
		map.addControl(navigation); 
		map.addControl(control); 	

		$(document).ready(function(){
			$("#nav_two").trigger("click");
		})
		
		//缩放
		map.addEventListener("zoomend", function () {
			//...
			var level = map.getZoom();
			if(level<=12){
					$("#nav_one").trigger("click");
			}
			if(level<=15 && level>=13){
					$("#nav_two").trigger("click");
			}
			if(level>=16){
					$("#nav_three").trigger("click");
			}
			console.log(level);
		});
		
		
		
		//拖动
		map.addEventListener('dragend', function(e){
		
			is_move = true;
			var lng=this.getCenter().lng;
			var lat = this.getCenter().lat;
			//var level = map.getZoom();
			//alert(level);
			
			//clock();
			
		});
		
</script>




<script>
	function clear(){
		var allOverlay = map.getOverlays();
		for (var i = 0; i < allOverlay.length ; i++)
		{       
			map.removeOverlay(allOverlay[i]);
		}
	}

	$(document).ready(function(){
			$("#chaxun").trigger("click");
		})
	
	
	function refresh(){
		$("#chaxun").trigger("click");
	}
	
	//self.setInterval("refresh()",10000);
	
	$("#chaxun").click(function(){
		clear();
		var mac = $("#tkeyword").val();
		//alert(mac);
		$.post(
				"http://baohe.toalls.com:8080/manbike0.3/index.php/Admin/Black/ajax_detail",
				{
					mac:mac
				},
				function(res){
					///alert(res.error_code);
					console.log("res:::::::::::"+res.results);
					if(res.error_code == 0){
						var list = res.results;
						for(value in list){
							console.log(list[value]);
							
							if(list[value].type == "history"){
								add_info_one_by_one(list[value]);	
							}
							if(list[value].type == "last"){
								add_last(list[value]);
							}
							if(list[value].type == "first"){
								add_first(list[value]);
							}
						}
					}else{
						alert(res.error_reason);
					}
				},'json');
		
		
	})

	function add_info_one_by_one(value){
						console.log("one_by_one:"+value);
						var point = new BMap.Point(value.lng, value.lat);
						var myIcon = new BMap.Icon("http://baohe.toalls.com:8080/manbike0.3/Public/myimages/alive.png", new BMap.Size(92,92));
						var marker = new BMap.Marker(point,{icon:myIcon});
						var label = new BMap.Label(value.id+":"+value.title+"|"+value.times,{offset:new BMap.Size(20,20)});
						map.addOverlay(marker);
						if (typeof(marker) == "undefined") { 
							console.log("============================");
							return false;
						}
						
						marker.setAnimation(BMAP_ANIMATION_BOUNCE);
						marker.setLabel(label);
						marker.addEventListener("click",function(e){
							//使用经纬度获取到相关的车位，然后使用车位获取到相关的数据，然后显示
							//以当前点为中心放大到网格
							/*var lng=map.getCenter().lng;
							var lat = map.getCenter().lat;
							//alert(lng);
							//alert(lat);
							var p = e.target;
							//alert("marker的位置是" + p.getPosition().lng + "," + p.getPosition().lat);    
							var point = new BMap.Point(p.getPosition().lng,p.getPosition().lat);
							map.centerAndZoom(point, map.getZoom());*/
							//在左边显示饼状图和折线图
							//使用id获取信息
							var arr = e.target.getLabel().content.split(':');
							
							//将label的内容传入去获取相关的数据
							//show_info(arr[0]);
							//$("#info_id").val(arr[0]);
						});
	}

	function add_last(value){
						console.log("one_by_one:"+value);
						var point = new BMap.Point(value.lng, value.lat);
						var myIcon = new BMap.Icon("http://baohe.toalls.com:8080/manbike0.3/Public/myimages/red.png", new BMap.Size(92,92));
						var marker = new BMap.Marker(point,{icon:myIcon});
						var label = new BMap.Label(value.id+":"+value.title+":当前所在地",{offset:new BMap.Size(20,40)});
						map.addOverlay(marker);
						if (typeof(marker) == "undefined") { 
							console.log("============================");
							return false;
						}
						
						
						map.centerAndZoom(point, 16);
						
						marker.setAnimation(BMAP_ANIMATION_BOUNCE);
						marker.setLabel(label);
						marker.addEventListener("click",function(e){
							//使用经纬度获取到相关的车位，然后使用车位获取到相关的数据，然后显示
							//以当前点为中心放大到网格
							/*var lng=map.getCenter().lng;
							var lat = map.getCenter().lat;
							//alert(lng);
							//alert(lat);
							var p = e.target;
							//alert("marker的位置是" + p.getPosition().lng + "," + p.getPosition().lat);    
							var point = new BMap.Point(p.getPosition().lng,p.getPosition().lat);
							map.centerAndZoom(point, map.getZoom());*/
							//在左边显示饼状图和折线图
							//使用id获取信息
							var arr = e.target.getLabel().content.split(':');
							
							//将label的内容传入去获取相关的数据
							//show_info(arr[0]);
							//$("#info_id").val(arr[0]);
						});
	}
	
	function add_first(value){
						console.log("one_by_one:"+value);
						var point = new BMap.Point(value.lng, value.lat);
						var myIcon = new BMap.Icon("http://baohe.toalls.com:8080/manbike0.3/Public/myimages/green.png", new BMap.Size(92,92));
						var marker = new BMap.Marker(point,{icon:myIcon});
						var label = new BMap.Label(value.id+":"+value.title+":当前所在地",{offset:new BMap.Size(20,40)});
						map.addOverlay(marker);
						if (typeof(marker) == "undefined") { 
							console.log("============================");
							return false;
						}
						
						
						marker.setAnimation(BMAP_ANIMATION_BOUNCE);
						marker.setLabel(label);
						marker.addEventListener("click",function(e){
							//使用经纬度获取到相关的车位，然后使用车位获取到相关的数据，然后显示
							//以当前点为中心放大到网格
							/*var lng=map.getCenter().lng;
							var lat = map.getCenter().lat;
							//alert(lng);
							//alert(lat);
							var p = e.target;
							//alert("marker的位置是" + p.getPosition().lng + "," + p.getPosition().lat);    
							var point = new BMap.Point(p.getPosition().lng,p.getPosition().lat);
							map.centerAndZoom(point, map.getZoom());*/
							//在左边显示饼状图和折线图
							//使用id获取信息
							var arr = e.target.getLabel().content.split(':');
							
							//将label的内容传入去获取相关的数据
							//show_info(arr[0]);
							//$("#info_id").val(arr[0]);
						});
	}
</script>



