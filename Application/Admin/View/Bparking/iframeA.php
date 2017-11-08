<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>test</title>
	<script src="__PUBLIC__/gentelella/vendors/jquery/dist/jquery.min.js"></script>
	<script src="http://localhost/manbike0.3/public/artDialog/artDialog.source.js?skin=default"></script>
<script src="http://localhost/manbike0.3/public/artDialog/plugins/iframeTools.source.js"></script>
</head>

<body style="margin:0">
<form id="bjy-form" class="form-inline" action="{:U('Admin/Bparking/add')}" method="post">
	<input type="hidden" name="pid" value="0">
	<table class="table table-striped table-bordered table-hover table-condensed">
		<tr>
			<th width="50%">车位名称：</th>
			<td>
				<input class="input-medium" type="text" name="title">
			</td>
		</tr>
		<tr>
			<th>可容纳车辆：</th>
			<td>
				<input class="input-medium" type="text" name="usable_num">
			</td>
		</tr>
		<tr>
			<th>已存储车辆：</th>
			<td>
				<input class="input-medium" type="text" name="storage_num">
			</td>
		</tr>
		<tr>
			<th>溢出车辆数量：</th>
			<td>
				<input class="input-medium" type="text" name="overflow_num">
			</td>
		</tr>
		<tr>
			<th>车位编号：</th>
			<td>
				<input class="input-medium" type="text" name="no">
			</td>
		</tr>
		<tr>
			<th>区域编号：</th>
			<td>
				<input class="input-medium" type="text" name="block_no">
			</td>
		</tr>
		<tr>
			<th>经纬度：</th>
			<td>
				<input id="aInput" name="lng_lat" value="拖动鼠标获取经纬度">
			</td>
		</tr>
		<tr>
			<th></th>
			<td>
				<input class="btn btn-success" onclick="add2()" type="submit" value="添加">
			</td>
		</tr>
	</table>
</form>
<div style="width:400px; height:300px; position: absolute; left: 370px; top:165px;">
 <button id="aButton">点击获取经纬度</button>
</div>
<script>
	// 传递给B页面
document.getElementById('aButton').onclick = function () {
	var aValue = document.getElementById('aInput').value;
	art.dialog.data('aValue', aValue);// 存储数据
	
	var path = art.dialog.data('homeDemoPath') || './';// 
	
	art.dialog.open(path + 'iframeB.html?fd', {
		id: 'AAA',
		width:500,
		left:900,
		close: function () {
			var bValue = art.dialog.data('bValue');// 读取B页面的数据
			if (bValue !== undefined) document.getElementById('aInput').value = bValue;
		}
	}, false);
};

// 关闭并返回数据到主页面
//document.getElementById('exit').onclick = function () {
//	art.dialog.close();
//};
function add2(){
	//setTimeout(function(){alert(1)},1);
	setTimeout(function(){
		// 刷新主页面
			art.dialog.data('iframeTools', '我知道你刷新了页面～哈哈'); // plugin.iframe.html可以收到
			var win = art.dialog.open.origin;//来源页面
			// 如果父页面重载或者关闭其子对话框全部会关闭
			win.location.reload();
			return false;
	},100);
}
</script>
</body>
</html>
