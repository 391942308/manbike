<?php if (!defined('THINK_PATH')) exit(); if(C('LAYOUT_ON')) { echo ''; } ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>跳转提示</title>
<style type="text/css">
*{ padding: 0; margin: 0; }
body{ background: #fff; font-family: '微软雅黑'; color: #9ACD32; font-size: 16px;text-align: center; }
.system-message{ padding: 24px 48px; }
.system-message h1{ font-size: 100px; font-weight: normal; line-height: 120px; margin-bottom: 12px; }
.system-message .jump{ padding-top: 10px}
.system-message .jump a{ color: #333;}
.system-message .success,.system-message .error{ line-height: 1.8em; font-size: 36px }
.system-message .detail{ font-size: 12px; line-height: 20px; margin-top: 12px; display:none}
</style>
</head>
<body>

<?php if(isset($message)) {?>
<div style="position:relative;width:80%;height:100%;text-align:center;top:300px">
	<img src="/manbike0.3/Public/images/14-150F314291C21.jpg" width="400px"/>
	<div style="position:absolute;width:80%;height:100%;z-indent:2;left:415px;top:0px;">
		<h1><?php echo($message); ?></h1>
		<div style="height:20px;"></div>
		<div style="padding-left:70px;">
			<p class="jump">
			页面自动 <a id="href" href="<?php echo($jumpUrl); ?>">跳转</a> 等待时间： <b id="wait"><?php echo($waitSecond); ?></b>
			</p>
		</div>
	</div>
</div>
<?php }else{?>
<div style="position:relative;width:80%;height:100%;text-align:center;top:300px">
	<img src="/manbike0.3/Public/images/14-150F314291C21.jpg" width="400px"/>
	<div style="position:absolute;width:80%;height:100%;z-indent:2;left:415px;top:0px;">
		<h1><?php echo($error); ?></h1>
		<div style="height:20px;"></div>
		<div style="padding-left:70px;">
			<p class="jump">
				页面自动 <a id="href" href="<?php echo($jumpUrl); ?>">跳转</a> 等待时间： <b id="wait"><?php echo($waitSecond); ?></b>
			</p>
		</div>
	</div>
</div>
<?php }?>
<p class="detail"></p>

<script type="text/javascript">
(function(){
var wait = document.getElementById('wait'),href = document.getElementById('href').href;
var interval = setInterval(function(){
	var time = --wait.innerHTML;
	if(time <= 0) {
		location.href = href;
		clearInterval(interval);
	};
}, 1000);
})();
</script>
</body>
</html>