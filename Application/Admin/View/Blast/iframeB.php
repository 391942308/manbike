<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>test</title>
<script src="__PUBLIC__/artDialog/artDialog.source.js?skin=default"></script>
<script src="__PUBLIC__/artDialog/plugins/iframeTools.source.js"></script>
</head>

<body style="margin:0">
<div style="padding:20px;">
<input style="width:15em; padding:4px" id="bInput" value=""> <button id="aButton">确定</button>
</div>
<script>
document.getElementById('bInput').value = art.dialog.data('aValue');// 读取A页面的数据

// 返回数据给A页面
document.getElementById('aButton').onclick = function () {
	var bValue = document.getElementById('bInput').value;
	art.dialog.data('bValue', bValue);// 存储数据
	art.dialog.close();
};

// 刷新A页面
document.getElementById('reload').onclick = function () {
	var win = art.dialog.open.origin;
	win.location.reload(); // 注意：如果父页面重载或者关闭其子对话框全部会关闭
	return false;
};
</script>
</body>
</html>
	<script src="__PUBLIC__/gentelella/vendors/jquery/dist/jquery.min.js"></script>
	<link id="artDialogSkin" href="__PUBLIC__/artDialog/skins/default.css" rel="stylesheet" type="text/css" />
	<script type="text/JavaScript" src="__PUBLIC__/artDialog/artDialog.js"></script>
	<script type="text/javascript" src="__PUBLIC__/artDialog/plugins/iframeTools.js"></script> <!-- 对iframe的新工具 -->
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	<style type="text/css">
		body, html, #allmap {
			width: 100%;
			height: 90%;
			margin: 0;
			font-family: "微软雅黑";
		}

		#l-map {
			height: 500px;
			width: 100%;
		}

		#r-result {
			width: 100%;
		}
	</style>
	<script type="text/javascript" src="http://api.map.baidu.com/getscript?v=2.0&ak=4c1bb2055e24296bbaef36574877b4e2"></script>
<div id="l-map"></div>
<script type="text/javascript">
	// 百度地图API功能
	//浏览器定位
	var geolocation = new BMap.Geolocation();
	geolocation.getCurrentPosition(function (r) {
		if (this.getStatus() == BMAP_STATUS_SUCCESS) {
			var map = new BMap.Map("l-map");
			var point = new BMap.Point(116.331398,39.897445);
			map.centerAndZoom(r.point, 12); //定义地图等级，就是放大倍数
			map.enableScrollWheelZoom(true); //启用地图滚轮放大缩小
			var marker = new BMap.Marker(r.point);// 创建标注
			map.addOverlay(marker);  // 将标注添加到地图中
			map.panTo(r.point);
			// alert('您的位置：' + r.point.lng + ',' + r.point.lat);

			marker.enableDragging(); //标注可拖拽
			//marker.disableDragging();           // 不可拖拽

			// 开启事件监听
			marker.addEventListener("dragend", function (e) {

				var x = e.point.lng; //经度
				var y = e.point.lat; //纬度
				//alert(x + "，" + y);
				document.getElementById('bInput').value=x + "," + y;
			});
		}
		else {
			alert('failed' + this.getStatus());
		}
	}, { enableHighAccuracy: true })

	//关于状态码
	//BMAP_STATUS_SUCCESS 检索成功。对应数值“0”。
	//BMAP_STATUS_CITY_LIST 城市列表。对应数值“1”。
	//BMAP_STATUS_UNKNOWN_LOCATION 位置结果未知。对应数值“2”。
	//BMAP_STATUS_UNKNOWN_ROUTE 导航结果未知。对应数值“3”。
	//BMAP_STATUS_INVALID_KEY 非法密钥。对应数值“4”。
	//BMAP_STATUS_INVALID_REQUEST 非法请求。对应数值“5”。
	//BMAP_STATUS_PERMISSION_DENIED 没有权限。对应数值“6”。(自 1.1 新增)
	//BMAP_STATUS_SERVICE_UNAVAILABLE 服务不可用。对应数值“7”。(自 1.1 新增)
	//BMAP_STATUS_TIMEOUT 超时。对应数值“8”。(自 1.1 新增)
</script>