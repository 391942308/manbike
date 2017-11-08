<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<!-- Meta, title, CSS, favicons, etc. -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>登陆 | 共享单车管理平台</title>

	<!-- Bootstrap -->
	<link href="/manbike0.3/Public/gentelella/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
	<!-- Font Awesome -->
	<link href="/manbike0.3/Public/gentelella/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<!-- NProgress -->
	<link href="/manbike0.3/Public/gentelella/vendors/nprogress/nprogress.css" rel="stylesheet">
	<!-- Animate.css -->
	<link href="/manbike0.3/Public/gentelella/vendors/animate.css/animate.min.css" rel="stylesheet">

	<!-- Custom Theme Style -->
	<link href="/manbike0.3/Public/gentelella/build/css/custom.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="/manbike0.3/Public/CSS/jquery.dialogbox.css">
	<style>
		html{
			background:url('/manbike0.3/Public/images/1b810006ebaa9327a9c81.jpg') no-repeat top left scroll;
			background-size:100%,100%;
		}
	</style>
	<script src="/manbike0.3/Public/JS/jquery-3.1.1.min.js"></script>
</head>
<script src="/manbike0.3/Public/JS/jquery.dialogBox.js"></script>
<script type="text/javascript">

/*
	$(function(){
		if($('#username').val() == ''){
			$('#username').focus();
		}else if($('#password').val() == ''){
			$('#password').focus();
		}
		$('#form').submit(function(){
				$.post("http://116.62.171.54:8080/manbike0.3/index.php/Admin/Login/dologin",$("#form").serialize(),function(data){
					if(data==1){
						$('#stantard-dialogBox1').dialogBox({
							hasMask: true,
							autoHide: true,
							content: '登录成功！',
							time:1500,
						});
						setTimeout(function(){
							window.location.href = 'http://116.62.171.54:8080/manbike0.3/index.php/Admin/Index/index';
						},1000);
					}else{
						if(data==-2){
							$('#username').focus();
							$('#stantard-dialogBox2').dialogBox({
								hasMask: true,
								autoHide: true,
								content: '用户名错误！',
								time:1500,
							});
						}
						else if(data==-3){
							$('#password').focus();
							$('#stantard-dialogBox3').dialogBox({
								hasMask: true,
								autoHide: true,
								content: '密码错误！',
								time:1500,
							});
						}
						else if(data==-5){
							$('#username').focus();
							$('#stantard-dialogBox4').dialogBox({
								hasMask: true,
								autoHide: true,
								content: '登录信息保存失败,请重新登录！',
								time:1500,
							});
						}
						else{
							$('#stantard-dialogBox5').dialogBox({
								hasMask: true,
								autoHide: true,
								content: '登录出现异常，请重试！',
								time:1500,
							});
						}
					}
					//notice(msg,pic);
				});
			return false;
		});
	});*/
</script>
<body class="login" >
<div>
	<a class="hiddenanchor" id="signup"></a>
	<a class="hiddenanchor" id="signin"></a>

	<div class="login_wrapper">
		<div class="animate form login_form">
			<section class="login_content">
				<form method="post" id="form" action="http://116.62.171.54:8080/manbike0.3/index.php/Admin/Login/dologin">
					<h1 style="color: black">登录后台</h1>
					<div>
						<input type="text" name="username" id="username" class="form-control" placeholder="Username" required="" />
					</div>
					<div>
						<input type="password" name="password" id="password" class="form-control" placeholder="Password" required="" />
					</div>
					<div>
						<input  class="btn btn-default submit" type="submit"  value="登陆">
						<a class="reset_pass" href="#">欢迎使用</a>
					</div>

					<div class="clearfix"></div>
				</form>
				<div id="stantard-dialogBox1"></div>
				<div id="stantard-dialogBox2"></div>
				<div id="stantard-dialogBox3"></div>
				<div id="stantard-dialogBox4"></div>
				<div id="stantard-dialogBox5"></div>
			</section>
		</div>
</div>
</body>
</html>