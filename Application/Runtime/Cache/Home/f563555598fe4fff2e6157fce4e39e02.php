<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=YeWyudDrXPyGtndIQlMztw7cIjI7si1t"></script>
    <script type="text/javascript" src="http://api.map.baidu.com/library/Heatmap/2.0/src/Heatmap_min.js"></script>
    <title>heatmap test</title>
	<script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.js"></script>
    <style type="text/css">
        ul,li{list-style: none;margin:0;padding:0;float:left;}
        html{height:100%}
        body{height:100%;margin:0px;padding:0px;font-family:"微软雅黑";}
        #container{height:97%;width:100%;}
        #r-result{width:100%;}
    </style>    
</head>
<body>
    <div id="container"></div>
    <div id="r-result">
        <input type="button"  onclick="openHeatmap();" value="打开热力图"/><input type="button"  onclick="closeHeatmap();" value="关闭热力图"/>
    </div>
</body>
</html>
<script type="text/javascript">

	var json_str = '<?php echo ($json_str); ?>';
	//alert(json_str);

    var map = new BMap.Map("container");          // 创建地图实例

    var point = new BMap.Point(120.193709,30.199936);
    map.centerAndZoom(point, 15);             // 初始化地图，设置中心点坐标和地图级别
    map.enableScrollWheelZoom(); // 允许滚轮缩放
    heatmapOverlay = new BMapLib.HeatmapOverlay({"radius":30});
    map.addOverlay(heatmapOverlay);
  function updateHeatmap(){
   
    /*
	 var points=[];
	for(var i=0;i<100;i++){
        var x = Math.random() * 9999;
        var y = Math.random() * 9999;
        var xStr = "120.19" + x;
        var yStr = "30.19" + y;
        points.push({"lng":parseFloat(xStr),"lat":parseFloat(yStr),"count":50});
    }
	*/
	var points = $.parseJSON(json_str);
	/*var points =[  
    {"lng":"120.193421","lat":"30.192715","count":"1000"},  
    {"lng":"120.197674","lat":"30.190081","count":"4000"},  
    {"lng":"120.196551","lat":"30.190368","count":"16000"} 
    ];*/
	//alert(points);
      
	

    heatmapOverlay.setDataSet({data:points,max:100});
  }

  setInterval(function() {updateHeatmap();}, 2000);

    //详细的参数,可以查看heatmap.js的文档 https://github.com/pa7/heatmap.js/blob/master/README.md
    //参数说明如下:
    /* visible 热力图是否显示,默认为true
     * opacity 热力的透明度,1-100
     * radius 势力图的每个点的半径大小   
     * gradient  {JSON} 热力图的渐变区间 . gradient如下所示
     *  {
            .2:'rgb(0, 255, 255)',
            .5:'rgb(0, 110, 255)',
            .8:'rgb(100, 0, 255)'
        }
        其中 key 表示插值的位置, 0~1. 
            value 为颜色值. 
     */

    //是否显示热力图
    function openHeatmap(){
        heatmapOverlay.show();
    }
    function closeHeatmap(){
        heatmapOverlay.hide();
    }
//  closeHeatmap();
</script>